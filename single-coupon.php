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
<!-- Begin Preconnect -->
<?php
$preconnectees = explode( "\n", str_replace( "\r", "", get_option( "csa_preconnect_urls" ) ) );
foreach( $preconnectees as $pcurl ){
	foreach( $protocols as $d ) {
		if( strpos( $pfurl, $d ) === 0 ){
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
<?php

wp_head();

FLTheme::head();

?>
</head>
<body <?php body_class(); ?><?php FLTheme::print_schema( ' itemscope="itemscope" itemtype="https://schema.org/WebPage"' ); ?>>
	<div class="fl-page">
		<div class="fl-page-content" itemprop="mainContentOfPage">
			<?php do_action( 'fl_content_open' ); ?>
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-sm-2"></div>
					<div class="fl-content col-xs-12 col-sm-8">
						<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'content', 'coupon' ); ?>
						<?php endwhile;
			endif; ?>
					</div>
					<div class="col-xs-12 col-sm-2"></div>
				</div>
			</div>

		<!-- Footer -->
		<?php do_action( 'fl_content_close' ); ?>

		</div><!-- .fl-page-content -->
	</div><!-- .fl-page -->
<?php  wp_footer(); ?>
</body>
</html>
