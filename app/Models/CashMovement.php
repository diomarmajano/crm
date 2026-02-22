<?php

namespace App\Models;

use App\Models\BelongsToTenant as ModelsBelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class CashMovement extends Model
{
    use ModelsBelongsToTenant;

    protected $guarded = [];

    public function shift()
    {
        return $this->belongsTo(CashShift::class, 'cash_shift_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pedido()
    {
        return $this->belongsTo(Pedidos::class, 'pedido_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
