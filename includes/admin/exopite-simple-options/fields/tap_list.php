<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Tap List
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
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_tap_list' ) ) {

	class Exopite_Simple_Options_Framework_Field_tap_list extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {
			parent::__construct( $field, $value, $unique, $config );
		}

		public function output() {

			$input_type = ( ! empty( $this->field['radio'] ) ) ? 'radio' : 'checkbox';
			$input_attr = ( $input_type == 'checkbox' ) ? '[]' : '';

			echo $this->element_before();

			if ( isset( $this->field['options'] ) ) {

				echo '<ul class="list">';

				$options = $this->field['options'];
				foreach ( $options as $key => $value ) {

					echo '<li class="list__item list__item--tappable">';
					echo '<div class="list__item__left">';

					if ( $input_type == 'radio' ) {
						# code...
					}

					switch ( $input_type ) {
						case 'radio':
							echo '<label class="radio-button">';
							echo '<input type="' . $input_type . '" name="' . $this->element_name( $input_attr ) . '" class="radio-button__input" id="' . $this->field['id'] . '-' . sanitize_title( $value ) . '" value="' . $key . '"' . $this->element_attributes( $key ) . $this->checked( $this->element_value(), $key ) . '/>';
							echo '<div class="radio-button__checkmark"></div>';
							echo '</label>';
							break;

						case 'checkbox':
							echo '<label class="checkbox checkbox--noborder">';
							echo '<input type="' . $input_type . '" name="' . $this->element_name( $input_attr ) . '" class="checkbox__input checkbox--noborder__input" id="' . $this->field['id'] . '-' . sanitize_title( $value ) . '" value="' . $key . '"' . $this->element_attributes( $key ) . $this->checked( $this->element_value(), $key ) . '>';
							echo '<div class="checkbox__checkmark checkbox--noborder checkbox--noborder__checkmark"></div>';
							echo '</label>';
							break;
					}


					echo '</div>';
					echo '<label for="' . $this->field['id'] . '-' . sanitize_title( $value ) . '" class="list__item__center">';
					echo $value;
					echo '</label>';
					echo '</li>';

				}

				echo '</ul>';
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
