// assets/js/home.js

const images = [
  'Scar_Mk17_LB_Aride.jpg',
  'Scar_Mk17_LB_Roche.jpg',
  'Scar_Mk17_LB_Sable.jpg',
  'sniper-banner.jpg',
  'terrain_11.jpg'
];

// Récupérer l'URL du thème passé via PHP
const themeUrl = wolfsoftData.themeUrl + '/assets/images/';
const bg = document.getElementById('background-rotator');

let index = Math.floor(Math.random() * images.length);

function setBackground(i) {
  bg.style.opacity = 0;
  setTimeout(() => {
    bg.style.backgroundImage = `url(${themeUrl + images[i]})`;
    bg.style.opacity = 1;
  }, 1000);
}

setBackground(index);

setInterval(() => {
  index = (index + 1) % images.length;
  setBackground(index);
}, 10000);

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
