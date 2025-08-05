<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Color
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
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_color_wp' ) ) {

	class Exopite_Simple_Options_Framework_Field_color_wp extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array(), $multilang ) {

			parent::__construct( $field, $value, $unique, $config, $multilang );
		}

		public function output() {

			$classes = ( isset( $this->field['class'] ) ) ? implode( ' ', explode( ' ', $this->field['class'] ) ) : '';

			/*
			 * Color Picker
			 *
			 * @link https://paulund.co.uk/adding-a-new-color-picker-with-wordpress-3-5
			 */

			echo $this->element_before();
			echo '<input type="text" class="colorpicker ' . $classes . '" ';
			if ( isset( $this->field['rgba'] ) && $this->field['rgba'] ) {
				echo 'data-alpha="true" ';
			}
			echo 'name="' . $this->element_name() . '" value="' . $this->element_value() . '"';
			echo $this->element_attributes() . '/>';

		}

		public static function enqueue( $args ) {

			// Add the color picker css file from WordPress
			wp_enqueue_style( 'wp-color-picker' );

			$resources = array(
				array(
					'name'       => 'wp-color-picker-alpha',
					'fn'         => 'wp-color-picker-alpha.min.js',
					'type'       => 'script',
					'dependency' => array( 'wp-color-picker' ),
					'version'    => '2.1.3',
					'attr'       => true,
				),
				array(
					'name'       => 'exopite-sof-wp-color-picker-loader',
					'fn'         => 'loader-color-picker.min.js',
					'type'       => 'script',
					'dependency' => array( 'wp-color-picker-alpha' ),
					'version'    => '20190407',
					'attr'       => true,
				),
			);

			parent::do_enqueue( $resources, $args );

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
