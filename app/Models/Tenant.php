<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'central';

    protected $fillable = [
        'database_name',
        'name',
        'slug',
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

    protected static function booted()
    {
        static::created(function ($tenant) {
            \App\Jobs\ProvisionTenantDatabase::dispatch($tenant);
        });
    }
}
