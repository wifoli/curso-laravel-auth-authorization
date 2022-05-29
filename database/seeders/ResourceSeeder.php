<?php

namespace Database\Seeders;

use App\Models\Resource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = Resource::create(['name' => 'Categorias']);
        $category->permissions()->createMany([
            ['name' => 'visualizar_categorias'],
            ['name' => 'visualizar_categoria'],
            ['name' => 'deletar_categoria'],
            ['name' => 'editar_categoria'],
        ]);

        $company = Resource::create(['name' => 'Empresas']);
        $company->permissions()->createMany([
            ['name' => 'visualizar_empresas'],
            ['name' => 'visualizar_empresa'],
            ['name' => 'deletar_empresa'],
            ['name' => 'editar_empresa'],
        ]);
    }
}
