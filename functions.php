<?php
// Création de la table à l'activation du thème
register_activation_hook(__FILE__, function () {
    global $wpdb;
    $table = $wpdb->prefix . 'wse_inscriptions';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(100) NOT NULL,
        prenom VARCHAR(100) NOT NULL,
        pseudo VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        adresse VARCHAR(255) NOT NULL,
        telephone VARCHAR(50) NOT NULL,
        age INT NOT NULL,
        team VARCHAR(100),
        association VARCHAR(100),
        profession VARCHAR(100),
        hobbies VARCHAR(255),
        pratique VARCHAR(50) NOT NULL,
        password VARCHAR(255) NOT NULL,
        date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
});

// Handler AJAX pour la connexion
add_action('wp_ajax_nopriv_wse_login', 'wse_login_handler');
add_action('wp_ajax_wse_login', 'wse_login_handler');
function wse_login_handler()
{
    global $wpdb;
    if (!session_id()) session_start();
    $login = sanitize_text_field($_POST['login']);
    $password = $_POST['password'];
    $table = $wpdb->prefix . 'wse_inscriptions';
    $user = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table WHERE email = %s OR pseudo = %s", $login, $login
    ));
    if ($user && password_verify($password, $user->password)) {
        $_SESSION['wse_user_id'] = $user->id;
        $_SESSION['wse_user_pseudo'] = $user->pseudo;
        wp_send_json(['success' => true, 'message' => 'Connexion réussie !']);
    
    } else {
        wp_send_json(['success' => false, 'message' => 'Identifiants incorrects.']);
    }
}

// Handler AJAX pour l'inscription
add_action('wp_ajax_nopriv_wse_register', 'wse_register_handler');
add_action('wp_ajax_wse_register', 'wse_register_handler');
function wse_register_handler()
{
    global $wpdb;
    $table = $wpdb->prefix . 'wse_inscriptions';
    $fields = array_map('sanitize_text_field', $_POST);

    // Hash du mot de passe
    $password_hash = password_hash($fields['password'], PASSWORD_DEFAULT);

    $wpdb->insert($table, [
        'nom'         => $fields['nom'],
        'prenom'      => $fields['prenom'],
        'pseudo'      => $fields['pseudo'],
        'email'       => $fields['email'],
        'adresse'     => $fields['adresse'],
        'telephone'   => $fields['telephone'],
        'age'         => intval($fields['age']),
        'team'        => $fields['team'],
        'association' => $fields['association'],
        'profession'  => $fields['profession'],
        'hobbies'     => $fields['hobbies'],
        'pratique'    => $fields['pratique'],
        'password' => $password_hash,
    ]);
    wp_send_json(['message' => 'Inscription enregistrée !']);
}

// Enqueue des scripts et styles + localisation de wolfsoftData
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

    // Style spécifique à la page Terrain
    wp_enqueue_style(
        'terrain-style',
        get_stylesheet_directory_uri() . '/assets/css/terrain.css',
        array('child-style')
    );

    // JS global pour toutes les pages
    wp_enqueue_script(
        'wolfsofteure-main',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        array(),
        null,
        true
    );
    wp_localize_script('wolfsofteure-main', 'wolfsoftData', array(
        'themeUrl' => get_stylesheet_directory_uri(),
        'ajaxUrl' => admin_url('admin-ajax.php')
    ));

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
            'themeUrl' => get_stylesheet_directory_uri(),
            'ajaxUrl' => admin_url('admin-ajax.php')
        ));
    }
}, 10);
