<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait MultiTenantRoleTrait
{
    public static function bootMultiTenantRoleTrait()
    {
        if (!app()->runningInConsole() && auth()->check() && request()->is('admin/roles*', 'admin/users*')) {
            $user = auth()->user();
            static::creating(function ($model) use ($user) {
                $model->tenant_id = $user->id;
            });
            if ($user->is_tenant_admin) {
                static::addGlobalScope('role_tenant_id', function (Builder $builder) use ($user) {
                    $builder->where('tenant_id', $user->id)
                            ->when(request()->is('admin/users*'), function ($query) {
                                $query->orWhere('id', 3);
                            });
                });
            }
        }
    }
}
