<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait MultiTenantAssetGroupTrait
{
    public static function bootMultiTenantAssetGroupTrait()
    {
        if (!app()->runningInConsole() && auth()->check()) {
            $user = auth()->user();
            static::creating(function ($model) use ($user) {
                $model->tenant_id = $user->tenant_id ?? $user->id;
            });
            if (!$user->is_admin) {
                static::addGlobalScope('asset_group_tenant_id', function (Builder $builder) use ($user) {
                    $builder->where('tenant_id', $user->tenant_id ?? $user->id);
                });
            }
        }
    }
}
