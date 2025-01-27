document.addEventListener('DOMContentLoaded', function () {
    const track = document.querySelector('.carouselTrack');
    const prevButton = document.querySelector('.prev');
    const nextButton = document.querySelector('.next');

    if (!track || !prevButton || !nextButton) {
        console.error("Un ou plusieurs éléments sont introuvables.");
        return;
    }

    let currentIndex = 0;
    let visibleItems = 4;  // Nombre d'éléments visibles à la fois par défaut (quand l'écran est large)
    let itemWidth = 100 / visibleItems;  // Largeur d'un élément basé sur visibleItems

    // Fonction pour mettre à jour le déplacement du carrousel en fonction de l'index actuel
    function updateCarousel() {
        track.style.transform = `translateX(-${(currentIndex * itemWidth)}%)`;
    }

    // Ajuste le nombre d'éléments visibles en fonction de la largeur de l'écran
    function adjustVisibleItems() {
        if (window.innerWidth <= 768) {
            visibleItems = 2;  // Afficher 2 éléments à la fois sur les petits écrans
        } else {
            visibleItems = 4;  // Afficher 4 éléments sur les grands écrans
        }

        // Recalculer la largeur de chaque élément
        itemWidth = 100 / visibleItems;

        // Recalculer la largeur de la track pour s'assurer qu'elle peut contenir tous les éléments
        track.style.width = `${track.children.length * itemWidth}%`;
    }

    // Ajuster les éléments visibles au démarrage et lors du redimensionnement de la fenêtre
    adjustVisibleItems();
    window.addEventListener('resize', adjustVisibleItems);

    // Fonction pour gérer le clic sur le bouton "Suivant"
    nextButton.addEventListener('click', () => {
        if (currentIndex < track.children.length - visibleItems) {
            currentIndex += visibleItems;  // Défilement de 2 éléments (ou 4 selon l'écran)
            updateCarousel();
        }
    });

    // Fonction pour gérer le clic sur le bouton "Précédent"
    prevButton.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex -= visibleItems;  // Défilement de 2 éléments (ou 4 selon l'écran)
            updateCarousel();
        }
    });
});
