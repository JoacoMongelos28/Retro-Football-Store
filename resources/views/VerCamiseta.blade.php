<x-header>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/verCamiseta.css') }}">
    @endpush
</x-header>

<main>
    <div class="contenedor-principal">
        @if ($camisetaObtenida)
        <div>
            <h1>{{ $camisetaObtenida->nombre }}</h1>
        </div>

        <div class="contenedor-camiseta">
            <section>
                <article>
                    <img src="{{ $camisetaObtenida->imagen }}" alt="{{ $camisetaObtenida->nombre }}"
                        title="{{ $camisetaObtenida->nombre }}">
                </article>
            </section>

            <section>
                <article>
                    <h3>{{ $camisetaObtenida->nombre }}</h3>
                    <p>{{ $camisetaObtenida->descripcion }}</p>
                    <p id="precio">{{ $camisetaObtenida->precio }}</p>

                    <select name="talle" id="talle">
                        <option value="">Selecciona un talle</option>
                        <option value="stock_talle_xs">XS</option>
                        <option value="stock_talle_s">S</option>
                        <option value="stock_talle_m">M</option>
                        <option value="stock_talle_l">L</option>
                        <option value="stock_talle_xl">XL</option>
                        <option value="stock_talle_xxl">XXL</option>
                    </select>
                    <input type="number" name="cantidad" id="cantidad" style="display: none;"
                        placeholder="Cantidad de camisetas">

                    <button class="btn-agregar" data-id="{{ $camisetaObtenida->id }}">🛒 Agregar al carrito</button>
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
                        
                        <p id="precio">{{ $camiseta->precio }}</p></a>
                    </article>
                @endforeach

                <button class="slider-next" onclick="moveSlide(1)">&#10095;</button>
            </div>
        </aside>
    </div>
    @else
        <h3>No existe la camiseta</h3>
    @endif
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="{{ asset('js/ver.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
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

        if (talle) {
            document.getElementById("cantidad").style.display = "block";
        } else {
            document.getElementById("cantidad").style.display = "none";
        }
    });
</script>

<x-footer></x-footer>
