<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Button Bar
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
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_button_bar' ) ) {

	class Exopite_Simple_Options_Framework_Field_button_bar extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {
			parent::__construct( $field, $value, $unique, $config );
		}

		public function output() {

			echo $this->element_before();

			if ( isset( $this->field['options'] ) ) {

				$options = $this->field['options'];
				$options = ( is_array( $options ) ) ? $options : array_filter( $this->element_data( $options ) );

				if ( ! empty( $options ) ) {

					echo '<div class="button-bar">';
					foreach ( $options as $key => $value ) {
						echo '<div class="button-bar__item">';
						echo '<input type="radio" name="' . $this->element_name() . '" value="' . $key . '"' . $this->element_attributes( $key ) . $this->checked( $this->element_value(), $key ) . '>';
						echo '<button class="button-bar__button">' . $value . '</button>';
						echo '</div>';

					}
					echo '</div>';
				}

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
