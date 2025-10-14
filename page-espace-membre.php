<?php
/* Template Name: Espace Membre */
get_header();

if (!session_id()) session_start();
if (isset($_SESSION['wse_user_id'])) {
    echo '<p>Bienvenue, ' . htmlspecialchars($_SESSION['wse_user_pseudo']) . ' !</p>';
    // Affiche le contenu personnalisé ici
} else {
    echo '<p>Veuillez vous connecter pour accéder à l\'espace membre.</p>';
}

get_footer();