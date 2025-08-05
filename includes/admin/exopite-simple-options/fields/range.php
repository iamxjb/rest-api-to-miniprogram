<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Range
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
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_range' ) ) {

	class Exopite_Simple_Options_Framework_Field_range extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {
			parent::__construct( $field, $value, $unique, $config );
		}

		public function output() {

			/**
			 * Update input if range changed
			 *
			 * @link https://stackoverflow.com/questions/10004723/html5-input-type-range-show-range-value/45210546#45210546
			 */
			$attr = array();
			if ( ! empty( $this->field['min'] ) ) {
				$attr[] = 'min="' . $this->field['min'] . '"';
			}
			if ( ! empty( $this->field['max'] ) ) {
				$attr[] = 'max="' . $this->field['max'] . '"';
			}
			if ( ! empty( $this->field['step'] ) ) {
				$attr[] = 'step="' . $this->field['step'] . '"';
			}
			$attrs   = ( ! empty( $attr ) ) ? ' ' . trim( implode( ' ', $attr ) ) : '';
			$unit    = ( isset( $this->field['unit'] ) ) ? '<em>' . $this->field['unit'] . '</em>' : '';
			$classes = ( isset( $this->field['class'] ) ) ? implode( ' ', explode( ' ', $this->field['class'] ) ) : '';

			echo $this->element_before();

			echo '<input type="range" name="' . $this->element_name() . '" oninput="updateRangeInput(this)" class="range ' . $classes . '"' . $attrs . ' value="' . $this->element_value() . '"' . $this->element_attributes() . '>' . $unit;
			echo '<input type="number" value="' . $this->element_value() . '" oninput="updateInputRange(this)"' . $attrs . '>';

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
