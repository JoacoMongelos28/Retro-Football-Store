<x-header>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/home.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    @endpush
</x-header>

<main>
    <div class="contenedor-principal">
        <div class="titulo-principal">
            <h1 class="text-3xl">Retro Football Store</h1>
        </div>

        <div>
            @if (session('error'))
                <p>
                    {{ session('error') }}
                </p>
            @endif
        </div>

        <div>
            <h2 class="titulo-productos-txt text-2xl">Productos destacados</h2>
        </div>

        <section class="contenedor-camisetas-destacadas">
            @if (!empty($camisetasDestacadas))
                @foreach ($camisetasDestacadas as $camiseta)
                    <article class="camiseta">
                        <a href="/home/camiseta/{{ $camiseta->slug }}" class="a-camiseta">
                            <div class="imagen-hover">
                                <img class="frontal" src="{{ $camiseta->imagen }}" alt="{{ $camiseta->nombre }}">
                                @if ($camiseta->imagen_trasera)
                                    <img class="trasera" src="{{ $camiseta->imagen_trasera }}"
                                        alt="{{ $camiseta->nombre }}">
                                @endif
                            </div>
                            <p class="nombre-camiseta">{{ $camiseta->nombre }}</p>
                            <p id="precio">${{ $camiseta->precio }}</p>
                            <div class="contenedor-btn-ver">VER</div>
                        </a>
                    </article>
                @endforeach
            @endif
        </section>

        <div class="contenedor-ver-todas">
            <a href="/camisetas" class="ver-todas-camisetas">Ver todas las camisetas</a>
        </div>

        <div>
            <h2 class="titulo-productos-txt text-2xl">Productos en oferta</h2>
        </div>

        <aside class="aside">
            <div class="contenedor-slider-principal swiper">
                <div class="contenedor-slider card-wrapper">
                    <ul class="contenedor-camisetas swiper-wrapper">
                        @foreach ($camisetasEnOferta as $camiseta)
                            <li class="camiseta camiseta-li swiper-slide">
                                <a href="/home/camiseta/{{ $camiseta->slug }}" class="a-camiseta">
                                    <div class="imagen-hover">
                                        <img class="frontal" src="{{ $camiseta->imagen }}"
                                            alt="{{ $camiseta->nombre }}">
                                        @if ($camiseta->imagen_trasera)
                                            <img class="trasera" src="{{ $camiseta->imagen_trasera }}"
                                                alt="{{ $camiseta->nombre }}">
                                        @endif
                                    </div>
                                    <p class="nombre-camiseta">{{ $camiseta->nombre }}</p>
                                    <p id="precio">${{ $camiseta->precio }}</p>
                                    <div class="contenedor-btn-ver">VER</div>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="swiper-pagination"></div>
                    <div class="swiper-slide-button swiper-button-prev"></div>
                    <div class="swiper-slide-button swiper-button-next"></div>
                </div>
            </div>
        </aside>
    </div>
</main>

<x-footer>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script src="{{ asset('js/home.js') }}"></script>
    @endpush
</x-footer>