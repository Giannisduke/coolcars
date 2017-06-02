<div class="container">
  <div class="row">
<div class="col-lg-2">
<h5>Our cars</h5>
</div>
<div class="col-lg-10">
<hr class="style4">
</div>

</div>
</div>

  <!-- width of .grid-sizer used for columnWidth -->

<div class="container">

    <div class="row">

	<?php
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => 18
			);
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
				wc_get_template_part( 'content', 'product' );
        //echo 'Product';
			endwhile;
		} else {
			echo __( 'No products found' );
		}
		wp_reset_postdata();
	?>
</div>
</div>
<!--/.products-->
