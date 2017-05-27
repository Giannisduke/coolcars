<div class="container-fluid nopad test">
    <div class="row test">


<div class="container-fluid test">
      <div class="row test">

        <div class="container test">
        <div class="row test">
        <div class="col-md-4 col-lg-4 test">
          <?php
        //  echo facetwp_display( 'facet', 'categories' );
          ?>
        </div>

        <div class="col-md-4 col-lg-4 test">
          <?php
          echo facetwp_display( 'facet', 'availability' );
          ?>
        </div>
        <div class="col-md-4 col-lg-4 test">
          <?php
      //    echo facetwp_display( 'facet', 'custom' );
          ?>
        </div>
        <div class="col-md-4 col-lg-4 test">
          <?php
      //    echo facetwp_display( 'facet', 'availability' );
          ?>
        </div>

        </div>
        </div>

    </div>
</div>
    </div>
      <div class="row test">
        <div class="facetwp-template">
          <ul class="products">
	<?php
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => 12
			);
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
				wc_get_template_part( 'content', 'product' );
			endwhile;
		} else {
			echo __( 'No products found' );
		}
		wp_reset_postdata();
	?>
</ul><!--/.products-->
        </div>



    </div>
</div>
