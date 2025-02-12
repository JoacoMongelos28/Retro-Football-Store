<x-header>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    @endpush
</x-header>

<main>
    <div class="contenedor-principal">
        <div class="contenedor-buscador">
            <div class="group">
                <svg class="icon" aria-hidden="true" viewBox="0 0 24 24">
                    <g>
                        <path
                            d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z">
                        </path>
                    </g>
                </svg>
                <input placeholder="Buscar camiseta" type="search" class="input">
            </div>
        </div>

        <div>
            @if (session('error'))
                <p>
                    {{ session('error') }}
                </p>
            @endif
        </div>

        <div>
            <h2 class="titulo-productos-txt">Productos destacados</h2>
        </div>

        <section class="contenedor-camisetas-destacadas">
            @if (!empty($camisetasDestacadas))
                @foreach ($camisetasDestacadas as $camiseta)
                    <article>
                        <a href="/home/camiseta/{{ $camiseta->slug }}"><img src="{{ $camiseta->imagen }}" alt="{{ $camiseta->nombre }}">
                        <p>{{ $camiseta->nombre }}</p>
                        Ver</a>
                        <p id="precio">{{ $camiseta->precio }}</p>
                    </article>
                @endforeach
            @endif
        </section>

        <div>
            <a href="/camisetas">Ver todas las camisetas</a>
        </div>

        <div>
            <h2 class="titulo-productos-txt">Productos en oferta</h2>
        </div>

        <aside class="slider-container">
            <button class="slider-prev" onclick="moveSlide(-1)">&#10094;</button>

            @foreach ($camisetasEnOferta as $index => $camiseta)
                <article class="slide {{ $index === 0 ? 'active' : '' }}">
                    <a href="/home/camiseta/{{ $camiseta->slug }}"><img src="{{ $camiseta->imagen }}" alt="{{ $camiseta->nombre }}" title="{{ $camiseta->nombre }}">
                    <p>{{ $camiseta->nombre }}</p>
                    Ver</a>
                    <p id="precio">{{ $camiseta->precio }}</p>
                </article>
            @endforeach

            <button class="slider-next" onclick="moveSlide(1)">&#10095;</button>
        </aside>
    </div>
</main>

<script src="{{ asset('js/home.js') }}"></script>

<x-footer></x-footer>
