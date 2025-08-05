<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Checkbox
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
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_checkbox' ) ) {

	class Exopite_Simple_Options_Framework_Field_checkbox extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {

			parent::__construct( $field, $value, $unique, $config );

		}

		public function output() {

			echo $this->element_before();
			$label = ( isset( $this->field['label'] ) ) ? $this->field['label'] : '';
			$style = ( isset( $this->field['style'] ) ) ? $this->field['style'] : '';

			switch ( $style ) {
				case 'fancy':
					echo '<label class="checkbox">';
					echo '<input type="checkbox" class="checkbox__input" name="' . $this->element_name() . '" value="yes"' . $this->element_attributes() . checked( $this->element_value(), 'yes', false ) . '>';
					echo '<div class="checkbox__checkmark"></div>';
					echo $label;
					echo '</label>';
					break;

				default:
					echo '<label><input type="checkbox" name="' . $this->element_name() . '" value="yes"' . $this->element_class() . $this->element_attributes() . checked( $this->element_value(), 'yes', false ) . '/> ' . $label . '</label>';
					break;
			}


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
