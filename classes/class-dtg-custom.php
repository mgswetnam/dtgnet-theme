<?php

/**
 * Helper class for Beaver Builder child theme
 * Use this file for any custom coding needed
 * @class DTG_DTGNET_Custom
 */

final class DTG_DTGNET_Custom {

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
    //add_action( "pre_get_posts", array( $this, "dtg_blog_category" ) );
    // Filters

    // Shortcodes

  }

  public function dtg_blog_category( $query ){
    if ( $query->is_home() && $query->is_main_query() ) {
      $query->set( 'cat', '8');
    }
  }
}
