<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Editor
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
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_editor' ) ) {

	class Exopite_Simple_Options_Framework_Field_editor extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {
			parent::__construct( $field, $value, $unique, $config );
		}

		public function output() {

			$classes = ( isset( $this->field['class'] ) ) ? explode( ' ', $this->field['class'] ) : array();//$this->element_class()
			$editor  = ( isset( $this->field['editor'] ) ) ? $this->field['editor'] : 'tinymce';

			echo $this->element_before();

			if ( $editor == 'tinymce' && isset( $this->field['sub'] ) && $this->field['sub'] ) {

				$classes[] = 'tinymce-js';
				$classes   = implode( ' ', $classes );

				echo '<textarea id="' . $this->field['id'] . '" name="' . $this->element_name() . '" class="' . $classes . '"' . $this->element_attributes() . '>' . $this->element_value() . '</textarea>';

			} elseif ( $editor == 'trumbowyg' ) {

				$classes[] = 'trumbowyg-js';
				$classes   = implode( ' ', $classes );

				echo '<textarea id="' . $this->field['id'] . '" name="' . $this->element_name() . '" data-icon-path="' . plugin_dir_url( __DIR__ ) . 'assets/editors/trumbowyg/icons.svg" class="' . $classes . '"' . $this->element_attributes() . '>' . $this->element_value() . '</textarea>';

			} else {

				$args = array(
					'textarea_rows' => 15,
					'editor_class'  => implode( ' ', $classes ),
					'textarea_name' => $this->element_name(),
					'teeny'         => false,
					// output the minimal editor config used in Press This
					'dfw'           => false,
					// replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
					'tinymce'       => true,
					// load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
					'quicktags'     => true
					// load Quicktags, can be used to pass settings directly to Quicktags using an array()
				);

				wp_editor( $this->element_value(), $this->field['id'], $args );

			}

			echo $this->element_after();

		}

		public static function enqueue( $args ) {

			if ( isset( $args['field'] ) && isset( $args['field']['editor'] ) && is_array( $args['field']['editor'] ) ) {

				foreach ( $args['field']['editor'] as $editor ) {

					switch ( $editor ) {

						case 'trumbowyg':

							$resources = array(
								array(
									'name'       => 'trumbowyg',
									'fn'         => 'editors/trumbowyg/trumbowyg.min.css',
									'type'       => 'style',
									'dependency' => array(),
									'version'    => '2.10.0',
									'attr'       => 'all',
								),
								array(
									'name'       => 'trumbowyg-colors',
									'fn'         => 'editors/trumbowyg/trumbowyg.colors.min.css',
									'type'       => 'style',
									'dependency' => array(),
									'version'    => '2.10.0',
									'attr'       => 'all',
								),
								array(
									'name'       => 'trumbowyg-user',
									'fn'         => 'editors/trumbowyg/trumbowyg.user.min.css',
									'type'       => 'style',
									'dependency' => array(),
									'version'    => '2.10.0',
									'attr'       => 'all',
								),
								array(
									'name'       => 'trumbowyg',
									'fn'         => 'editors/trumbowyg/trumbowyg.min.js',
									'type'       => 'script',
									'dependency' => array( 'jquery' ),
									'version'    => '1.8.2',
									'attr'       => true,
								),
								array(
									'name'       => 'trumbowyg-base64',
									'fn'         => 'editors/trumbowyg/trumbowyg.base64.min.js',
									'type'       => 'script',
									'dependency' => array( 'trumbowyg' ),
									'version'    => '1.8.2',
									'attr'       => true,
								),
								array(
									'name'       => 'trumbowyg-colors',
									'fn'         => 'editors/trumbowyg/trumbowyg.colors.min.js',
									'type'       => 'script',
									'dependency' => array( 'trumbowyg' ),
									'version'    => '1.8.2',
									'attr'       => true,
								),
								array(
									'name'       => 'trumbowyg-fontfamily',
									'fn'         => 'editors/trumbowyg/trumbowyg.fontfamily.min.js',
									'type'       => 'script',
									'dependency' => array( 'trumbowyg' ),
									'version'    => '1.8.2',
									'attr'       => true,
								),
								array(
									'name'       => 'trumbowyg-fontsize',
									'fn'         => 'editors/trumbowyg/trumbowyg.fontsize.min.js',
									'type'       => 'script',
									'dependency' => array( 'trumbowyg' ),
									'version'    => '1.8.2',
									'attr'       => true,
								),
								array(
									'name'       => 'exopite-sof-trumbowyg-loader',
									'fn'         => 'loader-jquery-trumbowyg.min.js',
									'type'       => 'script',
									'dependency' => array( 'trumbowyg' ),
									'version'    => '',
									'attr'       => true,
								),

							);

							parent::do_enqueue( $resources, $args );

							break;

					}

				}

			}


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
