<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Date
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
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_date' ) ) {

	class Exopite_Simple_Options_Framework_Field_date extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {
			parent::__construct( $field, $value, $unique, $config );
		}

		public function output() {

			$date_format = ( ! empty( $this->field['format'] ) ) ? $this->field['format'] : 'mm/dd/yy';
			$classes     = ( isset( $this->field['class'] ) ) ? implode( ' ', explode( ' ', $this->field['class'] ) ) : '';

			echo $this->element_before();

			echo $this->element_prepend();

			if ( isset( $this->field['picker'] ) && $this->field['picker'] == 'html5' ) {
				echo '<input type="date" ';
			} else {
				echo '<input type="text" ';
				echo 'class="datepicker ' . $classes . '" ';
			}
			echo 'name="' . $this->element_name() . '" ';
			if ( isset( $this->field['picker'] ) && $this->field['picker'] == 'html5' ) {
				echo 'value="' . $this->element_value() . '"' . $this->element_class() . $this->element_attributes() . ' ';
			} else {
				echo 'value="' . $this->element_value() . '"' . $this->element_attributes() . ' ';
				echo 'data-format="' . $date_format . '"';
			}
			echo '>';

			echo $this->element_append();

			echo $this->element_after();

		}

		public static function enqueue( $args ) {

			$resources = array(
				array(
					'name'       => 'exopite-sof-datepicker-loader',
					'fn'         => 'loader-datepicker.min.js',
					'type'       => 'script',
					'dependency' => array( 'jquery' ),
					'version'    => '',
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
