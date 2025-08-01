<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Checkbox
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_checkbox' ) ) {

	class Exopite_Simple_Options_Framework_Field_checkbox extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {

			parent::__construct( $field, $value, $unique, $config );

		}

		public function output() {

			echo esc_attr($this->element_before());
			$label = ( isset( $this->field['label'] ) ) ? $this->field['label'] : '';
			$style = ( isset( $this->field['style'] ) ) ? $this->field['style'] : '';

			switch ( $style ) {
				case 'fancy':
					echo '<label class="checkbox">';
					echo '<input type="checkbox" class="checkbox__input" name="' . esc_attr($this->element_name()) . '" value="yes"' . esc_attr($this->element_attributes()) . checked( esc_attr($this->element_value()), 'yes', false ) . '>';
					echo '<div class="checkbox__checkmark"></div>';
					echo esc_attr($label);
					echo '</label>';
					break;

				default:
					echo '<label><input type="checkbox" name="' . esc_attr($this->element_name()) . '" value="yes"' . esc_attr($this->element_class()) . esc_attr($this->element_attributes()) . checked( esc_attr($this->element_value()), 'yes', false ) . '/> ' . esc_attr($label) . '</label>';
					break;
			}


			echo esc_attr($this->element_after());

		}

	}

}
