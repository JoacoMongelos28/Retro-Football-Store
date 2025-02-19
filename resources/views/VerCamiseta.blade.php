<x-header>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/verCamiseta.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    @endpush
</x-header>

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

                    <div>
                        <p class="talles-txt">Talles disponibles:</p>
                        <div class="contenedor-talles">
                            @php $hayStock = false; @endphp

                            @foreach (['xs', 's', 'm', 'l', 'xl', 'xxl'] as $talle)
                                @php
                                    $stock = $camisetaObtenida["stock_talle_$talle"];
                                    $disabled = $stock == 0 ? 'disabled' : '';
                                    if ($stock > 0) {
                                        $hayStock = true;
                                    }
                                @endphp
                                <label class="talle-btn {{ $stock == 0 ? 'disabled' : '' }}">
                                    <input type="radio" name="talle" id="talle_{{ $talle }}"
                                        value="stock_talle_{{ $talle }}" data-stock="{{ $stock }}"
                                        {{ $disabled }}>
                                    {{ strtoupper($talle) }}
                                </label>
                            @endforeach
                        </div>

                        @if (!$hayStock)
                            <p class="mensaje-sin-stock">No hay stock disponible para esta camiseta.</p>
                        @endif
                        <p id="stock-mensaje"></p>
                        <div class="contenedor-botones-agregar" style="display: none;">
                            <div class="contenedor-cantidad">
                                <button class="btn-cantidad decrementar">−</button>
                                <input type="number" name="cantidad" id="cantidad" min="1" value="1"
                                    class="input-cantidad" readonly>
                                <button class="btn-cantidad incrementar">+</button>
                            </div>
                            <div>
                                <button class="btn-agregar" data-id="{{ $camisetaObtenida->id }}">AGREGAR AL
                                    CARRITO</button>
                            </div>
                        </div>
                    </div>
                    <p id="mensaje-error"></p>
                    <p class="descripcion-txt">{{ $camisetaObtenida->descripcion }}</p>
                </article>
            </section>
        </div>

        <aside class="aside">
            <div>
                <h2 class="otros-productos-txt text-2xl">Otros productos destacados</h2>
            </div>

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
    <input type="hidden" id="idUsuario" value="{{ $usuario }}">
</main>

<x-footer>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script src="{{ asset('js/verCamiseta.js') }}"></script>
    @endpush
</x-footer>