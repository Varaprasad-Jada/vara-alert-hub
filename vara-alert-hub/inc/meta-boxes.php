<?php
/**
 * Post editor fields: update note, last date and the page "last updated" switch.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add the meta boxes.
 */
function vara_alert_hub_add_meta_boxes() {
	add_meta_box( 'vara_alert_hub_details', esc_html__( 'Update details', 'vara-alert-hub' ), 'vara_alert_hub_details_box', 'post', 'side', 'default' );
	add_meta_box( 'vara_alert_hub_page_options', esc_html__( 'Page display', 'vara-alert-hub' ), 'vara_alert_hub_page_box', 'page', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'vara_alert_hub_add_meta_boxes' );

/**
 * Update note and last date fields.
 *
 * @param WP_Post $post Post.
 */
function vara_alert_hub_details_box( $post ) {
	wp_nonce_field( 'vara_alert_hub_save_meta', 'vara_alert_hub_meta_nonce' );
	$note = get_post_meta( $post->ID, '_vara_alert_hub_update_note', true );
	$date = get_post_meta( $post->ID, '_vara_alert_hub_last_date', true );
	?>
	<p>
		<label for="vara_alert_hub_update_note"><strong><?php esc_html_e( 'Update note', 'vara-alert-hub' ); ?></strong></label>
		<input type="text" class="widefat" id="vara_alert_hub_update_note" name="vara_alert_hub_update_note" maxlength="200" value="<?php echo esc_attr( $note ); ?>" />
		<span class="description"><?php esc_html_e( 'A short line shown at the top of the post, for example "Dates extended". Leave empty to hide it.', 'vara-alert-hub' ); ?></span>
	</p>
	<p>
		<label for="vara_alert_hub_last_date"><strong><?php esc_html_e( 'Last date', 'vara-alert-hub' ); ?></strong></label>
		<input type="date" class="widefat" id="vara_alert_hub_last_date" name="vara_alert_hub_last_date" value="<?php echo esc_attr( $date ); ?>" />
		<span class="description"><?php esc_html_e( 'Shows a "Last date" label on cards. Leave empty if the post has no deadline.', 'vara-alert-hub' ); ?></span>
	</p>
	<?php
}

/**
 * Page option.
 *
 * @param WP_Post $post Post.
 */
function vara_alert_hub_page_box( $post ) {
	wp_nonce_field( 'vara_alert_hub_save_meta', 'vara_alert_hub_meta_nonce' );
	$hide = get_post_meta( $post->ID, '_vara_alert_hub_hide_updated', true );
	?>
	<p>
		<label for="vara_alert_hub_hide_updated">
			<input type="checkbox" id="vara_alert_hub_hide_updated" name="vara_alert_hub_hide_updated" value="1" <?php checked( $hide, '1' ); ?> />
			<?php esc_html_e( 'Hide the "Last updated" line', 'vara-alert-hub' ); ?>
		</label>
	</p>
	<?php
}

/**
 * Save the fields.
 *
 * @param int $post_id Post ID.
 */
function vara_alert_hub_save_meta( $post_id ) {
	if ( ! isset( $_POST['vara_alert_hub_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vara_alert_hub_meta_nonce'] ) ), 'vara_alert_hub_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( 'post' === get_post_type( $post_id ) ) {
		$note = isset( $_POST['vara_alert_hub_update_note'] ) ? sanitize_text_field( wp_unslash( $_POST['vara_alert_hub_update_note'] ) ) : '';
		if ( '' === $note ) {
			delete_post_meta( $post_id, '_vara_alert_hub_update_note' );
		} else {
			update_post_meta( $post_id, '_vara_alert_hub_update_note', mb_substr( $note, 0, 200 ) );
		}

		$date  = isset( $_POST['vara_alert_hub_last_date'] ) ? sanitize_text_field( wp_unslash( $_POST['vara_alert_hub_last_date'] ) ) : '';
		$valid = preg_match( '/^(\d{4})-(\d{2})-(\d{2})$/', $date, $parts ) && checkdate( (int) $parts[2], (int) $parts[3], (int) $parts[1] );
		if ( $valid ) {
			update_post_meta( $post_id, '_vara_alert_hub_last_date', $date );
		} else {
			delete_post_meta( $post_id, '_vara_alert_hub_last_date' );
		}
	}

	if ( 'page' === get_post_type( $post_id ) ) {
		if ( ! empty( $_POST['vara_alert_hub_hide_updated'] ) ) {
			update_post_meta( $post_id, '_vara_alert_hub_hide_updated', '1' );
		} else {
			delete_post_meta( $post_id, '_vara_alert_hub_hide_updated' );
		}
	}
}
add_action( 'save_post', 'vara_alert_hub_save_meta' );
