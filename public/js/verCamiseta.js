new Swiper('.card-wrapper', {
    loop: true,
    spaceBetween: 30,

    pagination: {
        el: '.swiper-pagination',
        clickable: true,
        dynamicBullets: true,
    },

    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },

    breakpoints: {
        0: {
            slidesPerView: 2,
        },
        768: {
            slidesPerView: 2,
        },
        1024: {
            slidesPerView: 4,
        },
    }
});

const talleRadio = $('#talle');

if (talleRadio) {

    $('input[name="talle"]').change(function () {
        let stock = $(this).data('stock');
        $('.contenedor-botones-agregar').show();
        $('#stock-mensaje').text(stock ? "Stock disponible: " + stock : "No hay stock disponible.");
        $('.talle-btn').css('background-color', '');
        $(this).parent().css('background-color', 'rgb(232, 41, 91)');
    });
}

$(".incrementar, .decrementar").click(function () {
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

const idUsuario = $('#idUsuario').val();

if (idUsuario) {
    localStorage.removeItem("url_previa");
}

function cambiarImagen(nuevaImagen) {
    $('#imagen-principal').attr('src', nuevaImagen);
}

function mostrarMensajeError(mensaje) {
    $('#mensaje-error').css({
        'color': 'red',
        'font-weight': 'bold',
        'margin-top': '10px'
    }).text(mensaje);
}

function mostrarMensajeExitoso(mensaje) {
    $('#mensaje-error').css({
        'color': 'green',
        'font-weight': 'bold',
        'margin-top': '10px'
    }).text(mensaje);
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

$('.btn-agregar').on('click', function() {
    const camisetaId = $(this).data('id');
    const cantidad = $('#cantidad').val();
    const talleSeleccionado = $('input[name="talle"]:checked');
    const url = encodeURIComponent(window.location.href);

    if (!talleSeleccionado.length) {
        mostrarMensajeError("Debe seleccionar un talle.");
        return;
    }

    const talle = talleSeleccionado.val();
    const stock = talleSeleccionado.data('stock');

    $.ajax({
        url: '/agregar',
        method: 'POST',
        data: {
            id: camisetaId,
            cantidad: cantidad,
            talle: talle,
            url: url,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $(".carrito span").each(function() {
                $(this).text(response.carrito.cantidad);
            });

            $(".carrito p").each(function() {
                $(this).text(`$${response.carrito.total}`);
            });

            mostrarMensajeExitoso("Camiseta agregada al carrito correctamente");
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