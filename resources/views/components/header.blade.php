<html lang="es">

<head>
    <title>Retro Football Store</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Layout.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('styles')
</head>

<body>
    <header>
        <nav>
            <div class="contenedor-nav">
                <a href="/" class="logo-txt">
                    <span class="joaquin">Retro</span><span class="mongelos">Store</span>
                </a>
                <button class="menu-toggle" aria-label="Toggle menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="contenedor-menu-nav">
                    <a class="menu-a" href="/">Home</a>
                    @if ($usuario == null)
                        <a class="menu-a" href="/registro" onclick="guardarUrl()">Registrarse</a>
                        <a class="menu-a" href="/login" onclick="guardarUrl()">Iniciar Sesion</a>
                    @else
                        <a class="menu-a" href="/cerrar-sesion">Cerrar Sesion</a>
                        <div>
                            <a class="menu-a carrito" href="/carrito">
                                <p>${{ $total }}</p><i class="fa-solid fa-cart-shopping"></i>
                                <span>{{ $totalCantidad }}</span>
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </nav>
    </header>

    <script>
        function guardarUrl() {
            localStorage.setItem("url_previa", window.location.href);
        }
    </script>