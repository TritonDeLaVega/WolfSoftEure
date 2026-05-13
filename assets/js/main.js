document.addEventListener("DOMContentLoaded", () => {
    let audioUnlocked = false;

    document.addEventListener("click", () => {
        if (!audioUnlocked) {
            const a = new Audio(wolfsoftMain.themeUrl + '/assets/sounds/single-shot-sig-552-airsoft.wav');
            a.play().catch(() => { });
            audioUnlocked = true;
        }
    }, { once: true });


    /* ===========================
       VARIABLES GLOBALES
    ============================ */
    const themeUrl = wolfsoftMain.themeUrl + '/assets/images/';
    const soundUrl = wolfsoftMain.themeUrl + '/assets/sounds/';


    /* ===========================
       SCAR ANIMATION
    ============================ */
    const scarContainer = document.querySelector('.scar-container');
    const scarImg = document.querySelector('.scar-img');
    const burger = document.getElementById("burger-button");

    if (scarContainer && scarImg && burger) {

        const scarAudio = new Audio(wolfsoftMain.themeUrl + '/assets/sounds/single-shot-sig-552-airsoft.wav');

        function tirerBilleRafale() {
            let tirs = 0;

            function tirer() {
                const canon = scarContainer.querySelector('.canon-marker');
                if (!canon) return;

                const bille = document.createElement('div');
                bille.className = 'bille';

                const canonRect = canon.getBoundingClientRect();
                const containerRect = scarContainer.getBoundingClientRect();

                bille.style.left = `${canonRect.left - containerRect.left}px`;
                bille.style.top = `${canonRect.top - containerRect.top}px`;

                scarContainer.appendChild(bille);

                const burgerRect = burger.getBoundingClientRect();
                const scarRect = scarImg.getBoundingClientRect();

                const distanceX = burgerRect.left - scarRect.right;

                bille.animate(
                    [
                        { transform: 'translate(0, 0)' },
                        { transform: `translate(${distanceX}px, 0)` }
                    ],
                    { duration: 700, easing: 'linear' }
                );

                setTimeout(() => bille.remove(), 700);

                scarImg.classList.remove('recoil');
                void scarImg.offsetWidth;
                scarImg.classList.add('recoil');
                setTimeout(() => scarImg.classList.remove('recoil'), 150);

                if (audioUnlocked) {
                    scarAudio.currentTime = 0;
                    scarAudio.play().catch(() => { });
                }

                tirs++;
                if (tirs < 3) setTimeout(tirer, 100);
            }

            tirer();
        }

        setInterval(tirerBilleRafale, 8000);
    }


    /* ===========================
       BURGER MENU
    ============================ */
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


    /* ===========================
       MODALE INSCRIPTION
    ============================ */
    const newAccountBtn = document.getElementById('new-account-btn');
    const registerModal = document.getElementById('register-modal');
    const closeRegisterModal = document.getElementById('close-register-modal');
    const registerForm = document.getElementById('register-form');

    if (newAccountBtn && registerModal && closeRegisterModal && registerForm) {

        newAccountBtn.addEventListener("click", (e) => {
            e.preventDefault();
            registerModal.classList.add('active');
        });

        closeRegisterModal.addEventListener("click", () => {
            registerModal.classList.remove('active');
        });

        registerForm.addEventListener("submit", (e) => {
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
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            window.location.reload();
                        }
                    }

                });
        });

        registerModal.addEventListener("click", (e) => {
            if (e.target === registerModal) {
                registerModal.classList.remove('active');
            }
        });
    }


    /* ===========================
       MODALE CONNEXION
    ============================ */
    const loginBtn = document.getElementById('login-btn');
    const loginModal = document.getElementById('login-modal');
    const closeLoginModal = document.getElementById('close-login-modal');
    const loginForm = document.getElementById('login-form');

    if (loginBtn && loginModal && closeLoginModal) {

        loginBtn.addEventListener("click", (e) => {
            e.preventDefault();
            loginModal.classList.add('active');
        });

        closeLoginModal.addEventListener("click", () => {
            loginModal.classList.remove('active');
        });

        loginModal.addEventListener("click", (e) => {
            if (e.target === loginModal) loginModal.classList.remove('active');
        });
    }

    if (loginForm && loginModal) {
        loginForm.addEventListener("submit", (e) => {
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
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            window.location.reload();
                        }
                    }

                });
        });
    }


    /* ===========================
       MODALE CONTACT
    ============================ */
    const contactBtn = document.getElementById('contact-btn');
    const contactModal = document.getElementById('contact-modal');
    const closeContactModal = document.getElementById('close-modal');
    const contactForm = document.getElementById('contact-form');

    if (contactBtn && contactModal && closeContactModal && contactForm) {

        contactBtn.addEventListener("click", () => {
            contactModal.classList.add('active');
        });

        closeContactModal.addEventListener("click", () => {
            contactModal.classList.remove('active');
        });

        contactForm.addEventListener("submit", (e) => {
            e.preventDefault();
            alert('Message envoyé (simulation en local).');
            contactModal.classList.remove('active');
            contactForm.reset();
        });

        contactModal.addEventListener("click", (e) => {
            if (e.target === contactModal) contactModal.classList.remove('active');
        });
    }


    /* ===========================
       MODALE IMAGE TERRAIN
    ============================ */
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
            if (e.target === imgModal) imgModal.style.display = 'none';
        });
    }


    /* ===========================
       SWIPE MOBILE SLIDER TERRAIN
    ============================ */
    const terrainSlider = document.querySelector('.terrain-slider');

    if (terrainSlider) {
        let isDown = false;
        let startX = 0;
        let scrollLeft = 0;

        terrainSlider.addEventListener('touchstart', (e) => {
            isDown = true;
            startX = e.touches[0].pageX - terrainSlider.offsetLeft;
            scrollLeft = terrainSlider.scrollLeft;
        });

        terrainSlider.addEventListener('touchmove', (e) => {
            if (!isDown) return;
            const x = e.touches[0].pageX - terrainSlider.offsetLeft;
            const walk = (x - startX) * 1.5;
            terrainSlider.scrollLeft = scrollLeft - walk;
        });

        terrainSlider.addEventListener('touchend', () => {
            isDown = false;
        });
    }

});
