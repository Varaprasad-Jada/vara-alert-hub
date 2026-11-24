<?php
/**
 * A row in an update list.
 *
 * Arguments: compact (one line on wide screens), excerpt (show a short excerpt).
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vara_alert_hub_compact = ! empty( $args['compact'] );
$vara_alert_hub_excerpt = ! empty( $args['excerpt'] );
$vara_alert_hub_id      = get_the_ID();
$vara_alert_hub_cat     = ( 'post' === get_post_type() ) ? vara_alert_hub_primary_category( $vara_alert_hub_id ) : null;
$vara_alert_hub_mode    = vara_alert_hub_get( 'images' );

if ( $vara_alert_hub_cat ) {
	$vara_alert_hub_badge = $vara_alert_hub_cat->name;
} else {
	$vara_alert_hub_type  = get_post_type_object( get_post_type() );
	$vara_alert_hub_badge = $vara_alert_hub_type ? $vara_alert_hub_type->labels->singular_name : '';
}

$vara_alert_hub_has_thumb = ( 'off' !== $vara_alert_hub_mode && has_post_thumbnail() );
$vara_alert_hub_has_ph    = ( 'always' === $vara_alert_hub_mode && ! $vara_alert_hub_has_thumb );
$vara_alert_hub_date_fmt  = $vara_alert_hub_compact ? 'j M' : 'j M Y';

/* translators: %s: date */
$vara_alert_hub_updated = sprintf( __( 'Updated %s', 'vara-alert-hub' ), get_the_modified_date( $vara_alert_hub_date_fmt ) );
?>
<article id="post-<?php the_ID(); ?>" class="ah-row<?php echo $vara_alert_hub_compact ? ' ah-row--compact' : ''; ?>" style="<?php echo esc_attr( vara_alert_hub_item_style( $vara_alert_hub_id ) ); ?>">
	<?php if ( $vara_alert_hub_has_thumb ) : ?>
		<span class="ah-row__thumb"><?php the_post_thumbnail( 'thumbnail', array( 'alt' => '' ) ); ?></span>
	<?php elseif ( $vara_alert_hub_has_ph ) : ?>
		<span class="ah-row__thumb" aria-hidden="true"><?php echo esc_html( vara_alert_hub_initial( $vara_alert_hub_badge ? $vara_alert_hub_badge : get_the_title() ) ); ?></span>
	<?php endif; ?>

	<div class="ah-row__main">
		<?php if ( $vara_alert_hub_compact ) : ?>
			<?php echo vara_alert_hub_new_badge( $vara_alert_hub_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<h3 class="ah-row__title"><a href="<?php the_permalink(); ?>"><?php echo vara_alert_hub_highlight( get_the_title() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></h3>
			<?php if ( $vara_alert_hub_badge ) : ?>
				<span class="ah-badge"><?php echo esc_html( $vara_alert_hub_badge ); ?></span>
			<?php endif; ?>
			<span class="ah-date"><?php echo esc_html( $vara_alert_hub_updated ); ?></span>
		<?php else : ?>
			<div class="ah-row__meta">
				<?php echo vara_alert_hub_new_badge( $vara_alert_hub_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php if ( $vara_alert_hub_badge ) : ?>
					<span class="ah-badge"><?php echo esc_html( $vara_alert_hub_badge ); ?></span>
				<?php endif; ?>
				<span class="ah-date"><?php echo esc_html( $vara_alert_hub_updated ); ?></span>
				<?php echo vara_alert_hub_last_date_chip( $vara_alert_hub_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<h3 class="ah-row__title"><a href="<?php the_permalink(); ?>"><?php echo vara_alert_hub_highlight( get_the_title() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></h3>
			<?php if ( $vara_alert_hub_excerpt && ( has_excerpt() || '' !== trim( get_the_content() ) ) ) : ?>
				<p class="ah-row__excerpt"><?php echo vara_alert_hub_highlight( vara_alert_hub_excerpt( 22 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</article>
