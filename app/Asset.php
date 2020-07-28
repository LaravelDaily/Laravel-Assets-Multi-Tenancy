<?php

namespace App;

use App\Traits\MultiTenantAssetTrait;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use SoftDeletes, MultiTenantAssetTrait, CascadeSoftDeletes;

    protected $fillable = [
        'name', 'description', 'serial_number', 'price', 'warranty_expiry_date', 'sub_group_id', 'tenant_id',
    ];

    protected $cascadeDeletes = [
        'images', 'documents', 'notes',
    ];

    public function subGroup()
    {
        return $this->belongsTo(AssetGroup::class, 'sub_group_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
