<?php
/**
 * Create a Radio-Image control
 * @link http://ottopress.com/2012/making-a-custom-control-for-the-theme-customizer/
 * @package  iCare
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * The radio image class.
 */
class iCare_Custom_Radio_Image_Control extends WP_Customize_Control
{

    /**
     * Declare the control type.
     *
     * @access public
     * @var string
     */
    public $type = 'radio-image';

    /**
     * Enqueue scripts and styles for the custom control.
     *
     * Scripts are hooked at {@see 'customize_controls_enqueue_scripts'}.
     *
     * Note, you can also enqueue stylesheets here as well. Stylesheets are hooked
     * at 'customize_controls_print_styles'.
     *
     * @access public
     */
    public function enqueue()
    {
        wp_enqueue_script('jquery-ui-button');
    }

    /**
     * Render the control to be displayed in the Customizer.
     */
    public function render_content()
    {
        if (empty($this->choices)) {
            return;
        }
        $name = $this->id;
        ?>

		<span class="customize-control-title">
			<?php echo esc_attr($this->label); ?>
		</span>

		<?php if (!empty($this->description)): ?>
			<span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
		<?php endif;?>

		<div id="input_<?php echo esc_attr($this->id); ?>" class="image">
			<?php foreach ($this->choices as $value => $label): ?>

				<input class="image-select" type="radio" value="<?php echo esc_attr($value); ?>" id="<?php echo esc_attr($this->id . $value); ?>" name="<?php echo esc_attr($name); ?>" <?php $this->link();
        checked($this->value(), $value);?>>
					<label for="<?php echo esc_attr($this->id) . esc_attr($value); ?>">
						<img src="<?php echo esc_html($label); ?>" alt="<?php echo esc_attr($value); ?>" title="<?php echo esc_attr($value); ?>">
					</label>
				</input>

			<?php endforeach;?>
		</div>

		<script>jQuery(document).ready(function($) { $( '[id="input_<?php echo esc_attr($this->id); ?>"]' ).buttonset(); });</script>
		<?php
}
}

/**
 * The radio image class.
 */
class iCare_Custom_sortable_Control extends WP_Customize_Control
{

    /**
     * Declare the control type.
     *
     * @access public
     * @var string
     */
    public $type = 'home-sortable';
   
    /**
     * Enqueue scripts and styles for the custom control.
     *
     * Scripts are hooked at {@see 'customize_controls_enqueue_scripts'}.
     *
     * Note, you can also enqueue stylesheets here as well. Stylesheets are hooked
     * at 'customize_controls_print_styles'.
     *
     * @access public
     */
    /*Enqueue resources for the control*/
    public function enqueue()
    {
        wp_enqueue_style('icare-customizer-sortable-admin-stylesheet', get_template_directory_uri() . '/assets/customizer_js_css/css/icare-customizer-control-style.css', time());
        
        wp_enqueue_script('icare-customizer-chosen-script', get_template_directory_uri() . '/assets/customizer_js_css/js/chosen.jquery.js', array("jquery"), '1.4.1', true);
        wp_enqueue_style('icare-customizer-chosen-style', get_template_directory_uri() . '/assets/customizer_js_css/css/chosen.css');
        wp_enqueue_script('icare-customizer-sortable-script', get_template_directory_uri() . '/assets/customizer_js_css/js/icare-customizer_sortable.js', array('jquery', 'jquery-ui-draggable'), time(), true);
    }

    /**
     * Render the control to be displayed in the Customizer.
     */
    public function render_content()
    {
        if (empty($this->choices)) {
            return;
        }
        $values = json_decode($this->value());
        $name         = $this->id;
        ?>

		<span class="customize-control-title">
			<?php echo esc_attr($this->label); ?>
		</span>

		<?php if (!empty($this->description)): ?>
			<span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
		<?php endif;?>

		<div class="icare-customizer-sortable-general-control-sortable customizer-sortable-general-control-droppable">
			<?php 
			if(!empty($values)){ 
				foreach ($values as $value) {?>
					<div class="icare-customizer-sortable-general-control-sortable-container customizer-sortable-draggable ui-sortable-handle">
					<div class="customizer-sortable-customize-control-title">
						<?php echo $this->choices[$value]; ?>
					</div>
					<input type="hidden" class="icare-section-id" value="<?php echo $value; ?>">
					</div>	
				<?php }?>
				
			<?php }else{
			foreach ($this->choices as $value => $label): ?>
					<div class="icare-customizer-sortable-general-control-sortable-container customizer-sortable-draggable ui-sortable-handle">
					<div class="customizer-sortable-customize-control-title">
						<?php echo $label; ?>
					</div>
					<input type="hidden" class="icare-section-id" value="<?php echo $value; ?>">
					</div>

				<?php endforeach;
			}
        		if (!empty($value)) {?>
					<input type="hidden"
					       id="customizer-sortable-<?php echo $this->id; ?>-colector" <?php esc_url($this->link());?>
					       class="icare-customizer-sortable-colector"
					       value="<?php echo esc_textarea(json_encode($value)); ?>"/>
					<?php
				} else {?>
					<input type="hidden"
					       id="customizer-sortable-<?php echo $this->id; ?>-colector" <?php esc_url($this->link());?>
					       class="icare-customizer-sortable-colector"/>
					<?php
				}?>
		</div>
		<?php
}
}
