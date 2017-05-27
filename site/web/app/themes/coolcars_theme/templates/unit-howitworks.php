<?php
// query for the about page
$your_query = new WP_Query( 'pagename=process' );
// "loop" through query (even though it's just one page)
while ( $your_query->have_posts() ) : $your_query->the_post();
$sub = get_post_meta($id, 'subtitle', true);
?>
<section id="slide02" class="content-block">
    <div class="container-fluid no-padding">
      <div class="row legend">
        <header class="mx-auto centering">
            <h1 class="slideInUp slideInUp2"><?php the_title(); ?></h1>
              <h2 class="slideInUp slideInUp2"><?php echo $sub; ?></h2>
        </header>
      </div>
<svg class="uvc-svg-triangle" xmlns="http://www.w3.org/2000/svg" version="1.1" fill="#f8f8f9" width="100%" height="30" viewBox="0 0 0.156661 0.1" style="height: 30px;"><polygon points="0.156661,3.93701e-006 0.156661,0.000429134 0.117665,0.05 0.0783307,0.0999961 0.0389961,0.05 -0,0.000429134 -0,3.93701e-006 0.0783307,3.93701e-006 "></polygon></svg>

<div class="container">
<div class="row slide fs" >
  <div class="col-md-6">
    1
<?php
      the_content();
  endwhile;
  // reset post data (important!)
  wp_reset_postdata();
?>
</div> <!-- miso div 1 -->
<div class="col-md-6">

</div>
</div>
</div>
</div> <!-- .container -->
</section>
