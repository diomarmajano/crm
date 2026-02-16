<?php

namespace App\Models;

use App\Models\BelongsToTenant as ModelsBelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Pedidos extends Model
{
    use ModelsBelongsToTenant;

    protected $table = 'pedidos';

    protected $fillable = [
        'tenant_id',
        'pedido_id',
        'user_id',
        'medio_pago',
        'total_pedido',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
