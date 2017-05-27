<?php
$args = array(
      'posts_per_page' => 5
  );
  $featured = new WP_Query($args);

if ($featured->have_posts()): while($featured->have_posts()): $featured->the_post(); ?>

<div class="col-4 services">
  <div class="row justify-content-center">
    <div class="col-md-4 justify-content-center">
<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'alignright' ) ); ?>
</div>
<div class="col-md-8">
  <h3><a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a></h3>
<p ><?php the_excerpt();?></p>
</div>
</div>
</div>


<?php
endwhile; else:
endif;
