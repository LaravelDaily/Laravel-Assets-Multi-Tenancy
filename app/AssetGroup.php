<?php

namespace App;

use App\Traits\MultiTenantAssetTrait;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetGroup extends Model
{
    use SoftDeletes, CascadeSoftDeletes, MultiTenantAssetTrait;

    protected $fillable = [
        'name', 'parent_id', 'tenant_id',
    ];

    protected $cascadeDeletes = [
        'children', 'assets',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'sub_group_id');
    }
}
