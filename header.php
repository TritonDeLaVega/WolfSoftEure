<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <!-- SLIDER GLOBAL (derrière tout le site) -->
    <div class="background-slider">
        <div class="background" id="background-rotator"></div>
        <div class="background" id="background-rotator-next"></div>
    </div>

    <!-- HERO (ne contient plus que le logo) -->
    <div class="hero">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/pvc-patch-no-BG.png"
            alt="Logo WSE"
            class="logo-centered">
        <?php if (is_front_page()) : ?>
            <p class="hero-subtitle">Bienvenue sur le site&nbsp;!</p>
        <?php endif; ?>
    </div>




    <!-- Modale d'inscription -->
    <div class="modal" id="register-modal" aria-hidden="true">
        <div class="modal-content" role="dialog" aria-modal="true">
            <span class="close-modal" id="close-register-modal">&times;</span>
            <h2>Inscription nouveau membre</h2>
            <form id="register-form">
                <label>Nom*<input type="text" name="nom" required></label>
                <label>Prénom*<input type="text" name="prenom" required></label>
                <label>Pseudonyme de joueur*<input type="text" name="pseudo" required></label>
                <label>E-mail*<input type="email" name="email" required></label>
                <label>Mot de passe*<input type="password" name="password" required></label>
                <label>Adresse postale*<input type="text" name="adresse" required></label>
                <label>Numéro de téléphone*<input type="tel" name="telephone" required></label>
                <label>Âge*<input type="number" name="age" required></label>
                <label>Team<input type="text" name="team"></label>
                <label>Association<input type="text" name="association"></label>
                <label>Profession<input type="text" name="profession"></label>
                <label>Hobbies<input type="text" name="hobbies"></label>
                <label>Pratique Airsoft*
                    <select name="pratique" required>
                        <option value="">Sélectionner</option>
                        <option>Débutant</option>
                        <option>Occasionnel</option>
                        <option>Confirmé</option>
                        <option>Accro</option>
                        <option>Vétérant</option>
                        <option>Légende</option>
                        <option>Dieu</option>
                    </select>
                </label>
                <button type="submit">S'inscrire</button>
            </form>
        </div>
    </div>

    <!-- Modale de connexion -->
    <div class="modal" id="login-modal" aria-hidden="true">
        <div class="modal-content" role="dialog" aria-modal="true">
            <span class="close-modal" id="close-login-modal">&times;</span>
            <h2>Connexion utilisateur</h2>
            <form id="login-form">
                <label>E-mail ou pseudo*<input type="text" name="login" required></label>
                <label>Mot de passe*<input type="password" name="password" required></label>
                <button type="submit">Se connecter</button>
            </form>
        </div>
    </div>

    <header class="main-header">
        <div class="header-left">
            <span class="cloud-bg-header">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="site-title">Wolf Soft Eure</a>
            </span>
            <div class="scar-container">
                <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/scar.png'); ?>"
                    alt="Scar" class="scar-img">

                <!-- Marqueur invisible du canon -->
                <div class="canon-marker"></div>
            </div>

        </div>
        <button class="burger-menu" id="burger-button" aria-label="Ouvrir le menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </header>

    <nav class="fullscreen-menu" id="fullscreen-menu">
        <button id="close-menu" aria-label="Fermer le menu">×</button>
        <?php
        wp_nav_menu([
            'theme_location' => 'main-menu',
            'container'      => false,
            'menu_class'     => '',
            'items_wrap'     => '<ul>%3$s</ul>',
        ]);
        ?>
        <ul class="account-links">
            <li><a href="#" id="new-account-btn">Nouveau compte</a></li>
            <li><a href="#" id="login-btn">Se connecter</a></li>
        </ul>

    </nav>