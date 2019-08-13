<?php
$meta = get_post_meta( $post->ID );
?>
<?php do_action( 'fl_before_post' ); ?>
<article <?php post_class( 'fl-post' ); ?> id="fl-post-<?php the_ID(); ?>" itemscope itemtype="http://schema.org/BlogPosting">
	<header class="fl-post-header">
		<div class="csa_coupon_print navbar">
			<div class="back_link">
				<a href="<?=$_SERVER['HTTP_REFERER']?>" target="_self"><< Back</a>
			</div>
			<div class="print_coupon">
				<a href="#" id="print_coupon_link" onclick="window.print()">
					<i class="print_icon"></i>
					<span class="print_text">Print</span>
				</a>
			</div>
		</div>
		<div class="csa_coupon_print logo"><?php FLTheme::logo(); ?></div>
		<div class="csa_coupon_print title">
			<h1 class="title_text" itemprop="headline">
				<?php the_title(); ?>
				<?php edit_post_link( _x( 'Edit', 'Edit post link text.', 'fl-automator' ) ); ?>
			</h1>
		</div>
	</header><!-- .fl-post-header -->
	<?php do_action( 'fl_before_post_content' ); ?>
	<div class="csa_coupon_print content clearfix" itemprop="text">
		<?php
		$content = get_the_content();
		$content = wp_filter_nohtml_kses( $content );
		echo $content;
		?>
	</div><!-- .fl-post-content -->
	<?php do_action( 'fl_after_post_content' ); ?>
	<div class="csa_coupon_print disclaimer">
		<?=do_shortcode( $post->post_excerpt )?>
		<?php
		$custom = get_post_custom( $post->ID );
		if( array_key_exists( "csa_field_custom_expires", $custom ) === true && $custom[ "csa_field_custom_expires" ][ 0 ] != "" ){
			?><b>Expires <?=date("n/j/Y", strtotime( $custom[ "csa_field_custom_expires" ][ 0 ] ) )?>.</b><?php
		}
		?>
	</div>
	<div class="csa_coupon_print postscript">
		<?php
		$custom = get_post_custom( $post->ID );
		if( array_key_exists( "csa_field_custom_postscript", $custom ) === true && $custom[ "csa_field_custom_postscript" ][ 0 ] != "" ){
			echo do_shortcode( $custom[ "csa_field_custom_postscript" ][ 0 ] );
		}
		?>
	</div>
</article>
<?php do_action( 'fl_after_post' ); ?> <!-- .fl-post -->
