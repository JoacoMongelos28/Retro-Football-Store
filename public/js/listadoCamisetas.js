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

$('#filtro').on('change', function() {
    const filtro = $(this).val();
    const url = `/camisetas/${filtro}`;
    window.location.href = url;
});