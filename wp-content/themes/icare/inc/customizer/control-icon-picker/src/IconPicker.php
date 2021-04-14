<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Customize API: IconPicker class
 *
 * @package WordPress
 * @subpackage Customize
 * @since 1.0.0
 */

namespace WPTRT\Customize\Control;

use WP_Customize_Control;

/**
 * Customize Color Control class.
 *
 * @since 1.0.0
 *
 * @see WP_Customize_Control
 */
class IconPicker extends WP_Customize_Control {

	/**
	 * Type.
	 *
	 * @access public
	 * @since 1.0.0
	 * @var string
	 */
	public $type = 'icon-picker';

	/**
	 * Enqueue scripts/styles for the color picker.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue() {
		$control_root_url = str_replace(
			wp_normalize_path( untrailingslashit( WP_CONTENT_DIR ) ),
			untrailingslashit( content_url() ),
			dirname( __DIR__ )
		);

		/**
		 * Filters the URL for the scripts.
		 *
		 * @since 1.0.0
		 * @param string $control_root_url The URL to the root folder of the package.
		 * @return string
		 */
		$control_root_url = apply_filters( 'wptrt_icon_picker_url', $control_root_url );

		wp_enqueue_script(
			'wptrt-control-icon-picker',
			$control_root_url . '/dist/main.js',
			// We're including wp-color-picker for localized strings, nothing more.
			[ 'customize-controls', 'wp-element', 'jquery', 'customize-base'], // phpcs:ignore Generic.Arrays.DisallowShortArraySyntax
			'1.1',
			true
		);

		wp_enqueue_style('font-awesome', get_template_directory_uri() . "/assets/css/all.min.css");

		wp_enqueue_style(
			'fonticonpicker.base-theme.react',
			$control_root_url . '/dist/fonticonpicker.base-theme.react.css',
			// We're including wp-color-picker for localized strings, nothing more.
			array(), // phpcs:ignore Generic.Arrays.DisallowShortArraySyntax
			'1.1'
		);

		wp_enqueue_style(
			'fonticonpicker.material-theme.react',
			$control_root_url . '/dist/fonticonpicker.material-theme.react.css',
			// We're including wp-color-picker for localized strings, nothing more.
			array(), // phpcs:ignore Generic.Arrays.DisallowShortArraySyntax
			'1.1'
		);
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();
		$this->json['icons'] = icare_font_awesome_icon_array();
	}

	/**
	 * Empty JS template.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function content_template() {}
}
