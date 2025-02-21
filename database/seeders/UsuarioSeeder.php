<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RegistroModel;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarioAdmin = new RegistroModel();
        $usuarioAdmin->nombre = 'admin';
        $usuarioAdmin->usuario = 'admin';
        $usuarioAdmin->email = 'admin@gmail.com'; 
        $usuarioAdmin->contraseña = Hash::make('admin');
        $usuarioAdmin->tipo_usuario = 1;

        $usuarioAdmin->save();
    }
}
