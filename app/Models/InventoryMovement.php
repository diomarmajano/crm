<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $connection = 'tenant';

    protected $guarded = [];

    protected $table = 'inventory_movements';

    protected $fillable = [
        'inventory_id',
        // 'tenant_id',
        'user_id',
        'tipo',
        'cantidad',
        'stock_anterior',
        'stock_nuevo',
        'motivo',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function tenant()
    // {
    //     return $this->belongsTo(Tenant::class);
    // }
}
