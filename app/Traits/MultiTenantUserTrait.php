<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait MultiTenantUserTrait
{
    public static function bootMultiTenantUserTrait()
    {
        if (!app()->runningInConsole() && auth()->check() && request()->is('admin/users')) {
            $user = auth()->user();
            static::creating(function ($model) use ($user) {
                $model->tenant_id = $user->id;
            });
            if ($user->is_tenant_admin) {
                static::addGlobalScope('tenant_id', function (Builder $builder) use ($user) {
                    $builder->where('tenant_id', $user->id)->orWhere('id', $user->id);
                });
            }
        }
    }
}
