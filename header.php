<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package electrocommerce
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="yandex-verification" content="08ea5d3756084847" />
    <?php
        $page_id = '';
        $title = '';
        $description = '';
        $keywords = '';
        if (is_product()) {
            $product = wc_get_product( get_the_ID() );
            $title = get_the_title().' - '.get_bloginfo('name');
            $description = $product->post->post_excerpt;
            $terms = get_terms( 'product_tag' );
            $term_array = array();
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
                foreach ( $terms as $term ) {
                    $term_array[] = $term->name;
                }
            }
            $keywords = count($term_array) ? implode(",", $term_array) : '';

        } else if (is_shop()) {
            $page_id = wc_get_page_id('shop');
            $title = get_field('metatitle', $page_id);
            $description = get_field('metadescription', $page_id);
            $keywords = get_field('metakeywords', $page_id);
        } else if (is_singular()) {
            $title = get_field('metatitle', get_the_ID());
            $description = get_field('metadescription', get_the_ID());
            $keywords = get_field('metakeywords', get_the_ID());
        }

    ?>

    <meta name="title" content="<?php echo $title?>"/>
    <meta name="description" content="<?php echo $description?>"/>
    <meta name="keywords" content="<?php echo $keywords?>"/>
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php echo get_field('metatitle')?>
<?php wp_body_open(); ?>
<header>
    <div class="ec-header-top bg-primary">
        <div class="container">
            <div class="row">
                <div class="col-6">

                </div>
                <div class="col-6 justify-content-end d-flex">
                    <?php if($phone = get_theme_mod('ec_contact_phone')): ?>
                        <a class="btn btn-link _light" href="tel:<?php echo $phone;?>">
                            <i class="bi bi-telephone"></i> <?php echo $phone;?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="ec-header pt-3 pb-3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <div class="row">
                    <div class="col-4 d-flex align-items-center">
                        <a class="ec-header__menu-toggler dropdown-toggle" href="#" id="catalogDropdown" data-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-list"></i>
                        </a>
                        <ul class="dropdown-menu catalog-menu" aria-labelledby="catalogDropdown">
							<?php echo get_menu_items();?>
                    </div>
                    <div class="col-md-8 col-4">
                        <div class="ec-header__item ec-header__logo">
							<?php echo get_custom_logo()?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
				<?php aws_get_search_form( true ); ?>
            </div>
            <div class="col justify-content-end d-flex">
                <div class="ec-header__toolbar">
                    <div class="ec-header__toolbar-item">
                        <?php electrocommerce_cart_html()?>
                    </div>
                    <div class="ec-header__toolbar-item">
                        <?php electrocoommerce_loginout_link()?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php electrocommerce_modal(array(
    'id' => 'loginout',
    'content' => 'woocommerce_login_form',
    'content_type' => 'function',
    'caption' => __('Log In', 'electrocommerce'),
    'classes_dialog' => 'modal-dialog-centered modal-md'
));?>
