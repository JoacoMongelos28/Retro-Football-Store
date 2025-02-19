<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/compraMail.css') }}">
    <title>Retro Football Store</title>
</head>

<body>
    <main>
        <h3>Gracias por tu compra</h3>
        <section>
            @foreach ($productos as $producto)
                <article>
                    <img src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}">
                    <p>{{ $producto['nombre'] }}</p>
                    <p>{{ $producto['cantidad'] }}x{{ $producto['precio'] }}</p>
                    <p>Talle: {{ $producto['talle'] }}</p>
                    <div class="contenedor-a">
                        <a href="http://localhost/home/camiseta/{{ $producto['slug'] }}">Ver</a>
                    </div>
                </article>
            @endforeach
        </section>
    </main>
</body>

</html>
