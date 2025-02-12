<x-header>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/listadoCamisetas.css') }}">
    @endpush
</x-header>

<main>
    <div class="contenedor-principal">
        <div>
            <h1>Todas las Camisetas</h1>
        </div>

        <section class="contenedor-camisetas">
            @if (!empty($camisetas))
                @foreach ($camisetas as $camiseta)
                    <article>
                        <a href="/home/camiseta/{{ $camiseta->id }}"><img src="{{ $camiseta->imagen }}" alt="{{ $camiseta->nombre }}">
                        <p>{{ $camiseta->nombre }}</p>
                        Ver</a>
                        <p id="precio">{{ $camiseta->precio }}</p>
                        <button class="btn-agregar" data-id="{{ $camiseta->id }}">🛒 Agregar al carrito</button>
                    </article>
                @endforeach
            @endif

            {{ $camisetas->links() }}
        </section>
    </div>
</main>

<x-footer></x-footer>