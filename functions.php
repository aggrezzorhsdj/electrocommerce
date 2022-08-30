<?php
/**
 * electrocommerce functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package electrocommerce
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function electrocommerce_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on electrocommerce, use a find and replace
		* to change 'electrocommerce' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'electrocommerce', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
    register_nav_menus(array(
        'header_menu' => __('Header Menu', 'electrocommerce'),
        'footer_menu_1' => __('Footer Menu 1', 'electrocommerce'),
        'footer_menu_2' => __('Footer Menu 2', 'electrocommerce'),
        'catalog_menu' => __('Catalog Menu', 'electrocommerce'),
    ));

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'electrocommerce_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'electrocommerce_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function electrocommerce_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'electrocommerce_content_width', 640 );
}
add_action( 'after_setup_theme', 'electrocommerce_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function electrocommerce_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'electrocommerce' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'electrocommerce' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'electrocommerce_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function electrocommerce_scripts() {
	wp_enqueue_style( 'electrocommerce-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_enqueue_style( 'electrocommerce-base', get_template_directory_uri() . '/dist/css/main.css', array(), time() );
	wp_style_add_data( 'electrocommerce-style', 'rtl', 'replace' );

	wp_enqueue_script( 'electrocommerce-main', get_template_directory_uri() . '/dist/js/main.js', array(), time(), true );
	wp_enqueue_script( 'electrocommerce-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	wp_localize_script('electrocommerce-main', "electrocommerce", array(
	        "ajaxurl" => admin_url("admin-ajax.php")
    ));

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'electrocommerce_scripts' );

function electrocommerce_add_woocommerce_support(){
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'electrocommerce_add_woocommerce_support' );

/**
 * Show cart contents / total Ajax
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );

function woocommerce_header_add_to_cart_fragment( $fragments ) {
	ob_start();
    electrocommerce_cart_html();
	$fragments['#cart-customlocation'] = ob_get_clean();
	return $fragments;
}

remove_action( 'woocommerce_before_main_content',   'woocommerce_output_content_wrapper',     10 );
remove_action( 'woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end',   10 );
remove_action( 'woocommerce_before_shop_loop',  'woocommerce_result_count',   20 );
remove_action( 'woocommerce_before_shop_loop',  'woocommerce_catalog_ordering',   30 );
remove_action( 'woocommerce_single_product_summary',  'woocommerce_template_single_price',   10 );
remove_action( 'woocommerce_after_single_product_summary',  'woocommerce_output_related_products',   20 );

add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );
function new_loop_shop_per_page( $cols ) {
	$cols = 8;

	return $cols;
}

add_filter('loop_shop_columns', 'loop_columns', 999);
if (!function_exists('loop_columns')) {
    function loop_columns() {

        return array_key_exists('view', $_GET) && $_GET['view'] == 'list' ? 12 : 3;
    }
}


add_filter( 'woocommerce_product_get_rating_html', 'electrocommerce_woocommerce_product_get_rating_html_filter', 10, 3 );

/**
 * Function for `woocommerce_product_get_rating_html` filter-hook.
 *
 * @param  $html
 * @param  $rating
 * @param  $count
 *
 * @return
 */
function electrocommerce_woocommerce_product_get_rating_html_filter( $html, $rating, $count ){
    $label = sprintf( __( 'Rated %s out of 5', 'woocommerce' ), $rating );
    $html  = '<div class="star-rating" role="img" aria-label="' . esc_attr( $label ) . '">' . wc_get_star_rating_html( $rating, $count ) . '</div><div class="star-count">'.$count.'</div>';
    return $html;
}

/*
 * Adv. Woo Search customization
 */
/*add_action( 'wp_enqueue_scripts', 'aws_deregister_styles', 11 );
function aws_deregister_styles() {
    wp_dequeue_style( 'aws-style' );

}*/

add_filter( 'aws_searchbox_markup', 'my_aws_searchbox_markup', 10, 2 );
function my_aws_searchbox_markup( $markup, $params ) {
    $labelBegin = strpos($markup, '<label');
    $labelEnd = strpos($markup, '</label>');
    $label = mb_substr($markup, $labelBegin, $labelEnd - $labelBegin + strlen('</label>'));

    $inputBegin = strpos($markup, '<input type="search"');
    $inputEnd = strpos($markup, '/>');
    $input = mb_substr($markup, $inputBegin, $inputEnd - $inputBegin + strlen('/>'));

    $markup = str_replace($label . $input, $input . $label, $markup);

    $aws_wrapper = 'aws-wrapper';
    $bs_wrapper = 'form-floating';
    $markup = str_replace($aws_wrapper, $bs_wrapper, $markup);

    $aws_wrapper = 'aws-wrapper';
    $bs_wrapper = 'form-floating';
    $markup = str_replace($aws_wrapper, $bs_wrapper, $markup);

    $aws_search = 'aws-search-field';
    $bs_search = 'form-control';
    $markup = str_replace($aws_search, $aws_search . " " . $bs_search, $markup);

    $aws_label = 'aws-search-label';
    $bs_label = 'form-label';
    $markup = str_replace($aws_label, $bs_label, $markup);

    return $markup;
}

add_action('wp_ajax_ajax_get_products', 'ajax_get_products');
add_action('wp_ajax_nopriv_ajax_get_products', 'ajax_get_products');
function ajax_get_products() {
    $args = array(
        'post_type' =>  'product',
        'product_cat' => $_POST["product_cat"] || 0,
        'posts_per_page' => '2',
        'orderby'  => [ 'title'=>'ASC' ]
    );

	$meta_query = array();
    var_dump($args);

    if (!empty($_POST["params"]["filters"])) {
        foreach ($_POST["params"]["filters"] as $key => $value) {
            $type = 'min';
	        if (strpos($key, $type) === 0) {
	            $filter_key = explode($type, $key)[1];
                array_push($meta_query, array(
                    "key" => $filter_key,
                    "value" => $value,
                    "compare" => ">=",
                    "type" => "NUMERIC"
                ));
            }

            $type = 'max';
            if (strpos($key, $type) === 0) {
                $filter_key = explode($type, $key)[1];
                array_push($meta_query, array(
                    "key" => $filter_key,
                    "value" => $value,
                    "compare" => "<=",
                    "type" => "NUMERIC"
                ));
            }

            $type = 'reference';
            if (strpos($key, $type) === 0) {
                $filter_key = explode($type, $key)[1];
                if ($filter_key === "_category") {
                    $args['product_cat'] = implode(",", $value);
                } else {
                    array_push($meta_query, array(
                        "key" => $filter_key,
                        "value" => $value,
                        "compare" => "IN"
                    ));
                }
            }
        }
    }

    $args["meta_query"] = $meta_query;
    $args = $args + $_POST["params"]["query"];

	$count_results = '0';
	$ajax_query = new WP_Query( $args );
	// Start "saving" results' HTML
	$results_html = '';

    // Results found
	if ( $ajax_query->have_posts() ) {
		$count_results = $ajax_query->found_posts;

		ob_start();

		while( $ajax_query->have_posts() ) {
			$ajax_query->the_post();
			echo wc_get_template_part( 'content', 'product' );
		}

		// "Save" results' HTML as variable
		$results_html = ob_get_clean();
	}

    // Build ajax response

    // 1. value is HTML of new posts and 2. is total count of posts
	global $wpdb;
	$response = array(
        "html" => $results_html,
        "total" => $count_results,
        "total_pages" => $ajax_query->max_num_pages
    );
	echo json_encode( $response );

    // Always use die() in the end of ajax functions
	wp_die();
}


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custon Layered Nav Widget
 */
require get_template_directory() . '/woocommerce/widgets/class-wc-widget-custom-layered-nav.php';
function electrocommerce_load_widget(){
    register_widget('WC_Widget_Custom_Layered_Nav');
}
add_action('widgets_init', 'electrocommerce_load_widget');

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

