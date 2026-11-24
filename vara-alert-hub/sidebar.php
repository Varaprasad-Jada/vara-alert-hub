<?php
/**
 * The sidebar.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! vara_alert_hub_has_sidebar() ) {
	return;
}
?>
<aside id="secondary" class="sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'vara-alert-hub' ); ?>">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
