<?php
/**
 * Orbis – frontend HTML output.
 *
 * Available variables:
 *   $module   – OrbisModule instance.
 *   $settings – Module settings object.
 *   $id       – Module node ID.
 */

$circles   = $module->get_circles();
$photo_url = $module->get_photo_url();
?>
<div class="orbis-wrapper">

	<?php foreach ( $circles as $i => $circle ) : ?>
	<div class="orbis-circle orbis-circle-<?php echo esc_attr( $i ); ?>"></div>
	<?php endforeach; ?>

	<div class="orbis-content">
		<?php if ( 'image' === $settings->content_type && ! empty( $photo_url ) ) : ?>
			<img src="<?php echo esc_url( $photo_url ); ?>" alt="" />
		<?php elseif ( 'text' === $settings->content_type && ! empty( $settings->text_content ) ) : ?>
			<div class="orbis-text"><?php echo wp_kses_post( $settings->text_content ); ?></div>
		<?php endif; ?>
	</div>

</div>
