<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Image
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
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_image' ) ) {

	class Exopite_Simple_Options_Framework_Field_image extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {
			parent::__construct( $field, $value, $unique, $config );
		}

		/**
		 * Get an attachment ID given a URL.
		 *
		 * @param string $url
		 *
		 * @return int Attachment ID on success, 0 on failure
		 */
		function get_attachment_id( $url ) {
			$attachment_id = 0;
			$dir           = wp_upload_dir();

			// To handle relative urls
			if ( substr( $url, 0, strlen( '/' ) ) === '/' ) {

				$url = get_site_url() . $url;
			}
			if ( false !== strpos( $url, $dir['baseurl'] . '/' ) ) { // Is URL in uploads directory?

				$file       = basename( $url );
				$query_args = array(
					'post_type'   => 'attachment',
					'post_status' => 'inherit',
					'fields'      => 'ids',
					'meta_query'  => array(
						array(
							'value'   => $file,
							'compare' => 'LIKE',
							'key'     => '_wp_attachment_metadata',
						),
					)
				);
				$query      = new WP_Query( $query_args );
				if ( $query->have_posts() ) {
					foreach ( $query->posts as $post_id ) {
						$meta                = wp_get_attachment_metadata( $post_id );
						$original_file       = basename( $meta['file'] );
						$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
						if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
							$attachment_id = $post_id;
							break;
						}
					}
				}
			}

			return $attachment_id;
		}

		public function output() {

			/**
			 * Open WordPress Media Uploader with PHP and JavaScript
			 *
			 * @link https://rudrastyh.com/wordpress/customizable-media-uploader.html
			 */

			echo $this->element_before();

			$preview = '';
			$value   = $this->element_value();
			$add     = ( ! empty( $this->field['add_title'] ) ) ? $this->field['add_title'] : esc_attr__( '选择图片', 'exopite-sof' );
			$hidden  = ( empty( $value ) ) ? ' hidden' : '';
			$classes = ( isset( $this->field['class'] ) ) ? implode( ' ', explode( ' ', $this->field['class'] ) ) : '';
			$preview='';
			if ( ! empty( $value ) ) {
				$attachment = wp_get_attachment_image_src( $this->get_attachment_id( $value ), 'thumbnail' );
				if ( ! empty( $attachment ) ) {
					$preview = $attachment[0];
				}
			}

			echo '<div class="exopite-sof-media exopite-sof-image ' . $classes . '" ' . $this->element_attributes() . '>';
			echo '<div class="exopite-sof-image-preview' . $hidden . '">';
			if($preview !='')
			{
				echo '<div class="exopite-sof-image-inner"><i class="fa fa-times exopite-sof-image-remove"></i><img src="' . $preview . '" alt="preview" /></div>';
			}
			echo '</div>';

			echo '<input type="text" name="' . $this->element_name() . '" value="' . $this->element_value() . '">';
			echo '<a href="#" class="button button-primary exopite-sof-button">' . $add . '</a>';
			echo '</div>';
			echo $this->element_after();

		}

		public static function enqueue( $args ) {

			wp_enqueue_media();

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
