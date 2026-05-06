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

    bg.style.backgroundImage = `url(${themeUrl + images[index]})`;

    function nextImage() {
        const nextIndex = (index + 1) % images.length;
        bgNext.style.backgroundImage = `url(${themeUrl + images[nextIndex]})`;
        bgNext.style.opacity = 0;

        setTimeout(() => { bgNext.style.opacity = 1; }, 20);

        setTimeout(() => {
            bg.style.backgroundImage = bgNext.style.backgroundImage;
            bgNext.style.opacity = 0;
            index = nextIndex;
            setTimeout(nextImage, 5000);
        }, 1020);
    }

    setTimeout(nextImage, 5000);
});
