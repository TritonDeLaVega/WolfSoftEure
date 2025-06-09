const images = [
  'terrain_1.jpg',
  'terrain_2.jpg',
  'terrain_3.jpg',
  'terrain_4.jpg',
  'terrain_5.jpg',
  'terrain_6.jpg',
  'terrain_7.jpg',
  'terrain_8.jpg',
  'terrain_9.jpg',
  'terrain_10.jpg',
  'terrain_11.jpg'
];

const themeUrl = wolfsoftData.themeUrl + '/assets/images/';
const bg = document.getElementById('background-rotator');
const bgNext = document.getElementById('background-rotator-next');

let index = Math.floor(Math.random() * images.length);

// Affiche la première image
bg.style.backgroundImage = `url(${themeUrl + images[index]})`;

function nextImage() {
  // Calcule l'index suivant
  const nextIndex = (index + 1) % images.length;

  // Prépare la prochaine image au-dessus, invisible
  bgNext.style.backgroundImage = `url(${themeUrl + images[nextIndex]})`;
  bgNext.style.transition = 'opacity 1s ease-in-out';
  bgNext.style.opacity = 0;

  // Lance le fondu
  setTimeout(() => {
    bgNext.style.opacity = 1;
  }, 20);

  // Quand le fondu est fini, swap les images et cache le calque du dessus, puis prépare le prochain cycle
  setTimeout(() => {
    bg.style.backgroundImage = bgNext.style.backgroundImage;
    bgNext.style.opacity = 0;
    index = nextIndex;
    setTimeout(nextImage, 10000); // Relance la boucle après 10s
  }, 1020);
}

// Lance la boucle après 10s sur la première image
setTimeout(nextImage, 10000);

// === BURGER MENU ===
document.addEventListener("DOMContentLoaded", () => {
    const burger = document.getElementById("burger-button");
    const menu = document.getElementById("fullscreen-menu");
    const closeBtn = document.getElementById("close-menu");

    burger.addEventListener("click", () => {
        menu.style.display = "flex";
    });

    closeBtn.addEventListener("click", () => {
        menu.style.display = "none";
    });
});