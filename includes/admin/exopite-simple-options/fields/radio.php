<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Radio
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_radio' ) ) {

	class Exopite_Simple_Options_Framework_Field_radio extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {
			parent::__construct( $field, $value, $unique, $config );
		}

		public function output() {

			$classes = ( isset( $this->field['class'] ) ) ? implode( ' ', explode( ' ', $this->field['class'] ) ) : '';

			echo esc_attr($this->element_before());

			if ( isset( $this->field['options'] ) ) {

				$options = $this->field['options'];
				$options = ( is_array( $options ) ) ? $options : array_filter( $this->element_data( $options ) );
				$style   = ( isset( $this->field['style'] ) ) ? $this->field['style'] : '';

				if ( ! empty( $options ) ) {

					echo '<ul' . esc_attr($this->element_class()) . '>';
					foreach ( $options as $key => $value ) {

						switch ( $style ) {
							case 'fancy':
								echo '<li>';
								echo '<label class="radio-button ' . esc_attr($classes) . '">';
								echo '<input type="radio" class="radio-button__input" name="' . esc_attr($this->element_name()) . '" value="' . esc_attr($key) . '"' . esc_attr($this->element_attributes( $key )) . esc_attr($this->checked( $this->element_value()), esc_attr($key )) . '>';
								echo '<div class="radio-button__checkmark"></div>';
								echo esc_attr($value);
								echo '</label>';
								echo '</li>';
								break;

							default:
								echo '<li><label><input type="radio" name="' .esc_attr($this->element_name()) . '" value="' . esc_attr($key) . '"' . esc_attr($this->element_attributes( $key )) . esc_attr($this->checked( $this->element_value()), esc_attr($key )) . '/> ' . esc_attr($value) . '</label></li>';
								break;
						}

					}
					echo '</ul>';
				}

			} else {
				$label = ( isset( $this->field['label'] ) ) ? $this->field['label'] : '';

				switch ( $this->field['style'] ) {
					case 'fancy':
						echo '<label class="radio-button ' . esc_attr($classes) . '">';
						echo '<input type="radio" class="radio-button__input" name="' . esc_attr($this->element_name()) . '"' . esc_attr($this->element_attributes()) . checked( esc_attr($this->element_value()), 1, false ) . '>';
						echo '<div class="radio-button__checkmark"></div>';
						echo esc_attr($label);
						echo '</label>';
						break;

					default:
						echo '<label><input type="radio" name="' . esc_attr($this->element_name()) . '" value="1"' . esc_attr($this->element_class()) . esc_attr($this->element_attributes()) . checked( $this->element_value(), 1, false ) . '/> ' . esc_attr($label) . '</label>';
						break;
				}

			}

			echo esc_attr($this->element_after());

		}

	}

}
