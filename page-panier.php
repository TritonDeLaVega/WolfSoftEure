<?php
/*
Template Name: Panier WooCommerce
*/

get_header();
?>

<main class="page-content cart-page">
    <div class="page-container">
        <?php
        if (function_exists('woocommerce_cart')) {
            woocommerce_cart();
        } elseif (shortcode_exists('woocommerce_cart')) {
            echo do_shortcode('[woocommerce_cart]');
        } else {
            echo '<p>WooCommerce n\'est pas activé ou la page panier n\'est pas disponible.</p>';
        }
        ?>
    </div>
</main>

<?php
get_footer();
