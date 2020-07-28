<?php

namespace App;

use App\Traits\MultiTenantRoleTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes, MultiTenantRoleTrait;

    protected $fillable = [
        'title', 'tenant_id',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }
}
