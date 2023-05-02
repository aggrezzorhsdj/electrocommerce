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
<?php require get_template_directory() . "/sprites.php"; ?>
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
        <div class="ec-header__row">
            <div class="ec-header__menu ec-header__item">
                <a class="ec-header__menu-toggler dropdown-toggle" href="#">
                    <i class="bi bi-list"></i>
                </a>
                <div class="ec-menu">
                    <?php echo wp_nav_menu([
                        "menu" => "header_menu",
                        "menu_class" => "ec-menu__nav nav flex-column",
                        "link_class" => "ec-menu__link nav-link",
                        "walker" => new My_Walker_Nav_Menu()
                    ]);?>
                </div>
            </div>
            <div class="ec-header__logo ec-header__item">
                <?php echo get_custom_logo()?>
            </div>
            <div class="ec-header__search ec-header__item">
                <?php aws_get_search_form( true ); ?>
            </div>
            <div class="ec-header__toolbar ec-header__item">
                <div class="ec-header__toolbar-item">
                    <?php electrocommerce_cart_html()?>
                </div>
                <div class="ec-header__toolbar-item">
                    <?php electrocoommerce_loginout_link()?>
                </div>
            </div>
        </div>
    </div>

    <div class="ec-header__mobile">
        <div class="ec-header__back ec-header__mobile-item d-none"><i class="bi bi-arrow-left"></i></div>
        <div class="ec-header__menu-title ec-header__mobile-item"></div>
        <div class="ec-header__close ec-header__mobile-item"><i class="bi bi-x-lg"></i></div>
    </div>
</div>

<?php electrocommerce_modal(array(
    'id' => 'loginout',
    'content' => 'woocommerce_login_form',
    'content_type' => 'function',
    'caption' => __('Log In', 'electrocommerce'),
    'classes_dialog' => 'modal-dialog-centered modal-md'
));?>
