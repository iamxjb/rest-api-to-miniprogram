<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Textarea
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
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_textarea' ) ) {

	class Exopite_Simple_Options_Framework_Field_textarea extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {
			parent::__construct( $field, $value, $unique, $config );
		}

		public function output() {

			echo $this->element_before();
			echo '<textarea name="' . $this->element_name() . '"' . $this->element_class() . $this->element_attributes() . '>' . $this->element_value() . '</textarea>';
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
