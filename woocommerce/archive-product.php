<?php
defined('ABSPATH') || exit;

get_header(); ?>

<!-- HERO GLOBAL -->
<?php get_template_part('template-parts/hero'); ?>

<!-- SLIDER GLOBAL (optionnel) -->
<?php // get_template_part('template-parts/slider'); 
?>

<div class="wse-shop-container">

    <header class="woocommerce-products-header">
        <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
            <h1 class="woocommerce-products-header__title page-title">
                <?php woocommerce_page_title(); ?>
            </h1>
        <?php endif; ?>

        <?php do_action('woocommerce_archive_description'); ?>
    </header>

    <?php if (woocommerce_product_loop()) : ?>

        <?php do_action('woocommerce_before_shop_loop'); ?>

        <!-- CORRECTION MAJEURE : UL OBLIGATOIRE POUR WOOCOMMERCE -->
        <ul class="products wse-products-grid">
            <?php while (have_posts()) : the_post(); ?>
                <?php wc_get_template_part('content', 'product'); ?>
            <?php endwhile; ?>
        </ul>

        <?php do_action('woocommerce_after_shop_loop'); ?>

    <?php else : ?>

        <?php do_action('woocommerce_no_products_found'); ?>

    <?php endif; ?>

</div>

<?php get_footer(); ?>