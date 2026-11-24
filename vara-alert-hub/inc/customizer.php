<?php
/**
 * Customizer settings.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Checkbox sanitizer.
 *
 * @param mixed $value Value.
 * @return bool
 */
function vara_alert_hub_sanitize_checkbox( $value ) {
	return (bool) $value;
}

/**
 * Select and radio sanitizer: only allow the listed choices.
 *
 * @param mixed                $value   Value.
 * @param WP_Customize_Setting $setting Setting.
 * @return mixed
 */
function vara_alert_hub_sanitize_choice( $value, $setting ) {
	$control = $setting->manager->get_control( $setting->id );
	if ( $control && array_key_exists( $value, $control->choices ) ) {
		return $value;
	}
	return $setting->default;
}

/**
 * Number of days a post counts as new (1 to 30).
 *
 * @param mixed $value Value.
 * @return int
 */
function vara_alert_hub_sanitize_days( $value ) {
	return min( 30, max( 1, absint( $value ) ) );
}

/**
 * Register Customizer options.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function vara_alert_hub_customize_register( $wp_customize ) {
	$defaults = vara_alert_hub_defaults();

	$categories = array( 0 => esc_html__( 'None (hide featured cards)', 'vara-alert-hub' ) );
	foreach ( get_categories( array( 'hide_empty' => false ) ) as $category ) {
		$categories[ $category->term_id ] = $category->name;
	}

	$sections = array(
		'vara_alert_hub_home'   => array( esc_html__( 'Homepage', 'vara-alert-hub' ), 30 ),
		'vara_alert_hub_layout' => array( esc_html__( 'Layout and display', 'vara-alert-hub' ), 31 ),
		'vara_alert_hub_ticker' => array( esc_html__( 'Update ticker', 'vara-alert-hub' ), 32 ),
		'vara_alert_hub_footer' => array( esc_html__( 'Footer', 'vara-alert-hub' ), 33 ),
		'vara_alert_hub_404'    => array( esc_html__( '404 page', 'vara-alert-hub' ), 34 ),
	);
	foreach ( $sections as $id => $section ) {
		$wp_customize->add_section( $id, array(
			'title'    => $section[0],
			'priority' => $section[1],
		) );
	}

	$controls = array(
		'primary'             => array( 'color', 'colors', esc_html__( 'Primary colour', 'vara-alert-hub' ), 'sanitize_hex_color' ),
		'show_tabs'           => array( 'checkbox', 'vara_alert_hub_home', esc_html__( 'Show category tabs', 'vara-alert-hub' ), 'vara_alert_hub_sanitize_checkbox' ),
		'show_featured'       => array( 'checkbox', 'vara_alert_hub_home', esc_html__( 'Show featured cards', 'vara-alert-hub' ), 'vara_alert_hub_sanitize_checkbox' ),
		'featured_cat'        => array(
			'select',
			'vara_alert_hub_home',
			esc_html__( 'Category for featured cards', 'vara-alert-hub' ),
			'absint',
			array(
				'choices'     => $categories,
				'description' => esc_html__( 'Recently updated posts from this category appear as cards at the top of the homepage.', 'vara-alert-hub' ),
			),
		),
		'featured_count'      => array(
			'select',
			'vara_alert_hub_home',
			esc_html__( 'Number of featured cards', 'vara-alert-hub' ),
			'vara_alert_hub_sanitize_choice',
			array(
				'choices' => array(
					3 => '3',
					6 => '6',
					9 => '9',
				),
			),
		),
		'featured_title'      => array( 'text', 'vara_alert_hub_home', esc_html__( 'Featured heading', 'vara-alert-hub' ), 'sanitize_text_field' ),
		'show_latest'         => array( 'checkbox', 'vara_alert_hub_home', esc_html__( 'Show the latest updates list', 'vara-alert-hub' ), 'vara_alert_hub_sanitize_checkbox' ),
		'latest_title'        => array( 'text', 'vara_alert_hub_home', esc_html__( 'Latest updates heading', 'vara-alert-hub' ), 'sanitize_text_field' ),

		'sidebar'             => array(
			'select',
			'vara_alert_hub_layout',
			esc_html__( 'Sidebar on posts and pages', 'vara-alert-hub' ),
			'vara_alert_hub_sanitize_choice',
			array(
				'choices' => array(
					'right' => esc_html__( 'Right', 'vara-alert-hub' ),
					'left'  => esc_html__( 'Left', 'vara-alert-hub' ),
					'off'   => esc_html__( 'Off', 'vara-alert-hub' ),
				),
			),
		),
		'images'              => array(
			'select',
			'vara_alert_hub_layout',
			esc_html__( 'Featured images in cards and lists', 'vara-alert-hub' ),
			'vara_alert_hub_sanitize_choice',
			array(
				'choices'     => array(
					'off'    => esc_html__( 'Off (text only)', 'vara-alert-hub' ),
					'when'   => esc_html__( 'Only when a post has an image', 'vara-alert-hub' ),
					'always' => esc_html__( 'Always, with a placeholder', 'vara-alert-hub' ),
				),
				'description' => esc_html__( 'Posts always show their image on the post page when they have one.', 'vara-alert-hub' ),
			),
		),
		'show_author'         => array( 'checkbox', 'vara_alert_hub_layout', esc_html__( 'Show the author name on posts', 'vara-alert-hub' ), 'vara_alert_hub_sanitize_checkbox' ),
		'new_days'            => array(
			'number',
			'vara_alert_hub_layout',
			esc_html__( 'Days a post counts as new', 'vara-alert-hub' ),
			'vara_alert_hub_sanitize_days',
			array(
				'input_attrs' => array(
					'min' => 1,
					'max' => 30,
				),
			),
		),
		'new_label'           => array( 'text', 'vara_alert_hub_layout', esc_html__( 'Text of the new badge', 'vara-alert-hub' ), 'sanitize_text_field' ),

		'ticker_label'        => array( 'text', 'vara_alert_hub_ticker', esc_html__( 'Ticker label', 'vara-alert-hub' ), 'sanitize_text_field' ),
		'ticker_text'         => array(
			'textarea',
			'vara_alert_hub_ticker',
			esc_html__( 'Ticker text', 'vara-alert-hub' ),
			'sanitize_text_field',
			array( 'description' => esc_html__( 'Shown under the menu. Leave blank to hide the ticker.', 'vara-alert-hub' ) ),
		),

		'footer_about_title'  => array( 'text', 'vara_alert_hub_footer', esc_html__( 'First column heading', 'vara-alert-hub' ), 'sanitize_text_field' ),
		'footer_about'        => array(
			'textarea',
			'vara_alert_hub_footer',
			esc_html__( 'First column text', 'vara-alert-hub' ),
			'sanitize_textarea_field',
			array( 'description' => esc_html__( 'If blank, the site tagline is used. Adding a widget to a footer column replaces its default content.', 'vara-alert-hub' ) ),
		),
		'footer_links_title'  => array( 'text', 'vara_alert_hub_footer', esc_html__( 'Second column heading', 'vara-alert-hub' ), 'sanitize_text_field' ),
		'footer_latest_title' => array( 'text', 'vara_alert_hub_footer', esc_html__( 'Third column heading', 'vara-alert-hub' ), 'sanitize_text_field' ),
		'copyright'           => array(
			'text',
			'vara_alert_hub_footer',
			esc_html__( 'Copyright text', 'vara-alert-hub' ),
			'wp_kses_post',
			array( 'description' => esc_html__( 'If blank, the year and site name are used.', 'vara-alert-hub' ) ),
		),
		'show_credit'         => array( 'checkbox', 'vara_alert_hub_footer', esc_html__( 'Show the theme credit link', 'vara-alert-hub' ), 'vara_alert_hub_sanitize_checkbox' ),

		'e404_board'          => array( 'checkbox', 'vara_alert_hub_404', esc_html__( 'Show the live update board', 'vara-alert-hub' ), 'vara_alert_hub_sanitize_checkbox' ),
		'e404_title'          => array( 'text', 'vara_alert_hub_404', esc_html__( 'Heading', 'vara-alert-hub' ), 'sanitize_text_field' ),
		'e404_text'           => array( 'textarea', 'vara_alert_hub_404', esc_html__( 'Message', 'vara-alert-hub' ), 'sanitize_textarea_field' ),
	);

	foreach ( $controls as $key => $config ) {
		$id    = 'vara_alert_hub_' . $key;
		$extra = isset( $config[4] ) ? $config[4] : array();

		$wp_customize->add_setting( $id, array(
			'default'           => $defaults[ $key ],
			'sanitize_callback' => $config[3],
		) );

		if ( 'color' === $config[0] ) {
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, array(
				'label'   => $config[2],
				'section' => $config[1],
			) ) );
			continue;
		}

		$wp_customize->add_control( $id, array_merge(
			array(
				'label'   => $config[2],
				'section' => $config[1],
				'type'    => $config[0],
			),
			$extra
		) );
	}
}
add_action( 'customize_register', 'vara_alert_hub_customize_register' );
