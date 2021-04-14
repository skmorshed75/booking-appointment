<?php if(get_theme_mod('icare_show_aboutus')=='on'){
  $col = 'col-md-12';
  if(get_theme_mod('icare_aboutus_page')!="" && (get_theme_mod('icare_about_widget')!="0" || get_theme_mod('icare_about_image')!="")){
    $col = 'col-md-7';
  }
  ?>
<section id="icare-home-about-section">
  <div class="container pb-thirty pb-sm-sixty">
    <div class="section-content">
      <div class="row">
        <div class="<?php echo esc_attr($col); ?>">
          <?php 
            $icare_about_page_id = get_theme_mod('icare_aboutus_page');
            if($icare_about_page_id){
                      $args = array(
                  'page_id' => absint($icare_about_page_id)
                  );
                $query = new WP_Query($args);
                if($query->have_posts()):
                  while($query->have_posts()) : $query->the_post();
                ?>
                <h3 class="mt-zero line-height-one line-bottom"><?php the_title(); ?></h3>
                  <?php 
                if(has_excerpt()){
                  the_excerpt();
                }else{
                  the_content(); 
                } ?>
                <?php
                endwhile;
                endif;  
                wp_reset_postdata();
            } ?>  
            <div class="progressbar-container mt-thirty">
                <?php
                for ($i=1; $i < 6; $i++) { 
                  $icare_about_progressbar_title = get_theme_mod('icare_about_progressbar_title'.$i);
                  $icare_about_progressbar_percentage = get_theme_mod('icare_about_progressbar_percentage'.$i);
                  $icare_about_progressbar_disable = get_theme_mod('icare_about_progressbar_disable'.$i);

                  if(!$icare_about_progressbar_disable && $icare_about_progressbar_percentage!=''){ ?>

                  <div class="progress-item style2 ">
                    <div class="progress-title">
                      <h6><?php echo esc_html($icare_about_progressbar_title); ?></h6>
                    </div>
                    <div class="progress">
                      <div class="progress-bar" data-percent="<?php echo absint($icare_about_progressbar_percentage); ?>"  style="min-width: 2em;"></div>
                    </div>
                  </div>

                  <?php
                  }
                }
                ?>
            </div>   
        </div>
        <?php if(get_theme_mod('icare_about_widget')!="" || get_theme_mod('icare_about_image')!=""){?>
          <div class="col-md-5">
            <?php 
            $icare_about_widget = get_theme_mod('icare_about_widget');
            if($icare_about_widget){
              dynamic_sidebar($icare_about_widget);
            }else{
              echo wp_get_attachment_image(absint( get_theme_mod('icare_about_image') ), 'large');
            }
          ?>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</section>
<?php } ?>