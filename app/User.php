<?php

namespace App;

use App\Traits\MultiTenantUserTrait;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, CascadeSoftDeletes, MultiTenantUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'domain', 'is_suspended', 'tenant_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $cascadeDeletes = [
        'tenantUsers', 'roles', 'assetGroups',
    ];

    protected $with = [
        'roles',
    ];

    protected static function booted()
    {
        static::deleting(function ($user) {
            if ($user->domain) {
                $user->update([
                    'domain' => $user->domain . '-deleted-' . time()
                ]);
            }
        });
    }

    public function getIsAdminAttribute()
    {
        return $this->roles()->withoutGlobalScopes()->whereId(1)->exists();
    }

    public function getIsTenantAdminAttribute()
    {
        return $this->roles()->withoutGlobalScopes()->whereId(2)->exists();
    }

    public function getIsTenantUserAttribute()
    {
        return $this->roles()->withoutGlobalScopes()->whereId(3)->exists();
    }

    public function tenant()
    {
        return $this->belongsTo(self::class, 'tenant_id');
    }

    public function tenantUsers()
    {
        return $this->hasMany(self::class, 'tenant_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function assetGroups()
    {
        return $this->hasMany(AssetGroup::class, 'tenant_id');
    }
}
