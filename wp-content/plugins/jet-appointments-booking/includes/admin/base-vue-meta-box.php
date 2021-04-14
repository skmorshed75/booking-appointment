<?php
/**
 * Uses Vue component
 */
namespace JET_APB\Admin;

use JET_APB\Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Base_Vue_Meta_Box Class.
 *
 * @since 1.0.0
 */
class Base_Vue_Meta_Box {

	/**
	 * Services custom post type.
	 */
	public $current_screen_slug;

	/**
	 * Class constructor
	 */
	public function __construct( $current_screen_slug = '' ) {
		$this->current_screen_slug  = $current_screen_slug;

		if( ! $current_screen_slug ){
			return;
		}

		add_action( 'add_meta_boxes', [ $this, 'add_meta_box' ], 10 );

		add_action( 'admin_enqueue_scripts', [ $this, 'vue_assets' ], 10 );
		add_action( 'admin_enqueue_scripts', [ $this, 'assets' ], 11 );
		add_action( 'admin_enqueue_scripts', [ $this, 'meta_box_assets' ], 12 );

		add_action( 'admin_footer', [ $this, 'render_vue_templates' ] );

		add_action( 'wp_ajax_jet_apb_save_post_meta', [ $this, 'save_post_meta' ] );
		add_action( 'wp_ajax_nopriv__jet_apb_save_post_meta',  [ $this, 'save_post_meta' ] );
	}

	/**
	 * Class slug
	 * @return string
	 */
	public function slug(){
		return 'jet_apb_post_meta';
	}

	/**
	 * Checks the post page.
	 *
	 * @return boolean [description]
	 */
	public function is_cpt_page() {
		return is_admin() && function_exists( 'jet_engine' ) && get_current_screen()->id === $this->current_screen_slug;
	}

	/**
	 * Add a meta box to post.
	 */
	public function add_meta_box() {}

	/**
	 * Return default value.
	 *
	 * @return [array] [description]
	 */
	public function meta_box_default_value() {
		return [];
	}

	/**
	 * Return values from the database.
	 *
	 * @return [array] [description]
	 */
	public function meta_box_value(){
		$post_ID         = get_the_ID();
		$post_meta       = get_post_meta( $post_ID, 'jet_apb_post_meta', true );
		$post_meta       = is_array( $post_meta ) ? $post_meta : [] ;
		$post_meta['ID'] = $post_ID;

		return $post_meta;
	}

	/**
	 * Parsed data before written to the database and after get from the database.
	 *
	 * @return [array] [description]
	 */
	public function parse_settings( $settings ){
		return $settings;
	}

	/**
	 * Saves metadata to the database.
	 *
	 */
	public function save_post_meta(){

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Access denied', 'jet-appointments-booking' ),
			) );
		}

		$post_meta = ! empty( $_REQUEST['jet_apb_post_meta'] ) ? $_REQUEST['jet_apb_post_meta']: array();

		if ( empty( $post_meta ) || ! isset( $post_meta['ID'] ) ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Empty data or post ID not found!', 'jet-appointments-booking' ),
			) );
		}

		$result = update_post_meta( $post_meta['ID'] , 'jet_apb_post_meta' , $this->parse_settings( $post_meta ) );

		if( ! $result || is_wp_error( $result ) ){
			wp_send_json_error( [
				'message' => esc_html__( 'Failed to save data!', 'jet-appointments-booking' ),
			] );
		}

		wp_send_json_success( [
			'message' => esc_html__( 'Settings saved!', 'jet-appointments-booking' ),
		] );
	}

	/**
	 * Include scripts and styles
	 */
	public function assets() {}

	/**
	 * Include vue scripts.
	 */
	public function vue_assets() {
		if ( ! $this->is_cpt_page() ) {
			return;
		}

		$ui_data = jet_engine()->framework->get_included_module_data( 'cherry-x-vue-ui.php' );
		$ui      = new \CX_Vue_UI( $ui_data );
		$ui->enqueue_assets();

		$settings = wp_parse_args( $this->meta_box_value(), $this->meta_box_default_value() );
		$settings = $this->parse_settings( $settings );

		if ( ! empty( $settings ) ) {
			wp_localize_script( 'cx-vue', 'jetApbPostMeta', $settings );
		}
	}

	/**
	 * Include meta box scripts.
	 */
	public function meta_box_assets() {
		if ( ! $this->is_cpt_page() ) {
			return;
		}

		wp_enqueue_script(
			'jet_apb_post_meta_box',
			JET_APB_URL . 'assets/js/admin/meta-box.js',
			array( 'wp-api-fetch' ),
			JET_APB_VERSION,
			true
		);
	}

	/**
	 * Page components templates
	 *
	 * @return [type] [description]
	 */
	public function vue_templates() {
		return [];
	}

	/**
	 * Render vue templates
	 *
	 * @return [type] [description]
	 */
	public function render_vue_templates() {
		foreach ( $this->vue_templates() as $template ) {
			if ( is_array( $template ) ) {
				$this->render_vue_template( $template['file'], $template['dir'] );
			} else {
				$this->render_vue_template( $template );
			}
		}
	}

	/**
	 * Render vue template
	 *
	 * @return [type] [description]
	 */
	public function render_vue_template( $template, $path = null ) {

		if ( ! $path ) {
			$path = $this->slug();
		}

		$file = JET_APB_PATH . 'templates/admin/' . $path . '/' . $template . '.php';

		if ( ! is_readable( $file ) ) {
			return;
		}

		ob_start();
		include $file;
		$content = ob_get_clean();

		printf(
			'<script type="text/x-template" id="jet-apb-%1$s">%2$s</script>',
			$template,
			$content
		);
	}
}
