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
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
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
                    <div class="col d-flex align-items-center">
                        <a class="ec-header__menu-toggler dropdown-toggle" href="#" id="catalogDropdown" data-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-list"></i>
                        </a>
                        <ul class="dropdown-menu catalog-menu" aria-labelledby="catalogDropdown">
							<?php echo get_menu_items();?>
                    </div>
                    <div class="col-md-8">
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
                <?php electrocommerce_cart_html()?>
            </div>
        </div>
    </div>
</div>
