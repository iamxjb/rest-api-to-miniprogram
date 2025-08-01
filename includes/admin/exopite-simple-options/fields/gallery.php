<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Gallery
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_gallery' ) ) {

	class Exopite_Simple_Options_Framework_Field_gallery extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array(), $multilang ) {
			parent::__construct( $field, $value, $unique, $config, $multilang );

		}

		public function output() {

			echo esc_html( $this->element_before() );

			echo esc_html( $this->element_prepend() );

			$defaults = array(
				'add_button' => esc_attr__( 'Add to gallery', 'rest-api-to-miniprogram' ),
				'media_frame_title' => esc_attr__( 'Select images for gallery', 'rest-api-to-miniprogram' ),
				'media_frame_button' => esc_attr__( 'Add', 'rest-api-to-miniprogram' ),
				'media_type' => 'image',
			);

			$options = ( isset( $field['options'] ) && is_array( $field['options'] ) ) ? $field['options'] : array();
			$options = wp_parse_args( $options, $defaults );

			$value = $this->element_value();

			echo '<div class="exopite-sof-gallery-field" data-media-frame-title="' . esc_attr( $options['media_frame_title'] ) . '" data-media-frame-button="' . esc_attr( $options['media_frame_button'] ) . '" data-media-frame-type="' . esc_attr( $options['media_type'] ) . '">';
			echo '<input type="hidden" name="' . esc_html($this->element_name()) . '" data-control="gallery-ids" value="' . esc_html($this->element_value()) . '"' . esc_html($this->element_class()) . esc_html($this->element_attributes()) . '/>';
			echo '<div class="exopite-sof-gallery">';

			if ( $value ) :

				$meta_array = explode( ',', $value );
				foreach ( $meta_array as $meta_gall_item ) :

					echo '<span><span class="exopite-sof-image-delete"></span><img id="' . esc_html(esc_attr( $meta_gall_item )) . '" src="' . esc_html(wp_get_attachment_thumb_url( $meta_gall_item )) . '"></span>';

				endforeach;

			endif;

			echo '</div>';
			echo '<input class="exopite-sof-gallery-add button button-primary exopite-sof-button" type="button" value="' . esc_attr( $options['add_button'] ) . '" />';
			echo '</div>';

			echo esc_attr($this->element_append());

			echo esc_attr($this->element_after());

		}

	}

}
