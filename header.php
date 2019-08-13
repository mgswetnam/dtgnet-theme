<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<?php echo ( ( get_option( "csa_highest_code" ) != "" )? get_option( "csa_highest_code" ) : "" ); ?>
<?php do_action( 'fl_head_open' ); ?>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<!-- Begin Prefetch -->
<meta http-equiv='x-dns-prefetch-control' content='on' />
<?php
$prefetchees = explode( "\n", str_replace( "\r", "", get_option( "csa_prefetch_urls" ) ) );
$protocols = array( "http://", "https://", "//" );
foreach( $prefetchees as $pfurl ){
	foreach( $protocols as $d ) {
		if( strpos( $pfurl, $d ) === 0 ){
			$pfurl = str_replace( $d, "", $pfurl );
		}
	}
	echo ( ( $pfurl && $pfurl != "" )? "<link rel='dns-prefetch' href='//".$pfurl."' />" : "" );
}
?>
<!-- End Prefetch -->
<!-- Begin Preload -->
<?php
$preloadees = explode( "\n", str_replace( "\r", "", get_option( "csa_preload_resources" ) ) );
foreach( $preloadees as $plurl ){
	$plurlarray = explode( "|", $plurl );
	$plurl = ( ( array_key_exists( 0, $plurlarray ) )? $plurlarray[ 0 ] : "" );
	$as = ( ( array_key_exists( 1, $plurlarray ) )? $plurlarray[ 1 ] : "" );
	foreach( $protocols as $d ) {
		if( strpos( $plurl, $d ) === 0 ){
			$plurl = str_replace( $d, "", $plurl );
		}
	}
	echo ( ( $plurl && $plurl != "" )? "<link rel='preload' href='".$plurl."' as='".$as."' crossorigin />" : "" );
}
?>
<!-- End Preload -->
<!-- Begin Preconnect -->
<?php
$preconnectees = explode( "\n", str_replace( "\r", "", get_option( "csa_preconnect_urls" ) ) );
foreach( $preconnectees as $pcurl ){
	foreach( $protocols as $d ) {
		if( strpos( $pcurl, $d ) === 0 ){
			$pcurl = str_replace( $d, "", $pcurl );
		}
	}
	echo ( ( $pcurl && $pcurl != "" )? "<link rel='preconnect' href='//".$pcurl."' crossorigin />" : "" );
}
?>
<!-- End Preconnect -->
<?php echo apply_filters( 'fl_theme_viewport', "<meta name='viewport' content='width=device-width, initial-scale=1.0' />\n" ); ?>
<?php echo apply_filters( 'fl_theme_xua_compatible', "<meta http-equiv='X-UA-Compatible' content='IE=edge' />\n" ); ?>
<link rel="profile" href="https://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php FLTheme::title(); ?>
<?php FLTheme::favicon(); ?>
<?php FLTheme::fonts(); ?>
<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/respond.min.js"></script>
<![endif]-->
<?php

wp_head();

FLTheme::head();

?>
</head>

<body <?php body_class(); ?> itemscope="itemscope" itemtype="https://schema.org/WebPage">
	<a href="#maincontent" class="csa_skip_to_content" tabindex="1">Skip to content</a>
	<?php

	FLTheme::header_code();

	do_action( 'fl_body_open' );

	?>
	<div class="fl-page">
	<?php

	do_action( 'fl_page_open' );

	FLTheme::fixed_header();

	do_action( 'fl_before_top_bar' );

	FLTheme::top_bar();

	do_action( 'fl_after_top_bar' );
	do_action( 'fl_before_header' );

	FLTheme::header_layout();

	do_action( 'fl_after_header' );
	do_action( 'fl_before_content' );

	?>
	<div class="fl-page-content" itemprop="mainContentOfPage">

		<?php do_action( 'fl_content_open' ); ?>
