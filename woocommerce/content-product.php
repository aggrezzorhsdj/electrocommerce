<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<?php $columns = wc_get_loop_prop( 'columns' ); $type = $columns === 12 ? '_list' : ''?>
<div <?php wc_product_class( "col-md-$columns $type mb-4", $product ); ?>>
    <div class="ec-shop__product">
        <a class="ec-shop__product-link" href="<?php echo get_permalink($product->ID)?>">
            <div class="ec-shop__product-thumbnail ec-shop__product-item">
                <?php echo woocommerce_get_product_thumbnail()?>
            </div>
            <div class="ec-shop__product-link-content">
                <div class="ec-shop__product-title mb-2">
                    <?php echo get_the_title()?>
                </div>
                <div class="ec-shop__product-link-content-info">
                    <div class="ec-shop__product-rating mb-2">
                        <?php woocommerce_template_loop_rating()?>
                    </div>
                    <div class="ec-shop__product-stock mb-2">
                        <?php wc_get_template('loop/stock.php');?>
                    </div>
                </div>
            </div>
        </a>
        <div class="ec-shop__product-bottom">
            <div class="ec-shop__product-price">
                <?php woocommerce_template_loop_price();?>
            </div>
            <?php woocommerce_template_loop_add_to_cart()?>
        </div>
    </div>
</div>
