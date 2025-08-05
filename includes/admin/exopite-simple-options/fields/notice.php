<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Notice
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
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_notice' ) ) {

	class Exopite_Simple_Options_Framework_Field_notice extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {
			parent::__construct( $field, $value, $unique, $config );
		}

		public function output() {

			$classes = ( isset( $this->field['class'] ) ) ? implode( ' ', explode( ' ', $this->field['class'] ) ) : '';

			$content = ( isset( $this->field['content'] ) ) ? $this->field['content'] : '';

			if ( isset( $this->field['callback'] ) ) {

				$callback = $this->field['callback'];
				if ( is_callable( $callback['function'] ) ) {

					$args    = ( isset( $callback['args'] ) ) ? $callback['args'] : '';
					$content = call_user_func( $callback['function'], $args );

				}
			}

			echo $this->element_before();
			echo '<div class="exopite-sof-notice ' . $classes . '">' . $content . '</div>';
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
