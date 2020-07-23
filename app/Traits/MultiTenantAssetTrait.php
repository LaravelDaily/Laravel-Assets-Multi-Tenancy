<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait MultiTenantAssetTrait
{
    public static function bootMultiTenantAssetTrait()
    {
        if (!app()->runningInConsole() && auth()->check()) {
            $user = auth()->user();
            static::creating(function ($model) use ($user) {
                $model->tenant_id = $user->tenant_id ?? $user->id;
            });
            static::addGlobalScope('asset_tenant_id', function (Builder $builder) use ($user) {
                $builder->where('tenant_id', $user->tenant_id ?? $user->id);
            });
        }
    }
}
