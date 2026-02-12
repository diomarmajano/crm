<?php

namespace App\Models;

use App\Models\BelongsToTenant as ModelsBelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Pedidos extends Model
{
    use ModelsBelongsToTenant;

    protected $table = 'pedidos';

    protected $fillable = [
        'pedido_id',
        'cliente_id',
        'medio_pago',
        'total_pedido',
        'estado_pedido',
        'estado_pago',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function items()
    {
        return $this->hasMany(Items::class, 'pedido_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Clientes::class);
    }
}
