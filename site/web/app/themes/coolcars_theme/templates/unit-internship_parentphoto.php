
 <?php
$parent = get_post($post->post_parent);

echo '<h5>Hotel info</h5>';
echo '<p class="hotel_info">';

echo $parent->post_content;
echo '</p>';



 $images = get_post_meta( $post->post_parent, 'hotel_photo_01' );


 if (!empty($images)){

             foreach($images as $image){


             echo '<a href="'. pods_image_url($image, "original") .'"  data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-4">';
             echo pods_image( $image, "hotel-thumb" );
             echo '</a>';
             }

         } else {
             echo "There are no photos for this Hotel.";
     } ?>
