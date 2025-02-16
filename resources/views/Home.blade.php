<x-header>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/home.css') }}">
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
                        <div class="contenedor-btn-ver">VER</div></a>
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

    <aside class="slider-container">
        <button class="slider-prev" onclick="moveSlide(-1)">&#10094;</button>
    
        <div class="slider-wrapper">
            <div class="slider-track">
                @foreach ($camisetasEnOferta as $camiseta)
                    <article class="slide camiseta">
                        <a href="/home/camiseta/{{ $camiseta->slug }}" class="a-camiseta">
                            <div class="imagen-hover">
                                <img class="frontal" src="{{ $camiseta->imagen }}" alt="{{ $camiseta->nombre }}">
                                @if ($camiseta->imagen_trasera)
                                    <img class="trasera" src="{{ $camiseta->imagen_trasera }}" alt="{{ $camiseta->nombre }}">
                                @endif
                            </div>
                            <p class="nombre-camiseta">{{ $camiseta->nombre }}</p>
                            <p id="precio">${{ $camiseta->precio }}</p>
                            <div class="contenedor-btn-ver">VER</div>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    
        <button class="slider-next" onclick="moveSlide(1)">&#10095;</button>
    </aside>    
    </div>
</main>

<script src="{{ asset('js/home.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('.camiseta').each(function() {
            const trasera = $(this).find('.trasera');

            if (trasera.length > 0) {
                $(this).find('.imagen-hover').hover(
                    function() {
                        $(this).find('.frontal').css('opacity', '0');
                        $(this).find('.trasera').css('opacity', '1');
                    },
                    function() {
                        $(this).find('.frontal').css('opacity', '1');
                        $(this).find('.trasera').css('opacity', '0');
                    }
                );
            } else {
                $(this).find('.imagen-hover').off('mouseenter mouseleave');
            }
        });
    });    
</script>

<x-footer></x-footer>
