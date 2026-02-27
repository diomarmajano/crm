<?php

namespace App\Models;

use App\Models\BelongsToTenant as ModelsBelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    // use ModelsBelongsToTenant;

    protected $connection = 'tenant';

    protected $table = 'items_pedidos';

    protected $fillable = [
        // 'tenant_id',
        'pedido_id',
        'servicio_id',
        'nombre_servicio',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    // public function tenant()
    // {
    //     return $this->belongsTo(Tenant::class);
    // }

    public function pedido()
    {
        return $this->belongsTo(Pedidos::class);
    }

    // public function cliente()
    // {
    //     return $this->belongsTo(Clientes::class);
    // }

    // protected static function booted()
    // {
    //     static::creating(function ($item) {
    //         // Si el item tiene un pedido padre asociado, copiamos sus datos
    //         if ($item->pedido) {
    //             $item->tenant_id = $item->pedido->tenant_id;
    //         }
    //     });
    // }
}
