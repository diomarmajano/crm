<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait BelongsToTenant
{
    /**
     * El "boot" del trait. Se ejecuta automáticamente cuando usas el modelo.
     */
    protected static function bootBelongsToTenant()
    {
        // 1. EL FILTRO DE SEGURIDAD (Global Scope)
        // Si hay un Tenant identificado en la sesión/app, aplicamos el filtro.
        if (session()->has('tenant_id')) {
            static::addGlobalScope('tenant', function (Builder $builder) {
                $builder->where('tenant_id', session()->get('tenant_id'));
            });
        }

        // 2. LA ASIGNACIÓN AUTOMÁTICA (Creating Event)
        // Antes de crear el registro, le ponemos el ID del tenant.
        static::creating(function (Model $model) {
            if (! $model->tenant_id && session()->has('tenant_id')) {
                $model->tenant_id = session()->get('tenant_id');
            }
        });
    }

    /**
     * Relación inversa: Un modelo siempre pertenece a un Tenant.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
