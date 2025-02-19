<x-adminHeader>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @endpush
</x-adminHeader>

<main>
    <div class="contenedor-principal">

        <div>
            <h1 class="titulo-panel">Listado de Camisetas</h1>
        </div>

        <section>
            <article class="contenedor-opciones">
                <div>
                    <h3><a href="/admin/agregar">Agregar nueva camiseta</a></h3>
                </div>

                <div class="contenedor-filtro">
                    <form method="get" action="" class="form-filtro" id="formFiltro">
                        <select id="filtro" class="filtro-select" name="filtro">
                            <option value="todos" {{ $filtro == 'todos' ? 'selected' : '' }}>Todos</option>
                            <option value="destacados" {{ $filtro == 'destacados' ? 'selected' : '' }}>Destacados
                            </option>
                            <option value="ofertas" {{ $filtro == 'ofertas' ? 'selected' : '' }}>Ofertas</option>
                            <option value="precio-menor-mayor" {{ $filtro == 'precio-menor-mayor' ? 'selected' : '' }}>
                                Precio Menor a Mayor</option>
                            <option value="precio-mayor-menor" {{ $filtro == 'precio-mayor-menor' ? 'selected' : '' }}>
                                Precio Mayor a Menor</option>
                        </select>
                    </form>
                </div>
        </section>

        <div class="exitoso">
            @if (session('exitoso'))
                <p>
                    {{ session('exitoso') }}
                </p>
            @endif
        </div>

        <div>
            @if (session('error'))
                <p>
                    {{ session('error') }}
                </p>
            @endif
        </div>

        <div>
            @if (empty($camisetas) || $camisetas->isEmpty())
                <p class="error">No se encontraron camisetas</p>
            @endif
        </div>

        <section class="contenedor-camisetas">
            @if (!empty($camisetas) && $camisetas->isNotEmpty())
                @foreach ($camisetas as $camiseta)
                    <article class="camiseta">
                        <a href="/admin/camiseta/{{ $camiseta->slug }}" class="a-camiseta">
                            <div class="imagen-hover">
                                <img class="frontal" src="{{ $camiseta->imagen }}" alt="{{ $camiseta->nombre }}">
                                @if ($camiseta->imagen_trasera)
                                    <img class="trasera" src="{{ $camiseta->imagen_trasera }}"
                                        alt="{{ $camiseta->nombre }}">
                                @endif
                            </div>
                            <p class="nombre-camiseta">{{ $camiseta->nombre }}</p>
                            <p id="precio">${{ $camiseta->precio }}</p>
                            <div class="contenedor-botones-opciones">
                                <div class="contenedor-btn-ver">Ver</div>
                                <div class="contenedor-btn-editar"><a class="a-camiseta"
                                        href="/admin/editar/{{ $camiseta->slug }}">Editar</a></div>
                                <div class="contenedor-btn-eliminar"><button class="a-camiseta boton-eliminar" data-id="{{ $camiseta->id }}">Eliminar</button></div>
                            </div>
                        </a>
                    </article>
                @endforeach
            @endif
        </section>

        <div id="popup-eliminar" class="popup">
            <div class="popup-contenido">
                <p class="eliminar-txt">¿Quieres eliminar la camiseta?</p>
                <div class="contenedor-botones-popup">
                    <div>
                        <button id="cancelar-eliminar">Cancelar</button>
                    </div>
                    <div id="confirmar-eliminar">
                        <form id="form-eliminar" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<x-footer>
    @push('scripts')
        <script src="{{ asset('js/adminHome.js') }}"></script>
    @endpush
</x-footer>