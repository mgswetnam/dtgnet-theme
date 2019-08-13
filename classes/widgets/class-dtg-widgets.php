<?php
if ( ! class_exists( 'DTG_Featured_Links_Redux' ) ) {
  class DTG_Featured_Links_Redux extends WP_Widget
  {
    private $version;
    public static $_instance;

    static function init(){
      if ( !self::$_instance ) {
        self::$_instance = new self();
      }
      return self::$_instance;
    }

    public function __construct(){
      parent::__construct(
        'DTG_Featured_Links_Redux', // Base ID
        __('DTG Featured Links', 'canadensis-as'), // Name
        array( 'classname' => 'featured-links-widget', 'description' =>__('Displays list of featured links in the sidebar w/ icons.', 'canadensis-as'), ) // Args
      );
    }

    public function form( $instance ){
      // Get variables
      $instance = wp_parse_args( ( array ) $instance, array( 'heading' => '' ) );
      $heading = ( ( array_key_exists( 'heading', $instance ) )? $instance[ 'heading' ] : NULL );
      $title = ( ( array_key_exists( 'title', $instance ) )? $instance[ 'title' ] : NULL );
      $desc = ( ( array_key_exists( 'desc', $instance ) )? $instance[ 'desc' ] : NULL );
      $url = ( ( array_key_exists( 'url', $instance ) )? $instance[ 'url' ] : NULL );
      $icon = ( ( array_key_exists( 'icon', $instance ) )? $instance[ 'icon' ] : NULL );
      ?>
      <div class="field-wrapper">
        <label for="<?php echo esc_attr( $this->get_field_id( 'heading' ) ); ?>"><?php _e( 'Heading', 'canadensis-lindstrom' ); ?>
          <input class="upcoming dtg-featured-links text-field link-heading" id="<?php echo esc_attr( $this->get_field_id( 'heading' ) ); ?>" size='40' name="<?php echo esc_attr( $this->get_field_name( 'heading' ) ); ?>" type="text" value="<?php echo esc_attr( $heading ); ?>" />
        </label>
      </div>
      <!-- Begin Links -->
      <div id="dtg-featured-links-wrapper" class="dtg-admin dtg-featured-link-wrapper">
        <?php
        if( count( $url ) > 0 ){
          $i=0;
          foreach( $url as $item ){
        ?>
        <fieldset id="dtg-featured-link|<?=$i+1?>" class="dtg-admin dtg-featured-link" data-link-number="<?=$i+1?>">
          <legend>
            Link <?=$i+1?>
            <span>
              <i id="dtg-remove-link-<?=$i+1?>" class="dtg-remove-link dashicons dashicons-dismiss"></i>
            </span>
            <span>
              <i id="dtg-up-link-<?=$i+1?>" class="dtg-up-link dashicons dashicons-arrow-up-alt2"></i>
            </span>
            <span>
              <i id="dtg-down-link-<?=$i+1?>" class="dtg-down-link dashicons dashicons-arrow-down-alt2"></i>
            </span>
          </legend>
          <div class="field-wrapper title-wrap">
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>-<?=$i+1?>"><?php _e( 'Title', 'canadensis-as' ); ?>
              <input class="upcoming" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>-<?=$i+1?>" size='40' name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>[]" type="text" value="<?php echo esc_attr( $title[ $i ] ); ?>" />
            </label>
          </div>
          <div class="field-wrapper desc-wrap">
            <label for="<?php echo esc_attr( $this->get_field_id( 'desc' ) ); ?>-<?=$i+1?>"><?php _e( 'Desc.', 'canadensis-as' ); ?>
              <input class="upcoming" id="<?php echo esc_attr( $this->get_field_id( 'desc' ) ); ?>-<?=$i+1?>" size='40' name="<?php echo esc_attr( $this->get_field_name( 'desc' ) ); ?>[]" type="text" value="<?php echo esc_attr( $desc[ $i ] ); ?>" />
            </label>
          </div>
          <div class="field-wrapper url-wrap">
            <label for="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>-<?=$i+1?>"><?php _e( 'URL', 'canadensis-as' ); ?>
              <input class="upcoming" id="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>-<?=$i+1?>" size='40' name="<?php echo esc_attr( $this->get_field_name( 'url' ) ); ?>[]" type="text" value="<?php echo esc_attr( $url[ $i ] ); ?>" />
            </label>
          </div>
          <div class="field-wrapper icon-wrap">
            <label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>-<?=$i+1?>"><?php _e( 'Icon', 'canadensis-as' ); ?>
              <input class="upcoming" id="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>-<?=$i+1?>" size='40' name="<?php echo esc_attr( $this->get_field_name( 'icon' ) ); ?>[]" type="text" value="<?php echo esc_attr( $icon[ $i ] ); ?>" />
            </label>
          </div>
        </fieldset>
        <?php
            $i++;
          }
        }
        ?>
      </div>
      <div class="dtg-admin button-bar">
        <input type="button" id="dtg-add-link" name="dtg-add-link" class="dtg-featured-links form-button" title="Add Link" value="+" />
      </div>
      <?php
    }

    public function update( $new_instance, $old_instance )
    {
      $instance = $old_instance;
      $instance['heading'] = $new_instance['heading'];
      // Links array
      $instance['title'] = $new_instance['title'];
      $instance['desc'] = $new_instance['desc'];
      $instance['url'] = $new_instance['url'];
      $instance['icon'] = $new_instance['icon'];

      return $instance;
    }

    public function widget( $args, $instance )
    {
      extract( $args, EXTR_SKIP );
      $tl = 6;
      $heading = ( ( empty( $instance[ 'heading' ] ) )? '' : apply_filters( 'widget_title', $instance[ 'heading' ] ) );
      $heading = htmlspecialchars_decode( stripslashes( $heading ) );
      // Get other variables
      $title = ( ( array_key_exists( "title", $instance ) )? $instance[ "title" ] : array() );
      $desc = ( ( array_key_exists( "desc", $instance ) )? $instance[ "desc" ] : array() );
      $url = ( ( array_key_exists( "url", $instance ) )? $instance[ "url" ] : array() );
      $icon = ( ( array_key_exists( "icon", $instance ) )? $instance[ "icon" ] : array() );

      ob_start();
      echo $args['before_widget'];
      /*echo "<pre>";
      print_r( $instance );
      echo "</pre>";*/
  		if ( !empty( $instance[ 'heading' ] ) ) {
  			echo $args['before_title'] . $instance[ 'heading' ] . $args['after_title'];
  		}
      if( count( $url ) > 0 ){ ?>
        <div class="dtg-featured-links link-wrapper">
        <?php
        for( $i=0; $i<count( $url ); $i++ ){
          ?>
          <a href="<?=$url[ $i ]?>" class="outter-link" target="self">
            <div class="item-wrapper">
              <div class="item-icon-wrapper"><i class="<?=$icon[ $i ]?>"></i></div>
              <div class="item-text-wrapper">
                <div class="item-title"><?=$title[ $i ]?></div>
                <?php if( $desc[ $i ] != "" ){ ?>
                <div class="item-description"><?=$desc[ $i ]?></div>
                <?php } ?>
              </div>
            </div>
          </a>
          <?php
        }
        ?>
        </div>
        <?php
      }
      echo $args['after_widget'];
      $buffer =  ob_get_clean();
      echo $buffer;
    }
  }
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "DTG_Featured_Links_Redux" );' ) );

if (! class_exists('DTG_Latest_Articles')) {
  class DTG_Latest_Articles extends WP_Widget{
    private $version;
    public static $_instance;

    static function init(){
      if ( !self::$_instance ) {
        self::$_instance = new self();
      }
      return self::$_instance;
    }

    public function __construct(){
      parent::__construct(
        'DTG_Latest_Articles', // Base ID
        __('DTG Latest Articles', 'canadensis-ajp'), // Name
        array( 'classname' => 'latest_articles_widget', 'description' =>__( 'Displays a specified number of recent posts in descending date order with thumbnail.', 'canadensis-ajp' ), ) // Args
      );
    }

    public function form( $instance ){
      $instance = wp_parse_args( ( array ) $instance, array( 'title' => '' ) );
      $title = ( ( array_key_exists( 'title', $instance ) )? $instance[ 'title' ] : NULL );
      $postnum = ( ( array_key_exists( 'postnum', $instance ) )? $instance[ 'postnum' ] : NULL );

      ?>
      <!-- Title -->
      <p>
        <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title', 'canadensis-ajp'); ?>
          <input class="upcoming" id="<?php echo esc_attr($this->get_field_id('title')); ?>" size='40' name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
          <i>Title is not displayed: only for admin UI</i>
        </label>
      </p>
      <div style="display:table; width:100%; height:100%; position:relative;">
        <hr style="display: table-cell; text-align: center; vertical-align: middle; width:auto; height:auto;">
      </div><br/>
      <!-- Number of Posts -->
      <div class="dtg-admin-fields post-num">
        <label for="<?php echo esc_attr($this->get_field_id('postnum')); ?>">Number of Posts to Display<br/>
          <input type="number" id="<?php echo esc_attr($this->get_field_id('postnum')); ?>" style="width:50px;" name="<?php echo esc_attr($this->get_field_name('postnum')); ?>" value="<?php echo esc_attr( $postnum ); ?>" />
        </label>
      </div><br/>

      <?php
    }

    public function update($new_instance, $old_instance){
      $instance = $old_instance;
      $instance[ 'title' ] = $new_instance[ 'title' ];
      $instance[ 'postnum' ] = $new_instance[ 'postnum' ];

      return $instance;
    }

    public function widget($args, $instance){
      extract($args, EXTR_SKIP);
      $tl = 6;
      $title = empty( $instance[ 'title' ] ) ? '' : apply_filters( 'widget_title', $instance[ 'title' ] );
      $title = htmlspecialchars_decode( stripslashes( $title ) );
      $postnum = ( ( array_key_exists( 'postnum', $instance ) )? $instance[ 'postnum' ] : NULL );

      $arguments = array(
        'post_type' => 'post',
    		'posts_per_page' => $postnum,
        'orderby' => array(
          'date' => 'DESC',
        )
    	);
      $post_query = new WP_Query();
    	$all_wp_posts = $post_query->query( $arguments );
      // Render
      ob_start();
      echo $args['before_widget'];

  		if ( !empty( $instance[ 'title' ] ) ) {
  			echo $args['before_title'] . $instance[ 'title' ] . $args['after_title'];
  		}
      if( $postnum ){
        foreach( $all_wp_posts as $post ){
          $title = $post->post_title;
          $link = get_permalink( $post->ID );
          $thumb = get_the_post_thumbnail_url( $post->ID, array( 150,150 ) );
          $thumb = ( ( $thumb == false )? 'https://via.placeholder.com/150?text=A.J.+Perri' : $thumb );
        ?>
        <div class="dtg-blog-articles latest-articles">
          <div class="blog-article">
            <div class="blog-thumb">
              <a href="<?=$link?>" target="self"><img src="<?=$thumb?>" border="0"></a>
            </div>
            <div class="blog-title">
              <a href="<?=$link?>" target="self"><?=$title?></a>
            </div>
          </div>
        </div>
        <?php
        }
      }
      echo $args['after_widget'];
      $buffer =  ob_get_clean();
      echo $buffer;
    }
  }
}
add_action('widgets_init', create_function('', 'return register_widget("DTG_Latest_Articles");'));
?>
