<?php
/**
 * coolcars includes
 *
 * The $coolcars_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/coolcars/pull/1042
 */
$coolcars_includes = [
  'lib/assets.php',    // Scripts and stylesheets
  'lib/extras.php',    // Custom functions
  'lib/setup.php',     // Theme setup
  'lib/titles.php',    // Page titles
  'lib/wrapper.php',   // Theme wrapper class
  'lib/customizer.php', // Theme customizer
  'lib/button.php', // Load Gravity Forms via AJAX
  'lib/ajax.php' // Load Gravity Forms via AJAX
];

foreach ($coolcars_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'coolcars'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);

//Remove <p> from body
// remove_filter ('the_content', 'wpautop');

// Register Custom Navigation Walker (Soil)
require_once('bs4navwalker.php');

//declare your new menu
register_nav_menus( array(
    'primary' => __( 'Primary Menu', 'coolcars' ),
    'pages' => __( 'In Menu', 'coolcars' ),
) );


// Base of our Custom Walker
class IBenic_Walker extends Walker_Nav_Menu {

	// Displays start of an element. E.g '<li> Item Name'
    // @see Walker::start_el()
    function start_el(&$output, $item, $depth=0, $args=array(), $id = 0) {
    	$object = $item->object;
    	$type = $item->type;
    	$title = $item->title;
    	$description = $item->description;
    	$permalink = $item->url;
      $output .= "<li class='nav-item'>";

      //Add SPAN if no Permalink
      if( $permalink && $permalink != '#' ) {
      	$output .= '<a class="nav-link" href="' . $permalink . '">';
      } else {
      	$output .= '<span>';
      }

      $output .= $title;
      if( $description != '' && $depth == 0 ) {
      	$output .= '<small class="description">' . $description . '</small>';
      }
      if( $permalink && $permalink != '#' ) {
      	$output .= '</a>';
      } else {
      	$output .= '</span>';
      }
    }
}

//add SVG to allowed file uploads
function add_svg_to_upload_mimes( $upload_mimes ) {
	$upload_mimes['svg'] = 'image/svg+xml';
	$upload_mimes['svgz'] = 'image/svg+xml';
	return $upload_mimes;
}
add_filter( 'upload_mimes', 'add_svg_to_upload_mimes', 10, 1 );
//enable logo uploading via the customize theme page

function themeslug_theme_customizer( $wp_customize ) {
    $wp_customize->add_section( 'themeslug_logo_section' , array(
    'title'       => __( 'Logo', 'themeslug' ),
    'priority'    => 30,
    'description' => 'Upload a logo to replace the default site name and description in the header',
) );
$wp_customize->add_setting( 'themeslug_logo' );
$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'themeslug_logo', array(
    'label'    => __( 'Logo', 'themeslug' ),
    'section'  => 'themeslug_logo_section',
    'settings' => 'themeslug_logo',
    'extensions' => array( 'jpg', 'jpeg', 'gif', 'png', 'svg' ),
) ) );
}
add_action('customize_register', 'themeslug_theme_customizer');

add_filter( 'woocommerce_add_cart_item_data', 'ps_empty_cart', 10,  3);

function ps_empty_cart( $cart_item_data, $product_id, $variation_id ) {

    global $woocommerce;
    $woocommerce->cart->empty_cart();

    // Do nothing with the data and return
    return $cart_item_data;
}

remove_action ('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5, 0) ;
add_action ('woocommerce_before_single_product_summary', 'woocommerce_template_single_title', 5, 0) ;
remove_action ('woocommerce_single_product_summary', 'woocommerce_template_single_price') ;
add_action ('woocommerce_before_single_product_summary', 'woocommerce_template_single_price', 10, 0) ;

/**
 * The following hook will add a input field right before "add to cart button"
 * will be used for getting Your first name
 */

 function add_before_your_first_name_field() {


     echo '<fieldset class="wc-bookings-fields">';

      echo '<div class="form-group row">';
 }
 add_action( 'woocommerce_before_add_to_cart_button', 'add_before_your_first_name_field', 10 );

 function add_your_first_name_field() {

 echo '<div class="col-lg-6">';
     echo '<label>Name</label>';
     echo '  <input name="your-first-name" type="text" class="form-control" id="inputName" placeholder="placeholder inline" required>';
 echo '</div>';
 }
 add_action( 'woocommerce_before_add_to_cart_button', 'add_your_first_name_field', 20 );

 function add_after_your_first_name_field() {
   echo '</div>';
     echo '</fieldset>';
 }
 add_action( 'woocommerce_before_add_to_cart_button', 'add_after_your_first_name_field', 30 );



 function save_your_first_name_field( $cart_item_data, $product_id ) {
     if( isset( $_REQUEST['your-first-name'] ) ) {
         $cart_item_data[ 'your_first_name' ] = $_REQUEST['your-first-name'];
         /* below statement make sure every add to cart action as unique line item */
         $cart_item_data['unique_key'] = md5( microtime().rand() );
     }
     return $cart_item_data;
 }
 add_action( 'woocommerce_add_cart_item_data', 'save_your_first_name_field', 10, 2 );

 function render_meta_on_cart_and_checkout( $cart_data, $cart_item = null ) {
     $custom_items = array();
     /* Woo 2.4.2 updates */
     if( !empty( $cart_data ) ) {
         $custom_items = $cart_data;
     }
     if( isset( $cart_item['your_first_name'] ) ) {
         $custom_items[] = array( "name" => 'Your first name', "value" => $cart_item['your_first_name'] );
     }
     return $custom_items;
 }
 add_filter( 'woocommerce_get_item_data', 'render_meta_on_cart_and_checkout', 10, 2 );


 add_filter( 'woocommerce_get_item_data', 'so_34900990_display_cart_data', 10, 2 );
 function so_34900990_display_cart_data( $item_data, $cart_item ){

     if ( ! empty( $cart_item['booking'] ) ) {

         $date_format = apply_filters( 'woocommerce_bookings_date_format', wc_date_format() );
         $time_format = apply_filters( 'woocommerce_bookings_time_format', ', ' . wc_time_format() );
         $start_date = apply_filters( 'woocommerce_bookings_get_end_date_with_time', date_i18n( $date_format . $time_format, $cart_item['booking']['_start_date'] ) );

         $item_data[] = array(
             'key'    => __( 'Strat Date', 'your-textdomain' ),
             'value'   => $cart_item['booking']['_start_date'],
             'display' => $start_date,
         );
     }
     return $item_data;
 }


 add_filter( 'woocommerce_get_item_data', 'so_34900999_display_cart_data', 10, 3 );
 function so_34900999_display_cart_data( $item_data, $cart_item ){

     if ( ! empty( $cart_item['booking'] ) ) {

         $date_format = apply_filters( 'woocommerce_bookings_date_format', wc_date_format() );
         $time_format = apply_filters( 'woocommerce_bookings_time_format', ', ' . wc_time_format() );
         $end_date = apply_filters( 'woocommerce_bookings_get_end_date_with_time', date_i18n( $date_format . $time_format, $cart_item['booking']['_end_date'] ) );

         $item_data[] = array(
             'key'    => __( 'End Date', 'your-textdomain' ),
             'value'   => $cart_item['booking']['_end_date'],
             'display' => $end_date,
         );
     }
     return $item_data;
 }


 add_filter( 'woocommerce_get_item_data', function ( $data, $cartItem ) {
     if ( isset( $cartItem['your_first_name'] ) ) {
         $data[] = array(
             'name' => 'My custom data',
             'value' => $cartItem['your_first_name']
         );
     }

     return $data;
 }, 10, 2 );



 // Hook in specified cart item data
 add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

 function custom_override_checkout_fields( $fields  ) {
$stored_value = "something pulled from the DB";
   unset($fields['billing']['billing_address_1']);
   unset($fields['billing']['billing_address_2']);
   unset($fields['billing']['billing_postcode']);
   unset($fields['billing']['billing_state']);
   unset($fields['billing']['billing_company']);
   unset($fields['billing']['billing_address_2']);
   unset($fields['billing']['billing_country']);
   unset($fields['billing']['billing_city']);
 $fields['order']['order_comments']['placeholder'] = 'My new placeholder';
$fields['billing']['billing_first_name']['default'] = $cartItem['your_first_name'];


     return $fields;
 }

// add_action( 'woocommerce_before_cart', 'bbloomer_print_cart_array' );
function bbloomer_print_cart_array() {
$cart = WC()->cart->get_cart();
print_r($cart);
}
