<?php if (get_theme_mod('icare_show_team') == 'on') {    ?>
  <section id="icare-home-team-section">
    <div class="container pb-30 pb-sm-0"><?php 
      if (get_theme_mod('icare_team_head') != "" || get_theme_mod('icare_team_desc') != "") {?>
        <div class="section-heading text-center">
          <div class="row">
            <div class="col-md-8 col-md-offset-2">
              <?php if (get_theme_mod('icare_team_head') != "") {?>
              <h2 id="team_title" class="mt-zero mb-ten line-height-one text-uppercase"><?php echo esc_html(get_theme_mod('icare_team_head')); ?></h2>
              <?php } if (get_theme_mod('icare_team_desc') != "") {?>
              <p id="team_desc"><?php echo esc_html(get_theme_mod('icare_team_desc')); ?></p>
              <?php } ?>
            </div>
          </div>
        </div><?php 
      } ?>
      <div class="section-content">
        <div class="row"><?php
          for ($i = 1; $i < 5; $i++) {
            $icare_team_page_id   = get_theme_mod('icare_team_page' . $i);
            if ($icare_team_page_id) {
              $args  = array('page_id' => absint($icare_team_page_id));
              $query = new WP_Query($args);
              if ($query->have_posts()):
                while ($query->have_posts()): $query->the_post();
                  $icare_team_designation = get_theme_mod('icare_team_designation'.$i);
                  $icare_team_facebook = get_theme_mod('icare_team_facebook'.$i);
                  $icare_team_twitter = get_theme_mod('icare_team_twitter'.$i);
                  $icare_team_linkedin = get_theme_mod('icare_team_linkedin'.$i);?>
                  <div class="col-sm-6 col-md-3 mb-sm-30">
                    <div class="team-block">
                      <a id="test" href="<?php echo get_the_permalink(); ?>">
                        <div class="team-thumb"><?php 
                          if( has_post_thumbnail() ){
                            the_post_thumbnail('icare_home_team_profile',array("class"=>'img-full-width'));
                          } ?>
                          <div class="team-overlay">
                            <p><?php  echo icare_excerpt_word( get_the_excerpt(), 25);?></p>
                          </div>
                        </div>
                      </a>
                      <div class="info bg-white text-center">
                        <a href="<?php the_permalink(); ?>"><h4><?php the_title(); if($icare_team_designation!=''){?> <small>&ndash; <?php echo esc_attr($icare_team_designation); ?></small><?php } ?></h4></a>
                        <ul class="styled-icons icon-theme-colored icon-circled icon-dark icon-sm mt-15 mb-0">
                          <li><a href="<?php echo esc_url($icare_team_facebook); ?>"><i class="fab fa-facebook-f"></i></a></li>
                          <li><a href="<?php echo esc_url($icare_team_twitter); ?>"><i class="fab fa-twitter"></i></a></li>
                          <li><a href="<?php echo esc_url($icare_team_linkedin); ?>"><i class="fab fa-linkedin-in"></i></a></li>
                        </ul>
                      </div>
                    </div>
                  </div><?php 
                endwhile; 
                wp_reset_postdata();
              endif; 
            } 
          }?>
        </div>
      </div>
    </div>
  </section><?php 
} ?>