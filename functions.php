<?php
add_action('wp_enqueue_scripts', function () {
    // Style parent
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css'
    );

    // Style enfant
    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        array('parent-style')
    );

    // JS spécifique à la page d'accueil
    if (is_front_page()) {
        wp_enqueue_script(
            'wolfsofteure-home',
            get_stylesheet_directory_uri() . '/assets/js/home.js',
            array(),
            null,
            true
        );

        wp_localize_script('wolfsofteure-home', 'wolfsoftData', array(
            'themeUrl' => get_stylesheet_directory_uri()
        ));
    }
}, 10);