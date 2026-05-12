<?php
// === SUPPORTS DE THÈME ===
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('menus');

    register_nav_menus([
        'main-menu' => 'Menu principal',
    ]);
});

// === ENQUEUE STYLES & SCRIPTS ===
add_action('wp_enqueue_scripts', function () {

    // Style parent
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css'
    );

    // Style enfant (OBLIGATOIRE)
    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        ['parent-style']
    );

    // Style global
    wp_enqueue_style(
        'wolfsofteure-main',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        ['child-style']
    );

    // Style terrain uniquement sur la page "terrain"
    if (is_page('terrain')) {
        wp_enqueue_style(
            'wolfsofteure-terrain',
            get_stylesheet_directory_uri() . '/assets/css/terrain.css',
            ['wolfsofteure-main']
        );
    }

    // === JS GLOBAL ===
    wp_enqueue_script(
        'wolfsofteure-main-js',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        [],
        null,
        true
    );

    wp_localize_script('wolfsofteure-main-js', 'wolfsoftMain', [
        'themeUrl' => get_stylesheet_directory_uri(),
        'ajaxUrl'  => admin_url('admin-ajax.php'),
    ]);

    // === HERO + SLIDER DE FOND SUR TOUTES LES PAGES ===
    wp_enqueue_script(
        'wolfsofteure-home-js',
        get_stylesheet_directory_uri() . '/assets/js/home.js',
        ['wolfsofteure-main-js'],
        null,
        true
    );

    wp_localize_script('wolfsofteure-home-js', 'wolfsoftHome', [
        'themeUrl' => get_stylesheet_directory_uri(),
        'ajaxUrl'  => admin_url('admin-ajax.php'),
    ]);

    function wse_restrict_forum_access()
    {
        if (is_bbpress() && !is_user_logged_in()) {
            wp_redirect(wp_login_url(get_permalink()));
            exit;
        }
    }
    add_action('template_redirect', 'wse_restrict_forum_access');
});
