<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Text
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_text' ) ) {

	class Exopite_Simple_Options_Framework_Field_text extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array(), $multilang ) {
			parent::__construct( $field, $value, $unique, $config, $multilang );

		}

		public function output() {

			echo esc_attr($this->element_before());

			echo esc_attr($this->element_prepend());

			echo '<input type="' . esc_attr($this->element_type()) . '" name="' . esc_attr($this->element_name()) . '" value="' . esc_attr($this->element_value()) . '"' . esc_attr($this->element_class()) . esc_attr($this->element_attributes()) . '/>';

			echo esc_attr($this->element_append());

			echo esc_attr($this->element_after());

		}

	}

}
