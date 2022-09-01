<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php if ( $has_orders ) : ?>

    <div class="ec-account__orders">
        <?php
        foreach ( $customer_orders->orders as $customer_order ) {
            $order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
            $item_count = $order->get_item_count() - $order->get_item_count_refunded();
            ?>
            <div class="ec-account__orders-item ec-content-wrapper woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
                <div class="row">
                    <div class="col-md-5">
                       <div class="ec-account__orders-title mb-3">
                           <span class="h3">
                               <?php echo __('Order', 'woocommerce') . ' ' . esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() );?>
                           </span>
                           <span>
                               <time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
                           </span>
                       </div>
                        <div class="ec-account__orders-content">
                            <div class="mb-3">
                                <?php echo __('Shipping method', 'woocommerce') . ': '.$order->get_shipping_method()?>
                            </div>
                            <div class="mb-3">
                                <?php echo __('Contacts', 'woocommerce') . ': '.($order->get_shipping_phone() ? $order->get_shipping_phone() :  $order->get_billing_phone())?>
                            </div>
                            <div class="mb-3">
                                <?php echo __('Status', 'woocommerce').': '.$order->get_status()?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <?php foreach ($order->get_items() as $item_id => $item) :?>
                                <div class="col">
                                    <?php $product = wc_get_product($item->get_product_id());?>
                                    <a href="<?php echo $product->get_permalink()?>">
                                        <img width="120" src="<?php echo wp_get_attachment_image_url($product->get_image_id(), 'thumbnail')?>" alt="<?php echo $item->get_name()?>"/>
                                    </a>
                                </div>
                            <?php endforeach;?>
                        </div>

                    </div>
                    <div class="col-md-3 text-right">
                        <div class="h2 mb-3">
                            <?php echo wc_price( $order->get_total(), array( 'currency' => $order->get_currency() ) )?>
                        </div>
                        <div>
                            <?php
                            $actions = wc_get_account_orders_actions( $order );

                            if ( ! empty( $actions ) ) {
                                foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                                    echo '<a href="' . esc_url( $action['url'] ) . '" class="btn btn-primary ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php esc_html_e( 'Browse products', 'woocommerce' ); ?></a>
		<?php esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
