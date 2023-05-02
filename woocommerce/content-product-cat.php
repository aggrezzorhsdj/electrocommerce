<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product-cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<?php $columns = wc_get_loop_prop( 'columns' ); $type = $columns === 12 ? '_list' : ''?>
<?php $classes = "_bg col-xl-$columns col-md-6 $type mb-4"; ?>
<?php
    $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
    $image = wp_get_attachment_image( $thumbnail_id, "large" );
?>
<div <?php wc_product_cat_class( $classes, $category ); ?>>
    <div class="ec-shop__product">
        <a class="ec-shop__product-link" href="<?php echo get_term_link( $category->term_id, 'product_cat' )?>">
            <div class="ec-shop__product-thumbnail ec-shop__product-item">
                <?php echo $image;?>
            </div>
            <div class="ec-shop__product-link-content">
                <div class="ec-shop__product-title mb-2 h4 text-light">
                    <?php echo $category->name;?>
                </div>
                <div class="ec-shop__product-subtitle text-light">
                    <?php echo $category->description;?>
                </div>
            </div>
        </a>
    </div>
</div>
