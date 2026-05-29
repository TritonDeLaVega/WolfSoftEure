<?php
defined('ABSPATH') || exit;

get_header(); ?>

<div class="wse-product-wrapper">
    <div class="wse-product-content">
        <?php
        while (have_posts()) :
            the_post();
            wc_get_template_part('content', 'single-product');
        endwhile;
        ?>
    </div>
</div>

<?php get_footer(); ?>