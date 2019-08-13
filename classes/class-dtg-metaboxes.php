<?php

/**
 * Metabox factory for Castor Canadensis
 * @class DTG_DTGNET_Metaboxes
 */

final class DTG_DTGNET_Metaboxes {

  private $version;
  public static $_instance;

  static function init(){
    if ( !self::$_instance ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function __construct() {
    // Nothing needed here
  }

	static public function dtg_add_meta_boxes(){
		global $post;
    include FL_CHILD_THEME_DIR ."/includes/metaboxes.php";
		foreach( $metaboxes as $value ){
			add_meta_box( $value[ "mbid" ], $value[ "mbtitle" ], array( __CLASS__, 'dtg_add_custom_fields' ), $value[ "mbscreen" ], $value[ "mbcontext" ], $value[ "mbpriority" ], $value[ "fields" ] );
		}
	}

	public static function dtg_add_custom_fields( $post, $args ){
		global $post;
		$custom = get_post_custom( $post->ID );

		$search = array( '/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/<!--(.|\s)*?-->/' );
		$replace = array( '>', '<', '\\1', '' );

		ob_start();
    ?>
    <div class="dtg-admin">
    <?php
		foreach( $args[ "args" ] as $field ){
			$fieldval = ( ( array_key_exists( "fid", $field ) )? $field[ "fid" ] : NULL );
			$value = ( ( $fieldval && array_key_exists( $fieldval, $custom ) )? ( ( array_key_exists( 0, $custom[ $fieldval ] ) )? $custom[ $fieldval ][ 0 ] : "" ) : "" );
      $atts = "";
      $options = array();
			foreach( $field[ "attributes" ] as $k=>$v ){
        if( $k == "options" ){
          $options = $v;
        } else {
          $atts .= $k . "=\"" . $v . "\" ";
        }
			}
			switch( $field[ "ftype" ] ){
				case "input":{
          switch( $field[ "fsubtype" ] ){
            case "text":
            case "password":
            case "email":
            case "color":
            case "date":
            case "file":
            case "number":
            case "time":
            case "url":{
    			    ?>
              <div class="dtg-admin-field<?=( ( array_key_exists( "fwrapclass", $field ) )? " ".$field[ 'fwrapclass' ] : "" )?>">
      					<label for="<?=$field[ 'fid' ]?>"><?=$field[ 'flabel' ]?>  </label></br>
      					<input type="<?=$field[ 'fsubtype' ]?>" name="<?=$field[ 'fid' ]?>" <?=$atts?> value="<?=$value?>" />
              </div>
    					<?php
    					break;
            }
    				case "radio":{
              $i=0;
    			    ?>
              <div class="dtg-admin-field<?=( ( array_key_exists( "fwrapclass", $field ) )? " ".$field[ 'fwrapclass' ] : "" )?>">
                <label for="<?=$field[ 'fid' ]?>"><?=$field[ 'flabel' ]?>  </label></br>
                <?php foreach( $options as $kitem => $vitem ){ ?>
      					<label for="<?=$field[ 'fid' ]?>-<?=$i?>"><input type="<?=$field[ 'fsubtype' ]?>" id="<?=$field[ 'fid' ]?>-<?=$i?>" name="<?=$field[ 'fid' ]?>" <?=$atts?> <?php echo ( ( $value == $kitem )? 'checked' : '' ); ?> value="<?php echo strtolower( $kitem ); ?>" /><?=$vitem?></label><br/>
                <?php
                $i++;
                } ?>
              </div>
              <?php
    					break;
    				}
    				case "checkbox":{
              $i=0;
              $value = unserialize( $value );
    			    ?>
              <div class="dtg-admin-field<?=( ( array_key_exists( "fwrapclass", $field ) )? " ".$field[ 'fwrapclass' ] : "" )?>">
                <label for="<?=$field[ 'fid' ]?>"><?=$field[ 'flabel' ]?>  </label></br>
                <?php foreach( $options as $kitem => $vitem ){ ?>
      					<label for="<?=$field[ 'fid' ]?>-<?=$i?>"><input type="<?=$field[ 'fsubtype' ]?>" id="<?=$field[ 'fid' ]?>-<?=$i?>" name="<?=$field[ 'fid' ]?>[]" <?=$atts?> <?php echo ( ( is_array( $value ) )? ( ( in_array( strtolower( $kitem ), $value ) )? 'checked' : '' ) : '' ); ?> value="<?php echo strtolower( $kitem ); ?>" /><?=ucfirst( $vitem )?></label><br/>
                <?php
                $i++;
                } ?>
              </div>
              <?php
    					break;
    				}
          }
					break;
				}
				case "select":{
          $selected = ( ( $value != "" )? $value : ( ( $field[ 'fdefault' ] != "" )? $field[ 'fdefault' ] : "" ) );
			    ?>
          <div class="dtg-admin-field<?=( ( array_key_exists( "fwrapclass", $field ) )? " ".$field[ 'fwrapclass' ] : "" )?>">
  					<label for="<?=$field[ 'fid' ]?>"><?=$field[ 'flabel' ]?>  </label></br>
  					<select name="<?=$field[ 'fid' ]?>" <?=$atts?> value="<?=$value?>"/>
              <option value="">Select</option>
              <?php foreach( $options as $kitem => $vitem ){ ?>
              <option value="<?php echo strtolower( $kitem ); ?>" <?php echo ( ( $selected == strtolower( $kitem ) )? 'selected' : '' ) ?>><?=$vitem?></option>
              <?php } ?>
  					</select>
          </div>
					<?php
					break;
				}
				case "textarea":{
			    ?>
          <div class="dtg-admin-field<?=( ( array_key_exists( "fwrapclass", $field ) )? " ".$field[ 'fwrapclass' ] : "" )?>">
            <label for="<?=$field[ 'fid' ]?>"><?=$field[ 'flabel' ]?>  </label></br>
            <textarea name="<?=$field[ 'fid' ]?>" <?=$atts?>/><?=$value?></textarea>
          </div>
					<?php
					break;
				}
				case "hours":{
          $value = ( ( $value )? unserialize( $value ) : array() );
          $dotw = array( "sunday","monday","tuesday","wednesday","thursday","friday","saturday" );
			    ?>
          <div class="dtg-admin-field<?=( ( array_key_exists( "fwrapclass", $field ) )? " ".$field[ 'fwrapclass' ] : "" )?>">
  					<label for="<?=$field[ 'fid' ]?>-sunday"><?=$field[ 'flabel' ]?>  </label></br>
            <div class="dtg-hours days-wrapper">
              <div class="day-wrapper">
                <div class="day-field"></div>
                <div class="day-field">From</div>
                <div class="day-field"></div>
                <div class="day-field">To</div>
              </div>
              <?php foreach( $dotw as $day ){ ?>
              <?php if( $valkey = array_search( $day, $value ) ){ ?>
              <div class="day-wrapper">
                <div class="day-field"><input type="checkbox" id="<?=$field[ 'fid' ]?>-<?=$day?>" class="dtg-checkbox" name="<?=$field[ 'fid' ]?>[]" checked value="<?=$day?>" /> <?=ucfirst( $day )?> </div>
                <div class="day-field"><input type="number" id="<?=$field[ 'fid' ]?>-<?=$day?>-from-hr" class="dtg-numrange time" name="<?=$field[ 'fid' ]?>[]" min="0" max="23" placeholder="00" value="<?=$value[ $valkey+1 ]?>" ><span class="hr-min-colon">:</span></div>
                <div class="day-field"><input type="number" id="<?=$field[ 'fid' ]?>-<?=$day?>-from-min" class="dtg-numrange time" name="<?=$field[ 'fid' ]?>[]" min="0" max="59" placeholder="00" value="<?=$value[ $valkey+2 ]?>" ><span class="from-to-dash">-</span></div>
                <div class="day-field"><input type="number" id="<?=$field[ 'fid' ]?>-<?=$day?>-to-hr" class="dtg-numrange time" name="<?=$field[ 'fid' ]?>[]" min="0" max="23" placeholder="00" value="<?=$value[ $valkey+3 ]?>" ><span class="hr-min-colon">:</span></div>
                <div class="day-field"><input type="number" id="<?=$field[ 'fid' ]?>-<?=$day?>-to-min" class="dtg-numrange time" name="<?=$field[ 'fid' ]?>[]" min="0" max="59" placeholder="00" value="<?=$value[ $valkey+4 ]?>" ></div>
              </div>
              <?php } else { ?>
              <div class="day-wrapper">
                <div class="day-field"><input type="checkbox" id="<?=$field[ 'fid' ]?>-<?=$day?>" class="dtg-checkbox" name="<?=$field[ 'fid' ]?>[]" value="<?=$day?>" /> <?=ucfirst( $day )?> </div>
                <div class="day-field"><input type="number" id="<?=$field[ 'fid' ]?>-<?=$day?>-from-hr" class="dtg-numrange time" name="<?=$field[ 'fid' ]?>[]" min="0" max="23" placeholder="00" value="" ><span class="hr-min-colon">:</span></div>
                <div class="day-field"><input type="number" id="<?=$field[ 'fid' ]?>-<?=$day?>-from-min" class="dtg-numrange time" name="<?=$field[ 'fid' ]?>[]" min="0" max="59" placeholder="00" value="" ><span class="from-to-dash">-</span></div>
                <div class="day-field"><input type="number" id="<?=$field[ 'fid' ]?>-<?=$day?>-to-hr" class="dtg-numrange time" name="<?=$field[ 'fid' ]?>[]" min="0" max="23" placeholder="00" value="" ><span class="hr-min-colon">:</span></div>
                <div class="day-field"><input type="number" id="<?=$field[ 'fid' ]?>-<?=$day?>-to-min" class="dtg-numrange time" name="<?=$field[ 'fid' ]?>[]" min="0" max="59" placeholder="00" value="" ></div>
              </div>
              <?php } ?>
              <?php } ?>
            </div>
          </div>
					<?php
					break;
				}
			}
		}
    ?>
    </div>
    <?php
    $buffer =  ob_get_clean();

    //$buffer = preg_replace( $search, $replace, $buffer );
    echo $buffer;
	}

	public function dtg_save_custom_fields(){
		global $post;
    include FL_CHILD_THEME_DIR ."/includes/metaboxes.php";
    // Set variables
		$pid = ( ( $post )? $post->ID : NULL );
    $custom_fields = array();
    $args = NULL;
    // Get field ID from metabox array
    foreach( $metaboxes as $value ){
      $args = ( ( array_key_exists( "fields", $value ) )? $value[ "fields" ] : NULL );
      foreach( $args as $field ){
        $fid = ( ( array_key_exists( "fid", $field ) )? $field[ "fid" ] : NULL );
        array_push( $custom_fields, $fid );
      }
    }
    // Process all fields in array
    if( $args ){
      foreach( $custom_fields as $field ){
        if( $content = ( ( array_key_exists( $field, $_POST ) )? $_POST[ $field ] : "" ) ){
          update_post_meta( $pid, $field, $content );
        }
      }
    }
	}

}
