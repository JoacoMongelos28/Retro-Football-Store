<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\CarritoController;

Route::get('/', [HomeController::class, 'index']);

Route::get('home', [HomeController::class, 'index']);

Route::get('admin', [AdminController::class, 'admin']);

Route::get('registro', [RegistroController::class, 'registro']);

Route::post('registro/registrarUsuario', [RegistroController::class, 'registrarUsuario']);

Route::get('login', [LoginController::class, 'login']);

Route::post('validar', [LoginController::class, 'validar']);

Route::get('admin/agregar', [AdminController::class, 'agregar']);

Route::post('admin/agregarCamiseta', [AdminController::class, 'guardarCamiseta']);

Route::get('admin/editar/{camiseta:slug}', [AdminController::class, 'editar']);

Route::post('admin/editarCamiseta/{id}', [AdminController::class, 'editarCamiseta']);

Route::delete('admin/eliminar/{id}', [AdminController::class, 'eliminarCamiseta']);

Route::get('admin/camiseta/{camiseta:slug}', [AdminController::class, 'verCamiseta']);

Route::get('admin/camisetas/{filtro?}', [AdminController::class, 'filtrarCamisetas']);

Route::get('home/camiseta/{camiseta:slug}', [HomeController::class, 'verCamiseta']);

Route::post('agregar', [CarritoController::class, 'agregarAlCarrito']);

Route::get('carrito', [CarritoController::class, 'mostrarCarrito']);

Route::post('eliminar', [CarritoController::class, 'eliminarCamisetaDelCarrito']);

Route::post('actualizar', [CarritoController::class, 'actualizarCantidad']);

Route::post('pagar', [CarritoController::class, 'procesarPago']);

Route::get('carrito/estado', [CarritoController::class, 'pagoExitoso']);

Route::get('cerrar-sesion', [LoginController::class, 'cerrarSesion']);

Route::get('admin/cerrar-sesion', [LoginController::class, 'cerrarSesion']);

Route::get('camisetas/{filtro?}', [HomeController::class, 'listadoDeCamisetas']);