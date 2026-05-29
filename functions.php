<?php

/* ============================================================
   WOLF SOFT EURE — FUNCTIONS.PHP OPTIMISÉ (VERSION 2B)
   Code nettoyé, réorganisé, sans doublons, sans conflits
   ============================================================ */


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
add_action('after_setup_theme', function () {
    add_role('wse_member', 'Membre WSE', ['read' => true]);
});


/* ===========================
   RESTRICTION ACCÈS FORUM
=========================== */
add_action('template_redirect', function () {
    if (function_exists('is_bbpress') && is_bbpress() && !is_user_logged_in()) {
        wp_redirect(wp_login_url(get_permalink()));
        exit;
    }
});

/* ===========================
   AJAX INSCRIPTION WSE
=========================== */
function wse_ajax_register()
{
    if (
        !isset($_POST['wse_register_nonce']) ||
        !wp_verify_nonce($_POST['wse_register_nonce'], 'wse_register_action')
    ) {
        wp_send_json_error(['message' => 'Requête invalide (sécurité).']);
    }

    // Sanitize
    $nom        = sanitize_text_field($_POST['nom'] ?? '');
    $prenom     = sanitize_text_field($_POST['prenom'] ?? '');
    $pseudo     = sanitize_user($_POST['pseudo'] ?? '');
    $email      = sanitize_email($_POST['email'] ?? '');
    $password   = $_POST['password'] ?? '';
    $adresse    = sanitize_text_field($_POST['adresse'] ?? '');
    $telephone  = sanitize_text_field($_POST['telephone'] ?? '');
    $age        = intval($_POST['age'] ?? 0);
    $team       = sanitize_text_field($_POST['team'] ?? '');
    $association = sanitize_text_field($_POST['association'] ?? '');
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

    // Métas WSE
    update_user_meta($user_id, 'wse_adresse', $adresse);
    update_user_meta($user_id, 'wse_telephone', $telephone);
    update_user_meta($user_id, 'wse_age', $age);
    update_user_meta($user_id, 'wse_team', $team);
    update_user_meta($user_id, 'wse_association', $association);
    update_user_meta($user_id, 'wse_profession', $profession);
    update_user_meta($user_id, 'wse_hobbies', $hobbies);
    update_user_meta($user_id, 'wse_pratique', $pratique);

    // Rôle WSE + bbPress
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
function wse_ajax_login()
{
    if (
        !isset($_POST['wse_login_nonce']) ||
        !wp_verify_nonce($_POST['wse_login_nonce'], 'wse_login_action')
    ) {
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


/* ===========================
   AJAX CONTACT WSE
=========================== */
function wse_ajax_contact()
{
    if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['message'])) {
        wp_send_json_error(['message' => 'Champs manquants.']);
    }

    $name    = sanitize_text_field($_POST['name']);
    $email   = sanitize_email($_POST['email']);
    $message = sanitize_textarea_field($_POST['message']);

    if (!$name || !$email || !$message) {
        wp_send_json_error(['message' => 'Merci de remplir tous les champs.']);
    }

    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Adresse e-mail invalide.']);
    }

    $to = get_option('admin_email');
    $subject = "Nouveau message de contact WSE";
    $body = "Nom : $name\nEmail : $email\n\nMessage :\n$message";
    $headers = ['Content-Type: text/plain; charset=UTF-8'];

    $sent = wp_mail($to, $subject, $body, $headers);

    if (!$sent) {
        wp_send_json_error(['message' => 'Erreur lors de l’envoi du message.']);
    }

    wp_send_json_success(['message' => 'Message envoyé avec succès !']);
}
add_action('wp_ajax_nopriv_wse_contact', 'wse_ajax_contact');
add_action('wp_ajax_wse_contact',        'wse_ajax_contact');


/* ===========================
   MISE À JOUR PROFIL WSE
=========================== */
function wse_handle_profile_update()
{
    if (!is_user_logged_in()) return;

    // Suppression de compte
    if (isset($_POST['wse_delete_account'])) {
        if (
            !isset($_POST['wse_delete_account_nonce']) ||
            !wp_verify_nonce($_POST['wse_delete_account_nonce'], 'wse_delete_account_action')
        ) {
            return;
        }

        $user_id = get_current_user_id();
        if (current_user_can('administrator', $user_id)) {
            return;
        }

        require_once ABSPATH . 'wp-admin/includes/user.php';
        wp_delete_user($user_id);
        wp_redirect(home_url('/'));
        exit;
    }

    // Mise à jour profil
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
    if (
        !isset($_POST['wse_update_profile_nonce']) ||
        !wp_verify_nonce($_POST['wse_update_profile_nonce'], 'wse_update_profile_action')
    ) {
        return;
    }

    $user_id = get_current_user_id();

    $nom        = sanitize_text_field($_POST['wse_nom'] ?? '');
    $prenom     = sanitize_text_field($_POST['wse_prenom'] ?? '');
    $pseudo     = sanitize_user($_POST['pseudo'] ?? '');
    $email      = sanitize_email($_POST['email'] ?? '');
    $password   = $_POST['password'] ?? '';
    $adresse    = sanitize_text_field($_POST['wse_adresse'] ?? '');
    $telephone  = sanitize_text_field($_POST['wse_telephone'] ?? '');
    $age        = intval($_POST['wse_age'] ?? 0);
    $team       = sanitize_text_field($_POST['wse_team'] ?? '');
    $association = sanitize_text_field($_POST['wse_association'] ?? '');
    $profession = sanitize_text_field($_POST['wse_profession'] ?? '');
    $hobbies    = sanitize_text_field($_POST['wse_hobbies'] ?? '');
    $pratique   = sanitize_text_field($_POST['wse_pratique'] ?? '');

    if (!$nom || !$prenom || !$pseudo || !$email || !$adresse || !$telephone || !$age || !$pratique) {
        return;
    }

    if (!is_email($email)) {
        return;
    }

    // Email déjà pris
    $existing_email = get_user_by('email', $email);
    if ($existing_email && $existing_email->ID !== $user_id) {
        return;
    }

    // Pseudo déjà pris
    $existing_login = get_user_by('login', $pseudo);
    if ($existing_login && $existing_login->ID !== $user_id) {
        return;
    }

    // Mise à jour utilisateur
    $update_data = [
        'ID'           => $user_id,
        'user_login'   => $pseudo,
        'user_email'   => $email,
        'display_name' => $pseudo,
        'first_name'   => $prenom,
        'last_name'    => $nom,
    ];

    if (!empty($password)) {
        if (strlen($password) < 8) {
            return;
        }
        $update_data['user_pass'] = $password;
    }

    wp_update_user($update_data);

    // Métas WSE
    update_user_meta($user_id, 'wse_nom', $nom);
    update_user_meta($user_id, 'wse_prenom', $prenom);
    update_user_meta($user_id, 'wse_adresse', $adresse);
    update_user_meta($user_id, 'wse_telephone', $telephone);
    update_user_meta($user_id, 'wse_age', $age);
    update_user_meta($user_id, 'wse_team', $team);
    update_user_meta($user_id, 'wse_association', $association);
    update_user_meta($user_id, 'wse_profession', $profession);
    update_user_meta($user_id, 'wse_hobbies', $hobbies);

    // Pratique sécurisée
    $allowed_pratiques = ['Débutant', 'Occasionnel', 'Confirmé', 'Accro', 'Vétérant', 'Légende'];
    if (current_user_can('administrator')) {
        $allowed_pratiques[] = 'Dieu';
    }
    if (in_array($pratique, $allowed_pratiques, true)) {
        update_user_meta($user_id, 'wse_pratique', $pratique);
    }

    wp_redirect(home_url('/espace-membre/?updated=1'));
    exit;
}
add_action('template_redirect', 'wse_handle_profile_update');


/* ===========================
   BADGES WSE
=========================== */
function wse_bbp_user_badge($author_id)
{
    $pratique = get_user_meta($author_id, 'wse_pratique', true);
    if (!$pratique) return;

    $badges = [
        'Débutant'   => 'badge-debutant',
        'Occasionnel' => 'badge-occasionnel',
        'Confirmé'   => 'badge-confirme',
        'Accro'      => 'badge-accro',
        'Vétérant'   => 'badge-veterant',
        'Légende'    => 'badge-legende',
        'Dieu'       => 'badge-dieu'
    ];

    if (!isset($badges[$pratique])) return;

    echo '<span class="wse-badge ' . esc_attr($badges[$pratique]) . '">' . esc_html($pratique) . '</span>';
}

add_action('bbp_theme_after_reply_author_details', function () {
    wse_bbp_user_badge(bbp_get_reply_author_id());
});

add_action('bbp_theme_after_topic_author_details', function () {
    wse_bbp_user_badge(bbp_get_topic_author_id());
});


/* ===========================
   AVATAR PERSONNALISÉ WSE
=========================== */

/**
 * Upload avatar personnalisé
 */
function wse_save_user_avatar($user_id)
{
    if (!isset($_FILES['avatar']) || empty($_FILES['avatar']['name'])) {
        return;
    }

    if ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
        return;
    }

    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $uploaded = wp_handle_upload($_FILES['avatar'], ['test_form' => false]);

    if (isset($uploaded['error'])) {
        return;
    }

    $attachment = [
        'post_mime_type' => $uploaded['type'],
        'post_title'     => sanitize_file_name($_FILES['avatar']['name']),
        'post_content'   => '',
        'post_status'    => 'inherit'
    ];

    $attach_id = wp_insert_attachment($attachment, $uploaded['file']);

    $attach_data = wp_generate_attachment_metadata($attach_id, $uploaded['file']);
    wp_update_attachment_metadata($attach_id, $attach_data);

    update_user_meta($user_id, 'wse_avatar', $attach_id);
}
add_action('init', function () {
    if (isset($_POST['wse_update_profile']) && is_user_logged_in()) {
        wse_save_user_avatar(get_current_user_id());
    }
});


/**
 * Récupérer l’avatar WSE
 */
function wse_get_user_avatar($user_id, $size = 80)
{
    $avatar_id = get_user_meta($user_id, 'wse_avatar', true);

    if ($avatar_id) {
        $img = wp_get_attachment_image_src($avatar_id, 'thumbnail');
        if ($img) {
            return '<img class="wse-avatar" src="' . esc_url($img[0]) . '" width="' . $size . '" height="' . $size . '">';
        }
    }

    return get_avatar($user_id, $size);
}


/**
 * Afficher avatar WSE dans les topics & réponses
 */
add_action('bbp_theme_before_reply_author_details', function () {
    echo wse_get_user_avatar(bbp_get_reply_author_id(), 80);
});

add_action('bbp_theme_before_topic_author_details', function () {
    echo wse_get_user_avatar(bbp_get_topic_author_id(), 80);
});


/**
 * Désactiver l’avatar natif de bbPress (évite les doublons)
 */
add_filter('bbp_get_reply_author_avatar', '__return_empty_string');
add_filter('bbp_get_topic_author_avatar', '__return_empty_string');


/**
 * Remplacer Gravatar dans la page Profil bbPress
 */
add_filter('bbp_get_user_profile_avatar', function ($avatar, $user_id, $size) {

    $avatar_id = get_user_meta($user_id, 'wse_avatar', true);

    if ($avatar_id) {
        $img = wp_get_attachment_image_src($avatar_id, 'thumbnail');
        if ($img) {
            return '<img class="wse-avatar" src="' . esc_url($img[0]) . '" width="' . $size . '" height="' . $size . '">';
        }
    }

    return get_avatar($user_id, $size);
}, 10, 3);
add_filter('bbp_get_displayed_user_avatar', function ($avatar, $user_id, $size) {

    $avatar_id = get_user_meta($user_id, 'wse_avatar', true);

    if ($avatar_id) {
        $img = wp_get_attachment_image_src($avatar_id, 'thumbnail');
        if ($img) {
            return '<img class="wse-avatar" src="' . esc_url($img[0]) . '" width="' . $size . '" height="' . $size . '">';
        }
    }

    return get_avatar($user_id, $size);
}, 10, 3);
add_filter('bbp_get_user_avatar', function ($avatar, $user_id, $size) {

    $avatar_id = get_user_meta($user_id, 'wse_avatar', true);

    if ($avatar_id) {
        $img = wp_get_attachment_image_src($avatar_id, 'thumbnail');
        if ($img) {
            return '<img class="wse-avatar" src="' . esc_url($img[0]) . '" width="' . $size . '" height="' . $size . '">';
        }
    }

    return get_avatar($user_id, $size);
}, 10, 3);
add_filter('get_avatar', function ($avatar, $id_or_email, $size) {

    // Récupérer l'ID utilisateur
    $user_id = 0;

    if (is_numeric($id_or_email)) {
        $user_id = (int) $id_or_email;
    } elseif (is_object($id_or_email) && isset($id_or_email->user_id)) {
        $user_id = (int) $id_or_email->user_id;
    } elseif (is_string($id_or_email)) {
        $user = get_user_by('email', $id_or_email);
        if ($user) {
            $user_id = $user->ID;
        }
    }

    if (!$user_id) {
        return $avatar;
    }

    // Avatar WSE
    $avatar_id = get_user_meta($user_id, 'wse_avatar', true);

    if ($avatar_id) {
        $img = wp_get_attachment_image_src($avatar_id, 'thumbnail');
        if ($img) {
            return '<img class="wse-avatar" src="' . esc_url($img[0]) . '" width="' . $size . '" height="' . $size . '">';
        }
    }

    return $avatar;
}, 10, 3);


add_action('after_setup_theme', function () {
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
});


add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('wc-single-product');
    wp_enqueue_script('wc-product-gallery');
    wp_enqueue_script('wc-product-gallery-slider');
    wp_enqueue_script('wc-product-gallery-lightbox');
}, 20);
