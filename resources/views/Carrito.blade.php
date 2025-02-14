<x-header>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/carrito.css') }}">
    @endpush
</x-header>


<main>
    <div class="contenedor-principal">
        <h1 class="titulo-txt">Carrito de Compras</h1>

        @if ($carrito && count($carrito) > 0)
        <div class="carrito-container">
            @foreach ($carrito as $camiseta)
                <div class="carrito-item" id="carrito-item-{{ $camiseta->id }}">
                    <div class="carrito-img">
                        <img src="{{ $camiseta->imagen }}" alt="{{ $camiseta->nombre }}">
                    </div>
                    <div class="carrito-info">
                        <a href="/home/camiseta/{{ $camiseta->slug }}" class="carrito-nombre">{{ $camiseta->nombre }}</a>
                        <p class="carrito-talle">Talle: <span class="camiseta-talle">{{ $camiseta->talle }}</span></p>
                    </div>
                    <div class="carrito-cantidad">
                        <button class="decrementar" data-id="{{ $camiseta->id }}">-</button>
                        <input type="number" value="{{ $camiseta->cantidad }}" id="cantidad-{{ $camiseta->id }}" readonly>
                        <button class="incrementar" data-id="{{ $camiseta->id }}">+</button>
                    </div>
                    <div class="carrito-precio">${{ $camiseta->precio }}</div>
                    <div class="carrito-subtotal" id="total-{{ $camiseta->id }}">${{ $camiseta->total }}</div>
                    <div class="carrito-eliminar">
                        <button class="eliminar" data-id="{{ $camiseta->id }}">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    
        <div id="contenedor-pagar">
            <p id="totalPrecio">Total: ${{ $total }}</p>
            <form action="/pagar" method="POST">
                @csrf
                <button type="submit">Comprar</button>
            </form>
        </div>
    @else
        <p id="mensaje-vacio">No hay productos en el carrito</p>
    @endif

    @if (session('exitoso'))
        <p>{{ session('exitoso') }}</p>
    @endif
    
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(".eliminar").click(function() {
        let camisetaId = $(this).data("id");
        let boton = $(this);

        $.ajax({
            url: '/eliminar',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: camisetaId
            },
            success: function(response) {
                boton.closest(".carrito-item").remove();
                $(".carrito span").text(response.cantidadTotal);
                $(".carrito p").text(`$${response.total}`);
                $("#totalPrecio").text(`$${response.total}`);

                if (response.cantidadTotal === 0) {
                    $("table").remove();
                    $("#contenedor-pagar").remove();
                    $(".contenedor-principal").append(
                        '<p id="mensaje-vacio">No hay productos en el carrito</p>');
                }
            },
            error: function(xhr) {
                console.error("Error al eliminar del carrito:", xhr.responseText);
            }
        });
    });

    $(".incrementar, .decrementar").click(function() {
        let id = $(this).data("id");
        let accion = $(this).hasClass("incrementar") ? 1 : -1;
        let stock = parseInt($("#stock").text());

        $.ajax({
            url: "/actualizar",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id,
                stock: stock,
                accion: accion
            },
            success: function(response) {
                if (response.success) {
                    $("#cantidad-" + id).val(response.nuevaCantidad);
                    $("#total-" + id).text(response.nuevoTotal);
                    $(".carrito span").text(response.totalCantidadHeader);
                    $(".carrito p").text(`$${response.totalHeader}`);
                    $("#totalPrecio").text(`$${response.totalHeader}`);
                }
            },
            error: function(xhr) {
                console.error("Error al actualizar cantidad:", xhr.responseText);
            }
        });
    });
</script>

<x-footer></x-footer>