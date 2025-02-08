<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tipo_usuario', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_usuario');
        });

        Schema::create('usuario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('usuario');
            $table->string('email');
            $table->string('contraseña');
            $table->unsignedBigInteger('tipo_usuario');
            $table->foreign('tipo_usuario')->references('id')->on('tipo_usuario')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('carrito', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('usuario')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('carrito_camiseta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('carrito_id');
            $table->unsignedBigInteger('camiseta_id');
            $table->integer('cantidad');
            $table->string('talle');
            $table->foreign('carrito_id')->references('id')->on('carrito')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('camiseta_id')->references('id')->on('camiseta')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET foreign_key_checks = 0;');

        Schema::dropIfExists('usuario');
        Schema::dropIfExists('tipo_usuario');
        Schema::dropIfExists('carrito');
        Schema::dropIfExists('carrito_camiseta');

        DB::statement('SET foreign_key_checks = 1;');
    }
};
