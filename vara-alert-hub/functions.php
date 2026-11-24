<?php
/**
 * Vara Alert Hub functions and definitions.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VARA_ALERT_HUB_VERSION', '1.0.0' );

if ( ! isset( $content_width ) ) {
	$content_width = 800;
}

/**
 * Theme setup.
 */
function vara_alert_hub_setup() {
	load_theme_textdomain( 'vara-alert-hub', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 60,
		'width'       => 240,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'customize-selective-refresh-widgets' );

	add_editor_style( 'assets/css/content.css' );

	register_nav_menus( array(
		'primary' => esc_html__( 'Primary menu', 'vara-alert-hub' ),
	) );
}
add_action( 'after_setup_theme', 'vara_alert_hub_setup' );

/**
 * Widget areas: one sidebar and three footer columns.
 */
function vara_alert_hub_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'vara-alert-hub' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Appears next to posts and pages.', 'vara-alert-hub' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	for ( $i = 1; $i <= 3; $i++ ) {
		register_sidebar( array(
			/* translators: %d: footer column number */
			'name'          => sprintf( esc_html__( 'Footer column %d', 'vara-alert-hub' ), $i ),
			'id'            => 'footer-' . $i,
			'description'   => esc_html__( 'Replaces the default content of this footer column.', 'vara-alert-hub' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
	}
}
add_action( 'widgets_init', 'vara_alert_hub_widgets_init' );

/**
 * Styles and scripts. Nothing is loaded from outside the theme.
 */
function vara_alert_hub_scripts() {
	wp_enqueue_style( 'vara-alert-hub-content', get_template_directory_uri() . '/assets/css/content.css', array(), VARA_ALERT_HUB_VERSION );
	wp_enqueue_style( 'vara-alert-hub-style', get_stylesheet_uri(), array( 'vara-alert-hub-content' ), VARA_ALERT_HUB_VERSION );
	wp_add_inline_style( 'vara-alert-hub-style', vara_alert_hub_custom_css() );

	wp_enqueue_script(
		'vara-alert-hub-theme',
		get_template_directory_uri() . '/assets/js/theme.js',
		array(),
		VARA_ALERT_HUB_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'vara_alert_hub_scripts' );

/**
 * Sorting for the home list, archives and search.
 * The ah_sort query value only chooses between two fixed orders.
 *
 * @param WP_Query $query The query.
 */
function vara_alert_hub_pre_get_posts( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_home() ) {
		$query->set( 'orderby', 'modified' );
		$query->set( 'order', 'DESC' );
		return;
	}

	if ( $query->is_archive() || $query->is_search() ) {
		$sort = vara_alert_hub_sort_param();

		if ( $query->is_search() ) {
			if ( 'updated' === $sort ) {
				$query->set( 'orderby', 'modified' );
				$query->set( 'order', 'DESC' );
			}
			return;
		}

		if ( 'published' === $sort ) {
			$query->set( 'orderby', 'date' );
		} else {
			$query->set( 'orderby', 'modified' );
		}
		$query->set( 'order', 'DESC' );
	}
}
add_action( 'pre_get_posts', 'vara_alert_hub_pre_get_posts' );

/**
 * Body classes: sidebar position and full-width page templates.
 *
 * @param array $classes Body classes.
 * @return array
 */
function vara_alert_hub_body_class( $classes ) {
	$classes[] = 'ah-sidebar-' . vara_alert_hub_get( 'sidebar' );
	if ( vara_alert_hub_is_full_width_template() ) {
		$classes[] = 'ah-full-width';
	}
	return $classes;
}
add_filter( 'body_class', 'vara_alert_hub_body_class' );

require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/meta-boxes.php';
require get_template_directory() . '/inc/comments.php';
require get_template_directory() . '/inc/patterns.php';
