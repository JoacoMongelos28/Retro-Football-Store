let currentIndex = 0;
const slidesToShow = 3; // Cantidad de camisetas visibles al mismo tiempo
const slideWidth = 265; // Ancho del slide (ajústalo según el CSS)
const sliderTrack = document.querySelector('.slider-track');

function moveSlide(step) {
    const maxIndex = document.querySelectorAll('.slide').length - slidesToShow;

    currentIndex += step;
    if (currentIndex < 0) {
        currentIndex = maxIndex;
    } else if (currentIndex > maxIndex) {
        currentIndex = 0;
    }

    const moveAmount = -currentIndex * slideWidth;
    sliderTrack.style.transform = `translateX(${moveAmount}px)`;
}