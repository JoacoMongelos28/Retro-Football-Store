const $menuToggle = $('.menu-toggle');
const $menuNav = $('.contenedor-menu-nav');
const $menuLinks = $('.menu-a');

if ($menuToggle.length) {
    $menuToggle.on('click', function () {
        $menuNav.toggleClass('active');
    });

    $menuLinks.on('click', function () {
        $menuNav.removeClass('active');
    });

    $(document).on('click', function (event) {
        const isClickInsideMenu = $menuNav.has(event.target).length > 0;
        const isClickOnToggle = $menuToggle.has(event.target).length > 0;

        if (!isClickInsideMenu && !isClickOnToggle) {
            $menuNav.removeClass('active');
        }
    });
}

$(".group").click(function(event) {
    event.stopPropagation();

    if (!$(this).hasClass("expandido")) {
        $(this).addClass("expandido");
        $(this).find(".input").focus();
    }
});

$(document).click(function(event) {
    if (!$(event.target).closest(".group").length) {
        $(".group").removeClass("expandido");
    }
});

function guardarUrl() {
    localStorage.setItem("url_previa", window.location.href);
}

function redirectToUrl(form) {
    const filtro = $(form).find('input[name="filtro"]').val();
    const url = filtro ? `/camisetas/${encodeURIComponent(filtro.replace(/\s+/g, '-'))}` : '/camisetas';
    window.location.href = url;
    return false;
}