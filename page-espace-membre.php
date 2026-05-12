<?php
/* Template Name: Espace Membre */
get_header();
?>

<main class="site-main espace-membre">

<?php if ( is_user_logged_in() ) : ?>

    <?php $user = wp_get_current_user(); ?>
    <p>Bienvenue, <?php echo esc_html( $user->display_name ); ?> !</p>

    <!-- Ici tu pourras afficher les infos wse_* plus tard -->

<?php else : ?>

    <p>Veuillez vous connecter pour accéder à l'espace membre.</p>

<?php endif; ?>

</main>

<?php get_footer(); ?>
