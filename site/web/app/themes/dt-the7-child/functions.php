<?php
/**
 * Your code here.
 *
 */

 // Allow SVG
 add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

   global $wp_version;
   if ( $wp_version !== '4.7.2' ) {
      return $data;
   }

   $filetype = wp_check_filetype( $filename, $mimes );

   return [
       'ext'             => $filetype['ext'],
       'type'            => $filetype['type'],
       'proper_filename' => $data['proper_filename']
   ];

 }, 10, 4 );

 function cc_mime_types( $mimes ){
   $mimes['svg'] = 'image/svg+xml';
   return $mimes;
 }
 add_filter( 'upload_mimes', 'cc_mime_types' );

 function fix_svg() {
   echo '<style type="text/css">
         .attachment-266x266, .thumbnail img {
              width: 100% !important;
              height: auto !important;
         }
         </style>';
 }
 add_action( 'admin_head', 'fix_svg' );



 add_shortcode( 'persons2', 'the7dtchild_name_shortcode' );

 function the7dtchild_name_shortcode() {

$pa_koostis_value = get_post_meta($product->id, 'pa_persons', true);


 }


 if (  ! function_exists( 'woocommerce_template_loop_product_title' ) ) {

 	/**
 	 * Show the product title in the product loop. By default this is an H3.
 	 */
 	function woocommerce_template_loop_product_title() {
 		echo '<h3>' . get_the_title() . '</h3>';
 	}
 }
