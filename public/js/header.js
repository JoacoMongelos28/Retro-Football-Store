// Seleccionamos los elementos necesarios
const menuToggle = document.querySelector('.menu-toggle');
const menuNav = document.querySelector('.contenedor-menu-nav');
const menuLinks = document.querySelectorAll('.menu-a');

// Alternar el menú cuando se presiona el botón
menuToggle.addEventListener('click', () => {
    menuNav.classList.toggle('active');
});

// Ocultar el menú cuando se presiona un enlace
menuLinks.forEach(link => {
    link.addEventListener('click', () => {
        menuNav.classList.remove('active');
    });
});

document.addEventListener('click', (event) => {
    const isClickInsideMenu = menuNav.contains(event.target);
    const isClickOnToggle = menuToggle.contains(event.target);

    if (!isClickInsideMenu && !isClickOnToggle) {
        menuNav.classList.remove('active');
    }
});