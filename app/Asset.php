<?php

namespace App;

use App\Traits\MultiTenantAssetTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use SoftDeletes, MultiTenantAssetTrait;

    protected $fillable = [
        'name', 'description', 'serial_number', 'price', 'warranty_expiry_date', 'sub_group_id', 'tenant_id',
    ];

    public function subGroup()
    {
        return $this->belongsTo(AssetGroup::class, 'sub_group_id');
    }
}
