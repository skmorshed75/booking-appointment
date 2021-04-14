<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package iCare
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function icare_body_classes($classes)
{
    global $post;
    // Adds a class of group-blog to blogs with more than 1 published author.
    if (is_multi_author()) {
        $classes[] = 'group-blog';
    }
    if ( is_customize_preview() ) {
        $classes[] = 'icare-customizer';
    }

    // Add class on front page.
    if ( is_front_page() && 'posts' !== get_option( 'show_on_front' ) ) {
        $classes[] = 'icare-front-page';
    }
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }
    if(get_theme_mod('icare_site_layout')!=""){
        $classes[] = get_theme_mod('icare_site_layout');
    }
    if (is_single() || is_page() && get_post_meta($post->ID, 'content_layout', true) && get_post_meta($post->ID, 'content_layout', true) != "") {
        $classes[] = sanitize_text_field(get_post_meta($post->ID, 'content_layout', true));
    }
    return $classes;
}
add_filter('body_class', 'icare_body_classes');

if( !function_exists( 'icare_excerpt' ) ){
    function icare_excerpt( $content , $letter_count ){
        $content = strip_shortcodes( $content );
        $content = strip_tags( $content );
        $content = mb_substr( $content, 0 , $letter_count );

        if( strlen( $content ) == $letter_count ){
            $content .= "...";
        }
        return $content;
    }
}

function icare_excerpt_word($content, $limit)
{   
    $excerpt = explode(' ', $content, $limit);
    
    if (count($excerpt) >= $limit) {
        array_pop($excerpt);
        $excerpt = implode(" ", $excerpt) . '...';
    } else {
        $excerpt = implode(" ", $excerpt);
    }
    $excerpt = preg_replace('`[[^]]*]`', '', $excerpt);
    return $excerpt;
}

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function icare_pingback_header()
{
    if (is_singular() && pings_open()) {
        echo '<link rel="pingback" href="', esc_url(get_bloginfo('pingback_url')), '">';
    }
}
add_action('wp_head', 'icare_pingback_header');

/* Breadcrumbs  */
function icare_breadcrumbs()
{
    /* === OPTIONS === */
    $text['home']     = esc_html__('Home','icare'); // text for the 'Home' link
    $text['category'] = esc_html__('Category "%s"','icare'); // text for a category page
    $text['search']   = esc_html__('Search Results for "%s"','icare'); // text for a search results page
    $text['tag']      = esc_html__('Posts Tagged "%s"','icare'); // text for a tag page
    $text['author']   = esc_html__('Posted by %s','icare'); // text for an author page
    $text['404']      = esc_html__('Error 404','icare'); // text for the 404 page
    $text['page']     = esc_html__('Page %s','icare'); // text 'Page N'
    $text['cpage']    = esc_html__('Comment Page %s','icare'); // text 'Comment Page N'
    $wrap_before    = '<div class="breadcrumb text-left sm-text-center text-black mt-20" itemscope itemtype="http://schema.org/BreadcrumbList">'; // the opening wrapper tag
    $wrap_after     = '</div><!-- .breadcrumbs -->'; // the closing wrapper tag
    $sep            = '<span class="breadcrumbs__separator">&nbsp;&nbsp;›&nbsp;&nbsp;</span>'; // separator between crumbs
    $before         = '<span class="breadcrumbs__current">'; // tag before the current crumb
    $after          = '</span>'; // tag after the current crumb
    $show_on_home   = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $show_home_link = 1; // 1 - show the 'Home' link, 0 - don't show
    $show_current   = 1; // 1 - show current page title, 0 - don't show
    $show_last_sep  = 1; // 1 - show last separator, when current page title is not displayed, 0 - don't show
    /* === END OF OPTIONS === */
    global $post;
    $home_url       = home_url('/');
    $link           = '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
    $link          .= '<a class="breadcrumbs__link" href="%1$s" itemprop="item"><span itemprop="name">%2$s</span></a>';
    $link          .= '<meta itemprop="position" content="%3$s" />';
    $link          .= '</span>';
    $parent_id      = ( $post ) ? $post->post_parent : '';
    $home_link      = sprintf( $link, $home_url, $text['home'], 1 );
    if ( is_home() || is_front_page() ) {
        if ( $show_on_home ) echo $wrap_before . $home_link . $wrap_after;
    } else {
        $position = 0;
        echo $wrap_before;
        if ( $show_home_link ) {
            $position += 1;
            echo $home_link;
        }
        if ( is_category() ) {
            $parents = get_ancestors( get_query_var('cat'), 'category' );
            foreach ( array_reverse( $parents ) as $cat ) {
                $position += 1;
                if ( $position > 1 ) echo $sep;
                echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
            }
            if ( get_query_var( 'paged' ) ) {
                $position += 1;
                $cat = get_query_var('cat');
                echo $sep . sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
                echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
            } else {
                if ( $show_current ) {
                    if ( $position >= 1 ) echo $sep;
                    echo $before . sprintf( $text['category'], single_cat_title( '', false ) ) . $after;
                } elseif ( $show_last_sep ) echo $sep;
            }
        } elseif ( is_search() ) {
            if ( get_query_var( 'paged' ) ) {
                $position += 1;
                if ( $show_home_link ) echo $sep;
                echo sprintf( $link, $home_url . '?s=' . get_search_query(), sprintf( $text['search'], get_search_query() ), $position );
                echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
            } else {
                if ( $show_current ) {
                    if ( $position >= 1 ) echo $sep;
                    echo $before . sprintf( $text['search'], get_search_query() ) . $after;
                } elseif ( $show_last_sep ) echo $sep;
            }
        } elseif ( is_year() ) {
            if ( $show_home_link && $show_current ) echo $sep;
            if ( $show_current ) echo $before . get_the_time('Y') . $after;
            elseif ( $show_home_link && $show_last_sep ) echo $sep;
        } elseif ( is_month() ) {
            if ( $show_home_link ) echo $sep;
            $position += 1;
            echo sprintf( $link, get_year_link( get_the_time('Y') ), get_the_time('Y'), $position );
            if ( $show_current ) echo $sep . $before . get_the_time('F') . $after;
            elseif ( $show_last_sep ) echo $sep;
        } elseif ( is_day() ) {
            if ( $show_home_link ) echo $sep;
            $position += 1;
            echo sprintf( $link, get_year_link( get_the_time('Y') ), get_the_time('Y'), $position ) . $sep;
            $position += 1;
            echo sprintf( $link, get_month_link( get_the_time('Y'), get_the_time('m') ), get_the_time('F'), $position );
            if ( $show_current ) echo $sep . $before . get_the_time('d') . $after;
            elseif ( $show_last_sep ) echo $sep;
        } elseif ( is_single() && ! is_attachment() ) {
            if ( get_post_type() != 'post' ) {
                $position += 1;
                $post_type = get_post_type_object( get_post_type() );
                if ( $position > 1 ) echo $sep;
                echo sprintf( $link, get_post_type_archive_link( $post_type->name ), $post_type->labels->name, $position );
                if ( $show_current ) echo $sep . $before . get_the_title() . $after;
                elseif ( $show_last_sep ) echo $sep;
            } else {
                $cat = get_the_category(); $catID = $cat[0]->cat_ID;
                $parents = get_ancestors( $catID, 'category' );
                $parents = array_reverse( $parents );
                $parents[] = $catID;
                foreach ( $parents as $cat ) {
                    $position += 1;
                    if ( $position > 1 ) echo $sep;
                    echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
                }
                if ( get_query_var( 'cpage' ) ) {
                    $position += 1;
                    echo $sep . sprintf( $link, get_permalink(), get_the_title(), $position );
                    echo $sep . $before . sprintf( $text['cpage'], get_query_var( 'cpage' ) ) . $after;
                } else {
                    if ( $show_current ) echo $sep . $before . get_the_title() . $after;
                    elseif ( $show_last_sep ) echo $sep;
                }
            }
        } elseif ( is_post_type_archive() ) {
            $post_type = get_post_type_object( get_post_type() );
            if ( get_query_var( 'paged' ) ) {
                $position += 1;
                if ( $position > 1 ) echo $sep;
                echo sprintf( $link, get_post_type_archive_link( $post_type->name ), $post_type->label, $position );
                echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
            } else {
                if ( $show_home_link && $show_current ) echo $sep;
                if ( $show_current ) echo $before . $post_type->label . $after;
                elseif ( $show_home_link && $show_last_sep ) echo $sep;
            }
        } elseif ( is_attachment() ) {
            $parent = get_post( $parent_id );
            $cat = get_the_category( $parent->ID ); 
            if($cat){
	            $catID = $cat[0]->cat_ID;
	            $parents = get_ancestors( $catID, 'category' );
	            $parents = array_reverse( $parents );
	            $parents[] = $catID;
	            foreach ( $parents as $cat ) {
	                $position += 1;
	                if ( $position > 1 ) echo $sep;
	                echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
	            }
	            $position += 1;
	        }
            echo $sep . sprintf( $link, get_permalink( $parent ), $parent->post_title, $position );
            if ( $show_current ) echo $sep . $before . get_the_title() . $after;
            elseif ( $show_last_sep ) echo $sep;
        } elseif ( is_page() && ! $parent_id ) {
            if ( $show_home_link && $show_current ) echo $sep;
            if ( $show_current ) echo $before . get_the_title() . $after;
            elseif ( $show_home_link && $show_last_sep ) echo $sep;
        } elseif ( is_page() && $parent_id ) {
            $parents = get_post_ancestors( get_the_ID() );
            foreach ( array_reverse( $parents ) as $pageID ) {
                $position += 1;
                if ( $position > 1 ) echo $sep;
                echo sprintf( $link, get_page_link( $pageID ), get_the_title( $pageID ), $position );
            }
            if ( $show_current ) echo $sep . $before . get_the_title() . $after;
            elseif ( $show_last_sep ) echo $sep;
        } elseif ( is_tag() ) {
            if ( get_query_var( 'paged' ) ) {
                $position += 1;
                $tagID = get_query_var( 'tag_id' );
                echo $sep . sprintf( $link, get_tag_link( $tagID ), single_tag_title( '', false ), $position );
                echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
            } else {
                if ( $show_home_link && $show_current ) echo $sep;
                if ( $show_current ) echo $before . sprintf( $text['tag'], single_tag_title( '', false ) ) . $after;
                elseif ( $show_home_link && $show_last_sep ) echo $sep;
            }
        } elseif ( is_author() ) {
            $author = get_userdata( get_query_var( 'author' ) );
            if ( get_query_var( 'paged' ) ) {
                $position += 1;
                echo $sep . sprintf( $link, get_author_posts_url( $author->ID ), sprintf( $text['author'], $author->display_name ), $position );
                echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
            } else {
                if ( $show_home_link && $show_current ) echo $sep;
                if ( $show_current ) echo $before . sprintf( $text['author'], $author->display_name ) . $after;
                elseif ( $show_home_link && $show_last_sep ) echo $sep;
            }
        } elseif ( is_404() ) {
            if ( $show_home_link && $show_current ) echo $sep;
            if ( $show_current ) echo $before . $text['404'] . $after;
            elseif ( $show_last_sep ) echo $sep;
        } elseif ( has_post_format() && ! is_singular() ) {
            if ( $show_home_link && $show_current ) echo $sep;
            echo get_post_format_string( get_post_format() );
        }
        echo $wrap_after;
    }
}
/* Blog navigation */
if (!function_exists('icare_pagination')) {
    function icare_pagination()
    {
        global $wp_query;
        $big   = 999999999; // need an unlikely integer
        $pages = paginate_links(array(
            'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format'    => '?paged=%#%',
            'current'   => max(1, get_query_var('paged')),
            'total'     => $wp_query->max_num_pages,
            'prev_next' => false,
            'type'      => 'array',
            'prev_next' => true,
            'prev_text' => '&#171;',
            'next_text' => '&#187;',
        ));
        if (is_array($pages)) {
            $paged = (get_query_var('paged') == 0) ? 1 : get_query_var('paged');
            echo '<div class="col-md-12"><ul class="pagination theme-colored">';
            foreach ($pages as $page) {
                $active = strpos($page, 'current') ? 'class="active"' : '';
                echo "<li $active >$page</li>";
            }
            echo '</ul></div>';
        }
    }
}
// Comment Function
function icare_comments($comments, $args, $depth)
{
    extract($args, EXTR_SKIP);
    if ('div' == $args['style']) {
        $add_below = 'comment';
    } else {
        $add_below = 'div-comment';
    }
    $class = is_rtl() ? 'media-object img-thumbnail comment-gravtar-r' : 'media-object img-thumbnail comment-gravtar';
    ?>

<li <?php comment_class(empty($args['has_children']) ? '' : 'parent')?> id="comment-<?php comment_ID()?>">
    <div class="media comment-author"> <?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comments, $args['avatar_size'],'','',array('class'=>$class) ); ?>
        <div class="media-body">
            <h5 class="media-heading comment-heading"><?php printf( __( '<cite class="fn">%s</cite> <span class="says">says:</span>','icare' ), get_comment_author_link() ); ?></h5>
            <div class="comment-date"><a href="<?php echo get_comment_link(); ?>"><?php printf(esc_html__('%1$s at %2$s', 'icare'), get_comment_date(), get_comment_time());?></a></div>
            <?php
if ($comments->comment_approved == '0') {?>
            <p><?php _e('Your comment is awaiting moderation.', 'icare');?></p><br/>
        </div><?php } else {comment_text();}?>
        <?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'])));?></div>
    </div>

    <?php
}

/**
 * Adjust a hex color brightness
 * Allows us to create hover styles for custom link colors
 *
 * @param  strong  $hex   hex color e.g. #111111.
 * @param  integer $steps factor by which to brighten/darken ranging from -255 (darken) to 255 (brighten).
 * @return string        brightened/darkened hex color
 * @since  1.0.0
 */
function icare_adjust_color_brightness($hex, $steps)
{   if(!$hex) return;
    // Steps should be between -255 and 255. Negative = darker, positive = lighter.
    $steps = max(-255, min(255, $steps));

    // Format the hex color string.
    $hex = str_replace('#', '', $hex);

    if (3 == strlen($hex)) {
        $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
    }

    // Get decimal values.
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    // Adjust number of steps and keep it inside 0 to 255.
    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));

    $r_hex = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
    $g_hex = str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
    $b_hex = str_pad(dechex($b), 2, '0', STR_PAD_LEFT);

    return '#' . $r_hex . $g_hex . $b_hex;
}
/**
 * convert hex color into rgba
 *
 * @param  strong  $color   hex color e.g. #111111.
 * @param  integer $opcity  0.0 to 1.
 * @return string        rgb(a) color
 * @since  1.0.0
 */
function icare_hex2rgba($color, $opacity = false)
{

    $default = 'rgb(0,0,0)';

    //Return default if no color provided
    if (empty($color)) {
        return ;
    }

    //Sanitize $color if "#" is provided
    if ($color[0] == '#') {
        $color = substr($color, 1);
    }

    //Check if color has 6 or 3 characters and get values
    if (strlen($color) == 6) {
        $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
    } elseif (strlen($color) == 3) {
        $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
    }

    //Convert hexadec to rgb
    $rgb = array_map('hexdec', $hex);

    //Check if opacity is set(rgba or rgb)
    if ($opacity) {
        if (abs($opacity) > 1) {
            $opacity = 1.0;
        }

        $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
    } else {
        $output = 'rgb(' . implode(",", $rgb) . ')';
    }

    //Return rgb(a) color string
    return $output;
}

//Customizer sannitize callback functions

/* Custom Sanitization Function  */
function icare_sanitize_checkbox($checked)
{
    return ((isset($checked) && (true == $checked || 'on' == $checked)) ? true : false);
}

function icare_sanitize_selected($value)
{
    if ($value[0] == '') {
        return $value = '';
    } else {
        return wp_kses_post($value);
    }
}
function icare_sanitize_choices( $input, $setting ) {
    global $wp_customize;
 
    $control = $wp_customize->get_control( $setting->id );
 
    if ( array_key_exists( $input, $control->choices ) ) {
        return $input;
    } else {
        return $setting->default;
    }
}
function icare_sanitize_text($value)
{
    return wp_kses_post(force_balance_tags($value));
}

function icare_sanitize_json_string($json){
    $sanitized_value = array();
    foreach (json_decode($json,true) as $value) {
        $sanitized_value[] = esc_attr($value);
    }
    return json_encode($sanitized_value);
}

/**
 * Sanitize colors.
 *
 * @since 1.0.0
 * @param string $value The color.
 * @return string
 */
function icare_color_string_sanitization( $value ) {
    // This pattern will check and match 3/6/8-character hex, rgb, rgba, hsl, & hsla colors.
    $pattern = '/^(\#[\da-f]{3}|\#[\da-f]{6}|\#[\da-f]{8}|rgba\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)(,\s*(0\.\d+|1))\)|hsla\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)(,\s*(0\.\d+|1))\)|rgb\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)|hsl\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)\))$/';
    \preg_match( $pattern, $value, $matches );
    // Return the 1st match found.
    if ( isset( $matches[0] ) ) {
        if ( is_string( $matches[0] ) ) {
            return $matches[0];
        }
        if ( is_array( $matches[0] ) && isset( $matches[0][0] ) ) {
            return $matches[0][0];
        }
    }
    // If no match was found, return an empty string.
    return '';
}

/**
 * Sanitize colors.
 *
 * @since 1.0.0
 * @param array $value The color.
 * @return array
 */
function icare_color_array_sanitization( $value ) {
    return [
        'r' => (int) $value['r'],
        'g' => (int) $value['g'],
        'b' => (int) $value['b'],
        'h' => (int) $value['h'],
        's' => (int) $value['s'],
        'l' => (int) $value['l'],
        'a' => (float) $value['a'],

        'hex' => icare_color_string_sanitization( $value['hex'] ),
        'css' => icare_color_string_sanitization( $value['css'] ),

        'a11y' => [
            'luminance'         => (float) $value['a11y']['luminance'],
            'distanceFromWhite' => (float) $value['a11y']['distanceFromWhite'],
            'distanceFromBlack' => (float) $value['a11y']['distanceFromBlack'],
            'maxContrastColor'  => icare_color_string_sanitization( $value['a11y']['maxContrastColor'] ),
            'isDark'            => (float) $value['a11y']['isDark'],

            'readableContrastingColorFromWhite' => [
                icare_color_string_sanitization( $value['a11y']['readableContrastingColorFromWhite'][0] ),
                icare_color_string_sanitization( $value['a11y']['readableContrastingColorFromWhite'][1] ),
            ],
            'readableContrastingColorFromBlack' => [
                icare_color_string_sanitization( $value['a11y']['readableContrastingColorFromBlack'][0] ),
                icare_color_string_sanitization( $value['a11y']['readableContrastingColorFromBlack'][1] ),
            ]
        ]
    ];
}

/* Woocommerce supoport */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_before_main_content', 'icare_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'icare_theme_wrapper_end', 10);
function icare_theme_wrapper_start()
{
    ?>
<section class="inner-header-transform divider parallax section-overlay breadcrumb-overlay">
  <div class="container pt-thirty pb-thirty">
    <!-- Section Content -->
    <div class="section-content">
      <div class="row">
        <div class="col-sm-8 text-left flip xs-text-center crumbs">

        <?php if (!is_product()): ?>
                <h2 class="title"><?php woocommerce_page_title();?></h2>
        <?php else:
                woocommerce_template_single_title();
            endif;?>
        </div>
        <div class="col-sm-4">
          <?php icare_breadcrumbs();?>
        </div>
      </div>
    </div>
  </div>
</section>
<section>
  <div class="container mt-thirty mb-thirty pt-thirty pb-thirty">
    <div class="row">
      <div class="col-md-9 post-content">
        <div class="blog-posts single-post">
    <?php
}
function icare_theme_wrapper_end()
{?>
    </div>
       </div>
        <?php do_action('woocommerce_sidebar');?>
    </div>
</div>
</section>
<?php }

/**
 * icare_hide_page_title
 *
 * Removes the "shop" title on the main shop page
 */
function icare_hide_page_title()
{
    return false;
}
add_filter('woocommerce_show_page_title', 'icare_hide_page_title');
/**
 * Removes breadcrumbs
 */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

function icare_import_files() {
  return array(
    array(
      'import_file_name'             => 'Demo Import 1',
      'local_import_file'            => trailingslashit( get_template_directory() ) . 'inc/ocdi/demo-content.xml',
      'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'inc/ocdi/widgets.json',
      'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'inc/ocdi/customizer.dat',
      'import_preview_image_url'     => trailingslashit( get_template_directory() ) . 'screenshot.png',
      'import_notice'                => __( 'Save time by import our demo data, your website will be set up and ready to customize in minutes.', 'icare' ),
      'preview_url'                  => 'https://www.demo.webhuntinfotech.com/icare',
    ),
  );
}
add_filter( 'pt-ocdi/import_files', 'icare_import_files' );
function icare_after_import_setup() {
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
	
	// Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'primary', 'nav_menu' );
    $top_menu = get_term_by( 'name', 'social', 'nav_menu' );
    set_theme_mod( 'nav_menu_locations', array(
			'primary-menu' => $main_menu->term_id,
			'topbar-menu' => $top_menu->term_id,
		)
    );
}
add_action( 'pt-ocdi/after_import', 'icare_after_import_setup' );