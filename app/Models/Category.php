<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';

    protected $fillable = [
        'nombre_categoria',
    ];

    public function services()
    {
        return $this->hasMany(Services::class);
    }
}
