<?php

namespace App\Models;

use App\Models\BelongsToTenant as ModelsBelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use ModelsBelongsToTenant;

    protected $table = 'services';

    protected $fillable = [
        'service_name',
        'service_precio',
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
}
