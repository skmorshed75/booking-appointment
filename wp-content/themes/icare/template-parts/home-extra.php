<?php
if ( get_theme_mod('icare_show_extra')=='on') {
  $pagedata = '';
  if(get_theme_mod('icare_home_extra')!=""){
    $pagedata = get_post(get_theme_mod('icare_home_extra'));
    $src = wp_get_attachment_image_src(get_post_thumbnail_id( $pagedata->ID ),'large',false);
  }?>
<section id="icare-home-extra-section" <?php if(isset($src[0])){echo 'data-bg-img="'.$src[0].'" class="divider parallax layer-overlay color-overlay" data-parallax-ratio="0.7"';}?>>
  <div class="container pt-fifty pb-fifty">
    <div class="section-content"><?php
      if (get_theme_mod('icare_extra_head') || get_theme_mod('icare_extra_desc')) {?>
      <div class="row  text-center">
        <div class="col-md-12 wow fadeInUp icare_extra" data-wow-duration="1s" data-wow-delay="0.3s">
        <?php if (get_theme_mod('icare_extra_head') ) {?>
          <h3 id="extra_title" class="text-uppercase mt-zero"><?php echo esc_attr(get_theme_mod('icare_extra_head')); ?></h3>
          <?php } 
          if (get_theme_mod('icare_extra_desc')) {?>
          <h5 id="extra_desc" class="text-gray-dimgray font-weight-4h"><?php echo esc_attr(get_theme_mod('icare_extra_desc')); ?></h5>
          <?php } ?>
        </div>
      </div><?php 
      } if($pagedata!=""){?>
      <div class="row mt-fourty">
        <div class="col-sm-12">
      <?php 
      echo apply_filters('the_content',$pagedata->post_content);
      ?></div>
      </div><?php 
      } ?>
    </div>
  </div>
</section><?php } ?>