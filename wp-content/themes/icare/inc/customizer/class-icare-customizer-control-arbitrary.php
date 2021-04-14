<?php 
if( class_exists( 'WP_Customize_Control' ) ):	

class Icare_Display_Gallery_Control extends WP_Customize_Control{
	public $type = 'gallery';
	 
	public function render_content() {
	?>
	<label>
		<span class="customize-control-title">
			<?php echo esc_html( $this->label ); ?>
		</span>

		<?php if($this->description){ ?>
			<span class="description customize-control-description">
			<?php echo wp_kses_post($this->description); ?>
			</span>
		<?php } ?>

		<div class="gallery-screenshot clearfix">
    	<?php
        	{
        	$ids = explode( ',', $this->value() );
            	foreach ( $ids as $attachment_id ) {
                	$img = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
                	echo '<div class="screen-thumb"><img src="' . esc_url($img[0]) . '" /></div>';
            	}
        	}
    	?>
    	</div>

    	<input id="edit-gallery" class="button upload_gallery_button" type="button" value="<?php esc_attr_e('Add/Edit Gallery','icare') ?>" />
		<input id="clear-gallery" class="button upload_gallery_button" type="button" value="<?php esc_attr_e('Clear','icare') ?>" />
		<input type="hidden" class="gallery_values" <?php echo $this->link() ?> value="<?php echo esc_attr( $this->value() ); ?>">
	</label>
	<?php
	}
}


class Icare_Customize_Heading extends WP_Customize_Control {
	public $type = 'heading';

    public function render_content() {
    	if ( !empty( $this->label ) ) : ?>
            <h3 class="icare-accordion-section-title"><?php echo esc_html( $this->label ); ?></h3>
        <?php endif;

        if($this->description){ ?>
			<span class="description customize-control-description">
			<?php echo wp_kses_post($this->description); ?>
			</span>
		<?php }
    }
}

class Icare_Switch_Control extends WP_Customize_Control{
	public $type = 'switch';
	public $on_off_label = array();

	public function __construct($manager, $id, $args = array() ){
        $this->on_off_label = $args['on_off_label'];
        parent::__construct( $manager, $id, $args );
    }

	public function render_content(){
    ?> 
	    <span class="customize-control-title">
			<?php echo esc_html( $this->label ); ?>
		</span>

		<?php if($this->description){ ?>
			<span class="description customize-control-description">
			<?php echo wp_kses_post($this->description); ?>
			</span>
		<?php } ?>

		<?php
			$switch_class = ($this->value() == 'on') ? 'switch-on' : '';
			$on_off_label = $this->on_off_label;
		?>
		<div class="onoffswitch <?php echo $switch_class; ?>">
			<div class="onoffswitch-inner">
				<div class="onoffswitch-active">
					<div class="onoffswitch-switch"><?php echo esc_html($on_off_label['on']) ?></div>
				</div>

				<div class="onoffswitch-inactive">
					<div class="onoffswitch-switch"><?php echo esc_html($on_off_label['off']) ?></div>
				</div>
			</div>	
		</div>
		<input <?php $this->link(); ?> type="hidden" value="<?php echo esc_attr($this->value()); ?>"/>
		<?php
    }
}

endif;