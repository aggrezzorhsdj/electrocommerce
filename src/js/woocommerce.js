
'use strict';
(function($){
    $(document).ready(function() {
        if ("select2" in $.fn) {
            const $select2 = $('select.woocommerce-widget-layered-nav-dropdown,.country_select').select2();
            if ($select2.val()?.length) {
                toggleSelect2Label($select2);
            }

            $select2.on('select2:open', (element) => {
                toggleSelect2Label(element.target);
            }).on('select2:close', (element) => {
                const targetLabel = getSelect2Label(element.target);
                const targetOptions = $(element.target.selectedOptions);
                if (targetOptions.length === 0) {
                    targetLabel.removeClass('selected');
                }
            });
        }
    });

    const toggleSelect2Label = (element) => {
        const targetLabel = getSelect2Label(element);
        targetLabel.addClass('selected');
    };

    const getSelect2Label = (element) => $(element).parent().find('.form-label');

    $(document.body).on('added_to_cart', function( e, fragments, cart_hash, $button ) {
        $button = typeof $button === 'undefined' ? false : $button;

        if ( $button ) {
            $button.prop('disabled', false);
            $button.removeClass( 'loading' );

            if ( fragments ) {
                $button.addClass( 'added' );
            }

            // View cart text.
            if ( fragments && ! wc_add_to_cart_params.is_cart && $button.parent().find( '.added_to_cart' ).length === 0 ) {
                $button.toggleClass('d-none');
                $button.after( '<a href="' + wc_add_to_cart_params.cart_url + '" class="added_to_cart wc-forward btn btn-primary" title="' +
                    wc_add_to_cart_params.i18n_view_cart + '">' + wc_add_to_cart_params.i18n_view_cart + '</a>' );

                const timeout = setTimeout(() => {
                    $button.parent().find('.added_to_cart').remove();
                    $button.toggleClass('d-none');
                    clearTimeout(timeout);
                },5000);
            }

            $( document.body ).trigger( 'wc_cart_button_updated', [ $button ] );
        }
    });

    $(document).on('click', '.single_add_to_cart_button', function (e) {
        e.preventDefault();

        var $thisbutton = $(this),
            $form = $thisbutton.closest('form.cart'),
            id = $thisbutton.val(),
            product_qty = $form.find('input[name=quantity]').val() || 1,
            product_id = $form.find('input[name=product_id]').val() || id,
            variation_id = $form.find('input[name=variation_id]').val() || 0;

        var data = {
            action: 'woocommerce_ajax_add_to_cart',
            product_id: product_id,
            product_sku: '',
            quantity: product_qty,
            variation_id: variation_id,
        };

        $(document.body).trigger('adding_to_cart', [$thisbutton, data]);

        $.ajax({
            type: 'post',
            url: wc_add_to_cart_params.ajax_url,
            data: data,
            beforeSend: function (response) {
                $thisbutton.removeClass('added').addClass('loading');
            },
            complete: function (response) {
                $thisbutton.addClass('added').removeClass('loading');
            },
            success: function (response) {

                if (response.error && response.product_url) {
                    window.location = response.product_url;
                    return;
                } else {
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                }
            },
        });

        return false;
    });
})(jQuery)