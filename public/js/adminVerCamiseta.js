$(".boton-eliminar").on("click", function() {
    $(".popup").fadeIn();
});

$(".popup").on("click", function(event) {
    if (!$(event.target).closest(".popup-contenido").length) {
        $(".popup").fadeOut();
    }
});

$(".popup-contenido").on("click", function(event) {
    event.stopPropagation();
});

$("#cancelar-eliminar").on("click", function() {
    $(".popup").fadeOut();
});

function cambiarImagen(nuevaImagen) {
    document.getElementById("imagen-principal").src = nuevaImagen;
}

const $imagen = $('#imagen-principal');
const $lupa = $('#lupa');

$imagen.on('mousemove', function(e) {
    const { left, top, width, height } = $imagen[0].getBoundingClientRect();

    const x = ((e.clientX - left + window.scrollX) / width) * 100;
    const y = ((e.clientY - top + window.scrollY) / height) * 100;

    $lupa.css({
        'display': 'block',
        'background-image': `url(${$imagen[0].src})`,
        'background-size': `${width * 2}px ${height * 2}px`,
        'background-position': `${x}% ${y}%`,
        'left': `${e.clientX + window.scrollX - 60}px`,
        'top': `${e.clientY + window.scrollY - 60}px`
    });
});

$imagen.on('mouseleave', function() {
    $lupa.css('display', 'none');
});

function mostrarMensajeError(mensaje) {
    $('#mensaje-error').css({
        'color': 'red',
        'font-weight': 'bold',
        'margin-top': '10px'
    }).text(mensaje);
}