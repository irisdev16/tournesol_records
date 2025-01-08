document.addEventListener('DOMContentLoaded', function () {
    const trackCarousel = document.getElementById('trackCarousel');
    const prevButton = document.getElementById('prevButton');
    const nextButton = document.getElementById('nextButton');
    const items = document.querySelectorAll('.carousel-item');

    if (items.length === 0) {
        console.error('Aucun élément avec la classe .carousel-item trouvé');
        return; // Sortir si aucun élément n'est trouvé
    }


    let currentIndex = 0;
    const itemsPerPage = 3; // Nombre d'éléments visibles

    // Fonction pour mettre à jour la position du carrousel
    function updateCarouselPosition() {
        const offset = -currentIndex * 33.33; // Décalage en fonction du nombre d'éléments visibles
        trackCarousel.style.transform = `translateX(${offset}%)`;
    }

    prevButton.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateCarouselPosition();
        }
    });

    nextButton.addEventListener('click', () => {
        //console.log("Next button clicked");
        if (currentIndex < items.length - itemsPerPage) {
            currentIndex++;
            updateCarouselPosition();
        }
    });
});
