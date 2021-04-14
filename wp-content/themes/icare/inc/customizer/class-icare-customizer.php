<?php
/**
 * iCare Theme Customizer.
 *
 * @package iCare
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
if (!defined('ABSPATH')) {
	exit;
}
require_once dirname(__FILE__) . '/customizer-button/class-customize.php';
use \WPTRT\Customize\Control\ColorAlpha;
use \WPTRT\Customize\Control\IconPicker;

add_filter('wptrt_color_picker_alpha_url', function () {
	return get_template_directory_uri() . '/inc/customizer/control-color-alpha';
});
add_filter('wptrt_icon_picker_url', function () {
	return get_template_directory_uri() . '/inc/customizer/control-icon-picker';
});
if (!class_exists('iCare_Customizer')):

	/**
	 * The icare Customizer class
	 */
	class iCare_Customizer {
		public static $theme_slug = 'icare_';
		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action('customize_register', array($this, 'icare_customize_register'), 10);
			add_action('wp_enqueue_scripts', array($this, 'icare_add_customizer_css'), 130);

			add_action('customize_controls_print_styles', array($this, 'icare_customizer_custom_control_css'));
			add_action('customize_register', array($this, 'icare_edit_default_customizer_settings'), 99);
			add_action('init', array($this, 'icare_default_theme_mod_values'), 10);

			add_action('after_switch_theme', array($this, 'icare_set_style_theme_mods'));
			add_action('customize_save_after', array($this, 'icare_set_style_theme_mods'));
		}
		/**
		 * Returns an array of the desired default iCare Options
		 *
		 * @return array
		 */
		public static function icare_get_default_setting_values() {
			$default = json_encode(array("about", "service", "funfacts", "team", "features", "extra", "posts", "clients", "calltoaction"));
			return apply_filters('icare_setting_default_values', $args = array(
				self::$theme_slug . 'show_topbar'                => 'on',
				self::$theme_slug . 'home_reorder'               => $default,
				self::$theme_slug . 'cont_phone'                 => '+99-999 9999',
				self::$theme_slug . 'cont_email'                 => 'youremail@yourdomain.com',
				self::$theme_slug . 'cont_more_info'             => 'Mon-Fri 8:00 to 2:00',
				self::$theme_slug . 'cont_more_info_icon'        => 'far fa-clock',
				self::$theme_slug . 'home_blog_title'            => esc_html__('Latest Posts', 'icare'),
				self::$theme_slug . 'show_cta'                   => 'on',

				self::$theme_slug . 'calltoaction_title'         => esc_html__('Need Help? Call Us. +91-992920297', 'icare'),
				self::$theme_slug . 'calltoaction_btn1_text'     => esc_html__('Purchase Now', 'icare'),
				self::$theme_slug . 'calltoaction_btn1_url'      => '#',
				self::$theme_slug . 'calltoaction_btn2_text'     => esc_html__('Learn More', 'icare'),
				self::$theme_slug . 'calltoaction_btn2_url'      => '#',
				self::$theme_slug . 'fixed_header'               => 'on',
				self::$theme_slug . 'header_background_color'    => '',

				self::$theme_slug . 'crumb_and_title'            => 'allow_both',
				self::$theme_slug . 'crumb_title_align'          => 'left',
				self::$theme_slug . 'br_s_p_t'                   => 60,
				self::$theme_slug . 'br_s_p_b'                   => 60,
				self::$theme_slug . 'show_hero'                  => 'on',
				self::$theme_slug . 'hero_image'                 => get_template_directory_uri() . '/assets/images/icare-nurse-2141808_1280.jpg',
				self::$theme_slug . 'hero_section_bg_color'      => 'rgba(255,255,255,0.3)',
				self::$theme_slug . 'hero_title'                 => esc_html__('Need Consultant?', 'icare'),
				self::$theme_slug . 'hero_desc'                  => esc_html__('Make an appointment', 'icare'),
				self::$theme_slug . 'show_services'              => 'off',
				self::$theme_slug . 'service_head'               => esc_html__('Our Services', 'icare'),
				self::$theme_slug . 'service_readmore1'          => esc_html__('Read More &rarr;', 'icare'),
				self::$theme_slug . 'service_readmore2'          => esc_html__('Read More &rarr;', 'icare'),
				self::$theme_slug . 'service_readmore3'          => esc_html__('Read More &rarr;', 'icare'),
				self::$theme_slug . 'service_readmore4'          => esc_html__('Read More &rarr;', 'icare'),
				self::$theme_slug . 'service_readmore5'          => esc_html__('Read More &rarr;', 'icare'),
				self::$theme_slug . 'service_readmore6'          => esc_html__('Read More &rarr;', 'icare'),
				self::$theme_slug . 'show_aboutus'               => 'off',
				self::$theme_slug . 'show_features'              => 'off',
				self::$theme_slug . 'show_client_logo'           => 'off',
				self::$theme_slug . 'show_funfact'               => 'off',
				self::$theme_slug . 'show_team'                  => 'off',
				self::$theme_slug . 'show_extra'                 => 'off',
				self::$theme_slug . 'extra_color'                => '#f7f7f7',
				self::$theme_slug . 'show_post'                  => 'on',
				self::$theme_slug . 'show_footer_widget'         => 'on',
				self::$theme_slug . 'footer_widget_column'       => 3,
				self::$theme_slug . 'footer_background_color'    => '',
				self::$theme_slug . 'footer_heading_color'       => '#ffffff',
				self::$theme_slug . 'footer_text_color'          => '',
				self::$theme_slug . 'footer_copyright_text'      => esc_html__('iCare Theme Developed By | ', 'icare'),
				self::$theme_slug . 'footer_copyright_link_text' => esc_html__('WebHunt Infotech', 'icare'),
				self::$theme_slug . 'footer_copyright_link'      => 'https://www.webhuntinfotech.com',
			));
		}

		/**
		 * Adds a value to each Shopify setting if one isn't already present.
		 *
		 * @uses icare_get_default_setting_values()
		 */
		public function icare_default_theme_mod_values() {
			foreach (self::icare_get_default_setting_values() as $mod => $val) {
				add_filter('theme_mod_' . $mod, array($this, 'icare_get_theme_mod_value'), 10);
			}
		}

		/**
		 * Get theme mod value.
		 *
		 * @param string $value
		 * @return string
		 */
		public function icare_get_theme_mod_value($value) {
			$key            = substr(current_filter(), 10);
			$set_theme_mods = get_theme_mods();
			if (isset($set_theme_mods[$key])) {
				return $value;
			}

			$values = $this->icare_get_default_setting_values();

			return isset($values[$key]) ? $values[$key] : $value;
		}

		/**
		 * Set Customizer setting defaults.
		 * These defaults need to be applied separately as child themes can filter icare_setting_default_values
		 *
		 * @param  array $wp_customize the Customizer object.
		 * @uses   icare_get_default_setting_values()
		 */
		public function icare_edit_default_customizer_settings($wp_customize) {
			foreach (self::icare_get_default_setting_values() as $mod => $val) {
				$wp_customize->get_setting($mod)->default = $val;
			}
		}

		/**
		 * Add postMessage support for site title and description for the Theme Customizer along with several other settings.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 * @since  1.0.0
		 */
		public function icare_customize_register($wp_customize) {
			/**
			 * Custom controls
			 */
			require_once dirname(__FILE__) . '/class-icare-customizer-control-radio-image.php';
			require_once dirname(__FILE__) . '/class-icare-customizer-control-arbitrary.php';
			require_once dirname(__FILE__) . '/class-icare-customizer-control-pro.php';
			require_once dirname(__FILE__) . '/control-color-alpha/src/ColorAlpha.php';
			require_once dirname(__FILE__) . '/control-icon-picker/src/IconPicker.php';
			require_once dirname(__FILE__) . '/control-range-value/RangeControl.php';

			add_action('customize_register', function ($wp_customize) {
				$wp_customize->register_control_type('\WPTRT\Customize\Control\ColorAlpha');
				$wp_customize->register_control_type('\WPTRT\Customize\Control\IconPicker');
			});
			
			$categories = get_categories();
			$cats       = array();
			foreach ($categories as $category) {
				$cats[$category->term_id] = $category->name;
			}
			//Add Panel
			$theme_slug = self::$theme_slug;
			// genera panel
			$wp_customize->add_panel(
				'icare_general_settings_panel',
				array(
					'title'    => __('General Settings', 'icare'),
					'priority' => 10,
				)
			);
			// Home panel
			$wp_customize->add_panel(
				'icare_home_panel',
				array(
					'title'    => __('Home Sections', 'icare'),
					'priority' => 20,
				)
			);

			$section_controls = array(
				'icare_color'           => array(
					'title'       => esc_html__('Color', 'icare'),
					'description' => esc_html__('Set Primary Color of theme.', 'icare'),
					'panel'       => 'general_settings_panel',
					'priority'    => 10,
					'fields'      => array(
						'primary_color' => array(
							'title'       => esc_html__('Primary Color', 'icare'),
							'description' => '',
							'field_type'  => 'color',
							'sanitize'    => 'sanitize_hex_color',

						),
					),
				),
				'topbar_contact'        => array(
					'title'       => esc_html__('Topbar Options', 'icare'),
					'description' => esc_html__('Show Contact Information in topbar', 'icare'),
					'panel'       => 'general_settings_panel',
					'priority'    => 24,
					'fields'      => array(
						'show_topbar'            => array(
							'title'       => esc_html__('Show Topbar', 'icare'),
							'description' => '',
							'field_type'   => 'Icare_Switch_Control',
							'on_off_label' => array(
								'on'  => __('Yes', 'icare'),
								'off' => __('No', 'icare'),
							),

						),
						//topbar color optiond
						'topbar_bg_color'        => array(
							'title'       => esc_html__('Topbar Background Color', 'icare'),
							'description' => '',
							'field_type'  => 'color',
							'sanitize'    => 'sanitize_hex_color',
						),

						'topbar_menu_heading'    => array(
							'title'       => esc_html__('Top bar Menu', 'icare'),
							'description' => '',
							'field_type'  => 'Icare_Customize_Heading',
							'sanitize'    => 'icare_sanitize_text',
						),

						// topbar social icon or menu
						'topbar_social_or_menu'  => array(
							'title'       => esc_html__('Show social Media Links or a Simple Menu', 'icare'),
							'description' => __('These menu can be set in the <a class="linkToControl" id="nav_menu_locations[topbar-menu]" href="#">Menu Section</a>.', 'icare'),
							'field_type'  => 'select',
							'sanitize'    => 'sanitize_text_field',
							'choices'     => array(
								'social-links' => esc_html__('Social Links Menu', 'icare'),
								'simple-links' => esc_html__('Simple Menu', 'icare'),
								''             => esc_html__('Blank', 'icare'),
							),
						),

						'topbar_contact_heading' => array(
							'title'       => esc_html__('Contact Info ', 'icare'),
							'description' => '',
							'field_type'  => 'Icare_Customize_Heading',
							'sanitize'    => 'icare_sanitize_text',
						),

						'cont_phone'             => array(
							'title'       => esc_html__('Phone', 'icare'),
							'description' => '',
							'field_type'  => 'text',
							'sanitize'    => 'sanitize_text_field',
						),

						'cont_email'             => array(
							'title'       => esc_html__('Email', 'icare'),
							'description' => '',
							'field_type'  => 'text',
							'sanitize'    => 'sanitize_email',
						),

						'cont_more_info'         => array(
							'title'       => esc_html__('Extra Info', 'icare'),
							'description' => '',
							'field_type'  => 'text',
							'sanitize'    => 'sanitize_text_field',
						),
						'cont_more_info_icon'    => array(
							'title'       => esc_html__('Extra Info Icon', 'icare'),
							'description' => '',
							'field_type'  => 'IconPicker',
							'sanitize'    => 'sanitize_text_field',
						),

					),
				),
				'slider'                => array(
					'title'       => esc_html__('Hero Section', 'icare'),
					'description' => '',
					'panel'       => 'home_panel',
					'priority'    => 10,
					'fields'      => array(
						'show_hero'             => array(
							'title'        => esc_html__('Show Hero Section', 'icare'),
							'description'  => '',
							'field_type'   => 'Icare_Switch_Control',
							'on_off_label' => array(
								'on'  => __('Yes', 'icare'),
								'off' => __('No', 'icare'),
							),

						),
						'hero_image'            => array(
							'title'       => esc_html__('Hero Image', 'icare'),
							'description' => '',
							'field_type'  => 'image',
							'sanitize'    => 'esc_url_raw',

						),
						'hero_section_bg_color' => array(
							'title'       => esc_html__('Background Color', 'icare'),
							'description' => '',
							'field_type'  => 'ColorAlpha',
							'default'     => 'rgba(255,255,255,0.7)',
							'sanitize'    => 'icare_color_string_sanitization',
						),
						'hero_title'            => array(
							'title'       => esc_html__('Hero Title', 'icare'),
							'description' => '',
							'field_type'  => 'text',
							'sanitize'    => 'sanitize_text_field',
							'transport'   => 'postMessage',
						),
						'hero_desc'             => array(
							'title'       => esc_html__('Hero Description', 'icare'),
							'description' => '',
							'field_type'  => 'text',
							'sanitize'    => 'sanitize_text_field',
							'transport'   => 'postMessage',
						),
						'slider_shortcode'      => array(
							'title'       => esc_html__('Or put slider shortcode here', 'icare'),
							'description' => '',
							'field_type'  => 'text',
							'sanitize'    => 'sanitize_text_field',
						),
					),
				),
				//about us section
				'icare_about'           => array(
					'title'       => esc_html__('About Us Section', 'icare'),
					'description' => '',
					'panel'       => 'home_panel',
					'priority'    => 11,
					'fields'      => array(
						'show_aboutus' => array(
							'title'        => esc_html__('Enable Section', 'icare'),
							'description'  => '',
							'field_type'   => 'Icare_Switch_Control',
							'on_off_label' => array(
								'on'  => __('Yes', 'icare'),
								'off' => __('No', 'icare'),
							),

						),
						'aboutus_page' => array(
							'title'       => esc_html__('Select a page', 'icare'),
							'description' => '',
							'field_type'  => 'dropdown-pages',
							'sanitize'    => 'absint',

						),
					),
				),
				//Feature section
				'icare_features'        => array(
					'title'       => esc_html__('Features Section', 'icare'),
					'description' => '',
					'panel'       => 'home_panel',
					'priority'    => 13,
					'fields'      => array(
						'show_features'            => array(
							'title'        => esc_html__('Enable Section', 'icare'),
							'description'  => '',
							'field_type'   => 'Icare_Switch_Control',
							'on_off_label' => array(
								'on'  => __('Yes', 'icare'),
								'off' => __('No', 'icare'),
							),

						),
						'feature_section_heading'  => array(
							'title'       => esc_html__('Title & Description', 'icare'),
							'description' => '',
							'field_type'  => 'Icare_Customize_Heading',
							'sanitize'    => 'icare_sanitize_text',
						),
						'feature_head'             => array(
							'title'       => esc_html__('Title', 'icare'),
							'description' => '',
							'field_type'  => 'text',
							'sanitize'    => 'sanitize_text_field',
							'transport'   => 'postMessage',
						),
						'feature_desc'             => array(
							'title'       => esc_html__('Description', 'icare'),
							'description' => '',
							'field_type'  => 'textarea',
							'sanitize'    => 'icare_sanitize_text',
							'transport'   => 'postMessage',
						),
						'feature_section_bg_img'   => array(
							'title'       => esc_html__('Background Image', 'icare'),
							'description' => '',
							'field_type'  => 'Icare_Customize_Heading',
							'sanitize'    => 'icare_sanitize_text',
						),

						'feature_bg_img'           => array(
							'title'       => '',
							'description' => '',
							'field_type'  => 'image',
							'sanitize'    => 'esc_url_raw',
						),
					),
				),
				//services
				'services'              => array(
					'title'       => esc_html__('Service Section', 'icare'),
					'description' => '',
					'panel'       => 'home_panel',
					'priority'    => 12,
					'fields'      => array(
						'show_services'           => array(
							'title'        => esc_html__('Enable Section', 'icare'),
							'description'  => '',
							'field_type'   => 'Icare_Switch_Control',
							'on_off_label' => array(
								'on'  => __('Yes', 'icare'),
								'off' => __('No', 'icare'),
							),

						),
						'service_section_heading' => array(
							'title'       => esc_html__('Section Title & Sub Title', 'icare'),
							'description' => '',
							'field_type'  => 'Icare_Customize_Heading',
							'sanitize'    => 'icare_sanitize_text',
						),

						'service_head'            => array(
							'title'       => esc_html__('Service Heading', 'icare'),
							'description' => '',
							'field_type'  => 'text',
							'sanitize'    => 'sanitize_text_field',
							'transport'   => 'postMessage',
						),
						'service_desc'            => array(
							'title'       => esc_html__('Service Description', 'icare'),
							'description' => '',
							'field_type'  => 'textarea',
							'sanitize'    => 'icare_sanitize_text',
							'transport'   => 'postMessage',
						),
					),
				),
				//Team section
				'icare_team'            => array(
					'title'       => esc_html__('Team Section', 'icare'),
					'description' => '',
					'panel'       => 'home_panel',
					'priority'    => 14,
					'fields'      => array(
						'show_team'            => array(
							'title'        => esc_html__('Enable Section', 'icare'),
							'description'  => '',
							'field_type'   => 'Icare_Switch_Control',
							'on_off_label' => array(
								'on'  => __('Yes', 'icare'),
								'off' => __('No', 'icare'),
							),

						),
						'team_section_heading' => array(
							'title'       => esc_html__('Title & Description', 'icare'),
							'description' => '',
							'field_type'  => 'Icare_Customize_Heading',
							'sanitize'    => 'icare_sanitize_text',
						),
						'team_head'            => array(
							'title'       => esc_html__('Title', 'icare'),
							'description' => '',
							'field_type'  => 'text',
							'sanitize'    => 'sanitize_text_field',
							'transport'   => 'postMessage',
						),
						'team_desc'            => array(
							'title'       => esc_html__('Description', 'icare'),
							'description' => '',
							'field_type'  => 'textarea',
							'sanitize'    => 'icare_sanitize_text',
							'transport'   => 'postMessage',
						),

					),
				),
				//Fun facts
				'icare_counter_section' => array(
					'title'       => esc_html__('Fun Fact Section', 'icare'),
					'description' => '',
					'panel'       => 'home_panel',
					'priority'    => 14,
					'fields'      => array(
						'show_funfact'            => array(
							'title'        => esc_html__('Enable Section', 'icare'),
							'description'  => '',
							'field_type'   => 'Icare_Switch_Control',
							'on_off_label' => array(
								'on'  => __('Yes', 'icare'),
								'off' => __('No', 'icare'),
							),

						),
						'funfact_section_heading' => array(
							'title'       => esc_html__('Background Image', 'icare'),
							'description' => '',
							'field_type'  => 'Icare_Customize_Heading',
							'sanitize'    => 'icare_sanitize_text',
						),
						'funfact_bg'              => array(
							'title'       => esc_html__('Background Image', 'icare'),
							'description' => __('Recommended Image Size: 1800X400px', 'icare'),
							'field_type'  => 'image',
							'default'     => get_template_directory_uri() . '/assets/images/banner.jpg',
							'sanitize'    => 'esc_url',
						),

					),
				),
				// home extra 1
				'home_extra'            => array(
					'title'       => esc_html__('Home Extra Section', 'icare'),
					'description' => '',
					'panel'       => 'home_panel',
					'priority'    => 14,
					'fields'      => array(
						'show_extra'       => array(
							'title'        => esc_html__('Enable this Section', 'icare'),
							'description'  => '',
							'field_type'   => 'Icare_Switch_Control',
							'on_off_label' => array(
								'on'  => __('Yes', 'icare'),
								'off' => __('No', 'icare'),
							),

						),
						'extra_color'      => array(
							'title'       => esc_html__('Background Color', 'icare'),
							'description' => '',
							'field_type'  => 'color',
							'sanitize'    => 'sanitize_hex_color',

						),
						'extra_head'       => array(
							'title'       => esc_html__('Heading', 'icare'),
							'description' => '',
							'field_type'  => 'text',
							'sanitize'    => 'sanitize_text_field',
							'transport'   => 'postMessage',
						),
						'extra_head_color' => array(
							'title'       => esc_html__('Heading Color', 'icare'),
							'description' => '',
							'field_type'  => 'color',
							'sanitize'    => 'sanitize_hex_color',

						),
						'extra_desc'       => array(
							'title'       => esc_html__('Description', 'icare'),
							'description' => '',
							'field_type'  => 'textarea',
							'sanitize'    => 'icare_sanitize_text',
							'transport'   => 'postMessage',
						),
						'extra_desc_color' => array(
							'title'       => esc_html__('Description Color', 'icare'),
							'description' => '',
							'field_type'  => 'color',
							'sanitize'    => 'sanitize_hex_color',

						),
						'home_extra'       => array(
							'title'          => esc_html__('Select Page.', 'icare'),
							'description'    => '',
							'field_type'     => 'dropdown-pages',
							'allow_addition' => true,
							'sanitize'       => 'absint',
						),
					),
				),
				//home blog
				'home_blog'             => array(
					'title'       => esc_html__('Home Blog', 'icare'),
					'description' => '',
					'panel'       => 'home_panel',
					'priority'    => 16,
					'fields'      => array(
						'show_post'       => array(
							'title'        => esc_html__('Show Posts', 'icare'),
							'description'  => '',
							'field_type'   => 'Icare_Switch_Control',
							'on_off_label' => array(
								'on'  => __('Yes', 'icare'),
								'off' => __('No', 'icare'),
							),

						),
						'home_blog_title' => array(
							'title'       => esc_html__('Title', 'icare'),
							'description' => '',
							'field_type'  => 'text',
							'sanitize'    => 'sanitize_text_field',
							'transport'   => 'postMessage',
						),
					),
				),
				//Client logo
				'icare_client_logo'     => array(
					'title'       => esc_html__('Client Logo Section', 'icare'),
					'description' => '',
					'panel'       => 'home_panel',
					'priority'    => 17,
					'fields'      => array(
						'show_client_logo'       => array(
							'title'        => esc_html__('Enable Section', 'icare'),
							'description'  => '',
							'field_type'   => 'Icare_Switch_Control',
							'on_off_label' => array(
								'on'  => __('Yes', 'icare'),
								'off' => __('No', 'icare'),
							),

						),
						'client_section_heading' => array(
							'title'       => esc_html__('Title & Description', 'icare'),
							'description' => '',
							'field_type'  => 'Icare_Customize_Heading',
							'sanitize'    => 'icare_sanitize_text',
						),
						'client_head'            => array(
							'title'       => esc_html__('Title', 'icare'),
							'description' => '',
							'field_type'  => 'text',
							'sanitize'    => 'sanitize_text_field',
							'transport'   => 'postMessage',
						),
						'client_desc'            => array(
							'title'       => esc_html__('Description', 'icare'),
							'description' => '',
							'field_type'  => 'textarea',
							'sanitize'    => 'icare_sanitize_text',
							'transport'   => 'postMessage',
						),

						'client_logo_image'      => array(
							'title'       => esc_html__('Upload Client Logo', 'icare'),
							'description' => '',
							'field_type'  => 'Icare_Display_Gallery_Control',
							'sanitize'    => 'icare_sanitize_text',
						),
					),
				),
				'calltoaction'          => array(
					'title'       => esc_html__('Call To Action', 'icare'),
					'description' => '',
					'panel'       => 'home_panel',
					'priority'    => 18,
					'fields'      => array(
						//calltoaction
						'show_cta'               => array(
							'title'        => esc_html__('Show CallToAction section', 'icare'),
							'description'  => '',
							'field_type'   => 'Icare_Switch_Control',
							'on_off_label' => array(
								'on'  => __('Yes', 'icare'),
								'off' => __('No', 'icare'),
							),
						),
						'cta_bg_color'           => array(
							'title'       => esc_html__('Background Color', 'icare'),
							'description' => '',
							'field_type'  => 'color',
							'sanitize'    => 'sanitize_hex_color',
						),
						'cta_font_color'         => array(
							'title'       => esc_html__('Font Color', 'icare'),
							'description' => '',
							'field_type'  => 'color',
							'sanitize'    => 'sanitize_hex_color',
						),
						'calltoaction_title'     => array(
							'title'       => esc_html__('Title', 'icare'),
							'description' => '',
							'field_type'  => 'text',
							'sanitize'    => 'sanitize_text_field',
							'transport'   => 'postMessage',
						),
						'calltoaction_btn1_url'  => array(
							'title'       => esc_html__('Button 1 URL', 'icare'),
							'description' => '',
							'field_type'  => 'text',
							'sanitize'    => 'esc_url',
							'transport'   => 'postMessage',
						),
						'calltoaction_btn1_text' => array(
							'title'       => esc_html__('Button 1 Text', 'icare'),
							'description' => '',
							'field_type'  => 'text',
							'sanitize'    => 'sanitize_text_field',
							'transport'   => 'postMessage',
						),

						'calltoaction_btn2_url'  => array(
							'title'       => esc_html__('Button 2 URL', 'icare'),
							'description' => '',
							'field_type'  => 'text',
							'sanitize'    => 'esc_url',
							'transport'   => 'postMessage',
						),
						'calltoaction_btn2_text' => array(
							'title'       => esc_html__('Button 2 Text', 'icare'),
							'description' => '',
							'field_type'  => 'text',
							'sanitize'    => 'sanitize_text_field',
							'transport'   => 'postMessage',
						),
					),
				),
				'footer_options'        => array(
					'title'       => esc_html__('Footer Options', 'icare'),
					'description' => esc_html__('Customize Site footer here.', 'icare'),
					'panel'       => 'general_settings_panel',
					'priority'    => 99,
					'fields'      => array(
						'show_footer_widget'         => array(
							'title'       => esc_html__('Show Footer Widgets', 'icare'),
							'description' => '',
							'field_type'   => 'Icare_Switch_Control',
							'on_off_label' => array(
								'on'  => __('Yes', 'icare'),
								'off' => __('No', 'icare'),
							),

						),
						'footer_widget_column'       => array(
							'title'       => esc_html__('Footer Widget Column', 'icare'),
							'description' => '',
							'field_type'  => 'radio-image',
							'sanitize'    => 'absint',
							'transport'   => 'postMessage',
							'choices'     => array(
								6 => esc_url(get_template_directory_uri() . '/assets/images/layout/icare-footer-widgets-2.png'),
								4 => esc_url(get_template_directory_uri() . '/assets/images/layout/icare-footer-widgets-3.png'),
								3 => esc_url(get_template_directory_uri() . '/assets/images/layout/icare-footer-widgets-4.png'),
							),
						),
						'footer_copyright_heading'   => array(
							'title'       => esc_html__('Footer Copyright Text', 'icare'),
							'description' => '',
							'field_type'  => 'Icare_Customize_Heading',
							'sanitize'    => 'icare_sanitize_text',
						),
						//footer copyright options
						'footer_copyright_text'      => array(
							'title'       => esc_html__('Footer Copyright Text', 'icare'),
							'description' => '',
							'field_type'  => 'text',
							'sanitize'    => 'sanitize_text_field',
							'transport'   => 'postMessage',
						),
						'footer_copyright_link_text' => array(
							'title'       => esc_html__('Footer Copyright Link Text', 'icare'),
							'description' => '',
							'field_type'  => 'text',
							'sanitize'    => 'sanitize_text_field',
							'transport'   => 'postMessage',
						),
						'footer_copyright_link'      => array(
							'title'       => esc_html__('Footer Copyright Link', 'icare'),
							'description' => '',
							'field_type'  => 'text',
							'sanitize'    => 'esc_url_raw',
							'transport'   => 'postMessage',
						),
						'footer_social_or_menu'      => array(
							'title'       => esc_html__('Show social Media Links or a Simple Menu in footer', 'icare'),
							'description' => __('This menu can be set in the <a class="linkToControl" id="nav_menu_locations[footer-menu]" href="#">Menu Section</a>.', 'icare'),
							'field_type'  => 'select',
							'sanitize'    => 'sanitize_text_field',
							'choices'     => array(
								'social-links' => esc_html__('Social Links Menu', 'icare'),
								'simple-links' => esc_html__('Simple Menu', 'icare'),
								''             => esc_html__('Blank', 'icare'),
							),
						),

						'footer_color_heading'       => array(
							'title'       => esc_html__('Footer Color', 'icare'),
							'description' => '',
							'field_type'  => 'Icare_Customize_Heading',
							'sanitize'    => 'icare_sanitize_text',
						),
						//Footer Color options
						'footer_background_color'    => array(
							'title'       => esc_html__('Footer Background Color', 'icare'),
							'description' => '',
							'field_type'  => 'color',
							'sanitize'    => 'sanitize_hex_color',

						),
						'footer_heading_color'       => array(
							'title'       => esc_html__('Footer Heading Color', 'icare'),
							'description' => '',
							'field_type'  => 'color',
							'sanitize'    => 'sanitize_hex_color',
						),
						'footer_text_color'          => array(
							'title'       => esc_html__('Footer Text Color', 'icare'),
							'description' => '',
							'field_type'  => 'color',
							'sanitize'    => 'sanitize_hex_color',
						),
						'footer_link_color'          => array(
							'title'       => esc_html__('Footer Link Color', 'icare'),
							'description' => '',
							'field_type'  => 'color',
							'sanitize'    => 'sanitize_hex_color',
						),
					),
				),

				'header_image'          => array(
					'fields' => array(
						// Header Options
						'fixed_header'            => array(
							'title'       => esc_html__('Fixed Header', 'icare'),
							'description' => '',
							'field_type'   => 'Icare_Switch_Control',
							'on_off_label' => array(
								'on'  => __('Yes', 'icare'),
								'off' => __('No', 'icare'),
							),
						),
						'header_background_color' => array(
							'title'       => esc_html__('Header Background Color', 'icare'),
							'description' => '',
							'field_type'  => 'color',
							'sanitize'    => 'sanitize_hex_color',
						),
						'menu_color'              => array(
							'title'       => esc_html__('Menu Text Color', 'icare'),
							'description' => '',
							'field_type'  => 'color',
							'sanitize'    => 'sanitize_hex_color',
						),
						'menu_color_hover'        => array(
							'title'       => esc_html__('Menu Text Color on Hover', 'icare'),
							'description' => '',
							'field_type'  => 'color',
							'sanitize'    => 'sanitize_hex_color',
						),
						'menu_bg_color'           => array(
							'title'       => esc_html__('Menu Background Color', 'icare'),
							'description' => '',
							'field_type'  => 'color',
							'sanitize'    => 'sanitize_hex_color',
						),
					),
				),
				'breadcrumb_title'      => array(
					'title'       => esc_html__('Breadcrumbs & Title', 'icare'),
					'description' => '',
					'panel'       => 'general_settings_panel',
					'priority'    => 30,
					'fields'      => array(
						// Breadcrumb & Title Options
						'crumb_and_title'        => array(
							'field_type'  => 'select',
							'title'       => esc_html__('Page Title Type', 'icare'),
							'description' => esc_html__('Select pages title type.', 'icare'),
							'choices'     => array(
								'allow_both'  => 'Title Bar With Breadcrumbs',
								'allow_title' => 'Title Bar Only',
								'not_of_them' => 'Hide All',
							),
							'sanitize'    => 'sanitize_text_field',
						),

						'crumb_title_align'      => array(
							'field_type'  => 'select',
							'title'       => esc_html__('Page Title Align', 'icare'),
							'description' => '',
							'choices'     => array(
								'left'   => 'Left',
								'right'  => 'right',
								'center' => 'center',
							),
							'sanitize'    => 'sanitize_text_field',
						),
						'crumb_title_align'      => array(
							'field_type'  => 'select',
							'title'       => esc_html__('Page Title Align', 'icare'),
							'description' => '',
							'choices'     => array(
								'left'   => 'Left',
								'right'  => 'right',
								'center' => 'center',
							),
							'sanitize'    => 'sanitize_text_field',
						),

						'br_s_p_t'               => array(
							'field_type'  => 'number',
							'title'       => __('Breadcrumbs Section Padding Top', 'icare'),
							'description' => '',
							'sanitize'    => 'absint',
						),
						'br_s_p_b'               => array(
							'field_type'  => 'number',
							'title'       => __('Breadcrumbs Section Padding Bottom', 'icare'),
							'description' => '',
							'sanitize'    => 'absint',
						),
						'breadcrumbs_font_color' => array(
							'field_type'  => 'color',
							'title'       => __('Title bar Fonts color', 'icare'),
							'description' => '',
							'sanitize'    => 'sanitize_hex_color',
						),

						'breadcrumbs_bg_image'   => array(
							'field_type'  => 'image',
							'title'       => __('Background Image', 'icare'),
							'description' => '',
							'sanitize'    => 'esc_url',
						),
						'breadcrumbs_bg_video'   => array(
							'field_type'  => 'text',
							'title'       => __('Or enter background video URL', 'icare'),
							'description' => '',
							'sanitize'    => 'esc_url',
						),

					),
				),
				'background_image'      => array(
					'fields' => array(
						//Footer Color options
						'site_layout' => array(
							'title'       => esc_html__('Site layout', 'icare'),
							'description' => '',
							'field_type'  => 'select',
							'sanitize'    => 'sanitize_text_field',
							'choices'     => array(
								'boxed-layout pt-fourty pb-fourty pt-sm-zero' => esc_html__('Boxed Layout', 'icare'),
								''                                            => esc_html__('Full Width', 'icare'),
							),
							'default'     => '',
						),
					),
				),

			);

			foreach ($section_controls as $s_id => $section) {
				if (!in_array($s_id, array_keys($wp_customize->sections()))) {
					$args = array(
						'title'       => $section['title'],
						'description' => $section['description'],
						'priority'    => $section['priority'],
					);
					$args['panel'] = isset($section['panel']) ? $theme_slug . $section['panel'] : '';
					$wp_customize->add_section($s_id, $args);
				}
				foreach ($section['fields'] as $c_id => $option) {
					if ('image' == $option['field_type']) {
						$wp_customize->add_setting(
							// $id
							$theme_slug . $c_id,
							// parameters array
							array(
								'type'              => 'theme_mod',
								'sanitize_callback' => $option['sanitize'],
								'default'           => isset($option['default']) ? esc_url($option['default']) : '',
							)
						);

						$wp_customize->add_control(
							new WP_Customize_Image_Control(
								$wp_customize, $theme_slug . $c_id,
								array(
									'label'    => $option['title'],
									'section'  => $s_id,
									'settings' => $theme_slug . $c_id,
								)
							)
						);
					} else if ('ColorAlpha' == $option['field_type']) {
					$wp_customize->add_setting(
						// $id
						$theme_slug . $c_id,
						// parameters array
						array(
							'type'              => 'theme_mod',
							'sanitize_callback' => $option['sanitize'],
							'default'           => isset($option['default']) ? $option['default'] : '',
						)
					);

					$wp_customize->add_control(
						new ColorAlpha(
							$wp_customize, $theme_slug . $c_id,
							array(
								'label'    => $option['title'],
								'section'  => $s_id,
								'settings' => $theme_slug . $c_id,
							)
						)
					);
				} else if ('iCare_Customizer_Repeater' == $option['field_type']) {
					$default = isset($option['default']) ? $option['default'] : '';
					$wp_customize->add_setting(
						// $c_id
						$theme_slug . $c_id,
						// parameters array
						array(
							'type'              => 'theme_mod',
							'sanitize_callback' => $option['sanitize'],
							'default'           => $default,
						)
					);
					$args = array(
						'label'    => $option['title'],
						'section'  => $s_id,
						'settings' => $theme_slug . $c_id,
					);
					foreach ($option['fields'] as $field => $val) {
						$args[$field] = $val;
					}
					if (isset($option['active_callback'])) {
						$args['active_callback'] = $option['active_callback'];
					}
					$wp_customize->add_control(
						new iCare_Customizer_Repeater(
							$wp_customize, $theme_slug . $c_id,
							$args
						)
					);
				} else if ('Icare_Switch_Control' == $option['field_type']) {
					$wp_customize->add_setting(
						$theme_slug . $c_id,
						array(
							'sanitize_callback' => 'icare_sanitize_text',
							'default'           => 'off',
						)
					);
					$wp_customize->add_control(new Icare_Switch_Control($wp_customize, $theme_slug . $c_id, array(
						'section'      => $s_id,
						'label'        => $option['title'],
						'on_off_label' => isset($options['on_off_label']) ? $options['on_off_label'] : array('on' => __('Yes', 'icare'), 'off' => __('No', 'icare')),
					)));
				} else if ('Icare_Display_Gallery_Control' == $option['field_type']) {
					$wp_customize->add_setting(
						$theme_slug . $c_id,
						array(
							'sanitize_callback' => 'icare_sanitize_text',
						)
					);
					$wp_customize->add_control(new Icare_Display_Gallery_Control($wp_customize, $theme_slug . $c_id, array(
						'section' => $s_id,
						'label'   => $option['title'],
					)));
				} else if ('Icare_Customize_Heading' == $option['field_type']) {
					$wp_customize->add_setting(
						$theme_slug . $c_id,
						array(
							'sanitize_callback' => 'icare_sanitize_text',
						)
					);
					$wp_customize->add_control(new Icare_Customize_Heading($wp_customize, $theme_slug . $c_id, array(
						'section' => $s_id,
						'label'   => $option['title'],
					)));
				} else if ('IconPicker' == $option['field_type']) {
					$wp_customize->add_setting(
						$theme_slug . $c_id,
						array(
							'sanitize_callback' => 'icare_sanitize_text',
						)
					);
					$wp_customize->add_control(new IconPicker($wp_customize, $theme_slug . $c_id, array(
						'section' => $s_id,
						'type'    => 'icon-picker',
						'label'   => $option['title'],
					)));
				} else if ('radio-image' == $option['field_type']) {
					$wp_customize->add_setting(
						// $id
						$theme_slug . $c_id,
						// parameters array
						array(
							'type'              => 'theme_mod',
							'transport'         => isset($option['transport']) ? 'postMessage' : 'refresh',
							'sanitize_callback' => $option['sanitize'],
						)
					);
					$wp_customize->add_control(new iCare_Custom_Radio_Image_Control($wp_customize, $theme_slug . $c_id, array(
						'section'     => $s_id,
						'label'       => $option['title'],
						'description' => $option['description'],
						'type'        => 'radio-image',
						'choices'     => $option['choices'],
					)));
				} else {
					//Normal Loop
					$wp_customize->add_setting(
						// $c_id
						$theme_slug . $c_id,
						// parameters array
						array(
							'type'              => 'theme_mod',
							'transport'         => isset($option['transport']) ? 'postMessage' : 'refresh',
							'sanitize_callback' => $option['sanitize'],
						)
					);

					// Add setting control
					$params = array(
						'label'       => $option['title'],
						'settings'    => $theme_slug . $c_id,
						'type'        => $option['field_type'],
						'description' => $option['description'],
					);

					if (isset($option['choices'])) {
						$params['choices'] = $option['choices'];
					}

					if (isset($option['active_callback'])) {
						$params['active_callback'] = $option['active_callback'];
					}

					if (isset($option['input_attrs'])) {
						$params['input_attrs'] = $option['input_attrs'];
					}

					if ('header_image' == $s_id || 'colors' == $s_id) {
						$params['section'] = $s_id;
					} else {
						$params['section'] = $s_id;
					}
					$wp_customize->add_control(
						// $c_id
						$theme_slug . $c_id,
						$params
					);

				}
			}
		}

		// Move background color setting alongside background image.
		$wp_customize->get_control('background_color')->section  = 'background_image';
		$wp_customize->get_control('background_color')->priority = 20;

		$wp_customize->get_control('header_textcolor')->section  = 'header_image';
		$wp_customize->get_control('header_textcolor')->priority = 11;

		$wp_customize->get_control('icare_site_layout')->priority = 9;

		// Change control title and description.
		$wp_customize->get_section('background_image')->title       = esc_html__('Site Layout & Background', 'icare');
		$wp_customize->get_section('background_image')->description = esc_html__('Site Layout & Background Options', 'icare');

		$wp_customize->get_section('title_tagline')->title = esc_html__('Site Logo/Title/Tagline', 'icare');
		// Change header image section title & priority.
		$wp_customize->get_section('header_image')->title = esc_html__('Header Options', 'icare');

		// move general control into general panel
		$wp_customize->get_section('static_front_page')->panel = 'icare_general_settings_panel';
		$wp_customize->get_section('title_tagline')->panel     = 'icare_general_settings_panel';
		$wp_customize->get_section('background_image')->panel  = 'icare_general_settings_panel';
		$wp_customize->get_section('header_image')->panel      = 'icare_general_settings_panel';

		// Selective refresh.
		$wp_customize->get_setting('blogname')->transport         = 'postMessage';
		$wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
		$wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

		global $wp_registered_sidebars;

		$icare_widget_list[] = __("-- Don't Replace --", "icare");
		foreach ($wp_registered_sidebars as $wp_registered_sidebar) {
			$icare_widget_list[$wp_registered_sidebar['id']] = $wp_registered_sidebar['name'];
		}

		$icare_categories = get_categories(array('hide_empty' => 0));
		foreach ($icare_categories as $icare_category) {
			$icare_cat[$icare_category->term_id] = $icare_category->cat_name;
		}

		$icare_pages = get_pages(array('hide_empty' => 0));
		foreach ($icare_pages as $icare_pages_single) {
			$icare_page_choice[$icare_pages_single->ID] = $icare_pages_single->post_title;
		}


		$icare_post_count_choice = array(3 => 3, 6 => 6, 9 => 9);
		//ABOUT US PAGE
		for ($i = 1; $i < 6; $i++) {
			$wp_customize->add_setting(
				$theme_slug . 'about_progressbar_heading' . $i,
				array(
					'sanitize_callback' => 'icare_sanitize_text',
				)
			);

			$wp_customize->add_control(
				new Icare_Customize_Heading(
					$wp_customize,
					$theme_slug . 'about_progressbar_heading' . $i,
					array(
						'settings' => $theme_slug . 'about_progressbar_heading' . $i,
						'section'  => 'icare_about',
						'label'    => __('Progress Bar ', 'icare') . $i,
					)
				)
			);

			$wp_customize->add_setting(
				$theme_slug . 'about_progressbar_disable' . $i,
				array(
					'sanitize_callback' => 'absint',
				)
			);

			$wp_customize->add_control(
				$theme_slug . 'about_progressbar_disable' . $i,
				array(
					'settings' => $theme_slug . 'about_progressbar_disable' . $i,
					'section'  => 'icare_about',
					'label'    => __('Check to Disable', 'icare'),
					'type'     => 'checkbox',
				)
			);

			$wp_customize->add_setting(
				$theme_slug . 'about_progressbar_title' . $i,
				array(
					'sanitize_callback' => 'icare_sanitize_text',
					'default'           => sprintf(__('Progress Bar %d', 'icare'), $i),
				)
			);

			$wp_customize->add_control(
				$theme_slug . 'about_progressbar_title' . $i,
				array(
					'settings' => $theme_slug . 'about_progressbar_title' . $i,
					'section'  => 'icare_about',
					'type'     => 'text',
					'label'    => __('Title', 'icare'),
				)
			);

			$wp_customize->add_setting(
				$theme_slug . 'about_progressbar_percentage' . $i,
				array(
					'sanitize_callback' => 'icare_sanitize_choices',
					'default'           => rand(60, 100),
				)
			);

			$wp_customize->add_control(
				new Customizer_Range_Value_Control(
					$wp_customize,
					$theme_slug . 'about_progressbar_percentage' . $i,
					array(
						'settings' => $theme_slug . 'about_progressbar_percentage' . $i,
						'section'  => 'icare_about',
						'type'		=> 'range-value',
						'label'    => __('Percentage', 'icare'),
						'input_attrs' => array(
							'min'    => 1,
							'max'    => 100,
							'step'   => 1,
							'suffix' => '%', //optional suffix
					  	)
					)
				)
			);

			$wp_customize->add_setting(
				$theme_slug . 'about_image_heading',
				array(
					'sanitize_callback' => 'icare_sanitize_text',
				)
			);

			$wp_customize->add_control(
				new Icare_Customize_Heading(
					$wp_customize,
					$theme_slug . 'about_image_heading',
					array(
						'settings' => $theme_slug . 'about_image_heading',
						'section'  => 'icare_about',
						'label'    => __('Right Image', 'icare'),
					)
				)
			);

			$wp_customize->add_setting(
				$theme_slug . 'about_image',
				array(
					'sanitize_callback' => 'absint',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Media_Control(
					$wp_customize,
					$theme_slug . 'about_image',
					array(
						'section'     => 'icare_about',
						'settings'    => $theme_slug . 'about_image',
						'description' => __('Recommended Image Size: 500X600px', 'icare'),
					)
				)
			);

			$wp_customize->add_setting(
				$theme_slug . 'about_widget',
				array(
					'default'           => '0',
					'sanitize_callback' => 'icare_sanitize_choices',
				)
			);

			$wp_customize->add_control(
				$theme_slug . 'about_widget',
				array(
					'settings' => $theme_slug . 'about_widget',
					'section'  => 'icare_about',
					'type'     => 'select',
					'label'    => __('Replace Image by widget', 'icare'),
					'choices'  => $icare_widget_list,
				)
			);

		}
		//SERVICE PAGES
		for ($i = 1; $i < 7; $i++) {
			$wp_customize->add_setting(
				$theme_slug . 'service_header' . $i,
				array(
					'default'           => '',
					'sanitize_callback' => 'icare_sanitize_text',
				)
			);

			$wp_customize->add_control(
				new Icare_Customize_Heading(
					$wp_customize,
					$theme_slug . 'service_header' . $i,
					array(
						'settings' => $theme_slug . 'service_header' . $i,
						'section'  => 'services',
						'label'    => __('Service Page ', 'icare') . $i,
					)
				)
			);

			$wp_customize->add_setting(
				$theme_slug . 'service_page' . $i,
				array(
					'default'           => '',
					'sanitize_callback' => 'absint',
				)
			);

			$wp_customize->add_control(
				$theme_slug . 'service_page' . $i,
				array(
					'settings' => $theme_slug . 'service_page' . $i,
					'section'  => 'services',
					'type'     => 'dropdown-pages',
					'label'    => __('Select a Page', 'icare'),
				)
			);
			$wp_customize->add_setting(
				$theme_slug . 'service_img_or_icon' . $i,
				array(
					'sanitize_callback' => 'icare_sanitize_text',
					'default'           => 'icon',
					'transport'         => 'postMessage',
				)
			);
			$wp_customize->add_control($theme_slug . 'service_img_or_icon' . $i,
				array(
					'section' => 'services',
					'type'    => 'select',
					'label'   => '',
					'choices' => array('icon' => __('Icon', 'icare'), 'img' => __('Image', 'icare')),
				)
			);
			//service icon
			$wp_customize->add_setting(
				$theme_slug . 'service_page_icon' . $i,
				array(
					'default'           => '',
					'sanitize_callback' => 'icare_sanitize_text',
				)
			);

			$wp_customize->add_control(
				new IconPicker(
					$wp_customize,
					$theme_slug . 'service_page_icon' . $i,
					array(
						'settings'        => $theme_slug . 'service_page_icon' . $i,
						'section'         => 'services',
						'active_callback' => function () use ($i, $theme_slug) {return get_theme_mod($theme_slug . 'service_img_or_icon' . $i) == 'icon' ? 1 : 0;},
						'type'            => 'icon-picker',
						'label'           => __('FontAwesome Icon', 'icare'),
					)
				)
			);
			//service image
			$wp_customize->add_setting(
				$theme_slug . 'service_page_img' . $i,
				array(
					'sanitize_callback' => 'absint',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Media_Control(
					$wp_customize,
					$theme_slug . 'service_page_img' . $i,
					array(
						'section'         => 'services',
						'active_callback' => function () use ($i, $theme_slug) {return get_theme_mod($theme_slug . 'service_img_or_icon' . $i) == 'img' ? 1 : 0;},
						'settings'        => $theme_slug . 'service_page_img' . $i,
						'description'     => __('Recommended Image Size: 64X64px', 'icare'),
					)
				)
			);
			// Read more text
			$wp_customize->add_setting(
				$theme_slug . 'service_readmore' . $i,
				array(
					'sanitize_callback' => 'sanitize_text_field',
				)
			);

			$wp_customize->add_control($theme_slug . 'service_readmore' . $i,
				array(
					'section' => 'services',
					'type'    => 'text',
					'label'   => __('Read More text', 'icare'),
				)
			);
		}
		//TEAM PAGES
		for ($i = 1; $i < 5; $i++) {
			$wp_customize->add_setting(
				'icare_team_heading' . $i,
				array(
					'sanitize_callback' => 'icare_sanitize_text',
				)
			);

			$wp_customize->add_control(
				new Icare_Customize_Heading(
					$wp_customize,
					'icare_team_heading' . $i,
					array(
						'settings' => 'icare_team_heading' . $i,
						'section'  => 'icare_team',
						'label'    => __('Team Member ', 'icare') . $i,
					)
				)
			);

			$wp_customize->add_setting(
				'icare_team_page' . $i,
				array(
					'sanitize_callback' => 'absint',
				)
			);

			$wp_customize->add_control(
				'icare_team_page' . $i,
				array(
					'settings'    => 'icare_team_page' . $i,
					'section'     => 'icare_team',
					'type'        => 'dropdown-pages',
					'label'       => __('Select a Page', 'icare'),
					'description' => __('Page must have feaured image set. This will display as member profile image.', 'icare'),
				)
			);

			$wp_customize->add_setting(
				'icare_team_designation' . $i,
				array(
					'sanitize_callback' => 'icare_sanitize_text',
				)
			);

			$wp_customize->add_control(
				'icare_team_designation' . $i,
				array(
					'settings' => 'icare_team_designation' . $i,
					'section'  => 'icare_team',
					'type'     => 'text',
					'label'    => __('Team Member Designation', 'icare'),
				)
			);

			$wp_customize->add_setting(
				'icare_team_facebook' . $i,
				array(
					'default'           => 'https://facebook.com',
					'sanitize_callback' => 'esc_url_raw',
				)
			);

			$wp_customize->add_control(
				'icare_team_facebook' . $i,
				array(
					'settings' => 'icare_team_facebook' . $i,
					'section'  => 'icare_team',
					'type'     => 'url',
					'label'    => __('Facebook Url', 'icare'),
				)
			);

			$wp_customize->add_setting(
				'icare_team_twitter' . $i,
				array(
					'default'           => 'https://twitter.com',
					'sanitize_callback' => 'esc_url_raw',
				)
			);

			$wp_customize->add_control(
				'icare_team_twitter' . $i,
				array(
					'settings' => 'icare_team_twitter' . $i,
					'section'  => 'icare_team',
					'type'     => 'url',
					'label'    => __('Twitter Url', 'icare'),
				)
			);


			$wp_customize->add_setting(
				'icare_team_linkedin' . $i,
				array(
					'default'           => 'https://linkedin.com',
					'sanitize_callback' => 'esc_url_raw',
				)
			);

			$wp_customize->add_control(
				'icare_team_linkedin' . $i,
				array(
					'settings' => 'icare_team_linkedin' . $i,
					'section'  => 'icare_team',
					'type'     => 'url',
					'label'    => __('Linkedin Url', 'icare'),
				)
			);
		}
		//Features PAGES
		for ($i = 1; $i < 5; $i++) {
			$wp_customize->add_setting(
				$theme_slug . 'feature_header' . $i,
				array(
					'default'           => '',
					'sanitize_callback' => 'icare_sanitize_text',
				)
			);

			$wp_customize->add_control(
				new Icare_Customize_Heading(
					$wp_customize,
					$theme_slug . 'feature_header' . $i,
					array(
						'settings' => $theme_slug . 'feature_header' . $i,
						'section'  => 'icare_features',
						'label'    => __('Feature Page ', 'icare') . $i,
					)
				)
			);

			$wp_customize->add_setting(
				$theme_slug . 'feature_page' . $i,
				array(
					'default'           => '',
					'sanitize_callback' => 'absint',
				)
			);

			$wp_customize->add_control(
				$theme_slug . 'feature_page' . $i,
				array(
					'settings' => $theme_slug . 'feature_page' . $i,
					'section'  => 'icare_features',
					'type'     => 'dropdown-pages',
					'label'    => __('Select a Page', 'icare'),
				)
			);
			$wp_customize->add_setting(
				$theme_slug . 'feature_img_or_icon' . $i,
				array(
					'sanitize_callback' => 'icare_sanitize_text',
					'default'           => 'icon',
				)
			);
			$wp_customize->add_control($theme_slug . 'feature_img_or_icon' . $i,
				array(
					'section' => 'icare_features',
					'type'    => 'select',
					'label'   => '',
					'choices' => array('icon' => __('Icon', 'icare'), 'img' => __('Image', 'icare')),
				)
			);
			//feature icon
			$wp_customize->add_setting(
				$theme_slug . 'feature_page_icon' . $i,
				array(
					'default'           => '',
					'sanitize_callback' => 'icare_sanitize_text',
				)
			);

			$wp_customize->add_control(
				new IconPicker(
					$wp_customize,
					$theme_slug . 'feature_page_icon' . $i,
					array(
						'settings'        => $theme_slug . 'feature_page_icon' . $i,
						'section'         => 'icare_features',
						'type'            => 'icon-picker',
						'label'           => __('FontAwesome Icon', 'icare'),
						'active_callback' => function () use ($i, $theme_slug) {return get_theme_mod($theme_slug . 'feature_img_or_icon' . $i) == 'icon' ? 1 : 0;},
					)
				)
			);

			//feature image
			$wp_customize->add_setting(
				$theme_slug . 'feature_page_img' . $i,
				array(
					'sanitize_callback' => 'esc_url_raw',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,
					$theme_slug . 'feature_page_img' . $i,
					array(
						'section'         => 'icare_features',
						'active_callback' => function () use ($i, $theme_slug) {return get_theme_mod($theme_slug . 'feature_img_or_icon' . $i) == 'img' ? 1 : 0;},
						'settings'        => $theme_slug . 'feature_page_img' . $i,
						'description'     => __('Recommended Image Size: 64X64px', 'icare'),
					)
				)
			);
		}

		//fun facts
		for ($i = 1; $i < 5; $i++) {

			$wp_customize->add_setting(
				'icare_counter_heading' . $i,
				array(
					'sanitize_callback' => 'icare_sanitize_text',
				)
			);

			$wp_customize->add_control(
				new Icare_Customize_Heading(
					$wp_customize,
					'icare_counter_heading' . $i,
					array(
						'settings' => 'icare_counter_heading' . $i,
						'section'  => 'icare_counter_section',
						'label'    => __('Counter', 'icare') . $i,
					)
				)
			);

			$wp_customize->add_setting(
				'icare_counter_title' . $i,
				array(
					'sanitize_callback' => 'icare_sanitize_text',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'icare_counter_title' . $i,
				array(
					'settings' => 'icare_counter_title' . $i,
					'section'  => 'icare_counter_section',
					'type'     => 'text',
					'label'    => __('Title', 'icare'),
				)
			);

			$wp_customize->add_setting(
				'icare_counter_count' . $i,
				array(
					'sanitize_callback' => 'absint',
				)
			);

			$wp_customize->add_control(
				'icare_counter_count' . $i,
				array(
					'settings' => 'icare_counter_count' . $i,
					'section'  => 'icare_counter_section',
					'type'     => 'number',
					'label'    => __('Counter Value', 'icare'),
				)
			);

			$wp_customize->add_setting(
				'icare_counter_icon' . $i,
				array(
					'default'           => '',
					'sanitize_callback' => 'icare_sanitize_text',
				)
			);

			$wp_customize->add_control(
				new IconPicker(
					$wp_customize,
					'icare_counter_icon' . $i,
					array(
						'settings' => 'icare_counter_icon' . $i,
						'section'  => 'icare_counter_section',
						'type'		=> 'icon-picker',
						'label'    => __('Counter Icon', 'icare'),
					)
				)
			);
		}
		// feature left image
		$wp_customize->add_setting(
			$theme_slug . 'feature_image_heading',
			array(
				'sanitize_callback' => 'icare_sanitize_text',
			)
		);

		$wp_customize->add_control(
			new Icare_Customize_Heading(
				$wp_customize,
				$theme_slug . 'feature_image_heading',
				array(
					'settings' => $theme_slug . 'feature_image_heading',
					'section'  => 'icare_features',
					'label'    => __('Left Image', 'icare'),
				)
			)
		);

		$wp_customize->add_setting(
			$theme_slug . 'feature_image',
			array(
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				$theme_slug . 'feature_image',
				array(
					'section'     => 'icare_features',
					'settings'    => $theme_slug . 'feature_image',
					'description' => __('Recommended Image Size: 380X570px', 'icare'),
				)
			)
		);

		$wp_customize->add_setting(
			$theme_slug . 'feature_widget',
			array(
				'default'           => '0',
				'sanitize_callback' => 'icare_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			$theme_slug . 'feature_widget',
			array(
				'settings' => $theme_slug . 'feature_widget',
				'section'  => 'icare_features',
				'type'     => 'select',
				'label'    => __('Replace Image by widget', 'icare'),
				'choices'  => $icare_widget_list,
			)
		);
		/* Icare Pro Panel */
		$wp_customize->add_section('icare_pro', array(
			'title'    => __('Upgrade to iCare Premium', 'icare'),
			'priority' => 999,
		));
		$wp_customize->add_setting('icare_pro', array(
			'default'           => null,
			'sanitize_callback' => 'sanitize_text_field',
		));

		$wp_customize->add_control(new Icare_Pro_Control($wp_customize, 'icare_pro', array(
			'label'    => __('Icare Premium', 'icare'),
			'settings' => 'icare_pro',
			'section'  => 'icare_pro',
			'priority' => 1,
		)));
		/* Add Partial Refresh */
		$partial_refresh = array(
			'cont_text'              => array(
				'selector' => '.cont-text',
			),
			'hero_title'             => array(
				'selector' => '#h_title',
			),
			'hero_desc'              => array(
				'selector' => '#h_subtitle',
			),
			'calltoaction_title'     => array(
				'selector' => '#cta_title',
			),
			'calltoaction_text'      => array(
				'selector' => '#cta_desc',
			),
			'calltoaction_btn1_text' => array(
				'selector' => '#cta_btn_p',
			),
			'calltoaction_btn2_text' => array(
				'selector' => '#cta_btn_s',
			),
			'service_head'           => array(
				'selector' => '#service_title',
			),
			'service_desc'           => array(
				'selector' => '#service_desc',
			),
			'feature_head'           => array(
				'selector' => '#feature-title',
			),
			'feature_desc'           => array(
				'selector' => '#feature-desc',
			),
			'about_head'             => array(
				'selector' => '#aboutus_title',
			),
			'about_desc'             => array(
				'selector' => '#aboutus_desc',
			),
			'team_head'              => array(
				'selector' => '#team_title',
			),
			'team_desc'              => array(
				'selector' => '#team_desc',
			),
			'extra_head'             => array(
				'selector' => '#extra_title',
			),
			'extra_desc'             => array(
				'selector' => '#extra_desc',
			),
			'home_blog_title'        => array(
				'selector' => '#home_blog_title',
			),
			'footer_copyright_text'  => array(
				'selector' => '#icare-copyright-text',
			),
            'client_head'  => array(
                'selector' => '#client_title',
            ),
            'client_desc'  => array(
                'selector' => '#client_desc',
            ),
		);
		for ($i = 1; $i < 7; $i++) {
			$wp_customize->selective_refresh->add_partial($theme_slug . 'service_page_img' . $i, array(
				'selector'        => '#service_ico_img' . $i,
				'render_callback' => function () use ($theme_slug, $i) {
					return wp_get_attachment_image(absint(get_theme_mod($theme_slug . 'service_page_img' . $i)), 'icare_home_service_ico', true);
				},
			));
		}
		for ($i = 1; $i < 5; $i++) {
			$partial_refresh['counter_title' . $i] = array('selector' => '#counter_title' . $i);

		}
		foreach ($partial_refresh as $id => $opt) {
			$wp_customize->selective_refresh->add_partial($theme_slug . $id, array(
				'selector'        => $opt['selector'],
				'render_callback' => function () use ($theme_slug, $id) {
					return get_theme_mod($theme_slug . $id);
				},
			));
		}

		$wp_customize->selective_refresh->add_partial('custom_logo', array(
			'selector'        => '.icaremenu-brand',
			'render_callback' => array($this, 'icare_get_site_logo'),
		));

		$wp_customize->selective_refresh->add_partial('blogname', array(
			'selector'        => '.site-title',
			'render_callback' => array($this, 'icare_get_site_name'),
		));

		$wp_customize->selective_refresh->add_partial('blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => array($this, 'icare_get_site_description'),
		));

		// extra section control
		/**
		 * Filter number of front page sections in Twenty Seventeen.
		 *
		 * @since Twenty Seventeen 1.0
		 *
		 * @param int $num_sections Number of front page sections.
		 */

		// home page re-order
		$wp_customize->add_section($theme_slug . 'home_page', array(
			'title'       => __('Home Page Re-order', 'icare'),
			'description' => __('Here you can re-order your Home Page Section', 'icare'),
			'priority'    => 130, // Before Additional CSS.
		));

		$wp_customize->add_setting(
			// $id
			$theme_slug . 'home_reorder',
			// parameters array
			array(
				'type'              => 'theme_mod',
				'sanitize_callback' => 'icare_sanitize_json_string',
			)
		);
		$wp_customize->add_control(new iCare_Custom_sortable_Control($wp_customize, $theme_slug . 'home_reorder', array(
			'section' => $theme_slug . 'home_page',
			'type'    => 'home-sortable',
			'choices' => array(
				'about'        => __('About Us', 'icare'),
				'service'      => __('Home Service', 'icare'),
				'funfacts'     => __('Home Fun Facts', 'icare'),
				"team"         => __('Home Team', 'icare'),
				'features'     => __('Home Feature', 'icare'),
				'extra'        => __('Home Extra Section', 'icare'),
				'posts'        => __('Home Posts', 'icare'),
				'clients'      => __('Home Clients', 'icare'),
				'calltoaction' => __('Home CTA', 'icare'),
			),
		)));

	}
	/**
	 * Get all of the iCare theme mods.
	 *
	 * @return array $icare_theme_mods The iCare Theme Mods.
	 */
	public static function get_icare_theme_mods() {
		$icare_theme_mods = array(
			'primary_color'            => get_theme_mod('icare_primary_color'),
            'topbar_bg_color'          => get_theme_mod('icare_topbar_bg_color'),
			'background_color'         => get_theme_mod('background_color'),
			'fixed_header'             => get_theme_mod('icare_fixed_header'),
			'header_background_color'  => get_theme_mod('icare_header_background_color', '#fff'),
			'breadcrumbs_font_color'   => get_theme_mod('icare_breadcrumbs_font_color'),
			'br_s_p_t'                 => get_theme_mod('icare_br_s_p_t'),
			'br_s_p_b'                 => get_theme_mod('icare_br_s_p_b'),
			'menu_color'               => get_theme_mod('icare_menu_color'),
			'menu_color_hover'         => get_theme_mod('icare_menu_color_hover'),
			'menu_bg_color'            => get_theme_mod('icare_menu_bg_color'),
			'hero_section_bg_color'    => get_theme_mod('icare_hero_section_bg_color'),
			'extra_color'              => get_theme_mod('icare_extra_color'),
			'extra_head_color'         => get_theme_mod('icare_extra_head_color'),
			'extra_desc_color'         => get_theme_mod('icare_extra_desc_color'),
			'footer_background_color'  => get_theme_mod('icare_footer_background_color'),
			'footer_link_color'        => get_theme_mod('icare_footer_link_color'),
			'footer_heading_color'     => get_theme_mod('icare_footer_heading_color'),
			'footer_text_color'        => get_theme_mod('icare_footer_text_color'),
			'cta_bg_color'             => get_theme_mod('icare_cta_bg_color'),
			'cta_font_color'           => get_theme_mod('icare_cta_font_color'),
		);

		return apply_filters('icare_theme_mods', $icare_theme_mods);
	}



	/**
	 * Generate CSS.
	 *
	 * @param string $selector The CSS selector.
	 * @param string $style The CSS style.
	 * @param string $value The CSS value.
	 * @param string $prefix The CSS prefix.
	 * @param string $suffix The CSS suffix.
	 * @param bool   $echo Echo the styles.
	 */
	public	function icare_generate_css( $selector, $style, $value, $prefix = '', $suffix = '', $echo = true ) {

			$return = '';

			/*
			 * Bail early if we have no $selector elements or properties and $value.
			 */
			if ( ! $value || ! $selector ) {

				return;
			}

			$return = sprintf( '%s { %s: %s; }', $selector, $style, $prefix . $value . $suffix );

			if ( $echo ) {

				echo $return; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- We need to double check this, but for now, we want to pass PHPCS ;)

			}

			return $return;

	}
	public function icare_get_elements_array(){
		$icare_theme_mods = $this->get_icare_theme_mods();

		$els = array(
				'background-color' => array(
					'.top-header.icare-theme-bg-colored'=>$icare_theme_mods['topbar_bg_color'],
					'.social-links a' => icare_adjust_color_brightness($icare_theme_mods['topbar_bg_color'], 25),
					'.header_nav' => $icare_theme_mods['header_background_color'],
					'#icare-home-extra-section' => $icare_theme_mods['extra_color'],
					'#icare-home-extra-section.layer-overlay.color-overlay::before' => icare_hex2rgba($icare_theme_mods['extra_color'], 0.4),
					'.icare-footer,.icare-footer .styled-icons.icon-dark a' => $icare_theme_mods['footer_background_color'],
					'#icare-home-cta-section' => $icare_theme_mods['cta_bg_color'],
					'.icare-theme-bg-colored,.btn-theme-colored,.line-bottom::after,.icon-theme-colored.icon-dark a,button, input[type="button"], input[type="submit"]' => $icare_theme_mods['primary_color'],
					'.progress-item .progress-bar,#wp-calendar caption, #wp-calendar td#today'=> $icare_theme_mods['primary_color'],
					'.progress-item .progress-bar .percent'=> icare_adjust_color_brightness($icare_theme_mods['primary_color'], -25),
					'.icare-theme-bg-colored-transparent' => icare_hex2rgba($icare_theme_mods['primary_color'], 0.75),
					'.icon-theme-colored.icon-dark a:hover,.search_btn:hover' => icare_adjust_color_brightness($icare_theme_mods['primary_color'], -25),
					'#icare-home-feature-section.layer-overlay.overlay-blue::before,.team-block .team-thumb .team-overlay' => icare_hex2rgba($icare_theme_mods['primary_color'], 0.9),
				),
				'background' => array(
					'#cssmenu ul:not(.sub-menu) > li:hover>a, #cssmenu ul:not(.sub-menu) > li.active>a,#cssmenu ul ul li:hover>a,.slicknav_nav>li.active a,#cssmenu ul ul > li.active>a, .slicknav_nav>li:hover>a, .slicknav_nav ul.dropdown li:hover>a'=>$icare_theme_mods['menu_bg_color'],
					'#icare-home-slider-section.layer-overlay::before'=> $icare_theme_mods['hero_section_bg_color'],
					' .icare-footer-bottom' => icare_adjust_color_brightness($icare_theme_mods['footer_background_color'], 25)
				),

				'color' => array(
					'#primary-menu > li > a'=> $icare_theme_mods['menu_color'],
					'#cssmenu ul > li:hover>a, #cssmenu ul > li.active>a,.slicknav_nav>li.active a, .slicknav_nav>li:hover>a, .slicknav_nav ul.dropdown li:hover>a' => $icare_theme_mods['menu_color_hover'],
					'.brcmb-container h2.title,.brcmb-container li, .brcmb-container li a' => $icare_theme_mods['breadcrumbs_font_color'],
					'#extra_title'=>$icare_theme_mods['extra_head_color'],
					'#extra_desc'=>$icare_theme_mods['extra_desc_color'],
					'.icare-footer, .icare-footer caption' => $icare_theme_mods['footer_text_color'],
					'.icare-footer .icare-widget.dark .icare-widget-title' => $icare_theme_mods['footer_heading_color'],
					'#cta_title,#icare-home-cta-section p' => $icare_theme_mods['cta_font_color'],
					'.icare-text-colored,.icare-icon-box:hover .icon-hov i'=>$icare_theme_mods['primary_color'],
					'.icare-footer a' => $icare_theme_mods['footer_link_color'],
				),
				'border-color' => array(
					'.btn-theme-colored,.icare-widget .widget-title-border-bottom:after'=> $icare_theme_mods['primary_color']
				),
				'padding-top' => array('.brcmb-container' => $icare_theme_mods['br_s_p_t']),
				'padding-bottom' => array('.brcmb-container' => $icare_theme_mods['br_s_p_b'])
			);

		return apply_filters( 'icare_get_elements_array', $els );
	}
	/**
	 * Get Customizer css.
	 *
	 * @see get_icare_theme_mods()
	 * @return array $styles the css
	 */
	public function icare_get_customizer_css() {
		$icare_theme_mods = $this->get_icare_theme_mods();
		ob_start();
		$elements = $this->icare_get_elements_array();

		foreach ($elements as $property => $props) {
			foreach ($props as $el => $value) {
				/*
				 * If we don't have an elements array or it is empty
				 * then skip this iteration early;
				 */
				if ( empty( $el ) || empty($value) ) {
					continue;
				}

				$this->icare_generate_css($el, $property, $value);
			}
		}


        if(isset($icare_theme_mods['primary_color']) && $icare_theme_mods['primary_color']!=""){

            if(!$icare_theme_mods['topbar_bg_color']){
            	$this->icare_generate_css('.social-links a','background-color', icare_adjust_color_brightness($icare_theme_mods['primary_color'], 25));
        	}
            if(!$icare_theme_mods['menu_bg_color']){
            	$this->icare_generate_css('#cssmenu ul:not(.sub-menu) > li:hover>a, #cssmenu ul:not(.sub-menu) > li.active>a,#cssmenu ul ul li:hover>a,#cssmenu ul ul > li.active>a,.slicknav_nav>li.active a, .slicknav_nav>li:hover>a, .slicknav_nav ul.dropdown li:hover>a','background', $icare_theme_mods['primary_color']);
	        }

        }

		// Return the results.
		return apply_filters('icare_get_customizer_css', ob_get_clean());
	}

	/**
	 * Assign iCare styles to individual theme mods.
	 *
	 * @return void
	 */
	public function icare_set_style_theme_mods() {
		set_theme_mod('icare_styles', $this->icare_get_customizer_css());
	}

	/**
	 * Add CSS in <head> for styles handled by the theme customizer
	 * If the Customizer is active pull in the raw css. Otherwise pull in the prepared theme_mods if they exist.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function icare_add_customizer_css() {
		$icare_styles = get_theme_mod('icare_styles');
		$handle = wp_style_is('icare-theme-color-scheme') ? 'icare-theme-color-scheme' : 'icare-style';
		if (is_customize_preview() || (defined('WP_DEBUG') && true === WP_DEBUG) || (false === $icare_styles )) {
			wp_add_inline_style($handle, $this->icare_get_customizer_css());
		} else {
			wp_add_inline_style($handle, get_theme_mod('icare_styles'));
		}
	}

	/**
	 * Add CSS for custom controls
	 */
	public function icare_customizer_custom_control_css() {
		?>
            <style>
            .customize-control-radio-image .image.ui-buttonset input[type=radio] {
                height: auto;
            }

            .customize-control-radio-image .image.ui-buttonset label {
                display: inline-block;
                width: 18%;
                padding: 1%;
                box-sizing: border-box;
            }

            .customize-control-radio-image .image.ui-buttonset label.ui-state-active {
                background: none;
            }

            .customize-control-radio-image .customize-control-radio-buttonset label {
                background: #f7f7f7;
                line-height: 35px;
            }

            .customize-control-radio-image label img {
                opacity: 0.5;
            }

            #customize-controls .customize-control-radio-image label img {
                height: auto;
            }

            .customize-control-radio-image label.ui-state-active img {
                background: #dedede;
                opacity: 1;
            }

            .customize-control-radio-image label.ui-state-hover img {
                opacity: 1;
                box-shadow: 0 0 0 3px #f6f6f6;
            }
            </style>
            <?php
}

	/**
	 * Get site logo.
	 *
	 * @since 2.1.5
	 * @return string
	 */
	public function icare_get_site_logo() {
		return icare_site_title_or_logo(false);
	}

	/**
	 * Get site name.
	 *
	 * @since 2.1.5
	 * @return string
	 */
	public function icare_get_site_name() {
		return get_bloginfo('name', 'display');
	}

	/**
	 * Get site description.
	 *
	 * @since 2.1.5
	 * @return string
	 */
	public function icare_get_site_description() {
		return get_bloginfo('description', 'display');
	}

}
endif;
add_action('customize_controls_enqueue_scripts', 'icare_enqueue_control_assets');
function icare_enqueue_control_assets() {
	wp_enqueue_script('icare-custom-controls', get_template_directory_uri() . '/assets/js/icare-custom-controls.js', array('jquery', 'customize-controls'), '20130508', true);

	$actions       = icare_get_actions_required();
	$number_action = $actions['number_notice'];

	wp_localize_script('icare-custom-controls', 'icare_customizer_settings', array(
		'number_action' => $number_action,
		'action_url'    => admin_url('themes.php?page=ft_icare&tab=actions_required'),
	));

}
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function icare_customize_preview_js() {
	wp_enqueue_script('icare_customizer', get_template_directory_uri() . '/assets/js/icare-customizer-preview.js', array('customize-preview'), '20130508', true);
}
add_action('customize_preview_init', 'icare_customize_preview_js');

return new iCare_Customizer();