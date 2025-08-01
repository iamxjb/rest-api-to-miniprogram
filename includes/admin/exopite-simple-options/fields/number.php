<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Number
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_number' ) ) {

	class Exopite_Simple_Options_Framework_Field_number extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {
			parent::__construct( $field, $value, $unique, $config );
		}

		public function output() {

			echo esc_attr($this->element_before());

			$unit = ( isset( $this->field['unit'] ) ) ? '<em>' . $this->field['unit'] . '</em>' : '';

			$attr = array();
			if ( isset( $this->field['min'] ) ) {
				$attr[] = 'min="' . $this->field['min'] . '"';
			}
			if ( isset( $this->field['max'] ) ) {
				$attr[] = 'max="' . $this->field['max'] . '"';
			}
			if ( isset( $this->field['step'] ) ) {
				$attr[] = 'step="' . $this->field['step'] . '"';
			}
			$attrs = ( ! empty( $attr ) ) ? ' ' . trim( implode( ' ', $attr ) ) : '';

			echo esc_attr($this->element_prepend());

			echo '<input type="number" name="' . esc_attr($this->element_name()) . '" value="' . esc_attr($this->element_value()) . '"' . esc_attr($this->element_class()) . esc_attr($this->element_attributes()) . esc_attr($attrs) . '/>';

			echo esc_attr($this->element_append());

			echo esc_attr($unit);

			echo esc_attr($this->element_after());

		}

	}

}
