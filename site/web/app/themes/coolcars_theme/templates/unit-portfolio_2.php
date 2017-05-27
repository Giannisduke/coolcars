<?php
    $args = array(
        'orderby'       => 'name',
        'order'         => 'ASC',
        'hide_empty'    => 1,
        'taxonomy'      => 'type', //change this to any taxonomy
    );
    foreach (get_categories($args) as $tax) :
        $args = array(
            'post_type'         => 'portfolio', //change to your post_type
            'posts_per_page'    => -1,
            'orderby'           => 'title',
            'orderby'           => 'ASC',
            'tax_query' => array(
                array(
                    'taxonomy'  => 'type', //change this to any taxonomy
                    'field'     => 'slug',
                    'terms'     => $tax->slug
                )
            )
        );
        if (get_posts($args)) :
    ?>
<div class="grid-sizer"></div>
  <div class="gutter-sizer"></div>


  <?php

  foreach(get_posts($args) as $p) :
     echo '' . '<div class="ajax grid_item grid-item--'. $tax->name.'" data-filter=".grid-item--'. $tax->name.'" data-href='.get_the_permalink($p->ID ).' data-rel='.$p->ID.'>';


     echo '' . '<div class="content_small">';

     echo '' . '<img class="img-responsive center-block img-gray" data-src='.pods_image( get_post_meta( $p->ID, 'image_1', true ), 'original' );
     echo '' . '<img class="img-responsive center-block big_true" data-src='.pods_image( get_post_meta( $p->ID, 'image_2', true ), 'original' );



     echo '' . '</div>';

     echo '' . '<div class="content_big">';
      echo '' . '<div class="container-fluid">';
    echo '' . '<div class="row content">';
      echo '' . '<div class="content_body">';

     echo '' . '</div>';

      echo '' . '</div>';
      echo '' . '</div>'; //row
     echo '' . '</div>';

     echo '' . '</div>';

     ?>

<?php endforeach; ?>
<?php endif; endforeach; wp_reset_query(); ?>
