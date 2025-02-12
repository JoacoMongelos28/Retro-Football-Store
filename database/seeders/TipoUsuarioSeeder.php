<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoUsuario;

class TipoUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = new TipoUsuario();
        $admin->tipo_usuario = 'admin';
        $usuario = new TipoUsuario();
        $usuario->tipo_usuario = 'usuario';

        $admin->save();
        $usuario->save();
    }
}
