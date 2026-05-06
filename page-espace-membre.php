<?php
/* Template Name: Espace Membre */
get_header();

echo '<main class="site-main espace-membre">';

if (is_user_logged_in()) {
    $user = wp_get_current_user();
    echo '<p>Bienvenue, ' . esc_html($user->display_name) . ' !</p>';
    // Ici tu peux afficher les infos usermeta wse_*
} else {
    echo '<p>Veuillez vous connecter pour accéder à l\'espace membre.</p>';
}

echo '</main>';

get_footer();
