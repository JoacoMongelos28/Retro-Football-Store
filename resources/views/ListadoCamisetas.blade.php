<x-header>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/listadoCamisetas.css') }}">
    @endpush
</x-header>

<main>
    <div class="contenedor-principal">
        <div>
            @if ($filtro && !in_array($filtro, ['destacados', 'ofertas', 'precio-menor-mayor', 'precio-mayor-menor', 'todos']))
                <h2 class="text-3xl resultado">Resultados para: <strong>{{ $filtro }}</strong></h2>
            @else
                <h2 class="camisetas-txt text-3xl">Todas las Camisetas</h2>
            @endif
        </div>        

        @if (!empty($camisetasFiltradas) && $filtro && $camisetasFiltradas->isEmpty())
            <p class="error">No se encontraron camisetas</p>
        @endif

        <div class="contenedor-filtro">
            <form method="get" action="" class="form-filtro" id="formFiltro">
                <select id="filtro" class="filtro-select" name="filtro" onchange="filtrarCamisetas()">
                    <option value="todos" {{ $filtro == 'todos' ? 'selected' : '' }}>Todos</option>
                    <option value="destacados" {{ $filtro == 'destacados' ? 'selected' : '' }}>Destacados</option>
                    <option value="ofertas" {{ $filtro == 'ofertas' ? 'selected' : '' }}>Ofertas</option>
                    <option value="precio-menor-mayor" {{ $filtro == 'precio-menor-mayor' ? 'selected' : '' }}>Precio Menor a Mayor</option>
                    <option value="precio-mayor-menor" {{ $filtro == 'precio-mayor-menor' ? 'selected' : '' }}>Precio Mayor a Menor</option>
                </select>
            </form>
        </div>

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
                                <p id="precio">{{ $camiseta->precio }}</p>
                                <div class="contenedor-btn-ver">VER</div>
                            </a>
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
                                <p id="precio">{{ $camiseta->precio }}</p>
                                <div class="contenedor-btn-ver">VER</div>
                            </a>
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

<x-footer>
    @push('scripts')
        <script src="{{ asset('js/listadoCamisetas.js') }}"></script>
    @endpush
</x-footer>