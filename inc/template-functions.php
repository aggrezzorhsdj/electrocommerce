<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package electrocommerce
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function electrocommerce_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'electrocommerce_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function electrocommerce_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'electrocommerce_pingback_header' );

/**
 * Helpers
 */

function phone_formatter($data) {
    $result = '';
    if(  preg_match( '/^\d(\d{3})(\d{3})(\d{4})$/', $data,  $matches ) ) {
        $result = '8('.$matches[1] .')' . $matches[2] . $matches[3];
    }

    return $result;
}

function get_footer_menu($menu_name, $column_class = 'col-md-4') {
    $result = '';
    if(has_nav_menu($menu_name)) {
        $menu = wp_nav_menu([
            'theme_location' => $menu_name,
            'container' => false,
            'menu_class' => 'ec-footer__menu',
            'echo' => 0
        ]);
        $result = '
            <div class="'.$column_class.'">
                <div class="ec-footer__title h2">
                    '.wp_get_nav_menu_name($menu_name).'
                </div>
                '.$menu.'
            </div>
        ';
    }
    return $result;
}

function menu_items($menu_name) {
    $locations = get_nav_menu_locations();

    if ($locations && isset($locations['header_menu'])) {
        $items = wp_get_nav_menu_items($locations['header_menu'], [
            'output_key' => 'menu_order',
        ]);

        $menu_list = '';

        foreach ((array)$items as $key => $menu_item) {
            $menu_list .= get_menu_item($menu_item->classes[0], $menu_item->title, $menu_item->url);
        }

        return $menu_list;
    }
}

function get_menu_items($menu_name = 'catalog_menu') {
    $locations = get_nav_menu_locations();
    if ($locations && isset($locations[$menu_name])) {
        $items = wp_get_nav_menu_items($locations[$menu_name], [
            'output_key' => 'menu_order',
        ]);

        $menu_list = '';

        foreach ((array)$items as $key => $menu_item) {
            $icon = $menu_item->classes[0] ? "<i class='".implode(" ", $menu_item->classes)."'></i>" : "";
            $menu_list .= "<li><a class='dropdown-item' href='$menu_item->url'>$icon $menu_item->title</a></li>";
        }

        return $menu_list;
    }
}

function get_menu_item($classes, $title, $link) {
    $type = $classes ? '_tertiary' : '_primary';
    $menu_link = $classes ? ' 
        <span class="ec-button__icon">
            <svg width="20" height="20" stroke="#fff"><use xlink:href="#' . $classes . '"></use> </svg>
        </span>
        <span class="ec-button__content">' . $title . '</span>' :
        '<span class="ec-button__content">' . $title . '</span>';

    return '
        <li class="ec-nav-item">
            <a class="ec-nav-link ec-button ' . $type . '" href="' . $link . '">
                ' . $menu_link . '
            </a>
        </li>';
}


function electrocommerce_get_attr_values($term_id, $attr) {
    $term = get_term($term_id);
    $args = array(
        'post_type' => 'product',
        'product_cat' => $term->slug,
        'tax_query' => array(array(
            'taxonomy'        => $attr,
            'field'           => 'slug',
            'terms'           =>  array(''),
            'operator'        => 'NOT IN',
        ))
    );
    $query = new WP_Query($args);

    $properties = [];
    if ($query->have_posts()) {
        while($query->have_posts()) {
            $query->the_post();
            global $product;
            $attrs = $product->get_attributes('view');
            array_push($properties, $attrs);
        }
    }

    return get_unique_attrs($properties);
}

function get_unique_attrs($attrs) {
    $result = [];
    for($i = 0; $i < count($attrs); $i++) {
        foreach ($attrs[$i] as $key => $property) {
            $result = array_merge($result, $property->get_slugs());
        }
    }

    return array_unique($result);
}

function electrocommerce_cart_html() {
    ?>
    <a class="ec-header__toolbar-link" id="cart-customlocation" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>">
        <i class="bi bi-cart3"></i>
        <?php
        if ( ! WC()->cart->prices_include_tax ) {
            $amount = WC()->cart->cart_contents_total;
        } else {
            $amount = WC()->cart->cart_contents_total + WC()->cart->tax_total;
        }
        ?>
        <?php echo ($amount ? wc_price($amount) : esc_html_e('Cart', 'woocommerce'))?>
        <?php if ($amount !== 0) : ?>
            <span class="ec-cart-count"><?php echo WC()->cart->get_cart_contents_count()?></span>
        <?php endif;?>
    </a>
    <?php
}

function electrocommerce_form_field( $key, $args, $value = null ) {
    $defaults = array(
        'type'              => 'text',
        'label'             => '',
        'description'       => '',
        'placeholder'       => '',
        'maxlength'         => false,
        'required'          => false,
        'autocomplete'      => false,
        'id'                => $key,
        'class'             => array(),
        'label_class'       => array(),
        'input_class'       => array(),
        'return'            => false,
        'options'           => array(),
        'custom_attributes' => array(),
        'validate'          => array(),
        'default'           => '',
        'autofocus'         => '',
        'priority'          => '',
    );

    $args = wp_parse_args( $args, $defaults );
    $args = apply_filters( 'woocommerce_form_field_args', $args, $key, $value );

    if ( $args['required'] ) {
        $args['class'][] = 'validate-required';
        $required        = '&nbsp;<abbr class="required" title="' . esc_attr__( 'required', 'woocommerce' ) . '">*</abbr>';
    } else {
        $required = '&nbsp;<span class="optional">(' . esc_html__( 'optional', 'woocommerce' ) . ')</span>';
    }

    if ( is_string( $args['label_class'] ) ) {
        $args['label_class'] = array( $args['label_class'] );
    }

    if ( is_null( $value ) ) {
        $value = $args['default'];
    }

    // Custom attribute handling.
    $custom_attributes         = array();
    $args['custom_attributes'] = array_filter( (array) $args['custom_attributes'], 'strlen' );

    if ( $args['maxlength'] ) {
        $args['custom_attributes']['maxlength'] = absint( $args['maxlength'] );
    }

    if ( ! empty( $args['autocomplete'] ) ) {
        $args['custom_attributes']['autocomplete'] = $args['autocomplete'];
    }

    if ( true === $args['autofocus'] ) {
        $args['custom_attributes']['autofocus'] = 'autofocus';
    }

    if ( $args['description'] ) {
        $args['custom_attributes']['aria-describedby'] = $args['id'] . '-description';
    }

    if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
        foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
            $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
        }
    }

    if ( ! empty( $args['validate'] ) ) {
        foreach ( $args['validate'] as $validate ) {
            $args['class'][] = 'validate-' . $validate;
        }
    }

    $field           = '';
    $label_id        = $args['id'];
    $sort            = $args['priority'] ? $args['priority'] : '';
    $field_container = '<div class="col-md-6 mb-4"><div class="form-floating %1$s" id="%2$s" data-priority="' . esc_attr( $sort ) . '">%3$s</div></div>';

    switch ( $args['type'] ) {
        case 'country':
            $countries = 'shipping_country' === $key ? WC()->countries->get_shipping_countries() : WC()->countries->get_allowed_countries();

            if ( 1 === count( $countries ) ) {

                $field .= '<strong>' . current( array_values( $countries ) ) . '</strong>';

                $field .= '<input type="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . current( array_keys( $countries ) ) . '" ' . implode( ' ', $custom_attributes ) . ' class="country_to_state" readonly="readonly" />';

            } else {

                $field = '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="form-control country_to_state country_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' style="width:100%;"><option value="">' . esc_html__( 'Select a country / region&hellip;', 'woocommerce' ) . '</option>';

                foreach ( $countries as $ckey => $cvalue ) {
                    $field .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( $value, $ckey, false ) . '>' . esc_html( $cvalue ) . '</option>';
                }

                $field .= '</select>';

                $field .= '<noscript><button type="submit" name="woocommerce_checkout_update_totals" value="' . esc_attr__( 'Update country / region', 'woocommerce' ) . '">' . esc_html__( 'Update country / region', 'woocommerce' ) . '</button></noscript>';

            }

            break;
        case 'state':
            /* Get country this state field is representing */
            $for_country = isset( $args['country'] ) ? $args['country'] : WC()->checkout->get_value( 'billing_state' === $key ? 'billing_country' : 'shipping_country' );
            $states      = WC()->countries->get_states( $for_country );

            if ( is_array( $states ) && empty( $states ) ) {

                $field_container = '<div class="form-floating %1$s" id="%2$s" style="display: none">%3$s</div>';

                $field .= '<input type="hidden" class="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="" ' . implode( ' ', $custom_attributes ) . ' placeholder="' . esc_attr( $args['placeholder'] ) . '" readonly="readonly" data-input-classes="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '"/>';

            } elseif ( ! is_null( $for_country ) && is_array( $states ) ) {
                $data_label = ! empty( $args['label'] ) ? 'data-label="' . esc_attr( $args['label'] ) . '"' : '';

                $field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="state_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ? $args['placeholder'] : esc_html__( 'Select an option&hellip;', 'woocommerce' ) ) . '"  data-input-classes="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . $data_label . '>
						<option value="">' . esc_html__( 'Select an option&hellip;', 'woocommerce' ) . '</option>';

                foreach ( $states as $ckey => $cvalue ) {
                    $field .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( $value, $ckey, false ) . '>' . esc_html( $cvalue ) . '</option>';
                }

                $field .= '</select>';

            } else {

                $field .= '<input type="text" class="form-control ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" value="' . esc_attr( $value ) . '"  placeholder="' . esc_attr( $args['placeholder'] || $args['label'] ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" ' . implode( ' ', $custom_attributes ) . ' data-input-classes="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '"/>';

            }

            break;
        case 'textarea':
            $field .= '<textarea name="' . esc_attr( $key ) . '" class="form-control ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" ' . ( empty( $args['custom_attributes']['rows'] ) ? ' rows="2"' : '' ) . ( empty( $args['custom_attributes']['cols'] ) ? ' cols="5"' : '' ) . implode( ' ', $custom_attributes ) . ' style="resize:none;">' . esc_textarea( $value ) . '</textarea>';

            break;
        case 'checkbox':
            $field = '<label class="checkbox ' . implode( ' ', $args['label_class'] ) . '" ' . implode( ' ', $custom_attributes ) . '>
						<input type="' . esc_attr( $args['type'] ) . '" class="input-checkbox ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="1" ' . checked( $value, 1, false ) . ' /> ' . $args['label'] . $required . '</label>';

            break;
        case 'text':
        case 'password':
        case 'datetime':
        case 'datetime-local':
        case 'date':
        case 'month':
        case 'time':
        case 'week':
        case 'number':
        case 'email':
        case 'url':
        case 'tel':
            $field .= '<input type="' . esc_attr( $args['type'] ) . '" class="form-control ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] || $args['label'] ) . '"  value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';

            break;
        case 'hidden':
            $field .= '<input type="' . esc_attr( $args['type'] ) . '" class="input-hidden ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';

            break;
        case 'select':
            $field   = '';
            $options = '';

            if ( ! empty( $args['options'] ) ) {
                foreach ( $args['options'] as $option_key => $option_text ) {
                    if ( '' === $option_key ) {
                        // If we have a blank option, select2 needs a placeholder.
                        if ( empty( $args['placeholder'] ) ) {
                            $args['placeholder'] = $option_text ? $option_text : __( 'Choose an option', 'woocommerce' );
                        }
                        $custom_attributes[] = 'data-allow_clear="true"';
                    }
                    $options .= '<option value="' . esc_attr( $option_key ) . '" ' . selected( $value, $option_key, false ) . '>' . esc_html( $option_text ) . '</option>';
                }

                $field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ) . '">
							' . $options . '
						</select>';
            }

            break;
        case 'radio':
            $label_id .= '_' . current( array_keys( $args['options'] ) );

            if ( ! empty( $args['options'] ) ) {
                foreach ( $args['options'] as $option_key => $option_text ) {
                    $field .= '<input type="radio" class="input-radio ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" value="' . esc_attr( $option_key ) . '" name="' . esc_attr( $key ) . '" ' . implode( ' ', $custom_attributes ) . ' id="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '"' . checked( $value, $option_key, false ) . ' />';
                    $field .= '<label for="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '" class="radio ' . implode( ' ', $args['label_class'] ) . '">' . esc_html( $option_text ) . '</label>';
                }
            }

            break;
    }

    if ( ! empty( $field ) ) {
        $field_html = '';

        $field_html .= $field;

        if ( $args['description'] ) {
            $field_html .= '<span class="description" id="' . esc_attr( $args['id'] ) . '-description" aria-hidden="true">' . wp_kses_post( $args['description'] ) . '</span>';
        }

        $field_html .= '';

        if ( $args['label'] && 'checkbox' !== $args['type'] ) {
            $field_html .= '<label for="' . esc_attr( $label_id ) . '" class="form-label">' . wp_kses_post( $args['label'] ) . $required . '</label>';
        }

        $container_class = esc_attr( implode( ' ', $args['class'] ) );
        $container_id    = esc_attr( $args['id'] ) . '_field';
        $field           = sprintf( $field_container, $container_class, $container_id, $field_html );
    }

    /**
     * Filter by type.
     */
    $field = apply_filters( 'woocommerce_form_field_' . $args['type'], $field, $key, $args, $value );

    /**
     * General filter on form fields.
     *
     * @since 3.4.0
     */
    $field = apply_filters( 'woocommerce_form_field', $field, $key, $args, $value );

    if ( $args['return'] ) {
        return $field;
    } else {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $field;
    }
}

function electrocoommerce_loginout_link() {
    if (is_user_logged_in()) : ?>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <?php echo __('Account', 'electrocommerce');?>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) );?>"><?php echo __('Profile', 'electrocommerce');?></a>
                <a class="dropdown-item" href="<?php echo wp_logout_url( get_permalink( wc_get_page_id( 'myaccount' ) ) )?>"><?php echo __('Log Out', 'electrocommerce');?></a>
            </div>
        </div>
    <?php else:?>

        <?php electrocommerce_modal_button('loginout', __('Log In', 'electrocommerce'));?>
    <?php endif;?>
    <?php
}


function electrocommerce_modal($args) {
    $id = $args['id'];
    $content = get_args_value('content', $args);
    $content_type = get_args_value('content_type', $args, 'string');
    $caption = get_args_value('caption', $args);
    $footer = get_args_value('footer', $args);
    $classes_dialog = get_args_value('classes_dialog', $args);
    ?>
    <div class="modal fade" id="<?php echo $id?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $id;?>Title" aria-hidden="true">
        <div class="modal-dialog <?php echo $classes_dialog;?>" role="document">
            <div class="modal-content">

                    <div class="modal-header">
                        <?php if (!empty($caption)) :?>
                        <h5 class="modal-title" id="<?php echo $id?>Title"><?php echo $caption?></h5>
                        <?php endif;?>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">
                                <i class="bi bi-x-lg"></i>
                            </span>
                        </button>
                    </div>
                <div class="modal-body">
                    <?php if ($content_type === 'string') {
                        echo $content;
                    } else if ($content_type === 'function') {
                        call_user_func($content);
                    }
                    ?>
                </div>
                <?php if (!empty($footer)) :?>
                <div class="modal-footer">
                    <?php echo $footer;?>
                </div>
                <?php endif;?>
            </div>
        </div>
    </div>
    <?php
}

function electrocommerce_modal_button($id_modal, $text, $classes = 'btn btn-primary') {
    ?>
    <a role="button" href="#<?php echo $id_modal;?>" class="<?php echo $classes;?>" data-toggle="modal" data-target="#<?php echo $id_modal;?>">
        <?php echo $text ?>
    </a>
    <?php
}

function get_args_value($key, $args, $default = '') {
    return array_key_exists($key, $args) ? $args[$key] : $default;
}