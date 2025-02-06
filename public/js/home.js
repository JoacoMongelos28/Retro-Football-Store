let currentIndex = 0;

function showSlide(index) {
    const slides = document.querySelectorAll('.slide');
    if (index >= slides.length) {
        currentIndex = 0;
    } else if (index < 0) {
        currentIndex = slides.length - 1;
    } else {
        currentIndex = index;
    }

    // Ocultar todas las imágenes
    slides.forEach(slide => slide.style.display = 'none');

    // Mostrar la imagen actual
    slides[currentIndex].style.display = 'block';
}

function moveSlide(step) {
    showSlide(currentIndex + step);
}

// Inicializar el slider
document.addEventListener('DOMContentLoaded', function() {
    showSlide(currentIndex);
});
