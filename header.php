<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<!-- Modale d'inscription -->
<div class="modal" id="register-modal">
    <div class="modal-content">
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
<div class="modal" id="login-modal">
    <div class="modal-content">
        <span class="close-modal" id="close-login-modal">&times;</span>
        <h2>Connexion utilisateur</h2>
        <form id="login-form">
            <label>E-mail ou pseudo*<input type="text" name="login" required></label>
            <label>Mot de passe*<input type="password" name="password" required></label>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</div>

<body <?php body_class(); ?>>

    <header class="main-header">
        <div class="header-left">
            <span class="cloud-bg-header">
                <a href="<?php echo home_url(); ?>" class="site-title">Wolf Soft Eure</a>
            </span>
            <div class="scar-container" style="position: relative; display: inline-block;">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/scar.png" alt="Scar" class="scar-img">
            </div>
        </div>
        <div class="burger-menu" id="burger-button">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </header>

    <nav class="fullscreen-menu" id="fullscreen-menu">
        <button id="close-menu">×</button>
        <ul>
            <li><a href="#">Parties</a></li>
            <li><a href="#">Photos</a></li>
            <li><a href="#">Postuler</a></li>
            <li><a href="<?php echo get_permalink(get_page_by_path('terrain')); ?>">Terrain</a></li>
            <li><a href="#">Boutique</a></li>
            <li><a href="#" id="new-account-btn">Nouveau compte</a></li>
            <li><a href="#" id="login-btn">Se connecter</a></li>
        </ul>
    </nav>