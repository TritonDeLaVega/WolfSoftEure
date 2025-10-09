<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

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
            <li><a href="#">Nouveau compte</a></li>
            <li><a href="#">Se connecter</a></li>
        </ul>
    </nav>