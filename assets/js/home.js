document.addEventListener("DOMContentLoaded", () => {
    const bg = document.getElementById('background-rotator');
    const bgNext = document.getElementById('background-rotator-next');

    if (!bg || !bgNext) return;

    const images = [
        'home-backgrounds/terrain_1.jpg', 'home-backgrounds/terrain_2.jpg',
        'home-backgrounds/terrain_3.jpg', 'home-backgrounds/terrain_4.jpg',
        'home-backgrounds/terrain_5.jpg', 'home-backgrounds/terrain_6.jpg',
        'home-backgrounds/terrain_7.jpg', 'home-backgrounds/terrain_8.jpg',
        'home-backgrounds/terrain_9.jpg', 'home-backgrounds/terrain_10.jpg',
        'home-backgrounds/terrain_11.jpg', 'home-backgrounds/BannerWseOldSchool.jpg'
    ];

    const themeUrl = wolfsoftHome.themeUrl + '/assets/images/';
    let currentIndex = Math.floor(Math.random() * images.length);

    function loadAndShow(index, attempts, isInitialImage) {
        const image = new Image();
        image.onload = () => {
            if (isInitialImage) {
                bg.style.backgroundImage = `url(${image.src})`;
                bg.classList.add("active");
                setTimeout(() => loadAndShow((index + 1) % images.length, 0, false), 5000);
                return;
            }

            bgNext.style.backgroundImage = `url(${image.src})`;
            bgNext.classList.add("active");

            setTimeout(() => {
                bg.style.backgroundImage = bgNext.style.backgroundImage;
                bgNext.classList.remove("active");
                currentIndex = index;
                setTimeout(() => loadAndShow((currentIndex + 1) % images.length, 0, false), 5000);
            }, 1000);
        };

        image.onerror = () => {
            if (attempts + 1 < images.length) {
                loadAndShow((index + 1) % images.length, attempts + 1, isInitialImage);
                return;
            }

            setTimeout(() => loadAndShow(index, 0, isInitialImage), 5000);
        };
        image.src = themeUrl + images[index];
    }

    loadAndShow(currentIndex, 0, true);
});
