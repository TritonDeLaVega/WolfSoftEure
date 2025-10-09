<?php
/* Template Name: Terrain */
get_header();
?>

<div class="terrain-container">
    <div class="terrain-image zoom-wrapper">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/terrainMaps.png" alt="Carte du terrain" class="zoom-target">
    </div>

    <div class="terrain-text">
        <p>Le terrain se situe au bout de la <strong>Rue de l'Avenir, 27940 Courcelles-Sur-Seine.</strong><br>
            <strong>Coordonnées GPS :</strong> 49°11'14.3"N 1°20'40.0"E.
        </p>

        <p>Une fois arrivé au bout de la rue au STOP, tournez à droite ; puis empruntez le chemin de terre situé à quelques mètres sur la gauche.</p>

        <p>Enfin, prenez la première à droite : vous arriverez à l'accès de la zone neutre, où le personnel de l'association vous accueillera.</p>

        <p>Ce terrain est aménagé, et dispose entre autres d'un parking, de l'électricité, d'une zone de détente, d'un barbecue, et d'un WC.</p>

        <p>L'association propose également la vente de consommables, de repas et de boissons.</p>
    </div>
</div>

<?php get_footer(); ?>