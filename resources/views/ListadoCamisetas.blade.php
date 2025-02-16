<x-header>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/listadoCamisetas.css') }}">
    @endpush
</x-header>

<main>
    <div class="contenedor-principal">
        <div>
            @if ($filtro ?? false)
                <h2 class="text-2xl">Resultados para: <strong>{{ $filtro }}</strong></h2>
            @else
                <h2 class="camisetas-txt text-2xl">Todas las Camisetas</h2>
            @endif
        </div>

        @if (!empty($camisetasFiltradas) && $filtro && $camisetasFiltradas->isEmpty())
            <p class="error">No se encontraron camisetas</p>
            <h4>Otras camisetas que podrian gustarte!</h4>
        @endif

        <section class="contenedor-camisetas">
            @if ($filtro)
                @if (!empty($camisetasFiltradas) && $camisetasFiltradas->isNotEmpty())
                    @foreach ($camisetasFiltradas as $camiseta)
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
                                Ver
                            </a>
                            <p id="precio">{{ $camiseta->precio }}</p>
                        </article>
                    @endforeach
                @else
                    @foreach ($camisetas as $camiseta)
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
                                Ver
                            </a>
                            <p id="precio">{{ $camiseta->precio }}</p>
                        </article>
                    @endforeach
                @endif
            @else
                @foreach ($camisetas as $camiseta)
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

        <div class="paginacion">
            {{ $camisetas->links() }}
        </div>
    </div>
</main>

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