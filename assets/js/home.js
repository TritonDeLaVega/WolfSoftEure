document.addEventListener("DOMContentLoaded", () => {
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
    'terrain_11.jpg',
    'BannerWseOldSchool.jpg'
  ];

  const themeUrl = wolfsoftData.themeUrl + '/assets/images/';
  const bg = document.getElementById('background-rotator');
  const bgNext = document.getElementById('background-rotator-next');

  let index = Math.floor(Math.random() * images.length);

  // Affiche la première image
  bg.style.backgroundImage = `url(${themeUrl + images[index]})`;

  function nextImage() {
    const nextIndex = (index + 1) % images.length;
    bgNext.style.backgroundImage = `url(${themeUrl + images[nextIndex]})`;
    bgNext.style.transition = 'opacity 1s ease-in-out';
    bgNext.style.opacity = 0;

    setTimeout(() => {
      bgNext.style.opacity = 1;
    }, 20);

    setTimeout(() => {
      bg.style.backgroundImage = bgNext.style.backgroundImage;
      bgNext.style.opacity = 0;
      index = nextIndex;
      setTimeout(nextImage, 5000);
    }, 1020);
  }

  setTimeout(nextImage, 5000);

  // === BURGER MENU ===
  const burger = document.getElementById("burger-button");
  const menu = document.getElementById("fullscreen-menu");
  const closeBtn = document.getElementById("close-menu");

  if (burger && menu && closeBtn) {
    burger.addEventListener("click", () => {
      menu.style.display = "flex";
    });

    closeBtn.addEventListener("click", () => {
      menu.style.display = "none";
    });
  }


  // Modale de contact
  const contactBtn = document.getElementById('contact-btn');
  const modal = document.getElementById('contact-modal');
  const closeModal = document.getElementById('close-modal');
  const contactForm = document.getElementById('contact-form');

  if (contactBtn && modal && closeModal && contactForm) {
    contactBtn.onclick = () => { modal.style.display = 'flex'; };
    closeModal.onclick = () => { modal.style.display = 'none'; };
    contactForm.onsubmit = (e) => {
      e.preventDefault();
      modal.style.display = 'none';
      contactForm.reset();
    };
    // Fermer la modale si on clique en dehors du contenu
    modal.onclick = (e) => {
      if (e.target === modal) modal.style.display = 'none';
    };
  }

  //animation scar//
  const scarContainer = document.querySelector('.scar-container');
  const scarImg = document.querySelector('.scar-img');

  function tirerBilleRafale() {
    let tirs = 0;
    function tirer() {
      // Crée la bille
      const bille = document.createElement('div');
      bille.className = 'bille';

      // Positionne la bille au bout du canon
      bille.style.left = '235px';
      bille.style.top = '18px';

      scarContainer.appendChild(bille);

      // Calcule la distance jusqu'au menu burger
      const containerRect = scarContainer.getBoundingClientRect();
      const burgerRect = burger.getBoundingClientRect();
      const distance = burgerRect.left - containerRect.left - 235;

      // Anime la bille
      bille.animate([
        { transform: 'translateX(0)' },
        { transform: `translateX(${distance}px)` }
      ], {
        duration: 700,
        easing: 'linear'
      });
      bille.style.transform = `translateX(${distance}px)`;
      // Joue le son à chaque tir
      const audio = new Audio(wolfsoftData.themeUrl + '/assets/sounds/single-shot-sig-552-airsoft.wav');
      audio.currentTime = 0;
      audio.play();
      // Effet de recul sur le fusil
      scarImg.classList.remove('recoil'); // <-- force le retrait
      // Force le reflow pour que l'animation reparte à chaque fois
      void scarImg.offsetWidth;
      scarImg.classList.add('recoil');
      setTimeout(() => {
        scarImg.classList.remove('recoil');
      }, 150);

      // Supprime la bille après l'animation
      setTimeout(() => {
        bille.remove();
      }, 700);

      // Joue le son si tu as un fichier dans assets/sounds/shot.mp3
      // const audio = new Audio(wolfsoftData.themeUrl + '/assets/sounds/shot.mp3');
      // audio.play();

      tirs++;
      if (tirs < 3) {
        setTimeout(tirer, 100); // délai entre chaque bille de la rafale
      }
    }
    tirer();

  }

  // Lance la rafale toutes les 2 secondes
  setInterval(tirerBilleRafale, 2000);
});