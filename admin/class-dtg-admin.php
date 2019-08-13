<?php

/**
 * Helper class for Beaver Builder child theme
 * @class DTG_Admin
 */

final class DTG_Admin {

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
    add_action( "admin_head", array( $this, "dtg_cs_admin_setup" ) );
    // Filters
    //add_filter( "style_loader_tag", array( $this, "dtg_remove_type_attr" ), 10, 2 );
    // Shortcodes
    //add_shortcode( "dtg_render_title", array( $this, "dtg_render_title" ) );
  }

  // -- Actions --

  public static function dtg_cs_admin_setup(){
		// Scripts
    wp_enqueue_script( 'dtg-cs-admin', FL_CHILD_THEME_URL . '/admin/assets/dist/js/admin.min.js', array('jquery'), DTG_DTGNET_VERSION );
    // Styles
    wp_enqueue_style( 'dtg-cs-admin', FL_CHILD_THEME_URL . '/admin/assets/dist/css/admin.min.css', array(), DTG_DTGNET_VERSION );
  }

  // -- Filters --



  // -- Shortcodes --


}
