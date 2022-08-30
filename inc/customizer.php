<?php
/**
 * electrocommerce Theme Customizer
 *
 * @package electrocommerce
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function electrocommerce_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'electrocommerce_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'electrocommerce_customize_partial_blogdescription',
			)
		);
	}

    $transport = 'postMessage';

    if ($section = 'ec_header_section') {
        $wp_customize->add_section($section, array(
            'title' => __('Header Settings', 'electrocommerce'),
            'priority' => 100,
            'capability' => 'edit_theme_options',
            'description' => __('Change header options here', 'electrocommerce'),
        ));

        $setting = 'ec_header_logo_setting';

        $wp_customize->add_setting($setting, array(
            'default' => ''
        ));

        $wp_customize->add_control( $setting, array(
            'section' => $section,
            'label' => __('Header logo icon id', 'electrocommerce'),
            'type' => 'text'
        ));
    }

    if ($section = 'ec_general_section') {
        $wp_customize->add_section($section, array(
            'title' => __('General Settings', 'electrocommerce'),
            'priority' => 100,
            'capability' => 'edit_theme_options',
            'description' => __('Change general settings here', 'electrocommerce'),
        ));

        $setting = 'ec_contact_phone';

        $wp_customize->add_setting($setting, array(
            'default' => ''
        ));

        $wp_customize->add_control( $setting, array(
            'section' => $section,
            'label' => __('Contact phone number', 'electrocommerce'),
            'type' => 'text'
        ));

        $setting = 'ec_contact_address';

        $wp_customize->add_setting($setting, array(
            'default' => ''
        ));

        $wp_customize->add_control( $setting, array(
            'section' => $section,
            'label' => __('Contact address', 'electrocommerce'),
            'type' => 'text'
        ));

        $setting = 'ec_contact_social_instagram';

        $wp_customize->add_setting($setting, array(
            'default' => ''
        ));

        $wp_customize->add_control( $setting, array(
            'section' => $section,
            'label' => __('Instagram link', 'electrocommerce'),
            'type' => 'text'
        ));

        $setting = 'ec_contact_social_whatsapp';

        $wp_customize->add_setting($setting, array(
            'default' => ''
        ));

        $wp_customize->add_control( $setting, array(
            'section' => $section,
            'label' => __('Whatsapp link', 'electrocommerce'),
            'type' => 'text'
        ));

        $setting = 'ec_contact_social_vk';

        $wp_customize->add_setting($setting, array(
            'default' => ''
        ));

        $wp_customize->add_control( $setting, array(
            'section' => $section,
            'label' => __('VK link', 'electrocommerce'),
            'type' => 'text'
        ));
    }

    if ($panel = 'ec_front_page_carousel') {
        $wp_customize->add_panel($panel, array(
            'title' => __('Front Page Carousel', 'electrocommerce')
        ));

        for($i = 1; $i < 3; $i++) {
            if ($section = 'ec_front_page_'.$i) {
                $wp_customize->add_section($section, array(
                    'title' => __($i.' Slide', 'electrocommerce'),
                    'priority' => 100,
                    'capability' => 'edit_theme_options',
                    'panel' => $panel
                ));

                $setting1 = 'ec_front_page_'.$i.'_image';
                $setting2 = 'ec_front_page_'.$i.'_link';

                $wp_customize->add_setting($setting1, array(
                    'default' => ''
                ));

                $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $setting1, [
                    'label'    => __('Slide Image', 'electrocommerce'),
                    'settings' => $setting1,
                    'section'  => $section
                ] ));

                $wp_customize->add_setting($setting2, array(
                    'default' => ''
                ));

                $wp_customize->add_control( $setting2, array(
                    'section' => $section,
                    'label' => __('Slide link', 'electrocommerce'),
                    'description' => __('Enter the link without site name, ex. /catalog/phones', 'electrocommerce'),
                    'type' => 'text'
                ));
            }
        }
    }

    if ($section = 'ec_footer_section') {
        $wp_customize->add_section($section, array(
            'title' => __('Footer Section', 'electrocommerce'),
            'priority' => 100,
            'capability' => 'edit_theme_options',
            'description' => __('Change footer section here', 'electrocommerce'),
        ));

        $setting = 'ec_footer_contacts_enabled';

        $wp_customize->add_setting($setting, array(
            'default' => ''
        ));

        $wp_customize->add_control( $setting, array(
            'section' => $section,
            'label' => __('Enabled Footer Contacts', 'electrocommerce'),
            'type' => 'checkbox'
        ));

        $setting = 'ec_footer_contacts_title';

        $wp_customize->add_setting($setting, array(
            'default' => ''
        ));

        $wp_customize->add_control( $setting, array(
            'section' => $section,
            'label' => __('Footer Contacts Title', 'electrocommerce'),
            'type' => 'text'
        ));

        $setting = 'ec_footer_social_enabled';

        $wp_customize->add_setting($setting, array(
            'default' => ''
        ));

        $wp_customize->add_control( $setting, array(
            'section' => $section,
            'label' => __('Enabled Footer Socials', 'electrocommerce'),
            'type' => 'checkbox'
        ));

        $setting = 'ec_footer_logo';

        $wp_customize->add_setting($setting, array(
            'default' => ''
        ));

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $setting, [
            'label'    => __('Footer Logo', 'electrocommerce'),
            'settings' => $setting,
            'section'  => $section
        ] ));

    }
}
add_action( 'customize_register', 'electrocommerce_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function electrocommerce_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function electrocommerce_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function electrocommerce_customize_preview_js() {
	wp_enqueue_script( 'electrocommerce-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'electrocommerce_customize_preview_js' );
