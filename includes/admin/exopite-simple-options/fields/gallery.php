<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Gallery
 *
 */
// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotValidated
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
// phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash
// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
// phpcs:disable WordPress.WP.I18n.TextDomainMismatch
// phpcs:disable WordPress.Security.NonceVerification.Missing
// phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_var_export
// phpcs:disable WordPress.DB.SlowDBQuery.slow_db_query_meta_query
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_gallery' ) ) {

	class Exopite_Simple_Options_Framework_Field_gallery extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array(), $multilang ) {
			parent::__construct( $field, $value, $unique, $config, $multilang );

		}

		public function output() {

			echo $this->element_before();

			echo $this->element_prepend();

			$defaults = array(
				'add_button' => esc_attr__( 'Add to gallery', 'exopite-sof' ),
				'media_frame_title' => esc_attr__( 'Select images for gallery', 'exopite-sof' ),
				'media_frame_button' => esc_attr__( 'Add', 'exopite-sof' ),
				'media_type' => 'image',
			);

			$options = ( isset( $field['options'] ) && is_array( $field['options'] ) ) ? $field['options'] : array();
			$options = wp_parse_args( $options, $defaults );

			$value = $this->element_value();

			echo '<div class="exopite-sof-gallery-field" data-media-frame-title="' . esc_attr( $options['media_frame_title'] ) . '" data-media-frame-button="' . esc_attr( $options['media_frame_button'] ) . '" data-media-frame-type="' . esc_attr( $options['media_type'] ) . '">';
			echo '<input type="hidden" name="' . $this->element_name() . '" data-control="gallery-ids" value="' . $this->element_value() . '"' . $this->element_class() . $this->element_attributes() . '/>';
			echo '<div class="exopite-sof-gallery">';

			if ( $value ) :

				$meta_array = explode( ',', $value );
				foreach ( $meta_array as $meta_gall_item ) :

					echo '<span><span class="exopite-sof-image-delete"></span><img id="' . esc_attr( $meta_gall_item ) . '" src="' . wp_get_attachment_thumb_url( $meta_gall_item ) . '"></span>';

				endforeach;

			endif;

			echo '</div>';
			echo '<input class="exopite-sof-gallery-add button button-primary exopite-sof-button" type="button" value="' . esc_attr( $options['add_button'] ) . '" />';
			echo '</div>';

			echo $this->element_append();

			echo $this->element_after();

		}

	}

}
// phpcs:enable WordPress.Security.ValidatedSanitizedInput.InputNotValidated
// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
// phpcs:enable WordPress.Security.ValidatedSanitizedInput.MissingUnslash
// phpcs:enable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
// phpcs:enable WordPress.WP.I18n.TextDomainMismatch
// phpcs:enable WordPress.Security.NonceVerification.Missing
// phpcs:enable WordPress.PHP.DevelopmentFunctions.error_log_var_export
// phpcs:enable WordPress.DB.SlowDBQuery.slow_db_query_meta_query
