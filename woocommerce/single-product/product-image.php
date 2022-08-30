<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.1
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
		'woocommerce-product-gallery--columns-' . absint( $columns ),
		'images',
	)
);
?>

<?php $attachment_ids = $product->get_gallery_image_ids();?>
<div class="row">
    <div class="col">
        <div thumbsSlider="" class="swiper ec-product-gallery gallery-thumbs">
            <!-- Additional required wrapper -->
            <button class="ec-carousel__arrow swiper-prev btn btn-light" data-glide-dir="<">
                <i class="bi bi-arrow-up"></i>
            </button>
            <div class="swiper-wrapper">
                <!-- Slides -->
                <?php foreach ($attachment_ids as $attachment_id) : ?>
                    <div class="swiper-slide">
                        <?php echo wp_get_attachment_image($attachment_id, 'thumbnail')?>
                    </div>
                <?php endforeach;?>
            </div>
            <button class="ec-carousel__arrow swiper-next btn btn-light" data-glide-dir=">">
                <i class="bi bi-arrow-down"></i>
            </button>
        </div>
    </div>
    <div class="col-md-9">
        <div class="swiper ec-product-gallery gallery-top">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper pswp-gallery">
                <!-- Slides -->
                <?php foreach ($attachment_ids as $attachment_id) : ?>
                <?php list( $src, $width, $height ) = wp_get_attachment_image_src($attachment_id, 'large');?>
                    <a
                        href="<?php echo $src?>" class="swiper-slide"
                        data-pswp-width="<?php echo $width;?>"
                        data-pswp-height="<?php echo $height;?>"
                        target="_blank"
                    >
                        <img src="<?php echo $src?>" width="<?php echo $width;?>" height="<?php echo $height;?>"/>
                    </a>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</div>

