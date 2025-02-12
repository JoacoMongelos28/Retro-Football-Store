<!DOCTYPE html>
<html lang="es">

<head>
    <title>Retro Football Store</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Layout.css') }}">
    @stack('styles')
</head>

<body>
    <header>
        <nav>
            <div class="contenedor-nav">
                <a href="/admin" class="logo-txt">
                    <span class="joaquin">Retro</span><span class="mongelos">Store</span>
                </a>
                <button class="menu-toggle" aria-label="Toggle menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="contenedor-menu-nav">
                    <a class="menu-a" href="/admin">Home</a>
                    <a class="menu-a" href="/admin/listado">Listado de Camisetas</a>
                    <a class="menu-a" href="/cerrar-sesion">Cerrar Sesion</a>
                </div>
            </div>
        </nav>
    </header>