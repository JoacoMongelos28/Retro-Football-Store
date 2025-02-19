$(document).ready(function() {
    const redirectUrl = localStorage.getItem("url_previa") || "/home";
    $("#redirect").val(redirectUrl);
});