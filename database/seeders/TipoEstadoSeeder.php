<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoEstado;

class TipoEstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $destacado = new TipoEstado();
        $destacado->estado = 'destacado';
        $oferta = new TipoEstado();
        $oferta->estado = 'oferta';

        $destacado->save();
        $oferta->save();
    }
}
