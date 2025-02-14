<x-header>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/verCamiseta.css') }}">
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

        <aside>
            <div>
                <h2 class="otros-productos-txt text-2xl">Otros productos destacados</h2>
            </div>

            <div class="slider-container">
                <button class="slider-prev" onclick="moveSlide(-1)">&#10094;</button>

                @foreach ($camisetasEnOferta as $index => $camiseta)
                    <article class="slide {{ $index === 0 ? 'active' : '' }} camiseta">
                        <a href="/home/camiseta/{{ $camiseta->slug }}" class="a-camiseta">
                            <div class="imagen-hover">
                                <img class="frontal" src="{{ $camiseta->imagen }}" alt="{{ $camiseta->nombre }}">
                                @if ($camiseta->imagen_trasera)
                                    <img class="trasera" src="{{ $camiseta->imagen_trasera }}"
                                        alt="{{ $camiseta->nombre }}">
                                @endif
                            </div>
                            <p class="nombre-camiseta-oferta">{{ $camiseta->nombre }}</p>
                            <p class="precio-oferta">${{ $camiseta->precio }}</p>
                            <div class="contenedor-btn-ver">VER</div>
                        </a>
                    </article>
                @endforeach

                <button class="slider-next" onclick="moveSlide(1)">&#10095;</button>
            </div>
        </aside>
    </div>
    <input type="hidden" id="idUsuario" value="{{ $usuario }}">
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="{{ asset('js/ver.js') }}"></script>

<script>
    const talleRadio = $('#talle');

    if (talleRadio) {

        $('input[name="talle"]').change(function() {
            let stock = $(this).data('stock');
            $('.contenedor-botones-agregar').show();
            $('#stock-mensaje').text(stock ? "Stock disponible: " + stock : "No hay stock disponible.");
            $('.talle-btn').css('background-color', '');
            $(this).parent().css('background-color', 'rgb(232, 41, 91)');
        });
    }

    $(".incrementar, .decrementar").click(function() {
        let cantidadInput = $("#cantidad");
        let cantidad = parseInt(cantidadInput.val()) || 1;
        let accion = $(this).hasClass("incrementar") ? 1 : -1;

        cantidad += accion;

        if (cantidad < 1) {
            cantidad = 1;
        }

        if (cantidad > 10) {
            cantidad = 10;
        }

        cantidadInput.val(cantidad);
    });

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

        const idUsuario = $('#idUsuario').val();

        if (idUsuario) {
            localStorage.removeItem("url_previa");
        }
    });

    function cambiarImagen(nuevaImagen) {
        document.getElementById("imagen-principal").src = nuevaImagen;
    }

    document.addEventListener("DOMContentLoaded", function() {
        const imagen = document.getElementById("imagen-principal");
        const lupa = document.getElementById("lupa");

        imagen.addEventListener("mousemove", function(e) {
            const {
                left,
                top,
                width,
                height
            } = imagen.getBoundingClientRect();

            const x = ((e.clientX - left + window.scrollX) / width) * 100;
            const y = ((e.clientY - top + window.scrollY) / height) * 100;

            lupa.style.display = "block";

            lupa.style.backgroundImage = `url(${imagen.src})`;
            lupa.style.backgroundSize = `${width * 2}px ${height * 2}px`;
            lupa.style.backgroundPosition = `${x}% ${y}%`;

            lupa.style.left = `${e.clientX + window.scrollX - 60}px`;
            lupa.style.top = `${e.clientY + window.scrollY - 60}px`;
        });

        imagen.addEventListener("mouseleave", function() {
            lupa.style.display = "none";
        });

        const botonesAgregar = document.querySelectorAll(".btn-agregar");

        botonesAgregar.forEach(boton => {
            boton.addEventListener("click", function() {
                const camisetaId = boton.getAttribute("data-id");
                const cantidad = document.getElementById("cantidad").value;
                const talleSeleccionado = document.querySelector('input[name="talle"]:checked');
                const url = encodeURIComponent(window.location.href);
                const talle = talleSeleccionado.value;
                const stock = talleSeleccionado.getAttribute("data-stock");

                $.ajax({
                    url: '/agregar',
                    method: 'POST',
                    data: {
                        id: camisetaId,
                        cantidad: cantidad,
                        talle: talle,
                        url: url,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        document.querySelectorAll(".carrito span").forEach(span => {
                            span.textContent = response.carrito.cantidad;
                        });

                        document.querySelectorAll(".carrito p").forEach(p => {
                            p.textContent = `$${response.carrito.total}`;
                        });

                        mostrarMensajeExitoso(
                            "Camiseta agregada al carrito correctamente");
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                }).fail(function(xhr) {
                    if (xhr.status === 401) {
                        localStorage.setItem('url_previa', window.location.href);
                        window.location.href = '/login';
                    } else {
                        let response = JSON.parse(xhr.responseText);
                        mostrarMensajeError(response.error);
                        console.clear();
                    }
                });
            });
        });

        function mostrarMensajeError(mensaje) {
            let mensajeError = document.getElementById("mensaje-error");
            mensajeError.style.color = "red";
            mensajeError.style.fontWeight = "bold";
            mensajeError.style.marginTop = "10px";
            mensajeError.textContent = mensaje;
        }

        function mostrarMensajeExitoso(mensaje) {
            let mensajeError = document.getElementById("mensaje-error");
            mensajeError.style.color = "green";
            mensajeError.style.fontWeight = "bold";
            mensajeError.style.marginTop = "10px";
            mensajeError.textContent = mensaje;
        }
    });
</script>

<x-footer></x-footer>
