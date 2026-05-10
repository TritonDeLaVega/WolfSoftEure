<?php
/* Template Name: Terrain */
get_header();
?>

<!-- SECTION TERRAIN -->
<section class="terrain-section">

    <!-- SLIDER IMAGES -->
    <div class="terrain-slider">
        <div class="slide"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/terrainMaps.png" alt="Carte du terrain"></div>
        <div class="slide"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/terrain1.jpg" alt="Terrain 1"></div>
        <div class="slide"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/terrain2.jpg" alt="Terrain 2"></div>
        <div class="slide"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/terrain3.jpg" alt="Terrain 3"></div>
    </div>

    <!-- CONTENU TEXTE + CARTE -->
    <div class="terrain-wrapper">

        <div class="terrain-text">
            <p>Le terrain se situe au bout de la <strong>Rue de l'Avenir, 27940 Courcelles-Sur-Seine.</strong><br>
                <strong>Coordonnées GPS :</strong> 49°11'14.3"N 1°20'40.0"E.
            </p>

            <p>Une fois arrivé au bout de la rue au STOP, tournez à droite ; puis empruntez le chemin de terre situé à quelques mètres sur la gauche.</p>

            <p>Enfin, prenez la première à droite : vous arriverez à l'accès de la zone neutre, où le personnel de l'association vous accueillera.</p>

            <p>Ce terrain est aménagé, et dispose entre autres d'un parking, de l'électricité, d'une zone de détente, d'un barbecue, et d'un WC.</p>

            <p>L'association propose également la vente de consommables, de repas et de boissons.</p>
        </div>

        <!-- GOOGLE MAPS -->
        <div class="terrain-map">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2621.676624170993!2d1.343333!3d49.187306!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e6c8c5f0b2f0d9%3A0x8c3b0e0c2f0f0b0!2sRue%20de%20l&#39;Avenir%2C%2027940%20Courcelles-sur-Seine!5e0!3m2!1sfr!2sfr!4v0000000000000"
                width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>

    </div>
</section>

<?php get_footer(); ?>
