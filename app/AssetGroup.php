<?php

namespace App;

use App\Traits\MultiTenantAssetGroupTrait;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetGroup extends Model
{
    use SoftDeletes, CascadeSoftDeletes, MultiTenantAssetGroupTrait;

    protected $fillable = [
        'name', 'parent_id', 'tenant_id',
    ];

    protected $cascadeDeletes = [
        'children',
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
}
