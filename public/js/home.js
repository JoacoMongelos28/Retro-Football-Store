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