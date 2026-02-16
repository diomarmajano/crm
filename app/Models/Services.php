<?php

namespace App\Models;

use App\Models\BelongsToTenant as ModelsBelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use ModelsBelongsToTenant;

    protected $table = 'services';

    protected $fillable = [
        'tenant_id',
        'sku',
        'codigo',
        'id_category',
        'service_name',
        'detalles',
        'service_precio',
        'precio_promocion',
        'fecha_vencimiento',
        'service_icon',
        'is_active',

    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Category::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'id_service');
    }
}
