<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/compra.css') }}">
    <title>Retro Football Store</title>
</head>
<body>
    <main>
        <h3>Gracias por tu compra</h3>
        <section>
            @foreach ($productos as $camiseta)
                <article>
                    <img src="{{ $camiseta->imagen }}" alt="{{ $camiseta->nombre }}">
                    <p>{{ $camiseta->nombre }}</p>
                    <p>{{ $camiseta->cantidad }}x{{ $camiseta->precio }}</p>
                    <p>Talle: {{ $camiseta->talle }}</p>
                    <div class="contenedor-a">
                        <a href="{{ route('verCamiseta', ['camiseta' => $camiseta->slug]) }}">Ver</a>
                    </div>
                </article>
            @endforeach
        </section>
    </main>
</body>
</html>