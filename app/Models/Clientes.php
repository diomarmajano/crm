<?php

namespace App\Models;

use App\Models\BelongsToTenant as ModelsBelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    // use ModelsBelongsToTenant;

    protected $connection = 'tenant';

    protected $table = 'clientes';

    protected $fillable = [
        'cliente_name',
        'cliente_telefono',
        'cliente_email',
        'cliente_direccion',
    ];

    // public function tenant()
    // {
    //     return $this->belongsTo(Tenant::class);
    // }

    public function pedidos()
    {
        return $this->hasMany(Pedidos::class, 'cliente_id');
    }
}
