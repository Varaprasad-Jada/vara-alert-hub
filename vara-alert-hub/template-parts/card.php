<?php
/**
 * A featured card.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vara_alert_hub_id    = get_the_ID();
$vara_alert_hub_cat   = vara_alert_hub_primary_category( $vara_alert_hub_id );
$vara_alert_hub_mode  = vara_alert_hub_get( 'images' );
$vara_alert_hub_chip  = vara_alert_hub_last_date_chip( $vara_alert_hub_id );
$vara_alert_hub_label = $vara_alert_hub_cat ? $vara_alert_hub_cat->name : get_the_title();
?>
<article class="ah-card" style="<?php echo esc_attr( vara_alert_hub_item_style( $vara_alert_hub_id ) ); ?>">
	<?php if ( 'off' !== $vara_alert_hub_mode && has_post_thumbnail() ) : ?>
		<?php the_post_thumbnail( 'medium_large', array( 'class' => 'ah-card__img', 'alt' => '' ) ); ?>
	<?php elseif ( 'always' === $vara_alert_hub_mode ) : ?>
		<div class="ah-card__ph" aria-hidden="true"><?php echo esc_html( vara_alert_hub_initial( $vara_alert_hub_label ) ); ?></div>
	<?php endif; ?>

	<div class="ah-card__body">
		<div class="ah-card__top">
			<?php echo vara_alert_hub_new_badge( $vara_alert_hub_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php if ( $vara_alert_hub_cat ) : ?>
				<span class="ah-badge"><?php echo esc_html( $vara_alert_hub_cat->name ); ?></span>
			<?php endif; ?>
			<span class="ah-date"><?php echo esc_html( get_the_date( 'j M' ) ); ?></span>
		</div>

		<h3 class="ah-card__title"><a href="<?php the_permalink(); ?>"><?php echo esc_html( wp_strip_all_tags( get_the_title() ) ); ?></a></h3>

		<?php if ( has_excerpt() || '' !== trim( get_the_content() ) ) : ?>
			<p class="ah-card__excerpt"><?php echo esc_html( vara_alert_hub_excerpt( 16 ) ); ?></p>
		<?php endif; ?>

		<div class="ah-card__foot">
			<?php if ( $vara_alert_hub_chip ) : ?>
				<?php echo $vara_alert_hub_chip; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<?php
				/* translators: %s: date such as 22 Nov */
				printf( esc_html__( 'Updated %s', 'vara-alert-hub' ), esc_html( get_the_modified_date( 'j M' ) ) );
				?>
			<?php endif; ?>
		</div>
	</div>
</article>
