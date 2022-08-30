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