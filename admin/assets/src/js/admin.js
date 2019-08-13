/*import 'custom/filters.js'; */

/*
 * csa_admin v1.0
 * -------------------
 * Copyright (c) 2019 Matthew Swetnam
 */
jQuery( document ).ready( function( $ ) {
  console.log( "Canadensis Admin" );
  if( $( ".widget-liquid-right .csa-featured-links.text-field.link-heading" ).length ) {
    var id_prefix = $( ".widget-liquid-right .csa-featured-links.text-field.link-heading" ).attr( "id" ).replace( "heading", "" );
    var name_prefix = $( ".widget-liquid-right .csa-featured-links.text-field.link-heading" ).attr( "name" ).replace( "[heading]", "" );
    var thefields = ["Title","Desc","URL","Icon"];

    refresh_functions()
  } 

  $( ".csa-featured-links.form-button" ).on( "click", function( e ){
    e.preventDefault();

    if( $( ".csa-featured-link" ).length == 0 ){
      linknum = "1";
    } else {
      linknum = $( ".csa-featured-link" ).last().data( "link-number" );
      linknum++;
    }
    // Render fields
    var fieldset = add_element( '[{"type":"fieldset","atts":[{"id":"csa-featured-link|' + linknum + '","class":"csa-admin csa-featured-link","data-link-number":"' + linknum + '"}],"content":"" }]' );
    var deletebtn = add_element( '[{"type":"i","atts":[{"id":"csa-remove-link-' + linknum + '","class":"csa-remove-link dashicons dashicons-dismiss"}],"content":"" }]' );
    $( deletebtn ).on( "click", function(){ remove_link( linknum ); } );
    var deletewrap = add_element( '[{"type":"span","atts":[{}],"content":"" }]' );
    $( deletewrap ).append( deletebtn );
    var upbtn = add_element( '[{"type":"i","atts":[{"id":"csa-up-link-' + linknum + '","class":"csa-up-link dashicons dashicons-arrow-up-alt2"}],"content":"" }]' );
    var upwrap = add_element( '[{"type":"span","atts":[{}],"content":"" }]' );
    $( upwrap ).append( upbtn );
    var downbtn = add_element( '[{"type":"i","atts":[{"id":"csa-down-link-' + linknum + '","class":"csa-down-link dashicons dashicons-arrow-down-alt2"}],"content":"" }]' );
    var downwrap = add_element( '[{"type":"span","atts":[{}],"content":"" }]' );
    $( downwrap ).append( downbtn );
    var legend = add_element( '[{"type":"legend","atts":[{"class":"csa-link-name"}],"content":"Link ' + linknum + ' " }]' );
    $( legend ).append( deletewrap,upwrap,downwrap );
    $( fieldset ).append( legend );
    $.each( thefields, function( k, v ){
      var wrapper = add_element( '[{"type":"div","atts":[{"class":"field-wrapper ' + v.toLowerCase() + '-wrap"}],"content":"" }]' );
      var label = add_element( '[{"type":"label","atts":[{"for":"' + id_prefix + v.toLowerCase() + '-' + linknum + '"}],"content":"' + v + '" }]' );
      var input = add_element('[{"type":"input","atts":[{"id":"' + id_prefix + v.toLowerCase() + '-' + linknum + '","name":"' + name_prefix + '[' + v.toLowerCase() + '][]","class":"","size":"40"}],"content":"" }]');
      $( label ).append( input );
      $( wrapper ).append( label );
      $( fieldset ).append( wrapper );
    } );
    $( ".csa-admin.csa-featured-link-wrapper" ).append( fieldset );
  } );

  function refresh_functions(){
    $( "i[id^='csa-remove-link-']" ).each( function(){
      var mylinknum = $( this ).closest("fieldset").data( "link-number" );
      $( this ).off( "click" ).on( "click", function(){ remove_link( mylinknum ); } );
    } );
    $( "i[id^='csa-up-link-']" ).each( function(){
      var mylinknum = $( this ).closest("fieldset").data( "link-number" );
      $( this ).off( "click" ).on( "click", function(){ move_link_up( mylinknum ); } );
    } );
    $( "i[id^='csa-down-link-']" ).each( function(){
      var mylinknum = $( this ).closest("fieldset").data( "link-number" );
      $( this ).off( "click" ).on( "click", function(){ move_link_down( mylinknum ); } );
    } );
  }

  function remove_link( linknum ){
    if( confirm( "Are you sure you want to delete link " + linknum + "? NOTICE: CHANGE WILL ONLY BECOME PERMANENT ONCE THE WIDGET IS SAVED." ) ){
      $( "fieldset[id='csa-featured-link|" + linknum + "'" ).remove();
      renumber_links();
    }
  }

  function move_link_up( linknum ){
    if( $( "fieldset[id='csa-featured-link|" + linknum ).is(':not(:first-child)') ){
      var prevnum = linknum-1;
      $( "fieldset[id='csa-featured-link|" + linknum ).insertBefore( "fieldset[id='csa-featured-link|" + prevnum );
      renumber_links();
    }
  }

  function move_link_down( linknum ){
    if( $( "fieldset[id='csa-featured-link|" + linknum ).is(':not(:last-child)') ){
      var nextnum = linknum+1;
      $( "fieldset[id='csa-featured-link|" + linknum ).insertAfter( "fieldset[id='csa-featured-link|" + nextnum );
      renumber_links();
    }
  }

  function renumber_links(){
    var i=1;
    $( "fieldset[id^='csa-featured-link|" ).each( function(){
      $( this ).data( "link-number", i );
      $( this ).attr( "data-link-number", i );
      $( this ).attr( "id", "csa-featured-link|" + i );
      $( "legend", this )[ 0 ].childNodes[ 0 ].nodeValue = "Link " + i;
      $( "i[id^='csa-remove-link-']", this ).attr( "id", "csa-remove-link-" + i );
      $( "i[id^='csa-up-link-']", this ).attr( "id", "csa-up-link-" + i );
      $( "i[id^='csa-down-link-']", this ).attr( "id", "csa-down-link-" + i );
      $( "label[for^='" + id_prefix + "title-']", this ).attr( "for", id_prefix + "title-" + i );
      $( "input[id^='" + id_prefix + "title-']", this ).attr( "id", id_prefix + "title-" + i );
      $( "label[for^='" + id_prefix + "desc-']", this ).attr( "for", id_prefix + "desc-" + i );
      $( "input[id^='" + id_prefix + "desc-']", this ).attr( "id", id_prefix + "desc-" + i );
      $( "label[for^='" + id_prefix + "url-']", this ).attr( "for", id_prefix + "url-" + i );
      $( "input[id^='" + id_prefix + "url-']", this ).attr( "id", id_prefix + "url-" + i );
      $( "label[for^='" + id_prefix + "icon-']", this ).attr( "for", id_prefix + "icon-" + i );
      $( "input[id^='" + id_prefix + "icon-']", this ).attr( "id", id_prefix + "icon-" + i );
      i++;
    } );
    refresh_functions();
  }

  function add_element( data ) {
    var thedata = JSON.parse( data );
    var theelement;
    $.each( thedata, function( index ){
      theelement = document.createElement( thedata[ index ].type );
      var theatts = thedata[ index ].atts;
      $.each( theatts, function( i ){
        var thekeys = Object.keys( theatts[ i ] );
        $.each( thekeys, function( mykey ){
          var k = thekeys[ mykey ];
          var v = theatts[ i ][ thekeys[ mykey ] ];
          if( k==="class" ) {
            $( theelement ).addClass( v );
          } else {
            $( theelement ).attr( k, v );
          }
        } );
      } );
      $( theelement ).html( thedata[ index ].content );
    } );
    return theelement;
  }
} );
