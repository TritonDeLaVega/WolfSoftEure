<?php
/* Template Name: Espace Membre */
get_header();

// Protection : accès réservé aux utilisateurs connectés
if ( !is_user_logged_in() ) {
    wp_redirect( wp_login_url() );
    exit;
}
?>

<main class="site-main espace-membre">

    <?php $user = wp_get_current_user(); ?>
    <p>Bienvenue, <?php echo esc_html( $user->display_name ); ?> !</p>

    <!-- Ici tu pourras afficher les infos wse_* plus tard -->

</main>

<?php get_footer(); ?>
