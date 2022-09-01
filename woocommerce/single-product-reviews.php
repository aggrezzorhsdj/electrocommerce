<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.3.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
	return;
}

?>
<div id="reviews" class="woocommerce-Reviews">
	<div id="comments" class="ec-product-reviews">
		<div class="ec-product-reviews__header">
            <h2 class="mb-4">
                <?php
                $rating = $product->get_average_rating();
                $count = $product->get_review_count();
                if ( $count && wc_review_ratings_enabled() ) {
                    /* translators: 1: reviews count 2: product name */
                    $reviews_title = sprintf( esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'woocommerce' ) ), esc_html( $count ), '<span>' . get_the_title() . '</span>' );
                    echo apply_filters( 'woocommerce_reviews_title', $reviews_title, $count, $product ); // WPCS: XSS ok.
                } else {
                    esc_html_e( 'Reviews', 'woocommerce' );
                }
                ?>
            </h2>
            <button class="btn btn-primary ec-shop__panel-btn" data-toggle="modal" data-target="#reviewModal">
                <?php echo __('Add review', 'electrocommerce') ?>
            </button>
        </div>

		<div class="row">
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-5">
                        <div class="ec-product-reviews__stat-rated h1">
                            <?php echo $rating;?>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="ec-product-reviews__stat-lines">
                            <?php for($i = 5; $i > 0; $i--) : ?>
                            <?php $rating_count = $product->get_rating_count($i);?>
                            <div class="ec-product-reviews__stat-lines-line">
                                <div class="ec-product-reviews__stat-lines-line-col"><?php echo $i?></div>
                                <div class="ec-product-reviews__stat-lines-line-value">
                                    <div
                                            class="ec-product-reviews__stat-lines-line-value-item _load"
                                            style="width: <?php echo ($rating_count / $count * 100) ?>%"></div>
                                    <div class="ec-product-reviews__stat-lines-line-value-item _empty"></div>
                                </div>
                                <div class="ec-product-reviews__stat-lines-line-col"><?php echo $rating_count;?></div>
                            </div>
                            <?php endfor;?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <?php if ( have_comments() ) : ?>
                    <ol class="commentlist">
                        <?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
                    </ol>

                    <?php
                    if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
                        echo '<nav class="woocommerce-pagination">';
                        paginate_comments_links(
                            apply_filters(
                                'woocommerce_comment_pagination_args',
                                array(
                                    'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
                                    'next_text' => is_rtl() ? '&larr;' : '&rarr;',
                                    'type'      => 'list',
                                )
                            )
                        );
                        echo '</nav>';
                    endif;
                    ?>
                <?php else : ?>
                    <p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'woocommerce' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
	</div>
    <div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title h5"><?php _e('Submit review', 'electrocommerce')?></div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>
                        <div id="review_form_wrapper">
                            <div id="review_form">
                                <?php
                                $commenter    = wp_get_current_commenter();
                                $comment_form = array(
                                    /* translators: %s is product title */
                                    'title_reply'         => have_comments() ? esc_html__( 'Add a review', 'woocommerce' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'woocommerce' ), get_the_title() ),
                                    /* translators: %s is product title */
                                    'title_reply_to'      => esc_html__( 'Leave a Reply to %s', 'woocommerce' ),
                                    'title_reply_before'  => '<span id="reply-title" class="comment-reply-title">',
                                    'title_reply_after'   => '</span>',
                                    'comment_notes_after' => '',
                                    'label_submit'        => esc_html__( 'Submit', 'woocommerce' ),
                                    'logged_in_as'        => '',
                                    'comment_field'       => '',
                                );

                                $name_email_required = (bool) get_option( 'require_name_email', 1 );
                                $fields              = array(
                                    'author' => array(
                                        'label'    => __( 'Name', 'woocommerce' ),
                                        'type'     => 'text',
                                        'value'    => $commenter['comment_author'],
                                        'required' => $name_email_required,
                                    ),
                                    'email'  => array(
                                        'label'    => __( 'Email', 'woocommerce' ),
                                        'type'     => 'email',
                                        'value'    => $commenter['comment_author_email'],
                                        'required' => $name_email_required,
                                    ),
                                );

                                $comment_form['fields'] = array();

                                foreach ( $fields as $key => $field ) {
                                    $field_html  = '<p class="comment-form-' . esc_attr( $key ) . '">';
                                    $field_html .= '<label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] );

                                    if ( $field['required'] ) {
                                        $field_html .= '&nbsp;<span class="required">*</span>';
                                    }

                                    $field_html .= '</label><input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $field['value'] ) . '" size="30" ' . ( $field['required'] ? 'required' : '' ) . ' /></p>';

                                    $comment_form['fields'][ $key ] = $field_html;
                                }

                                $account_page_url = wc_get_page_permalink( 'myaccount' );
                                if ( $account_page_url ) {
                                    /* translators: %s opening and closing link tags respectively */
                                    $comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'woocommerce' ), '<a href="' . esc_url( $account_page_url ) . '">', '</a>' ) . '</p>';
                                }

                                if ( wc_review_ratings_enabled() ) {
                                    $comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'woocommerce' ) . ( wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '' ) . '</label><select name="rating" id="rating" required>
						<option value="">' . esc_html__( 'Rate&hellip;', 'woocommerce' ) . '</option>
						<option value="5">' . esc_html__( 'Perfect', 'woocommerce' ) . '</option>
						<option value="4">' . esc_html__( 'Good', 'woocommerce' ) . '</option>
						<option value="3">' . esc_html__( 'Average', 'woocommerce' ) . '</option>
						<option value="2">' . esc_html__( 'Not that bad', 'woocommerce' ) . '</option>
						<option value="1">' . esc_html__( 'Very poor', 'woocommerce' ) . '</option>
					</select></div>';
                                }

                                $comment_form['comment_field'] .= '<p class="comment-form-comment"><div class="form-floating"><textarea id="comment" name="comment" class="form-control" style="resize: none;" cols="8" rows="8" placeholder="'.esc_html__('Your review', 'woocommerce').'" required></textarea><label class="form-label" for="comment">' . esc_html__( 'Your review', 'woocommerce' ) . '&nbsp;<span class="required">*</span></label></div></p>';

                                $comment_form['class_submit'] = 'btn btn-primary';
                                $comment_form['submit_button'] = '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>';

                                comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
                                ?>
                            </div>
                        </div>
                    <?php else : ?>
                        <p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'woocommerce' ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
	<div class="clear"></div>
</div>
