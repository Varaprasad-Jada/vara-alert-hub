<?php
/**
 * Fallback template and the posts page.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$vara_alert_hub_title = ( is_home() && ! is_front_page() ) ? single_post_title( '', false ) : __( 'Latest updates', 'vara-alert-hub' );
?>
<header class="ah-head">
	<h1><?php echo esc_html( $vara_alert_hub_title ); ?></h1>
</header>
<?php
get_template_part( 'template-parts/loop' );

get_footer();
