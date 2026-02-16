<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\BelongsToTenant as ModelsBelongsToTenant;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasPanelShield, HasRoles, Notifiable;

    use ModelsBelongsToTenant;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tenant_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // 1. Si el usuario tiene CUALQUIER rol asignado, déjalo entrar.
        // (Shield se encargará después de mostrarle solo lo que puede ver)
        if ($this->roles()->count() > 0) {
            return true;
        }

        // 2. Si es tu correo específico de admin, déjalo entrar siempre (puerta trasera segura)
        if ($this->email === 'admin@cleanstack.cl') {
            return true;
        }

        // 3. A todos los demás (usuarios públicos sin rol), bloquéalos.
        return false;
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedidos::class);
    }
}
