<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;
global $wp;
get_header('shop');

if (is_product_category()) {
    $term_id = (int)get_queried_object_id();
}
?>
    <?php
    /**
     * Hook: woocommerce_before_main_content.
     *
     * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
     * @hooked woocommerce_breadcrumb - 20
     * @hooked WC_Structured_Data::generate_website_data() - 30
     */
    do_action('woocommerce_before_main_content');

    ?>
    <section class="ec-section ec-shop">
        <div class="container">
            <div class="row align-items-center mb-4">
                <div class="col-md-6">
                    <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
                        <div class="ec-shop__title">
                            <h1><?php woocommerce_page_title(); ?></h1>
                            <span class="ec-shop__title-counter">
                        <?php
                        $args = array(
                            'total' => wc_get_loop_prop('total')
                        );
                        wc_get_template('loop/result-count.php', $args);
                        ?>
                    </span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <div class="ec-shop__panel">
                        <?php $columns = wc_get_loop_prop('columns'); ?>
                        <a href="<?php echo home_url($wp->request) ?>"
                           class="btn ec-shop__panel-btn <?php echo $columns == 3 ? 'btn-hurma' : 'btn-primary ' ?>">
                            <i class="bi bi-grid-fill"></i>
                        </a>
                        <a href="<?php echo home_url($wp->request) ?>/?view=list"
                           class="btn ec-shop__panel-btn <?php echo $columns == 12 ? 'btn-hurma' : 'btn-primary' ?>">
                            <i class="bi bi-list"></i>
                        </a>

                        <button class="btn btn-primary ec-shop__panel-btn" data-toggle="modal" data-target="#filterModal">
                            <i class="bi bi-funnel"></i>
                            <?php echo esc_html_e('Filter', 'electrocommerce') ?>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="ec-shop__products col">
                    <?php
                    if (woocommerce_product_loop()) {

                        /**
                         * Hook: woocommerce_before_shop_loop.
                         *
                         * @hooked woocommerce_output_all_notices - 10
                         * @hooked woocommerce_result_count - 20
                         * @hooked woocommerce_catalog_ordering - 30
                         */
                        do_action('woocommerce_before_shop_loop');

                        woocommerce_product_loop_start();

                        if (wc_get_loop_prop('total')) {
                            while (have_posts()) {
                                the_post();

                                /**
                                 * Hook: woocommerce_shop_loop.
                                 */
                                do_action('woocommerce_shop_loop');

                                wc_get_template_part('content', 'product');
                            }
                        }

                        woocommerce_product_loop_end();

                        /**
                         * Hook: woocommerce_after_shop_loop.
                         *
                         * @hooked woocommerce_pagination - 10
                         */
                        do_action('woocommerce_after_shop_loop');
                    } else {
                        /**
                         * Hook: woocommerce_no_products_found.
                         *
                         * @hooked wc_no_products_found - 10
                         */
                        do_action('woocommerce_no_products_found');
                    }
                    ?>
                </div>
                <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-slideout" role="document">
                        <?php
                        if ( '' === get_option( 'permalink_structure' ) ) {
                            $form_action = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
                        } else {
                            $form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( user_trailingslashit( $wp->request ) ) );
                        }

                        ?>
                        <form method="get" action="<?php echo esc_url( $form_action )?>" class="woocommerce-widget-layered-nav-dropdown modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><?php esc_html_e('Filters', 'electrocommerce');?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <?php

                                $filters = get_term_meta($term_id, "wh_meta_filters", true);
                                if (!empty($filters)) {
                                    foreach ($filters as $key => $filter) {
                                        the_widget('WC_Widget_Custom_Layered_Nav', array(
                                            'attribute' => $filter["name"],
                                            'display_type' => 'dropdown',
                                            'query_type' => 'or'
                                        ));
                                    }
                                }
                                ?>

                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" type="submit" value="<?php echo esc_attr__( 'Apply', 'woocommerce' )?>"><?php echo esc_html__( 'Apply', 'woocommerce' )?></button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php esc_html_e('Cancel', 'electrocommerce')?></button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                /**
                 * Hook: woocommerce_sidebar.
                 *
                 * @hooked woocommerce_get_sidebar - 10
                 */
//                do_action('woocommerce_sidebar');
                ?>

            </div>
            <?php

            /**
             * Hook: woocommerce_after_main_content.
             *
             * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
             */
            do_action('woocommerce_after_main_content');
            ?>
        </div>
    </section>
<?php

get_footer('shop');
