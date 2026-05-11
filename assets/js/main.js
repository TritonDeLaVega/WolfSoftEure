document.addEventListener("DOMContentLoaded", () => {
    const themeUrl = wolfsoftMain.themeUrl + '/assets/images/';
    const soundUrl = wolfsoftMain.themeUrl + '/assets/sounds/';

    // === SCAR ANIMATION (toujours actif) ===
    const scarContainer = document.querySelector('.scar-container');
    const scarImg = document.querySelector('.scar-img');
    const burger = document.getElementById("burger-button");

    // Un seul objet Audio réutilisé pour éviter les ratés
    const scarAudio = new Audio(wolfsoftMain.themeUrl + '/assets/sounds/single-shot-sig-552-airsoft.wav');

    if (scarContainer && scarImg && burger) {

        function tirerBilleRafale() {
            let tirs = 0;

            function tirer() {
                const bille = document.createElement('div');
                bille.className = 'bille';

                // === POSITION EXACTE DU CANON ===
                const canon = scarContainer.querySelector('.canon-marker');
                const canonRect = canon.getBoundingClientRect();
                const containerRect = scarContainer.getBoundingClientRect();

                const canonX = canonRect.left - containerRect.left;
                const canonY = canonRect.top - containerRect.top;

                bille.style.left = `${canonX}px`;
                bille.style.top = `${canonY}px`;

                scarContainer.appendChild(bille);

                // === POSITION DU BURGER ===
                const burgerRect = burger.getBoundingClientRect();
                const scarRect = scarImg.getBoundingClientRect();

                // Tir strictement horizontal
                const distanceX = burgerRect.left - scarRect.right;
                const distanceY = 0;

                // === ANIMATION DE LA BILLE ===
                bille.animate(
                    [
                        { transform: 'translate(0, 0)' },
                        { transform: `translate(${distanceX}px, ${distanceY}px)` }
                    ],
                    { duration: 700, easing: 'linear' }
                );

                bille.style.transform = `translate(${distanceX}px, ${distanceY}px)`;

                // === DISPARITION ===
                setTimeout(() => bille.remove(), 700);

                // === RECUL DU SCAR ===
                scarImg.classList.remove('recoil');
                void scarImg.offsetWidth;
                scarImg.classList.add('recoil');
                setTimeout(() => scarImg.classList.remove('recoil'), 150);

                // === SON ===
                try {
                    scarAudio.currentTime = 0;
                    scarAudio.play();
                } catch (e) { }

                // === RAFALE ===
                tirs++;
                if (tirs < 3) setTimeout(tirer, 100);
            }

            tirer();
        }

        // Tir toutes les 8 secondes
        setInterval(tirerBilleRafale, 8000);
    }



    // === BURGER MENU ===
    const menu = document.getElementById("fullscreen-menu");
    const closeBtn = document.getElementById("close-menu");

    if (burger && menu && closeBtn) {
        burger.addEventListener("click", () => {
            menu.classList.add("active");
            document.body.style.overflow = "hidden";
        });

        closeBtn.addEventListener("click", () => {
            menu.classList.remove("active");
            document.body.style.overflow = "";
        });

        menu.addEventListener("click", (e) => {
            if (e.target === menu) {
                menu.classList.remove("active");
                document.body.style.overflow = "";
            }
        });
    }

    // === MODALE INSCRIPTION ===
    const newAccountBtn = document.getElementById('new-account-btn');
    const registerModal = document.getElementById('register-modal');
    const closeRegisterModal = document.getElementById('close-register-modal');
    const registerForm = document.getElementById('register-form');

    if (newAccountBtn && registerModal && closeRegisterModal && registerForm) {
        newAccountBtn.onclick = (e) => {
            e.preventDefault();
            registerModal.classList.add('active');
        };

        closeRegisterModal.onclick = () => {
            registerModal.classList.remove('active');
        };

        registerForm.onsubmit = (e) => {
            e.preventDefault();
            const formData = new FormData(registerForm);
            formData.append('action', 'wse_register');

            fetch(wolfsoftMain.ajaxUrl, {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        registerModal.classList.remove('active');
                        registerForm.reset();
                    }
                });
        };

        registerModal.onclick = (e) => {
            if (e.target === registerModal) {
                registerModal.classList.remove('active');
            }
        };
    }

    // === MODALE CONNEXION ===
    const loginBtn = document.getElementById('login-btn');
    const loginModal = document.getElementById('login-modal');
    const closeLoginModal = document.getElementById('close-login-modal');
    const loginForm = document.getElementById('login-form');

    if (loginBtn && loginModal && closeLoginModal) {
        loginBtn.onclick = (e) => {
            e.preventDefault();
            loginModal.classList.add('active');
        };
        closeLoginModal.onclick = () => {
            loginModal.classList.remove('active');
        };
        loginModal.onclick = (e) => {
            if (e.target === loginModal) loginModal.classList.remove('active');
        };
    }

    if (loginForm) {
        loginForm.onsubmit = function (e) {
            e.preventDefault();
            const formData = new FormData(loginForm);
            formData.append('action', 'wse_login');

            fetch(wolfsoftMain.ajaxUrl, {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        loginModal.classList.remove('active');
                        window.location.reload();
                    }
                });
        };
    }

    // === MODALE CONTACT ===
    const contactBtn = document.getElementById('contact-btn');
    const contactModal = document.getElementById('contact-modal');
    const closeContactModal = document.getElementById('close-modal');
    const contactForm = document.getElementById('contact-form');

    if (contactBtn && contactModal && closeContactModal && contactForm) {
        contactBtn.onclick = () => {
            contactModal.classList.add('active');
        };
        closeContactModal.onclick = () => {
            contactModal.classList.remove('active');
        };
        contactForm.onsubmit = (e) => {
            e.preventDefault();
            alert('Message envoyé (simulation en local).');
            contactModal.classList.remove('active');
            contactForm.reset();
        };
        contactModal.onclick = (e) => {
            if (e.target === contactModal) contactModal.classList.remove('active');
        };
    }
    // === AGRANDISSEMENT DES IMAGES DU SLIDER TERRAIN ===
    const terrainImages = document.querySelectorAll('.terrain-slider .slide img');
    const imgModal = document.getElementById('img-modal');
    const imgModalDisplay = document.getElementById('img-modal-display');
    const imgModalClose = document.querySelector('.img-modal-close');

    if (terrainImages.length > 0 && imgModal && imgModalDisplay && imgModalClose) {

        terrainImages.forEach(img => {
            img.addEventListener('click', () => {
                imgModal.style.display = 'flex';
                imgModalDisplay.src = img.src;
            });
        });

        imgModalClose.addEventListener('click', () => {
            imgModal.style.display = 'none';
        });

        imgModal.addEventListener('click', (e) => {
            if (e.target === imgModal) {
                imgModal.style.display = 'none';
            }
        });
    }

});
