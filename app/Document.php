<?php

namespace App;

use App\Traits\MultiTenantAssetTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Document extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantAssetTrait, HasMediaTrait;

    protected $appends = [
        'document',
    ];

    protected $fillable = [
        'description', 'asset_id', 'tenant_id',
    ];

    public function getDocumentAttribute()
    {
        return $this->getMedia('document')->last();
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
