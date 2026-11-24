<?php
/**
 * Comment markup and form changes.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Callback for wp_list_comments().
 *
 * @param WP_Comment $comment Comment.
 * @param array      $args    Arguments.
 * @param int        $depth   Depth.
 */
function vara_alert_hub_comment( $comment, $args, $depth ) {
	$author_id  = (int) get_post_field( 'post_author', $comment->comment_post_ID );
	$is_author  = $comment->user_id && (int) $comment->user_id === $author_id;
	$is_pending = '0' === $comment->comment_approved;
	$avatar_url = get_option( 'show_avatars' ) ? get_avatar_url( $comment, array(
		'size'    => 84,
		'default' => '404',
	) ) : '';
	?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( ! empty( $args['has_children'] ) ? 'parent' : '', $comment ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<?php if ( get_option( 'show_avatars' ) ) : ?>
				<span class="ah-avatar" aria-hidden="true">
					<?php echo esc_html( vara_alert_hub_initial( get_comment_author( $comment ) ) ); ?>
					<?php if ( $avatar_url ) : ?>
						<img src="<?php echo esc_url( $avatar_url ); ?>" alt="" width="42" height="42" loading="lazy" data-ah-avatar />
					<?php endif; ?>
				</span>
			<?php endif; ?>

			<div class="comment-main">
				<div class="comment-bubble<?php echo $is_pending ? ' comment-pending' : ''; ?>">
					<div class="comment-head">
						<span class="comment-author-name"><?php echo wp_kses_post( get_comment_author_link( $comment ) ); ?></span>
						<?php if ( $is_author ) : ?>
							<span class="ah-badge"><?php esc_html_e( 'Author', 'vara-alert-hub' ); ?></span>
						<?php endif; ?>
						<a class="comment-time" href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
							<time datetime="<?php comment_time( 'c' ); ?>"><?php echo esc_html( get_comment_date( get_option( 'date_format' ) . ', ' . get_option( 'time_format' ), $comment ) ); ?></time>
						</a>
					</div>
					<?php if ( $is_pending ) : ?>
						<span class="comment-pending-note"><?php esc_html_e( 'Your comment is awaiting moderation.', 'vara-alert-hub' ); ?></span>
					<?php endif; ?>
					<div class="comment-text"><?php comment_text(); ?></div>
				</div>
				<?php
				comment_reply_link( array_merge( $args, array(
					'add_below' => 'div-comment',
					'depth'     => $depth,
					'max_depth' => $args['max_depth'],
					'before'    => '<div class="comment-actions">',
					'after'     => '</div>',
				) ) );
				?>
			</div>
		</article>
	<?php
	// WordPress closes the list item.
}

/**
 * Comment form: comment box first, then name and email.
 *
 * @param array $fields Fields.
 * @return array
 */
function vara_alert_hub_comment_form_fields( $fields ) {
	unset( $fields['url'] );
	if ( isset( $fields['comment'] ) ) {
		$comment = $fields['comment'];
		unset( $fields['comment'] );
		$fields = array( 'comment' => $comment ) + $fields;
	}
	return $fields;
}
add_filter( 'comment_form_fields', 'vara_alert_hub_comment_form_fields' );

/**
 * Comment form wording.
 *
 * @param array $defaults Defaults.
 * @return array
 */
function vara_alert_hub_comment_form_defaults( $defaults ) {
	$defaults['title_reply']  = esc_html__( 'Leave a comment', 'vara-alert-hub' );
	$defaults['label_submit'] = esc_html__( 'Post comment', 'vara-alert-hub' );
	$defaults['class_submit'] = 'submit';
	return $defaults;
}
add_filter( 'comment_form_defaults', 'vara_alert_hub_comment_form_defaults' );
