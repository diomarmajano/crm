<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $superAdminRoleName = config('filament-shield.super_admin.name', 'super_admin');

        $role = Role::firstOrCreate(
            ['name' => $superAdminRoleName, 'guard_name' => 'web']
        );

        // 4. Asignar el rol al usuario
        $user->assignRole($role);
    }
}
