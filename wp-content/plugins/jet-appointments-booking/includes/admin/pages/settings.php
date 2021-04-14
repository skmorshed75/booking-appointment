<?php
namespace JET_APB\Admin\Pages;

use JET_APB\Admin\Helpers\Page_Config;
use JET_APB\Plugin;
use JET_APB\Time_Slots;

/**
 * Base dashboard page
 */
class Settings extends Base {

	/**
	 * Page slug
	 * @return string
	 */
	public function slug() {
		return 'jet-apb-settings';
	}

	/**
	 * Page title
	 * @return string
	 */
	public function title() {
		return __( 'Settings', 'jet-appointments-booking' );
	}

	/**
	 * Is settings page
	 *
	 * @return boolean [description]
	 */
	public function is_settings_page() {
		return true;
	}

	/**
	 * Page render funciton
	 * @return void
	 */
	public function render() {
		echo '<div class="wrap"><div id="jet-apb-settings-page"></div></div>';
	}

	/**
	 * Return  page config object
	 *
	 * @return [type] [description]
	 */
	public function page_config() {

		$settings = Plugin::instance()->settings->get_all();

		if ( empty( $settings['providers_cpt'] ) ) {
			$settings['providers_cpt'] = '';
		}

		if ( ! $settings['buffer_option'] ) {
			$settings['buffer_option'] = Plugin::instance()->tools->get_interval_time_slots( false );
		}

		if ( ! $settings['slot_option'] ) {
			$settings['slot_option'] = Plugin::instance()->tools->get_time_slots( false );
		}

		return new Page_Config(
			$this->slug(),
			array(
				'settings'   => $settings,
				'post_types' => \Jet_Engine_Tools::get_post_types_for_js( array(
					'value' => '',
					'label' => __( 'Select...', 'jet-appointments-booking' ),
				) ),
				'weekdays'   => array(
					'monday'    => __( 'Monday', 'jet-appointments-booking' ),
					'tuesday'   => __( 'Tuesday', 'jet-appointments-booking' ),
					'wednesday' => __( 'Wednesday', 'jet-appointments-booking' ),
					'thursday'  => __( 'Thursday', 'jet-appointments-booking' ),
					'friday'    => __( 'Friday', 'jet-appointments-booking' ),
					'saturday'  => __( 'Saturday', 'jet-appointments-booking' ),
					'sunday'    => __( 'Sunday', 'jet-appointments-booking' ),
				),
				'time_slots' => Time_Slots::prepare_slots_for_js(
					Time_Slots::generate_slots()
				),
			)
		);
	}

	/**
	 * Page specific assets
	 *
	 * @return [type] [description]
	 */
	public function assets() {
		//Enqueue script
		wp_enqueue_script( 'momentjs' );
		wp_enqueue_script( 'vuejs-datepicker' );
		wp_enqueue_script( 'jet-apb-working-hours' );
		wp_enqueue_script( 'jet-apb-settings' );

		//Enqueue style
		wp_enqueue_style( 'jet-apb-working-hours' );
		wp_enqueue_style( 'jet-apb-settings' );
	}

	/**
	 * Page components templates
	 *
	 * @return [type] [description]
	 */
	public function vue_templates() {
		return array(
			'settings',
			'settings-general',
			'settings-advanced',
			'settings-labels',
			'settings-working-hours'
		);
	}

}
