<x-AdminHeader>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/adminVerCamiseta.css') }}">
    @endpush
</x-AdminHeader>

<main>
    <div class="contenedor-principal">
        <div class="contenedor-camiseta">
            <section>
                <article class="contenedor-imagen">
                    <div class="miniaturas">
                        <img src="{{ $camisetaObtenida->imagen }}" alt="{{ $camisetaObtenida->nombre }}" class="miniatura"
                            onclick="cambiarImagen('{{ $camisetaObtenida->imagen }}')">
                        @if ($camisetaObtenida->imagen_trasera)
                            <img src="{{ $camisetaObtenida->imagen_trasera }}" alt="{{ $camisetaObtenida->nombre }}"
                                class="miniatura" onclick="cambiarImagen('{{ $camisetaObtenida->imagen_trasera }}')">
                        @endif
                    </div>

                    <img src="{{ $camisetaObtenida->imagen }}" alt="{{ $camisetaObtenida->nombre }}"
                        id="imagen-principal" title="{{ $camisetaObtenida->nombre }}">
                    <div class="lupa" id="lupa"></div>
                </article>
            </section>

            <section class="contenedor-info">
                <article>
                    <h2 class="nombre-camiseta text-2xl">{{ $camisetaObtenida->nombre }}</h2>
                    <p id="precio">${{ $camisetaObtenida->precio }}</p>
                    <p class="descripcion-txt">{{ $camisetaObtenida->descripcion }}</p>
                    <div>
                        <p class="talles-txt">Stock de cada talle:</p>
                        <div class="contenedor-talles">
                            @php $hayStock = false; @endphp

                            @foreach (['xs', 's', 'm', 'l', 'xl', 'xxl'] as $talle)
                                @php
                                    $stock = $camisetaObtenida["stock_talle_$talle"];
                                    if ($stock > 0) {
                                        $hayStock = true;
                                    }
                                @endphp

                                <div class="contenedor-info-stock">
                                    <span>{{ strtoupper($talle) }} = </span>
                                    <span class="stock-info">
                                        {{ $stock > 0 ? 'Stock disponible: ' . $stock : 'Sin stock' }}</span>
                                </div>
                            @endforeach
                        </div>
                        @if (!$hayStock)
                            <p class="mensaje-sin-stock">No hay stock disponible para esta camiseta.</p>
                        @endif
                    </div>
                    <div>
                        <div class="contenedor-botones-opciones">
                            <a class="a-camiseta contenedor-btn-volver" href="/admin">Volver</a>
                            <a class="a-camiseta contenedor-btn-editar"
                                href="/admin/editar/{{ $camisetaObtenida->slug }}">Editar</a>
                            <div class="contenedor-btn-eliminar"><button class="boton-eliminar">Eliminar</button></div>
                        </div>
                    </div>
                </article>
            </section>
        </div>
    </div>

    <div id="popup-eliminar" class="popup">
        <div class="popup-contenido">
            <p class="eliminar-txt">¿Quieres eliminar la camiseta?</p>
            <div class="contenedor-botones-popup">
                <div>
                    <button id="cancelar-eliminar">Cancelar</button>
                </div>
                <div id="confirmar-eliminar">
                    <form id="form-eliminar" method="POST" action="/admin/eliminar/{{ $camisetaObtenida->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<x-footer>
    @push('scripts')
        <script src="{{ asset('js/adminVerCamiseta.js') }}"></script>
    @endpush
</x-footer>
