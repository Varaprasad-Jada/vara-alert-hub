<?php
/**
 * Comments.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php esc_html_e( 'Comments', 'vara-alert-hub' ); ?>
			<span class="comments-count"><?php echo esc_html( number_format_i18n( get_comments_number() ) ); ?></span>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'style'             => 'ol',
				'callback'          => 'vara_alert_hub_comment',
				'max_depth'         => 3,
				'reverse_top_level' => true,
			) );
			?>
		</ol>

		<?php
		the_comments_pagination( array(
			'prev_text' => esc_html__( 'Older comments', 'vara-alert-hub' ),
			'next_text' => esc_html__( 'Newer comments', 'vara-alert-hub' ),
		) );
		?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'vara-alert-hub' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>

</div>
