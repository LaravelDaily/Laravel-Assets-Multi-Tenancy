<?php

namespace App;

use App\Traits\MultiTenantAssetTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use SoftDeletes, MultiTenantAssetTrait;

    protected $fillable = [
        'text', 'asset_id', 'tenant_id',
    ];

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
