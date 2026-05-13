<?php

/* ===========================
   SUPPORTS DE THÈME
=========================== */
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('menus');

    register_nav_menus([
        'main-menu' => 'Menu principal',
    ]);
});


/* ===========================
   ENQUEUE STYLES & SCRIPTS
=========================== */
add_action('wp_enqueue_scripts', function () {

    // Style parent
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css'
    );

    // Style enfant
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

    // HERO + SLIDER
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
});


/* ===========================
   RÔLES PERSONNALISÉS WSE
=========================== */
function wse_register_roles() {
    add_role(
        'wse_member',
        'Membre WSE',
        ['read' => true]
    );
}
add_action('after_setup_theme', 'wse_register_roles');


/* ===========================
   RESTRICTION ACCÈS FORUM
=========================== */
function wse_restrict_forum_access() {
    if (function_exists('is_bbpress') && is_bbpress() && !is_user_logged_in()) {
        wp_redirect(wp_login_url(get_permalink()));
        exit;
    }
}
add_action('template_redirect', 'wse_restrict_forum_access');


/* ===========================
   AJAX INSCRIPTION WSE
=========================== */
function wse_ajax_register() {

    if (!isset($_POST['wse_register_nonce']) ||
        !wp_verify_nonce($_POST['wse_register_nonce'], 'wse_register_action')) {
        wp_send_json_error(['message' => 'Requête invalide (sécurité).']);
    }

    // Sanitize
    $nom        = sanitize_text_field($_POST['nom'] ?? '');
    $prenom     = sanitize_text_field($_POST['prenom'] ?? '');
    $pseudo     = sanitize_text_field($_POST['pseudo'] ?? '');
    $email      = sanitize_email($_POST['email'] ?? '');
    $password   = $_POST['password'] ?? '';
    $adresse    = sanitize_text_field($_POST['adresse'] ?? '');
    $telephone  = sanitize_text_field($_POST['telephone'] ?? '');
    $age        = intval($_POST['age'] ?? 0);
    $team       = sanitize_text_field($_POST['team'] ?? '');
    $association= sanitize_text_field($_POST['association'] ?? '');
    $profession = sanitize_text_field($_POST['profession'] ?? '');
    $hobbies    = sanitize_text_field($_POST['hobbies'] ?? '');
    $pratique   = sanitize_text_field($_POST['pratique'] ?? '');

    // Validation
    if (!$nom || !$prenom || !$pseudo || !$email || !$password || !$adresse || !$telephone || !$age || !$pratique) {
        wp_send_json_error(['message' => 'Merci de remplir tous les champs obligatoires.']);
    }

    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Adresse e-mail invalide.']);
    }

    if (strlen($password) < 8) {
        wp_send_json_error(['message' => 'Le mot de passe doit contenir au moins 8 caractères.']);
    }

    if (email_exists($email)) {
        wp_send_json_error(['message' => 'Un compte existe déjà avec cet e-mail.']);
    }

    if (username_exists($pseudo)) {
        wp_send_json_error(['message' => 'Ce pseudonyme est déjà utilisé.']);
    }

    // Création du compte
    $user_id = wp_create_user($pseudo, $password, $email);

    if (is_wp_error($user_id)) {
        wp_send_json_error(['message' => 'Erreur lors de la création du compte.']);
    }

    // Mise à jour
    wp_update_user([
        'ID'           => $user_id,
        'display_name' => $pseudo,
        'first_name'   => $prenom,
        'last_name'    => $nom,
    ]);

    update_user_meta($user_id, 'wse_adresse', $adresse);
    update_user_meta($user_id, 'wse_telephone', $telephone);
    update_user_meta($user_id, 'wse_age', $age);
    update_user_meta($user_id, 'wse_team', $team);
    update_user_meta($user_id, 'wse_association', $association);
    update_user_meta($user_id, 'wse_profession', $profession);
    update_user_meta($user_id, 'wse_hobbies', $hobbies);
    update_user_meta($user_id, 'wse_pratique', $pratique);

    // Rôle
    $user = new WP_User($user_id);
    $user->set_role('wse_member');

    if (function_exists('bbp_set_user_role')) {
        bbp_set_user_role($user_id, 'bbp_participant');
    }

    // Connexion auto
    $signon = wp_signon([
        'user_login'    => $pseudo,
        'user_password' => $password,
        'remember'      => true,
    ], false);

    if (is_wp_error($signon)) {
        wp_send_json_error(['message' => 'Compte créé, mais connexion impossible.']);
    }

    wp_send_json_success([
        'message'  => 'Compte créé et connecté. Bienvenue à la WSE !',
        'redirect' => home_url('/espace-membre/')
    ]);
}
add_action('wp_ajax_nopriv_wse_register', 'wse_ajax_register');
add_action('wp_ajax_wse_register',        'wse_ajax_register');


/* ===========================
   AJAX CONNEXION WSE
=========================== */
function wse_ajax_login() {

    if (!isset($_POST['wse_login_nonce']) ||
        !wp_verify_nonce($_POST['wse_login_nonce'], 'wse_login_action')) {
        wp_send_json_error(['message' => 'Requête invalide (sécurité).']);
    }

    $login    = sanitize_text_field($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$login || !$password) {
        wp_send_json_error(['message' => 'Merci de remplir tous les champs.']);
    }

    if (is_email($login)) {
        $user = get_user_by('email', $login);
        if ($user) {
            $login = $user->user_login;
        }
    }

    $signon = wp_signon([
        'user_login'    => $login,
        'user_password' => $password,
        'remember'      => true,
    ], false);

    if (is_wp_error($signon)) {
        wp_send_json_error(['message' => 'Identifiants incorrects.']);
    }

    wp_send_json_success([
        'message'  => 'Connexion réussie.',
        'redirect' => home_url('/espace-membre/')
    ]);
}
add_action('wp_ajax_nopriv_wse_login', 'wse_ajax_login');
add_action('wp_ajax_wse_login',        'wse_ajax_login');
