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
                    <button class="btn-agregar" data-id="{{ $camisetaObtenida->id }}">🛒 Agregar al carrito</button>
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
                        <a href="/home/camiseta/{{ $camiseta->id }}"><img class="camisetas-oferta"
                                src="{{ $camiseta->imagen }}" alt="{{ $camiseta->nombre }}"
                                title="{{ $camiseta->nombre }}">
                            <p>{{ $camiseta->nombre }}</p>
                            Ver
                        </a>
                        <p id="precio">{{ $camiseta->precio }}</p>
                        <button class="btn-agregar" data-id="{{ $camiseta->id }}">🛒 Agregar al carrito</button>
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
    document.addEventListener("DOMContentLoaded", function() {
        const botonesAgregar = document.querySelectorAll(".btn-agregar");

        botonesAgregar.forEach(boton => {
            boton.addEventListener("click", function() {
                const camisetaId = boton.getAttribute("data-id");
                const cantidad = 1;
                const idUsuario = 1;

                $.ajax({
                    url: '/agregar',
                    method: 'POST',
                    data: {
                        id: camisetaId,
                        cantidad: cantidad,
                        idUsuario: idUsuario,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        document.querySelector(".carrito span").textContent =
                            response.carrito.cantidad;
                        document.querySelector(".carrito p").textContent =
                            `$${response.carrito.total}`;
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    error: function(error) {
                        if (error.status === 401) {
                            window.location.href = '/login';
                        } else {
                            alert(
                                "Ocurrió un error al agregar al carrito. Por favor, inténtalo de nuevo más tarde.");
                        }
                    }
                });
            });
        });
    });
</script>

<x-footer></x-footer>
