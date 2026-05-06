document.addEventListener("DOMContentLoaded", () => {
    const bg = document.getElementById('background-rotator');
    const bgNext = document.getElementById('background-rotator-next');

    if (!bg || !bgNext) return;

    const images = [
        'terrain_1.jpg', 'terrain_2.jpg', 'terrain_3.jpg',
        'terrain_4.jpg', 'terrain_5.jpg', 'terrain_6.jpg',
        'terrain_7.jpg', 'terrain_8.jpg', 'terrain_9.jpg',
        'terrain_10.jpg', 'terrain_11.jpg', 'BannerWseOldSchool.jpg'
    ];

    const themeUrl = wolfsoftHome.themeUrl + '/assets/images/';
    let index = Math.floor(Math.random() * images.length);

    // === PRÉCHARGEMENT DES IMAGES ===
    const loadedImages = [];
    let loadedCount = 0;

    images.forEach((img, i) => {
        const image = new Image();
        image.src = themeUrl + img;
        image.onload = () => {
            loadedImages[i] = image;
            loadedCount++;
        };
    });

    // Attendre que toutes les images soient chargées
    function waitForPreload() {
        if (loadedCount === images.length) {
            startSlider();
        } else {
            requestAnimationFrame(waitForPreload);
        }
    }

    waitForPreload();

    // === SLIDER ===
    function startSlider() {
        bg.style.backgroundImage = `url(${themeUrl + images[index]})`;
        bg.classList.add("active");

        function nextImage() {
            const nextIndex = (index + 1) % images.length;

            // L'image est déjà chargée → aucun flash possible
            bgNext.style.backgroundImage = `url(${themeUrl + images[nextIndex]})`;

            // Fade-in de la nouvelle image
            bgNext.classList.add("active");

            setTimeout(() => {
                // On remplace l'image principale
                bg.style.backgroundImage = bgNext.style.backgroundImage;

                // On remet bgNext invisible
                bgNext.classList.remove("active");

                index = nextIndex;

                setTimeout(nextImage, 5000);
            }, 1000);
        }

        setTimeout(nextImage, 5000);
    }
});
