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