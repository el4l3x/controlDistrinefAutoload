<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleAdm = Role::create(['name' => 'SuperAdmin']);
        $roleNor = Role::create(['name' => 'Normal']);
        $roleRes = Role::create(['name' => 'Restringido']);

        /* Permission::create([
            'name' => 'usuarios.index',
            'description' => 'Ver Usuarios',
        ])->syncRoles([$roleAdm]);
        Permission::create([
            'name' => 'usuarios.create',
            'description' => 'Crear un Usuario',
        ])->syncRoles([$roleAdm]);
        Permission::create([
            'name' => 'usuarios.edit',
            'description' => 'Editar un Usuario',
        ])->syncRoles([$roleAdm]);
        Permission::create([
            'name' => 'usuarios.destroy',
            'description' => 'Eliminar un Usuario',
        ])->syncRoles([$roleAdm]); */

        Permission::create([
            'name' => 'usuarios.index',
            'description' => 'Ver Usuarios',
        ])->syncRoles([$roleAdm]);
        Permission::create([
            'name' => 'dashboard.index',
            'description' => 'Ver Dashboard',
        ])->syncRoles([$roleAdm, $roleNor]);
        Permission::create([
            'name' => 'mejores.productos.index',
            'description' => 'Ver Mejores Productos',
        ])->syncRoles([$roleAdm, $roleNor]);
        Permission::create([
            'name' => 'monitor.index',
            'description' => 'Ver Monitor de Precios',
        ])->syncRoles([$roleAdm, $roleNor]);
        Permission::create([
            'name' => 'oportunidades.index',
            'description' => 'Ver Oportunidades de Ventas',
        ])->syncRoles([$roleAdm, $roleNor]);
        Permission::create([
            'name' => 'informes.excel',
            'description' => 'Descarga de Informes Excel',
        ])->syncRoles([$roleAdm, $roleNor]);
        Permission::create([
            'name' => 'consulta.stocks.netos',
            'description' => 'Consulta de Stocks y Netos',
        ])->syncRoles([$roleAdm, $roleNor]);
        Permission::create([
            'name' => 'desbloquear.pedidos',
            'description' => 'Desbloquear Pedidos',
        ])->syncRoles([$roleAdm, $roleNor]);
        Permission::create([
            'name' => 'subir.dtos.compra',
            'description' => 'Subir Dtos de Compra CSV',
        ])->syncRoles([$roleAdm, $roleNor]);
        Permission::create([
            'name' => 'modificar.precios',
            'description' => 'Modificar Precios en Masa',
        ])->syncRoles([$roleAdm, $roleNor]);
    }
}
