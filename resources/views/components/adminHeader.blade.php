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
                    <span class="retro">Retro</span><span class="store">Store</span>
                </a>
                <div class="contenedor-carrito-menu">
                    <div class="contenedor-buscador-mobile">
                        <div class="group">
                            <svg class="icon" aria-hidden="true" viewBox="0 0 24 24">
                                <g>
                                    <path
                                        d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z">
                                    </path>
                                </g>
                            </svg>
                            <input name="admin-filtro" placeholder="Buscar camiseta" type="search" class="input" value="{{ request('buscar') }}">
                        </div>
                    </div>
                    <button class="menu-toggle" aria-label="Toggle menu">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
                <div class="contenedor-menu-nav">
                        <div class="group">
                            <svg class="icon" aria-hidden="true" viewBox="0 0 24 24">
                                <g>
                                    <path
                                        d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z">
                                    </path>
                                </g>
                            </svg>
                            <input name="admin-filtro" placeholder="Buscar camiseta" type="search" class="input" value="{{ request('buscar') }}">
                        </div>
                    <a class="menu-a" href="/admin">Home</a>
                    <a class="menu-a" href="/cerrar-sesion">Cerrar Sesion</a>
                </div>
            </div>
        </nav>
    </header>