<?php
/**
 * The header.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'vara-alert-hub' ); ?></a>
<div id="page" class="site">

	<header id="masthead" class="site-header">
		<div class="header-inner">
			<div class="site-branding">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
					<?php
					$vara_alert_hub_tagline = get_bloginfo( 'description', 'display' );
					if ( $vara_alert_hub_tagline || is_customize_preview() ) :
						?>
						<p class="site-description"><?php echo esc_html( $vara_alert_hub_tagline ); ?></p>
					<?php endif; ?>
				<?php endif; ?>
			</div>

			<div class="header-tools">
				<button type="button" class="ah-icon-btn search-toggle" aria-expanded="false" aria-controls="search-panel">
					<span class="screen-reader-text"><?php esc_html_e( 'Search', 'vara-alert-hub' ); ?></span>
					<?php echo vara_alert_hub_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>
				<button type="button" class="ah-icon-btn menu-toggle" aria-expanded="false" aria-controls="site-navigation">
					<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'vara-alert-hub' ); ?></span>
					<span class="ah-icon-open"><?php echo vara_alert_hub_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="ah-icon-close"><?php echo vara_alert_hub_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</button>
			</div>
		</div>

		<div id="search-panel" class="search-panel" hidden>
			<div class="search-panel-inner"><?php get_search_form(); ?></div>
		</div>
	</header>

	<nav id="site-navigation" class="nav-bar" aria-label="<?php esc_attr_e( 'Primary menu', 'vara-alert-hub' ); ?>">
		<div class="nav-inner">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'menu_id'        => 'primary-menu',
				'container'      => false,
				'fallback_cb'    => 'vara_alert_hub_menu_fallback',
			) );
			?>
		</div>
	</nav>

	<?php
	$vara_alert_hub_ticker = trim( (string) vara_alert_hub_get( 'ticker_text' ) );
	if ( '' !== $vara_alert_hub_ticker ) :
		?>
		<div class="ticker-bar">
			<div class="ticker-inner">
				<span class="ticker-label"><?php echo esc_html( vara_alert_hub_get( 'ticker_label' ) ); ?></span>
				<div class="ticker-wrap"><p class="ticker-track"><?php echo esc_html( $vara_alert_hub_ticker ); ?></p></div>
			</div>
		</div>
	<?php endif; ?>

	<div class="content-wrapper<?php echo vara_alert_hub_has_sidebar() ? ' has-sidebar' : ''; ?>">
		<main id="primary" class="main-content">
