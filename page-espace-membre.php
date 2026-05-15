<?php
/* Template Name: Espace Membre */
get_header();

// Protection : accès réservé aux utilisateurs connectés
if ( !is_user_logged_in() ) {
    wp_redirect( wp_login_url() );
    exit;
}

$user = wp_get_current_user();
?>

<main class="site-main espace-membre">

    <section class="em-header">
        <h1>Bienvenue, <?php echo esc_html( $user->display_name ); ?> 👋</h1>
        <p class="em-subtitle">Tableau de bord du membre WSE</p>
    </section>

    <section class="em-grid">

        <!-- PROFIL -->
        <div class="em-card">
            <h2>Profil</h2>
            <p><strong>Pseudo :</strong> <?php echo esc_html( $user->display_name ); ?></p>
            <p><strong>Email :</strong> <?php echo esc_html( $user->user_email ); ?></p>
            <p><strong>Rôle :</strong> <?php echo implode(', ', $user->roles); ?></p>

            <a class="em-btn" href="/modifier-profil/">Modifier mon profil</a>
        </div>

        <!-- INFOS WSE -->
        <div class="em-card">
            <h2>Informations WSE</h2>
            <p><strong>Pratique :</strong> <?php echo esc_html( get_user_meta($user->ID, 'wse_pratique', true) ); ?></p>
            <p><strong>Âge :</strong> <?php echo esc_html( get_user_meta($user->ID, 'wse_age', true) ); ?></p>
            <p><strong>Team :</strong> <?php echo esc_html( get_user_meta($user->ID, 'wse_team', true) ); ?></p>
        </div>

        <!-- ACTIONS RAPIDES -->
        <div class="em-card">
            <h2>Actions rapides</h2>

            <a class="em-btn" href="/forum/">Accéder au forum</a>
            <a class="em-btn" href="<?php echo wp_logout_url( home_url() ); ?>">Se déconnecter</a>
        </div>

        <!-- ANNONCES / NEWS -->
        <div class="em-card">
            <h2>Actualités WSE</h2>
            <p>Aucune annonce pour le moment.</p>
        </div>

    </section>

</main>

<?php get_footer(); ?>
