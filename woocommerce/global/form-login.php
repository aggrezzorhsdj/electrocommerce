<?php
/**
 * Login form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( is_user_logged_in() ) {
	return;
}

?>
<form class="" method="post" <?php echo ( $hidden ) ? 'style="display:none;"' : ''; ?>>

	<?php do_action( 'woocommerce_login_form_start' ); ?>

	<?php echo ( $message ) ? wpautop( wptexturize( $message ) ) : ''; // @codingStandardsIgnoreLine ?>

	<div class="form-floating mb-4">
		<input type="text" class="form-control" name="username" id="username" autocomplete="username" placeholder="<?php esc_html_e( 'Username or email', 'woocommerce' ); ?>" />
        <label class="form-label" for="username"><?php esc_html_e( 'Username or email', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
    </div>
	<div class="form-floating mb-4">
		<input class="form-control" type="password" name="password" id="password" autocomplete="current-password" placeholder="<?php esc_html_e( 'Password', 'woocommerce' ); ?>"/>
        <label class="form-label" for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
    </div>
	<div class="clear"></div>

    <?php do_action( 'woocommerce_login_form' ); ?>
    <div class="form-check mb-4">
        <input class="form-check-input" name="rememberme" type="checkbox" id="rememberme" value="forever" />
        <label class="form-check-label" for="rememberme">
            <?php esc_html_e( 'Remember me', 'woocommerce' ); ?>
        </label>
    </div>

    <div class="mb-4">
        <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
    </div>

    <button type="submit" class="btn btn-primary btn-lg w-100" name="login" value="<?php esc_attr_e( 'Login', 'woocommerce' ); ?>"><?php esc_html_e( 'Login', 'woocommerce' ); ?></button>
    <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
    <input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ); ?>" />
    <div class="text-center mt-3 mb-3"><?php echo __('or', 'electrocommerce')?></div>
    <a href="<?php echo get_permalink( get_page_by_path('my-account'))?>" class="btn btn-secondary w-100"><?php echo __('Sign In', 'electrocommerce')?></a>
	<div class="clear"></div>

	<?php do_action( 'woocommerce_login_form_end' ); ?>

</form>
