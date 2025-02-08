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

        Schema::create('tipo_estado', function (Blueprint $table) {
            $table->id('id');
            $table->string('estado');
            
        });
        
        Schema::create('camiseta', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->integer('precio');
            $table->integer('stock_talle_s');
            $table->integer('stock_talle_m');
            $table->integer('stock_talle_l');
            $table->integer('stock_talle_xl');
            $table->integer('stock_talle_xxl');
            $table->integer('stock_talle_xs');
            $table->unsignedBigInteger('estado');
            $table->foreign('estado')->references('id')->on('tipo_estado')->onDelete('cascade')->onUpdate('cascade');
        });
        
        DB::statement('ALTER TABLE camiseta ADD COLUMN imagen MEDIUMBLOB');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camiseta');
        Schema::dropIfExists('tipo_estado');
    }
};
