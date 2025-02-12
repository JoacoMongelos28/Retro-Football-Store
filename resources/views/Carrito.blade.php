<x-header>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/carrito.css') }}">
    @endpush
</x-header>


<main>
    <div class="contenedor-principal">
        <h1>Carrito</h1>

        @if (session('exitoso'))
            <p>{{ session('exitoso') }}</p>
        @endif

        @if ($carrito && count($carrito) > 0)
            <table>
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($carrito as $camiseta)
                        <tr>
                            <td><img src="{{ $camiseta->imagen }}"></td>
                            <td>{{ $camiseta->nombre }}</td>
                            <td>${{ $camiseta->precio }}</td>
                            <td id="total-{{ $camiseta->id }}">{{ $camiseta->total }}</td>
                            <td>
                                <button class="decrementar" data-id="{{ $camiseta->id }}">-</button>
                                <input type="number" value="{{ $camiseta->cantidad }}" id="cantidad-{{ $camiseta->id }}"
                                    readonly>
                                <button class="incrementar" data-id="{{ $camiseta->id }}">+</button>
                            </td>
                            <td><button class="eliminar" data-id="{{ $camiseta->id }}">Eliminar</button></td>
                            <td><a href="/home/camiseta/{{ $camiseta->slug }}">Ver</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p id="totalPrecio">${{ $total }}</p>
            <form action="/pagar" method="POST">
                @csrf
                <button type="submit">Comprar</button>
            </form>
        @else
            <p>Tu carrito está vacío.</p>
        @endif
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
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
                    boton.closest("tr").remove();
                    $(".carrito span").text(response.cantidadTotal);
                    $(".carrito p").text(`$${response.total}`);
                    $("#totalPrecio").text(`$${response.total}`);
                },
                error: function(xhr) {
                    console.error("Error al eliminar del carrito:", xhr.responseText);
                }
            });
        });

        $(".incrementar, .decrementar").click(function() {
            let id = $(this).data("id");
            let accion = $(this).hasClass("incrementar") ? 1 : -1;

            $.ajax({
                url: "/actualizar",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: id,
                    accion: accion
                },
                success: function(response) {
                    if (response.success) {
                        $("#cantidad-" + id).val(response.nuevaCantidad);
                        $("#total-" + id).text(response.nuevoTotal);
                        $(".carrito span").text(response.totalCantidadHeader);
                        $(".carrito p").text(`$${response.totalHeader}`);
                        $("#totalPrecio").text(`$${response.totalHeader}`);
                    } else {
                        console.error("Error al actualizar cantidad:", response.error);
                    }
                },
                error: function(xhr) {
                    console.error("Error al actualizar cantidad:", xhr.responseText);
                }
            });
        });
    });
</script>

<x-footer></x-footer>