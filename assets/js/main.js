document.addEventListener("DOMContentLoaded", () => {
    const themeUrl = wolfsoftData.themeUrl + '/assets/images/';
    const soundUrl = wolfsoftData.themeUrl + '/assets/sounds/';

    // === ROTATE BACKGROUND ===
    const bg = document.getElementById('background-rotator');
    const bgNext = document.getElementById('background-rotator-next');

    if (bg && bgNext) {
        const images = [
            'terrain_1.jpg', 'terrain_2.jpg', 'terrain_3.jpg',
            'terrain_4.jpg', 'terrain_5.jpg', 'terrain_6.jpg',
            'terrain_7.jpg', 'terrain_8.jpg', 'terrain_9.jpg',
            'terrain_10.jpg', 'terrain_11.jpg', 'BannerWseOldSchool.jpg'
        ];
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
    }

    // === SCAR ANIMATION ===
    const scarContainer = document.querySelector('.scar-container');
    const scarImg = document.querySelector('.scar-img');
    const burger = document.getElementById("burger-button");

    if (scarContainer && scarImg && burger) {
        function tirerBilleRafale() {
            let tirs = 0;
            function tirer() {
                const bille = document.createElement('div');
                bille.className = 'bille';
                bille.style.left = '235px';
                bille.style.top = '18px';
                scarContainer.appendChild(bille);

                const containerRect = scarContainer.getBoundingClientRect();
                const burgerRect = burger.getBoundingClientRect();
                const distance = burgerRect.left - containerRect.left - 235;

                bille.animate([
                    { transform: 'translateX(0)' },
                    { transform: `translateX(${distance}px)` }
                ], { duration: 700, easing: 'linear' });
                bille.style.transform = `translateX(${distance}px)`;

                const audio = new Audio(soundUrl + 'single-shot-sig-552-airsoft.wav');
                audio.currentTime = 0;
                audio.play();

                scarImg.classList.remove('recoil');
                void scarImg.offsetWidth;
                scarImg.classList.add('recoil');
                setTimeout(() => { scarImg.classList.remove('recoil'); }, 150);
                setTimeout(() => { bille.remove(); }, 700);

                tirs++;
                if (tirs < 3) setTimeout(tirer, 100);
            }
            tirer();
        }

        setInterval(tirerBilleRafale, 2000);
    }

    // === BURGER MENU ===
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

    // === MODALE INSCRIPTION ===
    const newAccountBtn = document.getElementById('new-account-btn');
    const registerModal = document.getElementById('register-modal');
    const closeRegisterModal = document.getElementById('close-register-modal');
    const registerForm = document.getElementById('register-form');

    if (newAccountBtn && registerModal && closeRegisterModal && registerForm) {
        newAccountBtn.onclick = () => {
            registerModal.classList.add('active');
        };

        closeRegisterModal.onclick = () => {
            registerModal.classList.remove('active');
        };

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
                    registerModal.classList.remove('active');
                    registerForm.reset();
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
            loginModal.style.display = 'flex';
        };
        closeLoginModal.onclick = () => {
            loginModal.style.display = 'none';
        };
        loginModal.onclick = (e) => {
            if (e.target === loginModal) loginModal.style.display = 'none';
        };
    }

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
                    }
                });
        };
    }
});
