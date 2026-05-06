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
    // Style parent (si thème enfant)
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css'
    );

    // Style global
    wp_enqueue_style(
        'wolfsofteure-main',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        ['parent-style']
    );

    // Style terrain uniquement sur la page "terrain"
    if (is_page('terrain')) {
        wp_enqueue_style(
            'wolfsofteure-terrain',
            get_stylesheet_directory_uri() . '/assets/css/terrain.css',
            ['wolfsofteure-main']
        );
    }

    // JS global
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

    // JS spécifique à la home
    if (is_front_page()) {
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
    }
});

// === AJAX INSCRIPTION (UTILISATEUR WORDPRESS) ===
add_action('wp_ajax_nopriv_wse_register', 'wse_register_handler');
add_action('wp_ajax_wse_register', 'wse_register_handler');

function wse_register_handler() {
    $fields = array_map('sanitize_text_field', $_POST);

    if (empty($fields['email']) || empty($fields['password']) || empty($fields['pseudo'])) {
        wp_send_json(['success' => false, 'message' => 'Champs obligatoires manquants.']);
    }

    if (email_exists($fields['email']) || username_exists($fields['pseudo'])) {
        wp_send_json(['success' => false, 'message' => 'Email ou pseudo déjà utilisé.']);
    }

    $user_id = wp_insert_user([
        'user_login'   => $fields['pseudo'],
        'user_email'   => $fields['email'],
        'user_pass'    => $fields['password'],
        'display_name' => $fields['pseudo'],
        'role'         => 'subscriber',
    ]);

    if (is_wp_error($user_id)) {
        wp_send_json(['success' => false, 'message' => 'Erreur lors de la création du compte.']);
    }

    // Infos supplémentaires
    update_user_meta($user_id, 'wse_nom',        $fields['nom'] ?? '');
    update_user_meta($user_id, 'wse_prenom',     $fields['prenom'] ?? '');
    update_user_meta($user_id, 'wse_adresse',    $fields['adresse'] ?? '');
    update_user_meta($user_id, 'wse_telephone',  $fields['telephone'] ?? '');
    update_user_meta($user_id, 'wse_age',        $fields['age'] ?? '');
    update_user_meta($user_id, 'wse_team',       $fields['team'] ?? '');
    update_user_meta($user_id, 'wse_association',$fields['association'] ?? '');
    update_user_meta($user_id, 'wse_profession', $fields['profession'] ?? '');
    update_user_meta($user_id, 'wse_hobbies',    $fields['hobbies'] ?? '');
    update_user_meta($user_id, 'wse_pratique',   $fields['pratique'] ?? '');

    wp_send_json(['success' => true, 'message' => 'Inscription enregistrée !']);
}

// === AJAX CONNEXION (UTILISATEUR WORDPRESS) ===
add_action('wp_ajax_nopriv_wse_login', 'wse_login_handler');
add_action('wp_ajax_wse_login', 'wse_login_handler');

function wse_login_handler() {
    $login    = sanitize_text_field($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$login || !$password) {
        wp_send_json(['success' => false, 'message' => 'Champs manquants.']);
    }

    $creds = [
        'user_login'    => $login,
        'user_password' => $password,
        'remember'      => true,
    ];

    $user = wp_signon($creds, false);

    if (is_wp_error($user)) {
        wp_send_json(['success' => false, 'message' => 'Identifiants incorrects.']);
    }

    wp_send_json(['success' => true, 'message' => 'Connexion réussie !']);
}
