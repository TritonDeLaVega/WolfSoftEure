document.addEventListener("DOMContentLoaded", () => {
    const bg = document.getElementById('background-rotator');
    const bgNext = document.getElementById('background-rotator-next');

    if (!bg || !bgNext) return;

    const images = [
        'terrain_1', 'terrain_2', 'terrain_3', 'terrain_4',
        'terrain_5', 'terrain_6', 'terrain_7', 'terrain_8',
        'terrain_9', 'terrain_10', 'terrain_11', 'BannerWseOldSchool'
    ];

    const themeUrl = wolfsoftHome.themeUrl + '/assets/images/';
    let imageFormat = 'avif';
    let currentIndex = images.indexOf(bg.dataset.backgroundImage);
    const hasInitialBackground = currentIndex !== -1;

    if (!hasInitialBackground) {
        currentIndex = Math.floor(Math.random() * images.length);
    }

    function loadAndShow(index, attempts, isInitialImage) {
        const image = new Image();
        image.onload = () => {
            if (isInitialImage) {
                bg.style.backgroundImage = `url(${image.src})`;
                bg.dataset.backgroundImage = images[index];
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
                bg.dataset.backgroundImage = images[index];
                setTimeout(() => loadAndShow((currentIndex + 1) % images.length, 0, false), 5000);
            }, 1000);
        };

        image.onerror = () => {
            if (imageFormat === 'avif') {
                imageFormat = 'jpg';
                loadAndShow(index, attempts, isInitialImage);
                return;
            }

            if (attempts + 1 < images.length) {
                loadAndShow((index + 1) % images.length, attempts + 1, isInitialImage);
                return;
            }

            setTimeout(() => loadAndShow(index, 0, isInitialImage), 5000);
        };
        image.src = themeUrl + 'home-backgrounds/' + images[index] + '.' + imageFormat;
    }

    if (hasInitialBackground) {
        setTimeout(() => loadAndShow((currentIndex + 1) % images.length, 0, false), 5000);
    } else {
        loadAndShow(currentIndex, 0, true);
    }
});
