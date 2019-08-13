<?php

/**
 * Helper class for Beaver Builder child theme
 * @class DTG_Core
 */

final class DTG_Core {

  private $version;
  public static $_instance;

  static function init(){
    if ( !self::$_instance ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function __construct() {
    // Actions
    add_action( "wp_print_scripts", array( $this, "dtg_theme_setup" ) );
		add_action( 'add_meta_boxes', array( $this, 'dtg_add_meta_boxes' ) );
    // Filters
    add_filter( "style_loader_tag", array( $this, "dtg_remove_type_attr" ), 10, 2);
    add_filter( "script_loader_tag", array( $this, "dtg_remove_type_attr" ), 10, 2);
    // Shortcodes
    add_shortcode( "dtg_render_title", array( $this, "dtg_render_title" ) );
    add_shortcode( "dtg_social_icons", array( $this, "dtg_social_icons" ) );
  }

  public static function dtg_theme_setup(){
		// Scripts
    wp_enqueue_script( "jquery" );
    wp_enqueue_script( "dtg-ca-main", FL_CHILD_THEME_URL . "/assets/dist/js/main.min.js", array( "jquery" ), DTG_DTGNET_VERSION );
    // Styles
    wp_enqueue_style( "dtg-ca-main", FL_CHILD_THEME_URL . "/assets/dist/css/main.min.css", array(), DTG_DTGNET_VERSION );
  }

	public function dtg_add_meta_boxes(){
    $metabox = new DTG_DTGNET_Metaboxes();
    $metabox->dtg_add_meta_boxes();
	}

	public function dtg_save_custom(){
    $metabox = new DTG_DTGNET_Metaboxes();
    $metabox->dtg_save_custom_fields();
	}

  // This is necessary to remove type tag from scripts and styles
  // Revisit if Wordpress changes the way they load scripts and styles
  public static function dtg_remove_type_attr($tag, $handle) {
    return preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $tag );
  }

  public static function dtg_widgets_setup() {
    if ( function_exists( 'register_sidebar' ) ){
      register_sidebar( array(
          'name' => __( 'Header CTA', 'dtg-canadensis' ),
          'id' => 'dtg-header-cta',
          'before_widget' => '<aside class = "dtg-header-widget dtg-cta">',
          'after_widget' => '</aside>',
  				'before_title' => '<h4 class="fl-widget-title dtg-widget-title">',
  				'after_title' => '</h4>',
      ) );
      register_sidebar( array(
          'name' => __( 'Footer Ribbon', 'dtg-canadensis' ),
          'id' => 'dtg-footer-ribbon',
          'before_widget' => '<aside class = "dtg-footer-ribbon-widget">',
          'after_widget' => '</aside>',
  				'before_title' => '<h4 class="dtg-widget-title">',
  				'after_title' => '</h4>',
      ) );
      register_sidebar( array(
          'name' => __( 'Footer Sub Ribbon', 'dtg_canadensis' ),
          'id' => 'dtg-footer-sub-ribbon',
          'description' => 'Appears in the strip just below footer widgets',
          'class'  => 'dtg-footer-ribbon-widget-wrapper',
          'before_widget' => '<aside class = "dtg-footer-widget sub-ribbon">',
          'after_widget' => '</aside>',
  				'before_title' => '<h4 class="fl-widget-title dtg-widget-title">',
  				'after_title' => '</h4>',
      ) );
      register_sidebar( array(
          'name' => __( 'Article Adjacent', 'dtg_canadensis' ),
          'id' => 'dtg-articleadjacent',
          'description' => 'Stuff that appears with the articles',
          'class' => 'dtg_articleadjacent_wrapper',
          'before_widget' => '<aside class = "dtg_articleadjacent %2$s">',
          'after_widget' => '</aside>',
  				'before_title' => '<h4 class="fl-widget-title dtg_articleadjacent_title">',
  				'after_title' => '</h4>',
      ) );
      register_sidebar( array(
          'name' => __( 'After Nav', 'dtg_canadensis' ),
          'id' => 'dtg-afternav',
          'description' => 'Content to display just after the navigation bar',
          'class' => 'dtg_afternav_wrapper',
          'before_widget' => '<aside class = "dtg_afternav %2$s">',
          'after_widget' => '</aside>',
  				'before_title' => '<h4 class="fl-widget-title dtg_afternav_title">',
  				'after_title' => '</h4>',
      ) );
    }
  }

  public function dtg_render_title( $atts ){
    $a = shortcode_atts( array(
			"post" => "",
      "formatted" => "true"
		), $atts );

    $search = array( '/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/<!--(.|\s)*?-->/' );
    $replace = array( '>', '<', '\\1', '' );

    ob_start();
    $title = get_the_title( $a[ "post" ] );
    if( $a[ "formatted" ] == "false" ){
      ?>
      <span><?=$title?></span>
      <?php
      edit_post_link( _x( 'Edit', 'Edit page link text.', 'fl-automator' ) );
    } else {
      ?>
      <h1 class="fl-post-title" itemprop="headline"><?=$title?></h1>
      <?php
      edit_post_link( _x( 'Edit', 'Edit page link text.', 'fl-automator' ) );
    }

    $buffer =  ob_get_clean();

    $buffer = preg_replace( $search, $replace, $buffer );

    return $buffer;
  }

  public function dtg_social_icons( $atts ){
    $a = shortcode_atts( array(
      "bg" => "",
      "class" => "",
      "blog" => "",
		), $atts );
    // Set variables
    $bg = $a[ "bg" ];
    $class = $a[ "class" ];
    $blog = $a[ "blog" ];

    $search = array( '/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/<!--(.|\s)*?-->/' );
    $replace = array( '>', '<', '\\1', '' );

    $thesettings = FLTheme::get_settings();
    $socials = array();
    foreach( $thesettings as $key=>$value ){
    	if( strpos( $key, 'fl-social-' ) !== false && $key !== 'fl-social-icons-color' && $value !== '' ){
    		$socials[ $key ] = $value;
    	}
    }

    ob_start();
    ?>
    <div id="dtg-social-icons-wrapper-outter" class="dtg-social-icons wrapper-outter <?=$a[ "class" ]?>">
      <div class="wrapper-inner">
        <div class="wrapper-content">
        <?php
        if( $blog !== "" ){
        ?>
        <a href="<?=$blog?>" target="self" class="dtg-icon-item-wrapper" title="Blog">
          <span class="fa-stack fa-xs">
            <i class="fas fa-square fa-stack-2x"></i>
            <i class="dtg-icon-item dtgf dtg-blog fa-stack-1x fa-inverse" style="line-height:inherit;"></i>
          </span>
        </a>
        <?php }
      	foreach( $socials as $k=>$v ){
          $platform = str_replace( "fl-social-", "", $k );
          $fab = "";
          $incrowd = array( "facebook","twitter","google","snapchat","linkedin","yelp","xing","pinterest","tumblr","vimeo","youtube","flickr","instagram","skype","dribbble","500px","blogger","github","rss","email" );
          if( in_array( $platform, $incrowd ) === true ){
            switch( $platform ){
              case "facebook":{ $fab = "fab fa-facebook-f"; break; }
              case "twitter":{ $fab = "fab fa-twitter"; break; }
              case "google":{ $fab = "fab fa-google-plus-g"; break; }
              case "snapchat":{ $fab = "fab fa-snapchat-ghost"; break; }
              case "linkedin":{ $fab = "fab fa-linkedin-in"; break; }
              case "yelp":{ $fab = "fab fa-yelp"; break; }
              case "xing":{ $fab = "fab fa-xing"; break; }
              case "pinterest":{ $fab = "fab fa-pinterest-p"; break; }
              case "tumblr":{ $fab = "fab fa-tumblr"; break; }
              case "vimeo":{ $fab = "fab fa-vimeo-v"; break; }
              case "youtube":{ $fab = "fab fa-youtube"; break; }
              case "flickr":{ $fab = "fab fa-flickr"; break; }
              case "instagram":{ $fab = "fab fa-instagram"; break; }
              case "skype":{ $fab = "fab fa-skype"; break; }
              case "dribbble":{ $fab = "fab fa-dribbble"; break; }
              case "500px":{ $fab = "fab fa-500px"; break; }
              case "blogger":{ $fab = "fab fa-blogger-b"; break; }
              case "github":{ $fab = "fab fa-github"; break; }
              case "rss":{ $fab = "fas fa-rss"; break; }
              case "email":{ $fab = "fas fa-envelope"; break; }
            }
            ?>
            <a href="<?=$v?>" target="_blank" class="dtg-icon-item-link" title="<?=ucfirst( $platform )?>">
              <?php
              if( $bg !== "" ){ ?>
                <span class="dtg-icon-item-wrapper fa-stack fa-xs">
                  <i class="fas fa-<?=$bg?> fa-stack-2x"></i>
                  <i class="dtg-icon-item <?=$fab?> fa-stack-1x fa-inverse"></i>
                </span>
                <?php
              } else {
              ?>
              <i class="<?=$fab?>"></i>
            <?php } ?>
            </a>
            <?php
          }
        }
        ?>
        </div>
      </div>
    </div>
    <?php
  	$buffer =  ob_get_clean();

    // We have to minimize the HTML because otherwise
    // line breaks are rendered incorrectly in widgets
    $buffer = preg_replace( $search, $replace, $buffer );
    return $buffer;
  }
}
