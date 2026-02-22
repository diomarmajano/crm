<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            // Alimentos básicos y despensa
            'Abarrotes',
            'Lácteos y Huevos',
            'Panadería y Pastelería',
            'Carnes y Embutidos',
            'Frutas y Verduras',
            'Congelados',

            // Bebidas y snacks
            'Bebidas y Jugos',
            'Cervezas y Licores',
            'Snacks y Papas Fritas',
            'Confitería y Dulces',
            'Galletas y Cereales',

            // Artículos no comestibles
            'Limpieza del Hogar',
            'Cuidado Personal e Higiene',
            'Artículos para Mascotas',
            'Bazar y Librería',
            'Desechables y Plásticos',

            // Otros
            'Cigarros y Tabaco',
            'Helados',
            'Varios / Otros',
        ];

        foreach ($categorias as $cat) {
            Category::firstOrCreate(['nombre_categoria' => $cat]);
        }
    }
}
