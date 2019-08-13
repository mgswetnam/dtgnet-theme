<?php
//update_option('siteurl','http://dev.deadtreegames.com/');
//update_option('home','http://dev.deadtreegames.com/');
//update_option('siteurl','https://www.deadtreegames.com/');
//update_option('home','https://www.deadtreegames.com/');

// Defines
define( 'FL_CHILD_THEME_DIR', get_stylesheet_directory() );
define( 'FL_CHILD_THEME_URL', get_stylesheet_directory_uri() );
define( 'DTG_CONTENT_URL', content_url() );
define( 'DTG_DTGNET_VERSION', '1.0.0' );

// Classes
require_once 'classes/class-dtg-core.php';
require_once 'classes/class-dtg-custom.php';
require_once 'classes/class-dtg-metaboxes.php';
require_once 'classes/widgets/class-dtg-widgets.php';
if ( is_admin() ){
  require_once 'admin/class-dtg-admin.php';
}
require_once 'admin/class-dtg-customizer.php';

// Actions
add_action( "init", array( "DTG_Core", "init" ) );
add_action( "widgets_init", array( "DTG_Core", "dtg_widgets_setup" ) );
add_action( "init", array( "DTG_DTGNET_Custom", "init" ) );
add_action( 'init', array( 'DTG_DTGNET_Metaboxes', 'init' ) );
add_action( "init", array( "DTG_Featured_Links_Redux", "init" ) );
add_action( "init", array( "DTG_Latest_Articles", "init" ) );
if ( is_admin() ){
  add_action( 'init', array( 'DTG_Admin', 'init' ) );
}
add_action( 'init', array( 'DTG_Customizer', 'init' ) );

// Filters
add_filter( "widget_text", "do_shortcode" );

/* Begin Minification */
if ( ! is_admin() ){
  class WP_HTML_Compression {
  	// Settings
  	protected $compress_css = false;
  	protected $compress_js = false;
  	protected $info_comment = true;
  	protected $remove_comments = false;
  	// Variables
  	protected $html;

  	public function __construct( $html ){
  		if ( !empty( $html ) ){
  			$this->parseHTML( $html );
  		}
  	}

  	public function __toString(){
  		return $this->html;
  	}

  	protected function bottomComment( $raw, $compressed ){
  		$raw = strlen( $raw );
  		$compressed = strlen( $compressed );
  		$savings = ( $raw-$compressed ) / $raw * 100;
  		$savings = round( $savings, 2 );
  		return '<!--HTML compressed, size saved '.$savings.'%. From '.$raw.' bytes, now '.$compressed.' bytes-->';
  	}

  	protected function minifyHTML( $html ){
  		$pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
  		preg_match_all( $pattern, $html, $matches, PREG_SET_ORDER );
  		$overriding = false;
  		$raw_tag = false;
  		// Variable reused for output
  		$html = '';
  		foreach( $matches as $token ){
  			$tag = ( ( isset( $token[ 'tag' ] ) ) ? strtolower( $token[ 'tag' ] ) : null );
  			$content = $token[ 0 ];
  			if( is_null( $tag ) ){
  				if( !empty( $token[ 'script' ] ) ){
  					$strip = $this->compress_js;
  				}
  				else if( !empty( $token[ 'style' ] ) ){
  					$strip = $this->compress_css;
  				}
  				else if( $content == '<!--wp-html-compression no compression-->' ){
  					$overriding = !$overriding;
  					// Don't print the comment
  					continue;
  				}
  				else if( $this->remove_comments ){
  					if( !$overriding && $raw_tag != 'textarea' ){
  						// Remove any HTML comments, except MSIE conditional comments
  						$content = preg_replace( '/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content );
  					}
  				}
  			} else {
  				if( $tag == 'pre' || $tag == 'textarea' ){
  					$raw_tag = $tag;
  				}
  				else if( $tag == '/pre' || $tag == '/textarea' ){
  					$raw_tag = false;
  				} else {
  					if( $raw_tag || $overriding ){
  						$strip = false;
  					} else {
  						$strip = true;
  						// Remove any empty attributes, except: action, alt, content, src
  						$content = preg_replace( '/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content );
  						// Remove any space before the end of self-closing XHTML tags JavaScript excluded
  						$content = str_replace( ' />', '/>', $content );
  					}
  				}
  			}
  			if( $strip ){
  				$content = $this->removeWhiteSpace( $content );
  			}
  			$html .= $content;
  		}
  		return $html;
  	}

  	public function parseHTML( $html )
  	{
  		$this->html = $this->minifyHTML( $html );
  		if( $this->info_comment ){
  			$this->html .= "\n" . $this->bottomComment( $html, $this->html );
  		}
  	}

  	protected function removeWhiteSpace( $str )
  	{
  		$str = str_replace( "\t", ' ', $str );
  		$str = str_replace( "\n",  '', $str );
  		$str = str_replace( "\r",  '', $str );
  		while (stristr($str, '  ')){
  			$str = str_replace( '  ', ' ', $str );
  		}
  		return $str;
  	}
  }

  function wp_html_compression_finish( $html ){
  	return new WP_HTML_Compression( $html );
  }

  function wp_html_compression_start(){
  	ob_start( 'wp_html_compression_finish' );
  }
  add_action( 'get_header', 'wp_html_compression_start' );
}
/* End Minification */

/* Remove Query Strings from Static Resources */
function remove_cssjs_ver( $src ){
 if( strpos( $src, '?ver=' ) )
 $src = remove_query_arg( 'ver', $src );
 return $src;
}
add_filter( 'style_loader_src', 'remove_cssjs_ver', 10, 2 );
add_filter( 'script_loader_src', 'remove_cssjs_ver', 10, 2 );

/* Begin disable emoji functionality and corresponding code bloat since wordpress 4.2 */
function disable_emojis(){
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );

/* Filter function used to remove the tinymce emoji plugin */
function disable_emojis_tinymce( $plugins ) {
	if( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}
