<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'email',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    /**
     * Relación: Un Tenant tiene muchos usuarios.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function services()
    {
        return $this->hasMany(Services::class);
    }

    public function clientes()
    {
        return $this->hasMany(Clientes::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedidos::class);
    }

    public function movements()
    {
        return $this->hasMany(CashMovement::class);
    }

    public function cashShift()
    {
        return $this->hasMany(CashShift::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function InventoryMovement()
    {
        return $this->hasMany(InventoryMovement::class);
    }
}
