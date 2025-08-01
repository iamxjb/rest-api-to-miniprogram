<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Backup
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_backup' ) ) {
	class Exopite_Simple_Options_Framework_Field_backup extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {
			parent::__construct( $field, $value, $unique, $config );
		}

		public function output() {

			echo esc_html( $this->element_before() );

			if ( $this->config['type'] == 'menu' ) {

				$nonce   = wp_create_nonce( 'exopite_sof_backup' );
				$options = get_option( $this->unique );
				$export  = esc_url( add_query_arg( array(
					'action'  => 'exopite-sof-export-options',
					'export'  => $this->unique,
					'wpnonce' => $nonce
				), admin_url( 'admin-ajax.php' ) ) );

				$encoded_options = '';
				if ( $options ) {

					$encoded_options = $this->encode_string( $options );

				}

				echo '<textarea name="_nonce" class="exopite-sof__import"></textarea>';
				echo '<small class="exopite-sof-info--small">( ' . esc_attr__( 'copy-paste your backup string here', 'rest-api-to-miniprogram' ) . ' )</small>';
				echo '<a href="#" class="button button-primary exopite-sof-import-js" data-confirm="' . esc_attr__( 'Are you sure, you want to overwrite existing options?', 'rest-api-to-miniprogram' ) . '">' . esc_attr__( 'Import a Backup', 'rest-api-to-miniprogram' ) . '</a>';

				echo '<hr />';
				echo '<textarea name="_nonce" class="exopite-sof__export" readonly>' . esc_html( $encoded_options ) . '</textarea>';
				echo '<a href="' . esc_url( $export ) . '" class="button button-primary" target="_blank">' . esc_attr__( 'Download Backup', 'rest-api-to-miniprogram' ) . '</a>';

				echo '<hr />';
				echo '<small class="exopite-sof-info--small exopite-sof-info--warning">' . esc_attr__( 'Please be sure for reset all of framework options.', 'rest-api-to-miniprogram' ) . '</small>';
				echo '<a href="#" class="button button-warning exopite-sof-reset-js" data-confirm="' . esc_attr__( 'Are you sure, you want to reset all options?', 'rest-api-to-miniprogram' ) . '">' . esc_attr__( 'Reset All Options', 'rest-api-to-miniprogram' ) . '</a>';

				echo '<div class="exopite-sof--data" data-admin="' . esc_url( admin_url( 'admin-ajax.php' ) ) . '" data-unique="' . esc_attr( $this->unique ) . '" data-wpnonce="' . esc_attr( $nonce ) . '"></div>';

			} else {

				echo 'This item only available in menu!<br>';

			}

			echo esc_html( $this->element_after() );

		}

		/**
		 * Encode string for backup options
		 */
		function encode_string( $option ) {
			return json_encode( $option );
			// return rtrim( strtr( call_user_func( 'base' . '64' . '_encode', addslashes( gzcompress( serialize( $option ), 9 ) ) ), '+/', '-_' ), '=' );
		}

		/**
		 * Decode string for backup options
		 */
		function decode_string( $option ) {
			// phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash
	// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	// phpcs:disable WordPress.Security.NonceVerification.Missing
	// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			return json_decode( $_POST['value'], true );			
			// phpcs:enable WordPress.Security.ValidatedSanitizedInput.MissingUnslash
	// phpcs:enable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	// phpcs:enable WordPress.Security.NonceVerification.Missing
	// phpcs:enable WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			// return unserialize( gzuncompress( stripslashes( call_user_func( 'base' . '64' . '_decode', rtrim( strtr( $option, '-_', '+/' ), '=' ) ) ) ) );
		}

		public static function enqueue( $args ) {

			/*
			 * https://sweetalert.js.org/guides/
			 */
			// wp_enqueue_script( 'sweetalert', '//unpkg.com/sweetalert/dist/sweetalert.min.js', false, '2.1.0', true );
			$resources = array(
				array(
					'name'       => 'sweetalert',
					'fn'         => 'sweetalert.min.js',
					'type'       => 'script',
					'dependency' => false,
					'version'    => '2.1.0',
					'attr'       => true,
				),
			);

			parent::do_enqueue( $resources, $args );

		}

	}

}
