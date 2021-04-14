<?php
namespace JET_APB\Admin\Pages;

use JET_APB\Plugin;
use JET_APB\Time_Slots;
use JET_APB\Admin\Helpers\Page_Config;

/**
 * Base dashboard page
 */
class Set_Up extends Base {

	/**
	 * Page slug
	 *
	 * @return string
	 */
	public function slug() {
		return 'jet-apb-set-up';
	}

	/**
	 * Page title
	 *
	 * @return string
	 */
	public function title() {
		return __( 'Set Up', 'jet-appointments-booking' );
	}

	/**
	 * Page render funciton
	 *
	 * @return void
	 */
	public function render() {
		echo '<div id="jet-apb-set-up-page"></div>';
	}

	/**
	 * Set to true to hide page from admin menu
	 * @return boolean [description]
	 */
	public function is_hidden() {
		return Plugin::instance()->settings->get( 'hide_setup' );
	}

	/**
	 * Return  page config object
	 *
	 * @return [type] [description]
	 */
	public function page_config() {
		return new Page_Config(
			$this->slug(),
			array(
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
	 * Define that is setup page
	 *
	 * @return boolean [description]
	 */
	public function is_setup_page() {
		return true;
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
		wp_enqueue_script( 'jet-apb-set-up' );

		//Enqueue style
		wp_enqueue_style( 'jet-apb-working-hours' );
		wp_enqueue_style( 'jet-apb-set-up' );
	}

	/**
	 * Page components templates
	 *
	 * @return [type] [description]
	 */
	public function vue_templates() {
		return array(
			'set-up',
			array(
				'dir'  => 'jet-apb-settings',
				'file' => 'settings-working-hours'
			),
		);
	}

}
