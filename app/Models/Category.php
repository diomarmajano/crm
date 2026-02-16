<?php

namespace App\Models;

use App\Models\BelongsToTenant as ModelsBelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use ModelsBelongsToTenant;

    protected $table = 'category';

    protected $fillable = [
        'nombre_categoria',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function service()
    {
        return $this->hasMany(Services::class);
    }
}
