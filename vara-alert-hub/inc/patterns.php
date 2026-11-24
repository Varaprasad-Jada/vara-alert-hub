<?php
/**
 * Block patterns. They appear in the editor under Patterns > Vara Alert Hub.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the pattern category and patterns.
 */
function vara_alert_hub_register_patterns() {
	if ( ! function_exists( 'register_block_pattern' ) ) {
		return;
	}

	register_block_pattern_category( 'vara-alert-hub', array(
		'label' => esc_html__( 'Vara Alert Hub', 'vara-alert-hub' ),
	) );

	$button = static function ( $text ) {
		return "<!-- wp:buttons -->\n<div class=\"wp-block-buttons\"><!-- wp:button -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link wp-element-button\">" . $text . "</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons -->";
	};

	// 1. Important notice.
	register_block_pattern( 'vara-alert-hub/notice', array(
		'title'      => esc_html__( 'Important notice', 'vara-alert-hub' ),
		'categories' => array( 'vara-alert-hub' ),
		'keywords'   => array( 'notice', 'alert', 'announcement' ),
		'content'    => "<!-- wp:group {\"className\":\"ah-notice\"} -->\n<div class=\"wp-block-group ah-notice\"><!-- wp:paragraph {\"className\":\"ah-notice-label\"} -->\n<p class=\"ah-notice-label\">" . esc_html__( 'Important notice', 'vara-alert-hub' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>" . esc_html__( 'Write the notice or announcement here. Keep it short and clear.', 'vara-alert-hub' ) . "</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group -->",
	) );

	// 2. Important dates table.
	register_block_pattern( 'vara-alert-hub/important-dates', array(
		'title'      => esc_html__( 'Important dates table', 'vara-alert-hub' ),
		'categories' => array( 'vara-alert-hub' ),
		'keywords'   => array( 'dates', 'schedule', 'table' ),
		'content'    => "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . esc_html__( 'Important dates', 'vara-alert-hub' ) . "</h2>\n<!-- /wp:heading -->\n\n<!-- wp:table -->\n<figure class=\"wp-block-table\"><table><thead><tr><th>" . esc_html__( 'Event', 'vara-alert-hub' ) . '</th><th>' . esc_html__( 'Date', 'vara-alert-hub' ) . '</th></tr></thead><tbody><tr><td>' . esc_html__( 'Notification released', 'vara-alert-hub' ) . '</td><td>' . esc_html__( 'DD Month YYYY', 'vara-alert-hub' ) . '</td></tr><tr><td>' . esc_html__( 'Last date to apply', 'vara-alert-hub' ) . '</td><td>' . esc_html__( 'DD Month YYYY', 'vara-alert-hub' ) . '</td></tr><tr><td>' . esc_html__( 'Result or next step', 'vara-alert-hub' ) . '</td><td>' . esc_html__( 'DD Month YYYY', 'vara-alert-hub' ) . "</td></tr></tbody></table></figure>\n<!-- /wp:table -->",
	) );

	// 3. Info card with button.
	register_block_pattern( 'vara-alert-hub/info-card', array(
		'title'      => esc_html__( 'Info card with button', 'vara-alert-hub' ),
		'categories' => array( 'vara-alert-hub' ),
		'keywords'   => array( 'card', 'button', 'link' ),
		'content'    => "<!-- wp:group {\"className\":\"ah-info-card\"} -->\n<div class=\"wp-block-group ah-info-card\"><!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">" . esc_html__( 'Card title', 'vara-alert-hub' ) . "</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>" . esc_html__( 'A short description of this update, result or notice.', 'vara-alert-hub' ) . "</p>\n<!-- /wp:paragraph -->\n\n" . $button( esc_html__( 'Open link', 'vara-alert-hub' ) ) . "</div>\n<!-- /wp:group -->",
	) );

	// 4. How to apply, in steps.
	register_block_pattern( 'vara-alert-hub/how-to-steps', array(
		'title'      => esc_html__( 'How to apply (steps)', 'vara-alert-hub' ),
		'categories' => array( 'vara-alert-hub' ),
		'keywords'   => array( 'steps', 'guide', 'how to' ),
		'content'    => "<!-- wp:group {\"className\":\"ah-steps\"} -->\n<div class=\"wp-block-group ah-steps\"><!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">" . esc_html__( 'How to apply', 'vara-alert-hub' ) . "</h3>\n<!-- /wp:heading -->\n\n<!-- wp:list {\"ordered\":true} -->\n<ol class=\"wp-block-list\"><!-- wp:list-item -->\n<li>" . esc_html__( 'Visit the official website.', 'vara-alert-hub' ) . "</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>" . esc_html__( 'Register with your details.', 'vara-alert-hub' ) . "</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>" . esc_html__( 'Fill in the form and upload your documents.', 'vara-alert-hub' ) . "</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>" . esc_html__( 'Pay the fee and download the receipt.', 'vara-alert-hub' ) . "</li>\n<!-- /wp:list-item --></ol>\n<!-- /wp:list --></div>\n<!-- /wp:group -->",
	) );

	// 5. Banner for landing pages.
	register_block_pattern( 'vara-alert-hub/banner', array(
		'title'      => esc_html__( 'Banner', 'vara-alert-hub' ),
		'categories' => array( 'vara-alert-hub' ),
		'keywords'   => array( 'hero', 'banner', 'landing' ),
		'content'    => "<!-- wp:group {\"className\":\"ah-banner\"} -->\n<div class=\"wp-block-group ah-banner\"><!-- wp:paragraph {\"className\":\"ah-banner-label\"} -->\n<p class=\"ah-banner-label\">" . esc_html__( 'Site name', 'vara-alert-hub' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . esc_html__( 'All your updates in one place', 'vara-alert-hub' ) . "</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>" . esc_html__( 'One short line that tells visitors what they will find here.', 'vara-alert-hub' ) . "</p>\n<!-- /wp:paragraph -->\n\n" . $button( esc_html__( 'See latest updates', 'vara-alert-hub' ) ) . "</div>\n<!-- /wp:group -->",
	) );

	// 6. Three link cards.
	$card = static function ( $title, $text ) {
		return "<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:group {\"className\":\"ah-linkcard\"} -->\n<div class=\"wp-block-group ah-linkcard\"><!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">" . $title . "</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>" . $text . "</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group --></div>\n<!-- /wp:column -->";
	};
	register_block_pattern( 'vara-alert-hub/link-cards', array(
		'title'      => esc_html__( 'Three link cards', 'vara-alert-hub' ),
		'categories' => array( 'vara-alert-hub' ),
		'keywords'   => array( 'cards', 'columns', 'links' ),
		'content'    => "<!-- wp:columns -->\n<div class=\"wp-block-columns\">" . $card( esc_html__( 'First topic', 'vara-alert-hub' ), esc_html__( 'A short line about this topic.', 'vara-alert-hub' ) ) . "\n\n" . $card( esc_html__( 'Second topic', 'vara-alert-hub' ), esc_html__( 'A short line about this topic.', 'vara-alert-hub' ) ) . "\n\n" . $card( esc_html__( 'Third topic', 'vara-alert-hub' ), esc_html__( 'A short line about this topic.', 'vara-alert-hub' ) ) . "</div>\n<!-- /wp:columns -->",
	) );
}
add_action( 'init', 'vara_alert_hub_register_patterns' );
