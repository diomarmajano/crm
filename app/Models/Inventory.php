<?php

namespace App\Models;

use App\Models\BelongsToTenant as ModelsBelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use ModelsBelongsToTenant;

    protected $table = 'inventory';

    protected $guarded = [];

    protected $fillable = [
        'id_service',
        'stock_producto',
        'stock_minimo',
        'precio_compra',
        'precio_venta',
    ];

    protected static function booted()
    {
        // Se ejecuta automáticamente DESPUÉS de que se inserta un nuevo inventario
        static::created(function ($inventory) {
            // Solo creamos el movimiento si el stock inicial es mayor a 0
            if ($inventory->stock_producto > 0) {
                InventoryMovement::create([
                    'inventory_id' => $inventory->id,
                    'tenant_id' => $inventory->tenant_id,
                    // auth()->id() puede ser null si se crea por consola/seeder
                    'user_id' => auth()->id(),
                    'tipo' => 'entrada',
                    'cantidad' => $inventory->stock_producto,
                    'stock_anterior' => 0,
                    'stock_nuevo' => $inventory->stock_producto,
                    'motivo' => 'Inventario inicial',
                ]);
            }
        });
    }

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function service()
    {
        return $this->belongsTo(Services::class, 'id_service');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
