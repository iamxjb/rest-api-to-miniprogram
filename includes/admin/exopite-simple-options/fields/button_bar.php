<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Button Bar
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_button_bar' ) ) {

	class Exopite_Simple_Options_Framework_Field_button_bar extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {
			parent::__construct( $field, $value, $unique, $config );
		}

		public function output() {

			echo esc_attr( $this->element_before() );

			if ( isset( $this->field['options'] ) ) {

				$options = $this->field['options'];
				$options = ( is_array( $options ) ) ? $options : array_filter( $this->element_data( $options ) );

				if ( ! empty( $options ) ) {

					echo '<div class="button-bar">';
					foreach ( $options as $key => $value ) {
						echo '<div class="button-bar__item">';
						echo '<input type="radio" name="' . esc_attr( $this->element_name() ) . '" value="' . esc_attr($key ) . '"' . esc_attr($this->element_attributes( $key )) . esc_attr($this->checked( $this->element_value(), esc_attr($key) ) ) . '>';
						echo '<button class="button-bar__button">' . esc_attr( $value ) . '</button>';
						echo '</div>';

					}
					echo '</div>';
				}

			}

			echo esc_attr( $this->element_after() );

		}

	}

}
