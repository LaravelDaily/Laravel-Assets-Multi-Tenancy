<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait MultiTenantUserTrait
{
    public static function bootMultiTenantUserTrait()
    {
        if (!app()->runningInConsole() && auth()->check() && request()->is('admin/users*')) {
            $user = auth()->user();
            static::creating(function ($model) use ($user) {
                $model->tenant_id = $user->id;
            });
        }
    }
}
