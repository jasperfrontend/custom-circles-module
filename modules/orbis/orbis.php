<?php

/**
 * Orbis – decorative circle stacker for Beaver Builder.
 *
 * @class OrbisModule
 */
class OrbisModule extends FLBuilderModule {

	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Orbis', 'orbis' ),
			'description'     => __( 'Stack decorative circles around an image or text.', 'orbis' ),
			'category'        => __( 'Creative', 'orbis' ),
			'dir'             => ORBIS_DIR . 'modules/orbis/',
			'url'             => ORBIS_URL . 'modules/orbis/',
			'icon'            => 'icon.svg',
			'editor_export'   => true,
			'partial_refresh' => true,
		) );
	}

	/**
	 * Return the photo URL, handling both src and attachment ID.
	 */
	public function get_photo_url() {
		if ( ! empty( $this->settings->photo_src ) ) {
			return $this->settings->photo_src;
		}
		if ( ! empty( $this->settings->photo ) ) {
			return wp_get_attachment_url( $this->settings->photo );
		}
		return '';
	}

	/**
	 * Return all configured circles as an array of objects.
	 */
	public function get_circles() {
		if ( empty( $this->settings->circles ) || ! is_array( $this->settings->circles ) ) {
			return array();
		}

		return array_filter( $this->settings->circles, 'is_object' );
	}
}

/**
 * ── Register the module and its settings form ────────────────────────
 */
FLBuilder::register_module( 'OrbisModule', array(

	/* ================================================================
	 * Tab: General
	 * ============================================================= */
	'general' => array(
		'title'    => __( 'General', 'orbis' ),
		'sections' => array(
			'content' => array(
				'title'  => '',
				'fields' => array(
					'content_type' => array(
						'type'    => 'select',
						'label'   => __( 'Content Type', 'orbis' ),
						'default' => 'image',
						'options' => array(
							'image' => __( 'Image', 'orbis' ),
							'text'  => __( 'Text', 'orbis' ),
						),
						'toggle'  => array(
							'image' => array(
								'fields'   => array( 'photo' ),
								'sections' => array( 'image_style' ),
							),
							'text'  => array(
								'fields'   => array( 'text_content' ),
								'sections' => array( 'text_style' ),
							),
						),
					),
					'photo'        => array(
						'type'        => 'photo',
						'label'       => __( 'Image', 'orbis' ),
						'show_remove' => true,
					),
					'text_content' => array(
						'type'        => 'textarea',
						'label'       => __( 'Text', 'orbis' ),
						'rows'        => '4',
						'placeholder' => __( 'Enter your text…', 'orbis' ),
						'preview'     => array(
							'type'     => 'text',
							'selector' => '.orbis-text',
						),
					),
				),
			),
		),
	),

	/* ================================================================
	 * Tab: Style
	 * ============================================================= */
	'style' => array(
		'title'    => __( 'Style', 'orbis' ),
		'sections' => array(

			/* ── Content circle size ─────────────────────────── */
			'content_style' => array(
				'title'  => __( 'Content Circle', 'orbis' ),
				'fields' => array(
					'content_size' => array(
						'type'        => 'text',
						'label'       => __( 'Size', 'orbis' ),
						'default'     => '60',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '4',
						'help'        => __( 'Width of the content circle as a percentage of the container.', 'orbis' ),
					),
				),
			),

			/* ── Image-specific styling (toggled) ───────────── */
			'image_style' => array(
				'title'  => __( 'Image Style', 'orbis' ),
				'fields' => array(
					'image_border_style' => array(
						'type'    => 'select',
						'label'   => __( 'Border Style', 'orbis' ),
						'default' => 'none',
						'options' => array(
							'none'   => __( 'None', 'orbis' ),
							'solid'  => __( 'Solid', 'orbis' ),
							'dashed' => __( 'Dashed', 'orbis' ),
							'dotted' => __( 'Dotted', 'orbis' ),
						),
						'toggle'  => array(
							'none'   => array(),
							'solid'  => array( 'fields' => array( 'image_border_width', 'image_border_color' ) ),
							'dashed' => array( 'fields' => array( 'image_border_width', 'image_border_color' ) ),
							'dotted' => array( 'fields' => array( 'image_border_width', 'image_border_color' ) ),
						),
					),
					'image_border_width' => array(
						'type'        => 'text',
						'label'       => __( 'Border Width', 'orbis' ),
						'default'     => '3',
						'description' => 'px',
						'maxlength'   => '3',
						'size'        => '4',
					),
					'image_border_color' => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'orbis' ),
						'show_reset' => true,
					),
				),
			),

			/* ── Text-specific styling (toggled) ────────────── */
			'text_style' => array(
				'title'  => __( 'Text Style', 'orbis' ),
				'fields' => array(
					'content_bg_color' => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'orbis' ),
						'default'    => 'E07A5F',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.orbis-content',
							'property' => 'background-color',
						),
					),
					'text_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Text Color', 'orbis' ),
						'default'    => 'ffffff',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.orbis-text',
							'property' => 'color',
						),
					),
					'text_font_size'   => array(
						'type'        => 'text',
						'label'       => __( 'Font Size', 'orbis' ),
						'default'     => '18',
						'description' => 'px',
						'maxlength'   => '3',
						'size'        => '4',
					),
					'text_line_height' => array(
						'type'        => 'text',
						'label'       => __( 'Line Height', 'orbis' ),
						'default'     => '1.4',
						'description' => '',
						'size'        => '4',
					),
					'text_align'       => array(
						'type'    => 'select',
						'label'   => __( 'Text Align', 'orbis' ),
						'default' => 'center',
						'options' => array(
							'left'   => __( 'Left', 'orbis' ),
							'center' => __( 'Center', 'orbis' ),
							'right'  => __( 'Right', 'orbis' ),
						),
					),
					'text_rotation'    => array(
						'type'        => 'text',
						'label'       => __( 'Text Rotation', 'orbis' ),
						'default'     => '0',
						'description' => 'deg',
						'maxlength'   => '4',
						'size'        => '5',
						'placeholder' => '0',
						'help'        => __( 'Rotate the text in degrees (e.g. -15 or 30).', 'orbis' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.orbis-text',
							'property' => 'transform',
							'unit'     => 'deg',
						),
					),
					'content_border_style' => array(
						'type'    => 'select',
						'label'   => __( 'Border Style', 'orbis' ),
						'default' => 'none',
						'options' => array(
							'none'   => __( 'None', 'orbis' ),
							'solid'  => __( 'Solid', 'orbis' ),
							'dashed' => __( 'Dashed', 'orbis' ),
							'dotted' => __( 'Dotted', 'orbis' ),
						),
						'toggle'  => array(
							'none'   => array(),
							'solid'  => array( 'fields' => array( 'content_border_width', 'content_border_color' ) ),
							'dashed' => array( 'fields' => array( 'content_border_width', 'content_border_color' ) ),
							'dotted' => array( 'fields' => array( 'content_border_width', 'content_border_color' ) ),
						),
					),
					'content_border_width' => array(
						'type'        => 'text',
						'label'       => __( 'Border Width', 'orbis' ),
						'default'     => '3',
						'description' => 'px',
						'maxlength'   => '3',
						'size'        => '4',
					),
					'content_border_color' => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'orbis' ),
						'show_reset' => true,
					),
				),
			),
		),
	),

	/* ================================================================
	 * Tab: Circles
	 * ============================================================= */
	'circles' => array(
		'title'    => __( 'Circles', 'orbis' ),
		'sections' => array(
			'circles_general' => array(
				'title'  => '',
				'fields' => array(
					'circles' => array(
						'type'         => 'form',
						'label'        => __( 'Circle', 'orbis' ),
						'form'         => 'orbis_circle_settings',
						'preview_text' => 'bg_color',
						'multiple'     => true,
					),
				),
			),
		),
	),
) );

/**
 * ── Circle sub-form (shared by all four circles) ────────────────────
 */
FLBuilder::register_settings_form( 'orbis_circle_settings', array(
	'title' => __( 'Circle Settings', 'orbis' ),
	'tabs'  => array(
		'style'    => array(
			'title'    => __( 'Style', 'orbis' ),
			'sections' => array(
				'appearance' => array(
					'title'  => __( 'Appearance', 'orbis' ),
					'fields' => array(
						'bg_color'     => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'orbis' ),
							'default'    => 'dddddd',
							'show_reset' => true,
							'show_alpha' => true,
						),
						'size'         => array(
							'type'        => 'text',
							'label'       => __( 'Size', 'orbis' ),
							'default'     => '80',
							'description' => '%',
							'maxlength'   => '3',
							'size'        => '4',
							'help'        => __( 'Circle diameter as a percentage of the container width.', 'orbis' ),
						),
						'border_style' => array(
							'type'    => 'select',
							'label'   => __( 'Border Style', 'orbis' ),
							'default' => 'none',
							'options' => array(
								'none'   => __( 'None', 'orbis' ),
								'solid'  => __( 'Solid', 'orbis' ),
								'dashed' => __( 'Dashed', 'orbis' ),
								'dotted' => __( 'Dotted', 'orbis' ),
							),
							'toggle'  => array(
								'none'   => array(),
								'solid'  => array( 'fields' => array( 'border_width', 'border_color' ) ),
								'dashed' => array( 'fields' => array( 'border_width', 'border_color' ) ),
								'dotted' => array( 'fields' => array( 'border_width', 'border_color' ) ),
							),
						),
						'border_width' => array(
							'type'        => 'text',
							'label'       => __( 'Border Width', 'orbis' ),
							'default'     => '3',
							'description' => 'px',
							'maxlength'   => '3',
							'size'        => '4',
						),
						'border_color' => array(
							'type'       => 'color',
							'label'      => __( 'Border Color', 'orbis' ),
							'show_reset' => true,
						),
					),
				),
			),
		),
		'position' => array(
			'title'    => __( 'Position', 'orbis' ),
			'sections' => array(
				'offset' => array(
					'title'  => __( 'Offset from Center', 'orbis' ),
					'fields' => array(
						'offset_x'      => array(
							'type'        => 'text',
							'label'       => __( 'Horizontal (X)', 'orbis' ),
							'default'     => '0',
							'placeholder' => '0',
							'size'        => '5',
							'help'        => __( 'Negative = left, positive = right.', 'orbis' ),
						),
						'offset_x_unit' => array(
							'type'    => 'select',
							'label'   => __( 'X Unit', 'orbis' ),
							'default' => '%',
							'options' => array(
								'%'   => '%',
								'px'  => 'px',
								'rem' => 'rem',
								'em'  => 'em',
							),
						),
						'offset_y'      => array(
							'type'        => 'text',
							'label'       => __( 'Vertical (Y)', 'orbis' ),
							'default'     => '0',
							'placeholder' => '0',
							'size'        => '5',
							'help'        => __( 'Negative = up, positive = down.', 'orbis' ),
						),
						'offset_y_unit' => array(
							'type'    => 'select',
							'label'   => __( 'Y Unit', 'orbis' ),
							'default' => '%',
							'options' => array(
								'%'   => '%',
								'px'  => 'px',
								'rem' => 'rem',
								'em'  => 'em',
							),
						),
					),
				),
			),
		),
	),
) );
