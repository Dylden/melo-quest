const track = document.querySelector('.carousel-track');
const prevButton = document.querySelector('.prev');
const nextButton = document.querySelector('.next');

let currentIndex = 0;

console.log('COUCOU');

nextButton.addEventListener('click', () => {
    currentIndex = Math.min(currentIndex + 1, track.children.length - 1);
    track.style.transform = `translateX(-${currentIndex * 100}%)`
})

prevButton.addEventListener('click', () => {
    currentIndex = Math.max(currentIndex - 1, 0);
    track.style.transform = `translateX(-${currentIndex * 100}%)`;
});

document.addEventListener('DOMContentLoaded', function () {
    console.log("Carrousel chargé !")
})