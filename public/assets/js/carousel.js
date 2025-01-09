    document.addEventListener('DOMContentLoaded', function () {
    const track = document.querySelector('.carouselTrack');
    const prevButton = document.querySelector('.prev');
    const nextButton = document.querySelector('.next');

    if (!track || !prevButton ||  !nextButton) {
        console.error("Un ou plusieurs éléments sont introuvables.");
        return;
    }

    let currentIndex = 0;
    const totalItems = track.children.length; // Nombre total d'éléments dans le carrousel
    const visibleItems = 1; // Nombre d'éléments visibles à la fois
    const itemWidth = 100 / visibleItems; // Largeur d'un élément

    // Ajuste la largeur de la track pour s'assurer que le carrousel peut contenir tous les éléments
    track.style.width = `${totalItems * itemWidth}%`;

    // Fonction pour mettre à jour le déplacement du carrousel en fonction de l'index actuel
    function updateCarousel() {
        track.style.transform = `translateX(-${(currentIndex * itemWidth)}%)`;
    }

    // Gestion du clic sur le bouton "Suivant"
    nextButton.addEventListener('click', () => {
        if (currentIndex < totalItems - visibleItems) {
            currentIndex += visibleItems;
            updateCarousel();
        }
    });

    // Gestion du clic sur le bouton "Précédent"
    prevButton.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex -= visibleItems;
            updateCarousel();
        }
    });
});
