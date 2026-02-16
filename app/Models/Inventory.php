<?php

namespace App\Models;

use App\Models\BelongsToTenant as ModelsBelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use ModelsBelongsToTenant;

    protected $table = 'inventory';

    protected $fillable = [
        'id_service',
        'stock_producto',
        'stock_minimo',
        'precio_compra',
        'precio_venta',
    ];

    public function service()
    {
        return $this->belongsTo(Services::class, 'id_service');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
