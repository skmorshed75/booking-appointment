<?php
/**
 * Uses JetEngine meta component to process meta
 */
namespace JET_APB\Admin;

use JET_APB\Plugin;
use JET_APB\Time_Slots;

class Providers_Meta extends Base_Vue_Meta_Box {

	/**
	 * Default settings array
	 *
	 * @var array
	 */
	private $defaults;

	/**
	 * Class constructor
	 */
	public function __construct() {
		parent::__construct( Plugin::instance()->settings->get( 'providers_cpt' ) );

		$this->defaults = [
			'custom_schedule' =>[
				'use_custom_schedule' => false,
				'default_slot'        => 1800,
				'buffer_before'       => 0,
				'buffer_after'        => 0,
				'working_hours'       =>[
					'monday'    => [],
					'tuesday'   => [],
					'wednesday' => [],
					'thursday'  => [],
					'friday'    => [],
					'saturday'  => [],
					'sunday'    => [],
				],
				'days_off'            => [],
				'working_days'        => [],
				'weekdays'   => [
					'monday'    => esc_html__( 'Monday', 'jet-appointments-booking' ),
					'tuesday'   => esc_html__( 'Tuesday', 'jet-appointments-booking' ),
					'wednesday' => esc_html__( 'Wednesday', 'jet-appointments-booking' ),
					'thursday'  => esc_html__( 'Thursday', 'jet-appointments-booking' ),
					'friday'    => esc_html__( 'Friday', 'jet-appointments-booking' ),
					'saturday'  => esc_html__( 'Saturday', 'jet-appointments-booking' ),
					'sunday'    => esc_html__( 'Sunday', 'jet-appointments-booking' ),
				],
				'time_slots'          => Time_Slots::prepare_slots_for_js(
					Time_Slots::generate_slots()
				),
			]
		];

		add_filter( 'jet-engine/relations/registered-relation', [ $this, 'register_providers_relation' ] );
	}

	/**
	 * Regsiter services specific metabox on all services registration
	 *
	 * @param  [type] $meta_boxes_manager [description]
	 * @return [type]                     [description]
	 */
	public function register_providers_relation( $relations ){
		$services_cpt  = Plugin::instance()->settings->get( 'services_cpt' );
		$providers_cpt = Plugin::instance()->settings->get( 'providers_cpt' );

		if ( ! $services_cpt ) {
			return;
		}

		if ( empty( $relations ) ) {
			$relations = [];
		}

		$relations['item-0'] = [
			'name'                => 'services to providers',
			'post_type_1'         => $services_cpt,
			'post_type_2'         => $providers_cpt,
			'type'                => 'many_to_many',
			'post_type_1_control' => 1,
			'post_type_2_control' => 1,
			'parent_relation'     => '',
			'id'                  => 'item-0',
		];

		return $relations;
	}

	/**
	 * Return default value.
	 *
	 * @return [array] [description]
	 */
	public function meta_box_default_value() {
		return $this->defaults;
	}

	/**
	 * Add a meta box to post.
	 */
	public function add_meta_box(){
		if ( ! $this->is_cpt_page() ) {
			return;
		}

		add_meta_box(
			'schedule_meta_box',
			esc_html__( 'Custom Schedule', 'jet-appointments-booking' ),
			[ $this, 'custom_schedule_meta_box_callback' ],
			[ $this->current_screen_slug ],
			'normal',
			'low'
		);
	}

	/**
	 * Require metabox html.
	 */
	public function custom_schedule_meta_box_callback(){
		require_once( JET_APB_PATH .'templates/admin/custom-schedule-meta-box.php' );
	}

	/**
	 * Page components templates
	 *
	 * @return [type] [description]
	 */
	public function vue_templates() {
		return [
			[
				'dir'  => 'jet-apb-settings',
				'file' => 'settings-working-hours',
			]
		];
	}

	/**
	 * Parsed data before written to the database and after get from the database.
	 *
	 * @return [array] [description]
	 */
	public function parse_settings( $settings ){
		$new_settings = $settings['custom_schedule'];

		foreach ( $new_settings as $setting => $value ) {

			switch ( $setting ) {
				case 'working_hours':
					$new_settings[ $setting ] = $this->sanitize_working_hours( $value );
					break;

				case 'working_days':
				case 'days_off':
					if ( ! is_array( $value ) ) {
						$new_settings[ $setting ] = false;
					}
					break;

				case 'use_custom_schedule':
					$new_settings[ $setting ] = filter_var( $value, FILTER_VALIDATE_BOOLEAN );

					break;

				case 'default_slot':
				case 'buffer_before':
				case 'buffer_after':
					$new_settings[ $setting ] = intval( $value );

					break;

				default:
					$new_settings[ $setting ] = $value;
				break;
			}
		}


		$placeholder = [
			'value' => '-1',
			'label' =>  esc_html__( 'Select Time', 'jet-appointments-booking' )
		];

		$new_settings[ 'slot_option' ]   = Plugin::instance()->tools->get_time_slots( false );
		array_unshift( $new_settings[ 'slot_option' ], $placeholder );

		$new_settings[ 'buffer_option' ] = Plugin::instance()->tools->get_interval_time_slots( false );
		array_unshift( $new_settings[ 'buffer_option' ], $placeholder );


		$settings[ 'custom_schedule' ] = $new_settings;

		return $settings;
	}

	/**
	 * Sanitize updated working hours
	 *
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function sanitize_working_hours( $input ) {
		$defaults  = $this->defaults['custom_schedule']['working_hours'];
		$sanitized = array();

		foreach ( $defaults as $key => $default_value ) {
			$sanitized[ $key ] = ! empty( $input[ $key ] ) ? $input[ $key ] : $default_value;
		}

		return $sanitized;
	}

	/**
	 * Include scripts and styles
	 */
	public function assets() {
		if ( ! $this->is_cpt_page() ) {
			return;
		}

		//Enqueue script
		wp_enqueue_script( 'cx-vue' );
		wp_enqueue_script( 'momentjs' );
		wp_enqueue_script( 'vuejs-datepicker' );
		wp_enqueue_script( 'jet-apb-working-hours' );

		//Enqueue style
		wp_enqueue_style( 'jet-apb-working-hours' );
		wp_enqueue_style( 'jet-appointments-booking-admin' );
	}

}
