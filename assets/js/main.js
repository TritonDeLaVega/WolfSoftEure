document.addEventListener("DOMContentLoaded", () => {

    //animation scar//
    const scarContainer = document.querySelector('.scar-container');
    const scarImg = document.querySelector('.scar-img');
    const burger = document.getElementById("burger-button"); // <-- Ajout ici

    if (scarContainer && scarImg && burger) { // Sécurise l'exécution 
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
    }
    // Gestion de la modale d'inscription
    const newAccountBtn = document.getElementById('new-account-btn');
    const registerModal = document.getElementById('register-modal');
    const closeRegisterModal = document.getElementById('close-register-modal');
    const registerForm = document.getElementById('register-form');

    if (newAccountBtn && registerModal && closeRegisterModal && registerForm) {
        newAccountBtn.onclick = () => { registerModal.style.display = 'flex'; };
        closeRegisterModal.onclick = () => { registerModal.style.display = 'none'; };
        registerForm.onsubmit = (e) => {
            e.preventDefault();
            const formData = new FormData(registerForm);
            formData.append('action', 'wse_register');
            fetch(wolfsoftData.ajaxUrl, {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    registerModal.style.display = 'none';
                    registerForm.reset();
                });
        };
        registerModal.onclick = (e) => {
            if (e.target === registerModal) registerModal.style.display = 'none';
        };
    }

    // Gestion ouverture/fermeture modale connexion //
    const loginBtn = document.getElementById('login-btn');
    const loginModal = document.getElementById('login-modal');
    const closeLoginModal = document.getElementById('close-login-modal');

    if (loginBtn && loginModal && closeLoginModal) {
        loginBtn.onclick = (e) => { e.preventDefault(); loginModal.style.display = 'flex'; };
        closeLoginModal.onclick = () => { loginModal.style.display = 'none'; };
        loginModal.onclick = (e) => {
            if (e.target === loginModal) loginModal.style.display = 'none';
        };
    }
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.onsubmit = function (e) {
            e.preventDefault();
            const formData = new FormData(loginForm);
            formData.append('action', 'wse_login');
            fetch(wolfsoftData.ajaxUrl, {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        loginModal.style.display = 'none';
                        // Ici tu peux rediriger ou afficher le profil utilisateur
                    }
                });
        };
    }
});   