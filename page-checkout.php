<?php
/*
Template Name: Checkout WooCommerce
*/

get_header();
?>

<main class="page-content checkout-page">
    <div class="page-container">
        <?php
        if (function_exists('woocommerce_checkout')) {
            woocommerce_checkout();
        } elseif (shortcode_exists('woocommerce_checkout')) {
            echo do_shortcode('[woocommerce_checkout]');
        } else {
            echo '<p>WooCommerce n\'est pas activé ou la page de checkout n\'est pas disponible.</p>';
        }
        ?>
    </div>
</main>

<?php
get_footer();
