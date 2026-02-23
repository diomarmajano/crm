<?php

namespace App\Filament\Resources\Tenants\Pages;

use App\Filament\Resources\Tenants\TenantResource;
use App\Models\Services;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected static bool $canCreateAnother = false;

    protected function afterCreate(): void
    {
        // $this->record es el Tenant recién creado
        $tenant = $this->record;

        $defaultCategories = [
            ['name' => ''],
        ];

        // $defaultServices = [
        //     ['name' => 'Abrigo', 'price' => 9990, 'icon' => 'https://cdn-icons-png.flaticon.com/128/12019/12019301.png'],
        //     ['name' => 'Almohada', 'price' => 6990, 'icon' => 'https://cdn-icons-png.flaticon.com/128/4289/4289334.png'],
        //     ['name' => 'Blusa y Bata De Medico', 'price' => 3990, 'icon' => 'https://cdn-icons-png.flaticon.com/128/2865/2865471.png'],
        //     ['name' => 'Bolsa De Ropa S', 'price' => 12900, 'icon' => 'https://cdn-icons-png.flaticon.com/128/9619/9619860.png'],
        //     ['name' => 'Bolsa De Ropa XS', 'price' => 8900, 'icon' => 'https://cdn-icons-png.flaticon.com/128/10725/10725113.png'],
        //     ['name' => 'Cama De Perro', 'price' => 13990, 'icon' => 'https://cdn-icons-png.flaticon.com/128/3629/3629448.png'],
        //     ['name' => 'Chaleco', 'price' => 5000, 'icon' => 'https://cdn-icons-png.flaticon.com/128/892/892397.png'],
        //     ['name' => 'Cobertor/Quilt', 'price' => 12000, 'icon' => 'https://cdn-icons-png.flaticon.com/128/6249/6249008.png'],
        //     ['name' => 'Corbata', 'price' => 3190, 'icon' => 'https://cdn-icons-png.flaticon.com/128/594/594029.png'],
        //     ['name' => 'Delivery', 'price' => 4990, 'icon' => 'https://cdn-icons-png.flaticon.com/128/3257/3257833.png'],
        //     ['name' => 'Docena Lavada y Planchada', 'price' => 24000, 'icon' => 'https://cdn-icons-png.flaticon.com/128/2503/2503380.png'],
        //     ['name' => 'Docena Solo Plancha', 'price' => 19000, 'icon' => 'https://cdn-icons-png.flaticon.com/128/2793/2793867.png'],
        //     ['name' => 'Express', 'price' => 5000, 'icon' => 'https://cdn-icons-png.flaticon.com/128/4285/4285622.png'],
        //     ['name' => 'Frazada, sencilla y delgada', 'price' => 7700, 'icon' => 'https://cdn-icons-png.flaticon.com/128/1948/1948716.png'],
        //     ['name' => 'Juego De Sabanas Lavadas y Planchadas', 'price' => 9990, 'icon' => 'https://cdn-icons-png.flaticon.com/128/4605/4605879.png'],
        //     ['name' => 'Juego De Sabanas Solo Lavado', 'price' => 4990, 'icon' => 'https://cdn-icons-png.flaticon.com/128/4605/4605879.png'],
        //     ['name' => 'Juego De Sabanas Solo Plancha', 'price' => 7990, 'icon' => 'https://cdn-icons-png.flaticon.com/128/4605/4605879.png'],
        //     ['name' => 'Juego De Toallas', 'price' => 4990, 'icon' => 'https://cdn-icons-png.flaticon.com/128/5059/5059805.png'],
        //     ['name' => 'Mochila', 'price' => 8990, 'icon' => 'https://cdn-icons-png.flaticon.com/128/3275/3275938.png'],
        //     ['name' => 'Pantalon o Falda', 'price' => 5500, 'icon' => 'https://cdn-icons-png.flaticon.com/128/6847/6847493.png'],
        //     ['name' => 'Parka', 'price' => 8990, 'icon' => 'https://cdn-icons-png.flaticon.com/128/5499/5499300.png'],
        //     ['name' => 'Plumón 2 Plazas', 'price' => 14000, 'icon' => 'https://cdn-icons-png.flaticon.com/128/3364/3364291.png'],
        //     ['name' => 'Plumón 2 Plazas Plumas', 'price' => 16000, 'icon' => 'https://cdn-icons-png.flaticon.com/128/3364/3364291.png'],
        //     ['name' => 'Plumón Individual', 'price' => 10990, 'icon' => 'https://cdn-icons-png.flaticon.com/128/3364/3364291.png'],
        //     ['name' => 'Plumón Individual Plumas', 'price' => 13990, 'icon' => 'https://cdn-icons-png.flaticon.com/128/3364/3364291.png'],
        //     ['name' => 'Plumón King', 'price' => 17000, 'icon' => 'https://cdn-icons-png.flaticon.com/128/3364/3364291.png'],
        //     ['name' => 'Plumón King Plumas', 'price' => 19000, 'icon' => 'https://cdn-icons-png.flaticon.com/128/3364/3364291.png'],
        //     ['name' => 'Prenda Lavada y Planchada', 'price' => 2500, 'icon' => 'https://cdn-icons-png.flaticon.com/128/2793/2793867.png'],
        //     ['name' => 'Prenda Solo Planchada', 'price' => 2100, 'icon' => 'https://cdn-icons-png.flaticon.com/128/2793/2793867.png'],
        //     ['name' => 'Sueter', 'price' => 5000, 'icon' => 'https://cdn-icons-png.flaticon.com/128/6847/6847493.png'],
        //     ['name' => 'Terno Completo', 'price' => 10990, 'icon' => 'https://cdn-icons-png.flaticon.com/128/3074/3074252.png'],
        //     ['name' => 'Vestido De Fiesta', 'price' => 17500, 'icon' => 'https://cdn-icons-png.flaticon.com/128/2302/2302175.png'],
        //     ['name' => 'Vestido De Fiesta Sencillo', 'price' => 13500, 'icon' => 'https://cdn-icons-png.flaticon.com/128/2789/2789170.png'],
        //     ['name' => 'Vestón', 'price' => 8990, 'icon' => 'https://cdn-icons-png.flaticon.com/128/4799/4799376.png'],
        //     ['name' => 'Zapatilla De Cuero o Gamuza', 'price' => 9990, 'icon' => 'https://cdn-icons-png.flaticon.com/128/860/860895.png'],
        //     ['name' => 'Zapatillas De Tela o Sinteticas', 'price' => 8990, 'icon' => 'https://cdn-icons-png.flaticon.com/128/3343/3343850.png'],
        // ];

        // foreach ($defaultServices as $service) {
        //     Services::create([
        //         'tenant_id' => $tenant->id,
        //         'service_name' => $service['name'],
        //         'service_precio' => $service['price'],
        //         'service_icon' => $service['icon'],
        //         'is_active' => true,
        //     ]);
        // }

    }
}
