<?php
/**
 * Orbis – dynamic CSS for each module instance.
 *
 * Available variables:
 *   $module   – OrbisModule instance.
 *   $id       – Module node ID.
 *   $settings – Module settings object.
 */

$content_size = ! empty( $settings->content_size ) ? max( 1, floatval( $settings->content_size ) ) : 60;
$circles      = $module->get_circles();

$allowed_border_styles = array( 'none', 'solid', 'dashed', 'dotted' );
$allowed_units         = array( '%', 'px', 'rem', 'em' );
?>

/* ── Container ───────────────────────────────────────────────────── */
.fl-node-<?php echo $id; ?> .orbis-wrapper {
	position: relative;
	width: 100%;
	aspect-ratio: 1 / 1;
}

/* ── Decorative circles ──────────────────────────────────────────── */
<?php foreach ( $circles as $i => $circle ) :

	$c_size    = ! empty( $circle->size ) ? max( 1, floatval( $circle->size ) ) : 80;
	$offset_x  = isset( $circle->offset_x ) ? floatval( $circle->offset_x ) : 0;
	$offset_xu = isset( $circle->offset_x_unit ) ? $circle->offset_x_unit : '%';
	$offset_y  = isset( $circle->offset_y ) ? floatval( $circle->offset_y ) : 0;
	$offset_yu = isset( $circle->offset_y_unit ) ? $circle->offset_y_unit : '%';

	if ( ! in_array( $offset_xu, $allowed_units, true ) ) {
		$offset_xu = '%';
	}
	if ( ! in_array( $offset_yu, $allowed_units, true ) ) {
		$offset_yu = '%';
	}

	$border_style = isset( $circle->border_style ) ? $circle->border_style : 'none';
	if ( ! in_array( $border_style, $allowed_border_styles, true ) ) {
		$border_style = 'none';
	}
?>
.fl-node-<?php echo $id; ?> .orbis-circle-<?php echo $i; ?> {
	position: absolute;
	border-radius: 50%;
	width: <?php echo $c_size; ?>%;
	aspect-ratio: 1 / 1;
	top: calc(50% + <?php echo $offset_y . $offset_yu; ?>);
	left: calc(50% + <?php echo $offset_x . $offset_xu; ?>);
	transform: translate(-50%, -50%);
	z-index: <?php echo $i + 1; ?>;
	<?php if ( ! empty( $circle->bg_color ) ) : ?>
	background-color: <?php echo FLBuilderColor::hex_or_rgb( $circle->bg_color ); ?>;
	<?php endif; ?>
	<?php if ( 'none' !== $border_style && ! empty( $circle->border_width ) ) : ?>
	border: <?php echo intval( $circle->border_width ); ?>px <?php echo $border_style; ?> <?php echo ! empty( $circle->border_color ) ? FLBuilderColor::hex_or_rgb( $circle->border_color ) : 'transparent'; ?>;
	<?php endif; ?>
}
<?php endforeach; ?>

/* ── Content circle ──────────────────────────────────────────────── */
.fl-node-<?php echo $id; ?> .orbis-content {
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
	width: <?php echo $content_size; ?>%;
	aspect-ratio: 1 / 1;
	border-radius: 50%;
	overflow: hidden;
	z-index: 5;
	display: flex;
	align-items: center;
	justify-content: center;
}

/* Image mode */
.fl-node-<?php echo $id; ?> .orbis-content img {
	width: 100%;
	height: 100%;
	object-fit: cover;
	display: block;
	border-radius: 50%;
}

<?php if ( 'image' === $settings->content_type ) : ?>
	<?php
	$img_border = isset( $settings->image_border_style ) ? $settings->image_border_style : 'none';
	if ( ! in_array( $img_border, $allowed_border_styles, true ) ) {
		$img_border = 'none';
	}
	?>
	<?php if ( 'none' !== $img_border && ! empty( $settings->image_border_width ) ) : ?>
.fl-node-<?php echo $id; ?> .orbis-content {
	border: <?php echo intval( $settings->image_border_width ); ?>px <?php echo $img_border; ?> <?php echo ! empty( $settings->image_border_color ) ? FLBuilderColor::hex_or_rgb( $settings->image_border_color ) : 'transparent'; ?>;
}
	<?php endif; ?>
<?php endif; ?>

/* Text mode */
<?php if ( 'text' === $settings->content_type ) : ?>
	<?php
	$txt_border = isset( $settings->content_border_style ) ? $settings->content_border_style : 'none';
	if ( ! in_array( $txt_border, $allowed_border_styles, true ) ) {
		$txt_border = 'none';
	}
	?>
.fl-node-<?php echo $id; ?> .orbis-content {
	<?php if ( ! empty( $settings->content_bg_color ) ) : ?>
	background-color: <?php echo FLBuilderColor::hex_or_rgb( $settings->content_bg_color ); ?>;
	<?php endif; ?>
	<?php if ( 'none' !== $txt_border && ! empty( $settings->content_border_width ) ) : ?>
	border: <?php echo intval( $settings->content_border_width ); ?>px <?php echo $txt_border; ?> <?php echo ! empty( $settings->content_border_color ) ? FLBuilderColor::hex_or_rgb( $settings->content_border_color ) : 'transparent'; ?>;
	<?php endif; ?>
}
.fl-node-<?php echo $id; ?> .orbis-text {
	padding: 15%;
	<?php if ( ! empty( $settings->text_color ) ) : ?>
	color: <?php echo FLBuilderColor::hex_or_rgb( $settings->text_color ); ?>;
	<?php endif; ?>
	<?php if ( ! empty( $settings->text_font_size ) ) : ?>
	font-size: <?php echo intval( $settings->text_font_size ); ?>px;
	<?php endif; ?>
	<?php if ( ! empty( $settings->text_line_height ) ) : ?>
	line-height: <?php echo floatval( $settings->text_line_height ); ?>;
	<?php endif; ?>
	<?php if ( ! empty( $settings->text_align ) ) : ?>
	text-align: <?php echo esc_attr( $settings->text_align ); ?>;
	<?php endif; ?>
	<?php if ( isset( $settings->text_rotation ) && '' !== $settings->text_rotation && '0' !== $settings->text_rotation ) : ?>
	transform: rotate(<?php echo floatval( $settings->text_rotation ); ?>deg);
	<?php endif; ?>
}
<?php endif; ?>
