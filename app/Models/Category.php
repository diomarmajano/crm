<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $connection = 'tenant';

    protected $table = 'category';

    protected $fillable = [
        'nombre_categoria',
    ];

    public function services()
    {
        return $this->hasMany(Services::class);
    }
}
