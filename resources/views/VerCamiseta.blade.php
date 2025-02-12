<x-header>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/verCamiseta.css') }}">
    @endpush
</x-header>

<main>
    <div class="contenedor-principal">
        <div>
            <h1>{{ $camisetaObtenida->nombre }}</h1>
        </div>

        <div class="contenedor-camiseta">
            <section>
                <article class="contenedor-imagen">
                    <img src="{{ $camisetaObtenida->imagen }}" alt="{{ $camisetaObtenida->nombre }}" id="imagen-principal"
                        title="{{ $camisetaObtenida->nombre }}">
                    <div class="lupa" id="lupa"></div>

                    <div class="miniaturas">
                        <img src="{{ $camisetaObtenida->imagen }}" alt="{{ $camisetaObtenida->nombre }}"
                            class="miniatura" onclick="cambiarImagen('{{ $camisetaObtenida->imagen }}')">
                        <img src="{{ $camisetaObtenida->imagen_trasera }}" alt="{{ $camisetaObtenida->nombre }}"
                            class="miniatura" onclick="cambiarImagen('{{ $camisetaObtenida->imagen_trasera }}')">
                    </div>
                </article>
            </section>

            <section>
                <article>
                    <h3>{{ $camisetaObtenida->nombre }}</h3>
                    <p>{{ $camisetaObtenida->descripcion }}</p>
                    <p id="precio">{{ $camisetaObtenida->precio }}</p>
                    <p id="stock-mensaje"></p>

                    @if (count($tallesDisponibles) > 0)
                        <select name="talle" id="talle">
                            <option value="">Talles disponibles</option>
                            @foreach ($tallesDisponibles as $talle)
                                <option value="stock_talle_{{ strtolower($talle['talle']) }}"
                                    data-stock="{{ $talle['stock'] }}">{{ $talle['talle'] }}
                                </option>
                            @endforeach
                        </select>
                        <input type="number" name="cantidad" id="cantidad" style="display: none;"
                            placeholder="Cantidad de camisetas">
                        <button class="btn-agregar" data-id="{{ $camisetaObtenida->id }}">🛒 Agregar al
                            carrito</button>
                    @else
                        <p style="color: red">No hay talles disponibles para esta camiseta en este momento.</p>
                    @endif

                    <p id="mensaje-error"></p>
                </article>
            </section>
        </div>

        <aside>
            <div>
                <h2 class="otros-productos-txt">Otros productos destacados</h2>
            </div>

            <div class="slider-container">
                <button class="slider-prev" onclick="moveSlide(-1)">&#10094;</button>

                @foreach ($camisetasEnOferta as $index => $camiseta)
                    <article class="slide {{ $index === 0 ? 'active' : '' }}">
                        <a href="/home/camiseta/{{ $camiseta->slug }}"><img class="camisetas-oferta"
                                src="{{ $camiseta->imagen }}" alt="{{ $camiseta->nombre }}"
                                title="{{ $camiseta->nombre }}">
                            <p>{{ $camiseta->nombre }}</p>
                            Ver

                            <p id="precio">{{ $camiseta->precio }}</p>
                        </a>
                    </article>
                @endforeach

                <button class="slider-next" onclick="moveSlide(1)">&#10095;</button>
            </div>
        </aside>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="{{ asset('js/ver.js') }}"></script>
<script>
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
            const x = ((e.clientX - left) / width) * 100;
            const y = ((e.clientY - top) / height) * 100;

            lupa.style.display = "block";
            lupa.style.backgroundImage = `url(${imagen.src})`;
            lupa.style.backgroundSize = `${width * 2}px ${height * 2}px`;
            lupa.style.backgroundPosition = `${x}% ${y}%`;
            lupa.style.left = `${e.clientX - 60}px`;
            lupa.style.top = `${e.clientY - 10}px`;
        });

        imagen.addEventListener("mouseleave", function() {
            lupa.style.display = "none";
        });

        const botonesAgregar = document.querySelectorAll(".btn-agregar");

        botonesAgregar.forEach(boton => {
            boton.addEventListener("click", function() {
                const camisetaId = boton.getAttribute("data-id");
                const cantidad = document.getElementById("cantidad").value;
                const talle = document.getElementById("talle").value;
                const url = encodeURIComponent(window.location.href);

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
                        document.querySelector(".carrito span").textContent =
                            response.carrito.cantidad;
                        document.querySelector(".carrito p").textContent =
                            `$${response.carrito.total}`;
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

    document.getElementById("talle").addEventListener("change", function() {
        const talle = this.value;
        const cantidadInput = document.getElementById("cantidad");
        const stockMensaje = document.getElementById('stock-mensaje');
        var selectedOption = this.options[this.selectedIndex];
        var stock = selectedOption.getAttribute('data-stock');

        if (talle) {
            cantidadInput.style.display = "block";
        } else {
            cantidadInput.style.display = "none";
        }

        if (stock) {
            stockMensaje.textContent = "Stock disponible: " + stock;
        } else {
            stockMensaje.textContent = "No hay stock disponible.";
        }
    });
</script>

<x-footer></x-footer>