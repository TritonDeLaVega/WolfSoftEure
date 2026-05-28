<?php
/* Template Name: Modifier Profil */
get_header();

if (!is_user_logged_in()) {
    wp_redirect(wp_login_url());
    exit;
}

$user = wp_get_current_user();

$wse_nom         = get_user_meta($user->ID, 'wse_nom', true);
$wse_prenom      = get_user_meta($user->ID, 'wse_prenom', true);
$wse_adresse     = get_user_meta($user->ID, 'wse_adresse', true);
$wse_telephone   = get_user_meta($user->ID, 'wse_telephone', true);
$wse_age         = get_user_meta($user->ID, 'wse_age', true);
$wse_team        = get_user_meta($user->ID, 'wse_team', true);
$wse_association = get_user_meta($user->ID, 'wse_association', true);
$wse_profession  = get_user_meta($user->ID, 'wse_profession', true);
$wse_hobbies     = get_user_meta($user->ID, 'wse_hobbies', true);
$wse_pratique    = get_user_meta($user->ID, 'wse_pratique', true);

$is_admin = current_user_can('administrator');
?>

<main class="site-main espace-membre">

    <section class="em-header">
        <h1>Modifier mon profil</h1>
        <p class="em-subtitle">Mets à jour tes informations WSE</p>
    </section>

    <form class="em-form" method="post" enctype="multipart/form-data">

        <?php wp_nonce_field('wse_update_profile_action', 'wse_update_profile_nonce'); ?>

        <h2 class="em-form-title">Identité</h2>

        <label>Avatar actuel</label>
        <?php
        $avatar_id = get_user_meta($user->ID, 'wse_avatar', true);
        if ($avatar_id) {
            $img = wp_get_attachment_image_src($avatar_id, 'thumbnail');
            if ($img) {
                echo '<img src="' . esc_url($img[0]) . '" class="wse-avatar" width="120" height="120" style="margin-bottom:10px;border-radius:8px;">';
            }
        } else {
            echo '<p>Aucun avatar enregistré.</p>';
        }
        ?>

        <label for="avatar">Changer d’avatar</label>
        <input type="file" id="avatar" name="avatar" accept="image/*">

        <label>Nom*
            <input type="text" name="wse_nom" value="<?php echo esc_attr($wse_nom); ?>" required>
        </label>

        <label>Prénom*
            <input type="text" name="wse_prenom" value="<?php echo esc_attr($wse_prenom); ?>" required>
        </label>

        <label>Pseudonyme de joueur*
            <input type="text" name="pseudo" value="<?php echo esc_attr($user->user_login); ?>" required>
        </label>

        <label>E-mail*
            <input type="email" name="email" value="<?php echo esc_attr($user->user_email); ?>" required>
        </label>

        <label>Nouveau mot de passe
            <input type="password" name="password" placeholder="Laisse vide pour ne pas changer">
        </label>

        <h2 class="em-form-title">Coordonnées</h2>

        <label>Adresse postale*
            <input type="text" name="wse_adresse" value="<?php echo esc_attr($wse_adresse); ?>" required>
        </label>

        <label>Numéro de téléphone*
            <input type="tel" name="wse_telephone" value="<?php echo esc_attr($wse_telephone); ?>" required>
        </label>

        <label>Âge*
            <input type="number" name="wse_age" value="<?php echo esc_attr($wse_age); ?>" required>
        </label>

        <h2 class="em-form-title">Profil Airsoft</h2>

        <label>Team
            <input type="text" name="wse_team" value="<?php echo esc_attr($wse_team); ?>">
        </label>

        <label>Association
            <input type="text" name="wse_association" value="<?php echo esc_attr($wse_association); ?>">
        </label>

        <label>Profession
            <input type="text" name="wse_profession" value="<?php echo esc_attr($wse_profession); ?>">
        </label>

        <label>Hobbies
            <input type="text" name="wse_hobbies" value="<?php echo esc_attr($wse_hobbies); ?>">
        </label>

        <label>Pratique Airsoft*
            <select name="wse_pratique" required>
                <option value="">Sélectionner</option>
                <?php
                $options = ['Débutant', 'Occasionnel', 'Confirmé', 'Accro', 'Vétérant', 'Légende'];
                foreach ($options as $opt) {
                    printf(
                        '<option value="%1$s" %2$s>%1$s</option>',
                        esc_attr($opt),
                        selected($wse_pratique, $opt, false)
                    );
                }
                if ($is_admin) {
                    printf(
                        '<option value="Dieu" %s>Dieu</option>',
                        selected($wse_pratique, 'Dieu', false)
                    );
                }
                ?>
            </select>
        </label>

        <input type="hidden" name="wse_update_profile" value="1">

        <button type="submit" class="em-btn em-btn-primary">Enregistrer les modifications</button>
    </form>

    <?php if (!$is_admin) : ?>
        <form class="em-form em-form-delete" method="post" onsubmit="return confirm('Es-tu sûr de vouloir supprimer ton compte ? Cette action est définitive.');">
            <?php wp_nonce_field('wse_delete_account_action', 'wse_delete_account_nonce'); ?>
            <button type="submit" name="wse_delete_account" value="1" class="em-btn em-btn-danger">
                Supprimer mon compte
            </button>
        </form>
    <?php endif; ?>

</main>

<?php get_footer(); ?>