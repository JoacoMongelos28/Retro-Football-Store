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

$("input[name='admin-filtro']").on("input", function () {
    let filtro = $(this).val().toLowerCase().trim();
    $(".nombre-camiseta").each(function () {
        let nombreCamiseta = $(this).text().toLowerCase();
        if (nombreCamiseta.includes(filtro)) {
            $(this).closest(".camiseta").show();
        } else {
            $(this).closest(".camiseta").hide();
        }
    });
});