<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'central';

    protected $fillable = [
        'database_name',
        'name',
        'slug',
        // 'domain',
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

    // public function services()
    // {
    //     return $this->hasMany(Services::class);
    // }

    // public function clientes()
    // {
    //     return $this->hasMany(Clientes::class);
    // }

    // public function pedidos()
    // {
    //     return $this->hasMany(Pedidos::class);
    // }

    // public function movements()
    // {
    //     return $this->hasMany(CashMovement::class);
    // }

    // public function cashShift()
    // {
    //     return $this->hasMany(CashShift::class);
    // }

    // public function inventories()
    // {
    //     return $this->hasMany(Inventory::class);
    // }

    // public function InventoryMovement()
    // {
    //     return $this->hasMany(InventoryMovement::class);
    // }

    protected static function booted()
    {
        static::created(function ($tenant) {
            // 1. Generar un nombre único para la BD
            $dbName = 'tenant_'.$tenant->id.'_'.Str::slug($tenant->name);

            // 2. Crear la base de datos física usando la conexión central
            DB::connection('central')->statement("CREATE DATABASE `{$dbName}`");

            // Guardamos el nombre de la BD en el modelo
            $tenant->database_name = $dbName;
            $tenant->saveQuietly();

            // --- INICIO DE LA CORRECCIÓN ---

            // 3. Le decimos a Laravel en tiempo de ejecución cuál es el nombre de la BD
            config(['database.connections.tenant.database' => $dbName]);

            // 4. Purgamos la conexión 'tenant' para obligar a Laravel a reconectar con el nuevo nombre
            DB::purge('tenant');

            // --- FIN DE LA CORRECCIÓN ---

            // 5. Ahora sí, ejecutamos las migraciones
            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);
        });
    }
}
