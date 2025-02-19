$('.camiseta').each(function () {
    const trasera = $(this).find('.trasera');

    if (trasera.length > 0) {
        $(this).find('.imagen-hover').hover(
            function () {
                $(this).find('.frontal').css('opacity', '0');
                $(this).find('.trasera').css('opacity', '1');
            },
            function () {
                $(this).find('.frontal').css('opacity', '1');
                $(this).find('.trasera').css('opacity', '0');
            }
        );
    } else {
        $(this).find('.imagen-hover').off('mouseenter mouseleave');
    }
});

$('#filtro').on('change', function () {
    const filtro = $(this).val();
    const url = `/admin/camisetas/${filtro}`;
    window.location.href = url;
});

const popupEliminar = $("#popup-eliminar");
const botonesEliminar = $(".boton-eliminar");
const cancelarEliminar = $("#cancelar-eliminar");
const formEliminar = $("#form-eliminar");

botonesEliminar.on("click", function () {
    const camisetaId = $(this).data("id");
    formEliminar.attr("action", `/admin/eliminar/${camisetaId}`);
    popupEliminar.fadeIn();
});

$(".popup").on("click", function (event) {
    if (!$(event.target).closest(".popup-contenido").length) {
        popupEliminar.fadeOut();
    }
});

$(".popup-contenido").on("click", function (event) {
    event.stopPropagation();
});

cancelarEliminar.on("click", function () {
    popupEliminar.fadeOut();
});