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



/**
 * Ensure cart contents update when products are added to the cart via AJAX
 */
function my_header_add_to_cart_fragment( $fragments ) {

    ob_start();
    $count = WC()->cart->cart_contents_count;
    ?><a class="cart-contents" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><?php
    if ( $count > 0 ) {
        ?>
        <span class="cart-contents-count"><?php echo esc_html( $count ); ?></span>
        <?php
    }
        ?></a><?php

    $fragments['a.cart-contents'] = ob_get_clean();

    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'my_header_add_to_cart_fragment' );

/**
 * Add Cart icon and count to header if WC is active
 */
function my_wc_cart_count() {

    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

        $count = WC()->cart->cart_contents_count;
        ?><a class="cart-contents" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>">

          <?php
        if ( $count > 0 ) {
            ?>
            <span class="cart-contents-count"><?php echo esc_html( $count ); ?></span>

            <?php
        }
                ?></a><?php
    }

}
add_action( 'your_theme_header_top', 'my_wc_cart_count' );


function my_facetwp_is_main_query( $is_main_query, $query ) {
    if ( isset( $query->query_vars['facetwp'] ) ) {
        $is_main_query = true;
    }
    return $is_main_query;
}
add_filter( 'facetwp_is_main_query', 'my_facetwp_is_main_query', 10, 2 );






function woocommerce_get_product_thumbnail( $size = 'shop_catalog', $deprecated1 = 0, $deprecated2 = 0 ) {
  global $post;
  $image_size = apply_filters( 'single_product_archive_thumbnail_size', $size );

  if ( has_post_thumbnail() ) {
    $props = wc_get_product_attachment_props( get_post_thumbnail_id(), $post );
    return get_the_post_thumbnail( $post->ID, $image_size, array(
      'title'	 => $props['title'],
      'alt'    => $props['alt'],
    ) );
  } elseif ( wc_placeholder_img_src() ) {
    return wc_placeholder_img( $image_size );
  }
}

function woocommerce_before_shop_loop_item_title(){
  echo '<div class="row">';
    echo '<div class="col-lg-8">';
}
add_action('woocommerce_shop_loop_item_title', 'woocommerce_before_shop_loop_item_title', 9);



function woocommerce__shop_loop_item_price(){
  global $product;

  $price = $product->get_price_including_tax();
  $price = round( $price, 0 );
    echo '</div>';
  echo '<div class="col-lg-4">';
  echo '<span class="from_in">';
  echo 'from:';
  echo '</span>';
  echo '<span class="price_in">';
echo $price;
    echo '</span>';
    echo '<span class="from">';
    echo ' €';
    echo '</span>';

  echo '</div>';
}
add_action('woocommerce_shop_loop_item_title', 'woocommerce__shop_loop_item_price', 12);


function woocommerce_after_shop_loop_item_title(){
  echo '</div>';
}
add_action('woocommerce_shop_loop_item_title', 'woocommerce_after_shop_loop_item_title', 14);


function isa_woo_get_open_pa(){

  echo '<div class="col-lg-12 attr">';
  //echo '<div class="col-lg-12">';
  echo '  <table class="table">

                  <tbody>
                    <tr>';


}
add_action('woocommerce_after_shop_loop_item', 'isa_woo_get_open_pa', 15);


/*remove ead more */
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

/**
* WooCommerce: Show only one custom product attribute above Add-to-cart button on single product page.
*/
function isa_woo_get_one_pa(){

    // Edit below with the title of the attribute you wish to display
    $desired_att = 'Persons';

    global $product;
    $attributes = $product->get_attributes();

    if ( ! $attributes ) {
        return;
    }

    $out = '';

    foreach ( $attributes as $attribute ) {

        if ( $attribute['is_taxonomy'] ) {

            // sanitize the desired attribute into a taxonomy slug
            $tax_slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $desired_att)));

            // if this is desired att, get value and label

            if ( $attribute['name'] == 'pa_' . $tax_slug ) {

                $terms = wp_get_post_terms( $product->id, $attribute['name'], 'all' );

                // get the taxonomy
                $tax = $terms[0]->taxonomy;

                // get the tax object
                $tax_object = get_taxonomy($tax);

                // get tax label
                if ( isset ($tax_object->labels->name) ) {
                    $tax_label = $tax_object->labels->name;
                } elseif ( isset( $tax_object->label ) ) {
                    $tax_label = $tax_object->label;
                }

                foreach ( $terms as $term ) {

                  //  $out .= $tax_label . ': ';
                  $out .= '<td><img src="https://coolcars.dev/app/uploads/2017/02/icon_attr_persons.svg" class="icon-all"><h6>
' . $term->name . '</h6></td>';

                }

            } // our desired att

        } else {

            // for atts which are NOT registered as taxonomies

            // if this is desired att, get value and label
            if ( $attribute['name'] == $desired_att ) {
              //  $out .= $attribute['name'] . ': ';
                $out .= $attribute['value'];
            }
        }


    }

    echo $out;
}
add_action('woocommerce_after_shop_loop_item', 'isa_woo_get_one_pa', 16);

/**
* WooCommerce: Show only one custom product attribute above Add-to-cart button on single product page.
*/
function isa_woo_get_two_pa(){

    // Edit below with the title of the attribute you wish to display
    $desired_att = 'Fuel';

    global $product;
    $attributes = $product->get_attributes();

    if ( ! $attributes ) {
        return;
    }

    $out = '';

    foreach ( $attributes as $attribute ) {

        if ( $attribute['is_taxonomy'] ) {

            // sanitize the desired attribute into a taxonomy slug
            $tax_slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $desired_att)));

            // if this is desired att, get value and label

            if ( $attribute['name'] == 'pa_' . $tax_slug ) {

                $terms = wp_get_post_terms( $product->id, $attribute['name'], 'all' );

                // get the taxonomy
                $tax = $terms[0]->taxonomy;

                // get the tax object
                $tax_object = get_taxonomy($tax);

                // get tax label
                if ( isset ($tax_object->labels->name) ) {
                    $tax_label = $tax_object->labels->name;
                } elseif ( isset( $tax_object->label ) ) {
                    $tax_label = $tax_object->label;
                }

                foreach ( $terms as $term ) {

                  //  $out .= $tax_label . ': ';
                  $out .= '<td><img src="https://coolcars.dev/app/uploads/2017/02/icon_attr_fuel.svg" class="icon-all"> <h6> ' . $term->name . '</h6></td>';

                }

            } // our desired att

        } else {

            // for atts which are NOT registered as taxonomies

            // if this is desired att, get value and label
            if ( $attribute['name'] == $desired_att ) {
              //  $out .= $attribute['name'] . ': ';
                $out .= $attribute['value'];
            }
        }


    }

    echo $out;
}
add_action('woocommerce_after_shop_loop_item', 'isa_woo_get_two_pa', 17);

/**
* WooCommerce: Show only one custom product attribute above Add-to-cart button on single product page.
*/
function isa_woo_get_three_pa(){

    // Edit below with the title of the attribute you wish to display
    $desired_att = 'Gear';

    global $product;
    $attributes = $product->get_attributes();

    if ( ! $attributes ) {
        return;
    }

    $out = '';

    foreach ( $attributes as $attribute ) {

        if ( $attribute['is_taxonomy'] ) {

            // sanitize the desired attribute into a taxonomy slug
            $tax_slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $desired_att)));

            // if this is desired att, get value and label

            if ( $attribute['name'] == 'pa_' . $tax_slug ) {

                $terms = wp_get_post_terms( $product->id, $attribute['name'], 'all' );

                // get the taxonomy
                $tax = $terms[0]->taxonomy;

                // get the tax object
                $tax_object = get_taxonomy($tax);

                // get tax label
                if ( isset ($tax_object->labels->name) ) {
                    $tax_label = $tax_object->labels->name;
                } elseif ( isset( $tax_object->label ) ) {
                    $tax_label = $tax_object->label;
                }

                foreach ( $terms as $term ) {

                  //  $out .= $tax_label . ': ';
                  $out .= '<td><img src="https://coolcars.dev/app/uploads/2017/02/icon_attr_gear.svg" class="icon-all"> <h6> ' . $term->name . '</h6></td>';

                }

            } // our desired att

        } else {

            // for atts which are NOT registered as taxonomies

            // if this is desired att, get value and label
            if ( $attribute['name'] == $desired_att ) {
              //  $out .= $attribute['name'] . ': ';
                $out .= $attribute['value'];
            }
        }

    }

    echo $out;
}
add_action('woocommerce_after_shop_loop_item', 'isa_woo_get_three_pa', 18);

function isa_woo_get_close_pa(){

  echo '</tr></tbody></table>';
  echo '</div>';

}
add_action('woocommerce_after_shop_loop_item', 'isa_woo_get_close_pa' , 19);

// define the woocommerce_before_single_product callback
function attributes_woocommerce_before_single_product(  ) {
  	echo '<div class="container">';
};
// add the action
add_action( 'woocommerce_before_single_product', 'attributes_woocommerce_before_single_product', 10, 0 );

// define the woocommerce_after__single_product callback
function attributes_woocommerce_after_single_product(  ) {
    echo '</div>';
};
// add the action
add_action( 'woocommerce_after_single_product', 'attributes_woocommerce_after_single_product', 20, 0 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title' , 5, 0 );



function woocommerce_template_before_single_title() {

  echo '<div class="row">';

}
add_action( 'woocommerce_before_single_product_summary', 'woocommerce_template_before_single_title', 14, 0 );

add_action( 'woocommerce_before_single_product_summary', 'woocommerce_template_single_title', 15, 0 );

function woocommerce_template_after_single_title() {

  echo '</div>';

}
add_action( 'woocommerce_before_single_product_summary', 'woocommerce_template_after_single_title', 17, 0 );

function woocommerce_template_loop_price() {
  global $product;

  $price = $product->get_price_including_tax();
  $price = round( $price, 0 );
  echo '<div class="col-lg-4 col-xs-4 product_price_box">';
  echo '<span class="product_price">';
  echo '<span class="from_in">';
  echo 'from:';
  echo '</span>';
  echo '<span class="price_in">';
echo $price;
    echo '</span>';
    echo '<span class="from">';
    echo ' €';
    echo '</span>';
    echo '</span>';
  //echo $product->list_attributes();
  echo '</div>';
}
add_action( 'woocommerce_before_single_product_summary', 'woocommerce_template_loop_price', 16, 0 );

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs' , 10, 0 );
add_action( 'woocommerce_before_single_product_summary', 'woocommerce_output_product_data_tabs', 30, 0 );


function woocommerce_tabs_header() {

  if (sizeof(WC()->cart->get_cart()) != 0) {
    $wrap_header = get_template_part('templates/unit', 'tabs_2');
  } else {
    $wrap_header = get_template_part('templates/unit', 'tabs');
  }
  return $wrap_header;
 }
 add_action( 'woocommerce_single_product_summary', 'woocommerce_tabs_header' , 15, 0 );

 function woocommerce_tabs_body() {

   if (sizeof(WC()->cart->get_cart()) != 0) {
     $wrap_body = get_template_part('templates/unit', 'tabsbody_2');

   } else {
     get_template_part('templates/unit', 'tabsbody');
   }
   return $wrap_body;
  }
add_action( 'woocommerce_single_product_summary', 'woocommerce_tabs_body' , 16, 0 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart' , 30, 0 );

add_action( 'woocommerce_single_product_tabs', 'woocommerce_template_single_add_to_cart' , 10, 0 );

remove_action( 'woocommerce_before_single_product', 'wc_print_notices' );
add_action( 'woocommerce_checkout_after_customer_details', 'wc_print_notices', 10 );

// hide coupon form everywhere
function hide_coupon_field( $enabled ) {
	if ( is_cart() || is_checkout() ) {
		$enabled = false;
	}

	return $enabled;
}
add_filter( 'woocommerce_coupons_enabled', 'hide_coupon_field' );


add_filter( 'woocommerce_add_cart_item_data', '_empty_cart' );

    function _empty_cart( $cart_item_data ) {

        WC()->cart->empty_cart();

        return $cart_item_data;
    }


    // Bookings Date Format Tweak
add_filter( 'woocommerce_bookings_date_format' , 'woocommerce_bookings_custom_date_format' );
/**
 * woocommerce_bookings_custom_date_format
 *
 * @access      public
 * @since       1.0
 * @return      void
*/
function woocommerce_bookings_custom_date_format() {

	return 'M, j Y'; // your custom format here, see http://fr2.php.net/manual/en/function.date.php

}

add_action( 'woocommerce_get_item_data', 'wc_ninja_add_custom_product_meta', 15, 2 );
function wc_ninja_add_custom_product_meta(  $other_data, $cart_item ) {
	if ( ! empty( $cart_item['booking'] ) ) {
		$item = $cart_item['booking'];

		if ( 'night' === $item['_duration_unit'] ) {
			$per_night = $item['_cost'] / $item['duration'];

			$other_data[] = array(
				'name'    => 'Cost Per Night',
				'value'   => wc_price( $per_night ),
				'display' => '',
			);
		}
	}
	return $other_data;
}


// Hook in
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

function custom_override_checkout_fields( $fields ) {

  unset($fields['billing']['billing_address_1']);
  unset($fields['billing']['billing_address_2']);
  unset($fields['billing']['billing_postcode']);
  unset($fields['billing']['billing_state']);
  unset($fields['billing']['billing_company']);
  unset($fields['billing']['billing_address_2']);
  unset($fields['billing']['billing_country']);
  unset($fields['billing']['billing_city']);
$fields['order']['order_comments']['placeholder'] = 'My new placeholder';
$fields['billing']['billing_first_name']['default'] = $_REQUEST['your-first-name'];
$fields['billing']['billing_last_name']['default'] = $_REQUEST['your-last-name'];
$fields['billing']['billing_email']['default'] = $_REQUEST['your-email'];
$fields['billing']['billing_phone']['default'] =  $_REQUEST['your-phone'];

    return $fields;
}


add_filter( 'woocommerce_enqueue_styles', 'wooc_dequeue_styles' );

function wooc_dequeue_styles( $enqueue_styles ) {
    unset( $enqueue_styles['woocommerce-general'] );
    return $enqueue_styles;
}


/**
 * Optimize WooCommerce Scripts
 * Remove WooCommerce Generator tag, styles, and scripts from non WooCommerce pages.
 */
add_action( 'wp_enqueue_scripts', 'child_manage_woocommerce_styles', 99 );

function child_manage_woocommerce_styles() {
 //remove generator meta tag
 remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );

 //first check that woo exists to prevent fatal errors
 if ( function_exists( 'is_woocommerce' ) ) {
 //dequeue scripts and styles
 if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
 wp_dequeue_style( 'woocommerce_frontend_styles' );
 wp_dequeue_style( 'woocommerce_fancybox_styles' );
 wp_dequeue_style( 'woocommerce_chosen_styles' );
 wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
 wp_dequeue_script( 'wc_price_slider' );
 wp_dequeue_script( 'wc-single-product' );
 wp_dequeue_script( 'wc-add-to-cart' );
 wp_dequeue_script( 'wc-cart-fragments' );
 wp_dequeue_script( 'wc-checkout' );
 wp_dequeue_script( 'wc-add-to-cart-variation' );
 wp_dequeue_script( 'wc-single-product' );
 wp_dequeue_script( 'wc-cart' );
 wp_dequeue_script( 'wc-chosen' );
 wp_dequeue_script( 'woocommerce' );
 wp_dequeue_script( 'prettyPhoto' );
 wp_dequeue_script( 'prettyPhoto-init' );
 wp_dequeue_script( 'jquery-blockui' );
 wp_dequeue_script( 'jquery-placeholder' );
 wp_dequeue_script( 'fancybox' );
 wp_dequeue_script( 'jqueryui' );
 }
 }

}
/**
 * The following hook will add a input field right before "add to cart button"
 * will be used for getting Your first name
 */

 function add_before_your_first_name_field() {
     echo '<fieldset class="wc-bookings-fields">';

      echo '<div class="form-group row">';
 }
 add_action( 'woocommerce_before_add_to_cart_button', 'add_before_your_first_name_field', 10 );

 function add_after_your_first_name_field() {
   echo '</div>';
     echo '</fieldset>';
 }
 add_action( 'woocommerce_before_add_to_cart_button', 'add_after_your_first_name_field', 30 );

function add_your_first_name_field() {
echo '<div class="col-lg-6">';
    echo '<label>Name</label>';
    echo '  <input name="your-first-name" type="text" class="form-control" id="inputName" placeholder="" required>';
echo '</div>';
}
add_action( 'woocommerce_before_add_to_cart_button', 'add_your_first_name_field', 20 );

function add_your_last_name_field() {
echo '<div class="col-lg-6">';
echo '<div class="form-group">';
echo '<label>Last Name</label>';
    echo '<input type="text" name="your-last-name" value="" />';
echo '</div>';
echo '</div>';
}
add_action( 'woocommerce_before_add_to_cart_button', 'add_your_last_name_field', 21 );

function add_your_email_field() {
echo '<div class="col-lg-6">';
echo '<label>Email</label>';
    echo '<input type="email" name="your-email" value="" />';
echo '</div>';
}
add_action( 'woocommerce_before_add_to_cart_button', 'add_your_email_field', 22 );


function add_your_phone_field() {
echo '<div class="col-lg-6">';
echo '<label>Phone</label>';
  //echo '<input name="your-phone" type="text" id="demo" value="10:30 AM" data-format="hh:mm A">';
  echo '<input type="text" name="your-phone" value="" />';
echo '</div>';
}
add_action( 'woocommerce_before_add_to_cart_button', 'add_your_phone_field', 23 );

function add_intime_field() {
  echo '<div class="col-lg-6">';
 echo '<input type="text" name="in-time" value="12:30" class="in_time"/>';

  echo '</div>';
}
add_action( 'woocommerce_bookings_time', 'add_intime_field', 10 );


function add_outtime_field() {
  echo '<div class="col-lg-6">';
  echo '<input type="text" name="out-time" value="12:30" class="out_time"/>';
  //echo '<input name="out-time" type="text" id="outtime" value="10:30 AM" data-format="hh:mm A">';
  //echo '<input type="text" name="out-time" value="12:30 PM" class="time_element"/>';
  echo '</div>';
}
add_action( 'woocommerce_bookings_time', 'add_outtime_field', 11 );


function add_location_start_field() {
  echo '<div class="col-lg-6">';
  echo '<div class="row form-group">';
}
add_action( 'woocommerce_bookings_time', 'add_location_start_field', 12 );

function add_location_end_field() {
echo '</div>';
  echo '</div>';
}
add_action( 'woocommerce_bookings_time', 'add_location_end_field', 20 );

function add_pickup_field_1() {
  echo '<div class="col-lg-4 text-center">';
  echo '<input id="radio1" type="radio" name="pick-up" value="Airport" class="custom-radio" checked="">
       <label for="pick-up">Airport</label>
      </div>';
}
add_action( 'woocommerce_bookings_time', 'add_pickup_field_1', 13 );

function add_pickup_field_2() {
  echo '<div class="col-lg-4 text-center">';
  echo '<input id="radio1" type="radio" name="pick-up" value="Port" class="custom-radio">
       <label for="pick-up">Port</label>
      </div>';
}
add_action( 'woocommerce_bookings_time', 'add_pickup_field_2', 14 );

function add_pickup_field_3() {
  echo '<div class="col-lg-4 text-center">';
  echo '<input id="radio1" type="radio" name="pick-up" value="other_location" class="custom-radio">
       <label for="pick-up">other location</label>';
}
add_action( 'woocommerce_bookings_time', 'add_pickup_field_3', 15 );

function add_pickup_field_3_b() {
  echo '<div class="reveal-if-active">
  <textarea name="pick-up" class="input-text form-control require-if-active" data-require-pair="#pick-up-hotel" id="order_comments" placeholder="" rows="4" cols="5"></textarea>
  </div>
  </div>';
}
add_action( 'woocommerce_bookings_time', 'add_pickup_field_3_b', 16 );

function add_location_2_start_field() {
  echo '<div class="col-lg-6">';
    echo '<div class="row form-group">';
}
add_action( 'woocommerce_bookings_time', 'add_location_2_start_field', 21 );

function add_location_2_end_field() {
 echo '</div>';
  echo '</div>';
}
add_action( 'woocommerce_bookings_time', 'add_location_2_end_field', 26 );

function add_dropoff_field_1() {
  echo '<div class="col-lg-4 text-center">';
  echo '<input id="radio1" type="radio" name="drop-off" value="Airport" class="custom-radio" checked="">
       <label for="drop-off">Airport</label>
      </div>';
}
add_action( 'woocommerce_bookings_time', 'add_dropoff_field_1', 22 );

function add_dropoff_field_2() {
  echo '<div class="col-lg-4 text-center">';
  echo '<input id="radio1" type="radio" name="drop-off" value="Port" class="custom-radio">
       <label for="drop-off">Port</label>
      </div>';
}
add_action( 'woocommerce_bookings_time', 'add_dropoff_field_2', 23 );

function add_dropoff_field_3() {
  echo '<div class="col-lg-4 text-center">';
  echo '<input id="radio1" type="radio" name="drop-off" value="other_location" class="custom-radio">
       <label for="drop-off">other location</label>';
}
add_action( 'woocommerce_bookings_time', 'add_dropoff_field_3', 24 );

function add_dropoff_field_3_b() {
  echo '<div class="reveal-if-active">
  <textarea name="drop-off" class="input-text form-control require-if-active" data-require-pair="#drop-off-hotel" id="order_comments" placeholder="" rows="4" cols="5"></textarea>
  </div>
  </div>';
}
add_action( 'woocommerce_bookings_time', 'add_dropoff_field_3_b', 25 );


function add_info_field() {
  echo '<div class="col-lg-12 additional_info">';
  echo '<label>Additional info</label>';
  echo '<textarea name="order_comments" class="input-text form-control" id="order_comments" placeholder="" rows="4" cols="5"></textarea>';
  echo '</div>';
}
add_action( 'woocommerce_before_add_to_cart_button', 'add_info_field', 19 );

function save_your_first_name_field( $cart_item_data, $product_id ) {
    if( isset( $_REQUEST['your-first-name'] ) ) {
        $cart_item_data[ 'your_first_name' ] = $_REQUEST['your-first-name'];
        /* below statement make sure every add to cart action as unique line item */
        $cart_item_data['unique_key'] = md5( microtime().rand() );
    }
    return $cart_item_data;
}
add_action( 'woocommerce_add_cart_item_data', 'save_your_first_name_field', 10, 2 );



function save_your_last_name_field( $cart_item_data, $product_id ) {
    if( isset( $_REQUEST['your-last-name'] ) ) {
        $cart_item_data[ 'your_last_name' ] = $_REQUEST['your-last-name'];
        /* below statement make sure every add to cart action as unique line item */
        $cart_item_data['unique_key'] = md5( microtime().rand() );
    }
    return $cart_item_data;
}
add_action( 'woocommerce_add_cart_item_data', 'save_your_last_name_field', 10, 3 );


function save_your_email_field( $cart_item_data, $product_id ) {
    if( isset( $_REQUEST['your-email'] ) ) {
        $cart_item_data[ 'your_email' ] = $_REQUEST['your-email'];
        /* below statement make sure every add to cart action as unique line item */
        $cart_item_data['unique_key'] = md5( microtime().rand() );
    }
    return $cart_item_data;
}
add_action( 'woocommerce_add_cart_item_data', 'save_your_email_field', 10, 4 );

function save_your_phone_field( $cart_item_data, $product_id ) {
    if( isset( $_REQUEST['your-phone'] ) ) {
        $cart_item_data[ 'your_phone' ] = $_REQUEST['your-phone'];
        /* below statement make sure every add to cart action as unique line item */
        $cart_item_data['unique_key'] = md5( microtime().rand() );
    }
    return $cart_item_data;
}
add_action( 'woocommerce_add_cart_item_data', 'save_your_phone_field', 10, 5 );


function save_intime_field( $cart_item_data, $product_id ) {
    if( isset( $_REQUEST['in-time'] ) ) {
        $cart_item_data[ 'in_time' ] = $_REQUEST['in-time'];
        /* below statement make sure every add to cart action as unique line item */
        $cart_item_data['unique_key'] = md5( microtime().rand() );
    }
    return $cart_item_data;
}
add_action( 'woocommerce_add_cart_item_data', 'save_intime_field', 10, 6 );

function save_outtime_field( $cart_item_data, $product_id ) {
    if( isset( $_REQUEST['out-time'] ) ) {
        $cart_item_data[ 'out_time' ] = $_REQUEST['out-time'];
        /* below statement make sure every add to cart action as unique line item */
        $cart_item_data['unique_key'] = md5( microtime().rand() );
    }
    return $cart_item_data;
}
add_action( 'woocommerce_add_cart_item_data', 'save_outtime_field', 10, 7 );


function save_pickup_field( $cart_item_data, $product_id ) {
    if( isset( $_REQUEST['pick-up'] ) ) {
        $cart_item_data[ 'pick_up' ] = $_REQUEST['pick-up'];
        /* below statement make sure every add to cart action as unique line item */
        $cart_item_data['unique_key'] = md5( microtime().rand() );
    }
    return $cart_item_data;
}
add_action( 'woocommerce_add_cart_item_data', 'save_pickup_field', 10, 8 );

function save_dropoff_field( $cart_item_data, $product_id ) {
    if( isset( $_REQUEST['drop-off'] ) ) {
        $cart_item_data[ 'drop_off' ] = $_REQUEST['drop-off'];
        /* below statement make sure every add to cart action as unique line item */
        $cart_item_data['unique_key'] = md5( microtime().rand() );
    }
    return $cart_item_data;
}
add_action( 'woocommerce_add_cart_item_data', 'save_dropoff_field', 10, 9 );


function save_your_date_month_field( $cart_item_data, $product_id ) {
    if( isset( $_REQUEST['start-date-month'] ) ) {
        $cart_item_data[ 'wc_bookings_field_start_date_month' ] = $_REQUEST['start-date-month'];
        /* below statement make sure every add to cart action as unique line item */
        $cart_item_data['unique_key'] = md5( microtime().rand() );
    }
    return $cart_item_data;
}
add_action( 'woocommerce_add_cart_item_data', 'save_your_date_month_field', 10, 10 );



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

function render_meta_on_cart_and_checkout_2( $cart_data, $cart_item = null ) {
    $custom_items = array();
    /* Woo 2.4.2 updates */
    if( !empty( $cart_data ) ) {
        $custom_items = $cart_data;
    }
    if( isset( $cart_item['your_last_name'] ) ) {
        $custom_items[] = array( "lastname" => 'Your last name', "value" => $cart_item['your_last_name'] );
    }
    return $custom_items;
}
add_filter( 'woocommerce_get_item_data', 'render_meta_on_cart_and_checkout_2', 10, 3 );

function render_meta_on_cart_and_checkout_3( $cart_data, $cart_item = null ) {
    $custom_items = array();
    /* Woo 2.4.2 updates */
    if( !empty( $cart_data ) ) {
        $custom_items = $cart_data;
    }
    if( isset( $cart_item['your_email'] ) ) {
        $custom_items[] = array( "email" => 'Your email', "value" => $cart_item['your_email'] );
    }
    return $custom_items;
}

add_filter( 'woocommerce_get_item_data', 'render_meta_on_cart_and_checkout_3', 10, 4 );

function render_meta_on_cart_and_checkout_4( $cart_data, $cart_item = null ) {
    $custom_items = array();
    /* Woo 2.4.2 updates */
    if( !empty( $cart_data ) ) {
        $custom_items = $cart_data;
    }
    if( isset( $cart_item['your_phone'] ) ) {
        $custom_items[] = array( "phone" => 'Your phone', "value" => $cart_item['your_phone'] );
    }
    return $custom_items;
}

add_filter( 'woocommerce_get_item_data', 'render_meta_on_cart_and_checkout_4', 10, 5 );


function render_meta_on_cart_and_checkout_5( $cart_data, $cart_item = null ) {
    $custom_items = array();
    /* Woo 2.4.2 updates */
    if( !empty( $cart_data ) ) {
        $custom_items = $cart_data;
    }
    if( isset( $cart_item['in_time'] ) ) {
        $custom_items[] = array( "intime" => 'In time', "value" => $cart_item['in_time'] );
    }
    return $custom_items;
}

add_filter( 'woocommerce_get_item_data', 'render_meta_on_cart_and_checkout_5', 10, 6 );

function render_meta_on_cart_and_checkout_6( $cart_data, $cart_item = null ) {
    $custom_items = array();
    /* Woo 2.4.2 updates */
    if( !empty( $cart_data ) ) {
        $custom_items = $cart_data;
    }
    if( isset( $cart_item['out_time'] ) ) {
        $custom_items[] = array( "outtime" => 'Out time', "value" => $cart_item['out_time'] );
    }
    return $custom_items;
}

add_filter( 'woocommerce_get_item_data', 'render_meta_on_cart_and_checkout_6', 10, 7 );


function render_meta_on_cart_and_checkout_8( $cart_data, $cart_item = null ) {
    $custom_items = array();
    /* Woo 2.4.2 updates */
    if( !empty( $cart_data ) ) {
        $custom_items = $cart_data;
    }
    if( isset( $cart_item['pick_up'] ) ) {
        $custom_items[] = array( "pickup" => 'Pickup location', "value" => $cart_item['pick_up'] );
    }
    return $custom_items;
}

add_filter( 'woocommerce_get_item_data', 'render_meta_on_cart_and_checkout_8', 10, 9 );


function render_meta_on_cart_and_checkout_9( $cart_data, $cart_item = null ) {
    $custom_items = array();
    /* Woo 2.4.2 updates */
    if( !empty( $cart_data ) ) {
        $custom_items = $cart_data;
    }
    if( isset( $cart_item['drop_off'] ) ) {
        $custom_items[] = array( "dropoff" => 'Dropoff location', "value" => $cart_item['drop_off'] );
    }
    return $custom_items;
}

add_filter( 'woocommerce_get_item_data', 'render_meta_on_cart_and_checkout_9', 10, 10 );


function render_meta_on_cart_and_checkout_10( $cart_data, $cart_item = null ) {
    $custom_items = array();
    /* Woo 2.4.2 updates */
    if( !empty( $cart_data ) ) {
        $custom_items = $cart_data;
    }
    if( isset( $cart_item['wc_bookings_field_start_date_month'] ) ) {
        $custom_items[] = array( "startdate" => 'Start Date', "value" => $cart_item['wc_bookings_field_start_date_month'] );
    }
    return $custom_items;
}

add_filter( 'woocommerce_get_item_data', 'render_meta_on_cart_and_checkout_10', 10, 11 );


function get_cart_content($cart_data, $cart_item = null  ) {
  $content = WC()->cart->cart_contents;

  	$output = '';
  	foreach( $content as $item ) {
  		// Get the image and your specified image size.
  		//$image = get_the_post_thumbnail($item['product_id'], 'small_thumb' );

  		$output .='<span class="top-cart-item-cool">Cool!</span>
                <span class="top-cart-item">
                '. get_the_title( $item['product_id'] ) .'
                is avalaible.
                </span>

                <span class="top-cart-item-quantity">x '.  $item['_start_date'].'</span>
                ';
  	}

  	echo $output;


}
add_action( 'woocommerce_checkout_cart_info', 'get_cart_content', 1 );





function tshirt_order_meta_handler( $item_id, $values, $cart_item_key ) {
    if( isset( $values['your_first_name'] ) ) {
        wc_add_order_item_meta( $item_id, "your_first_name", $values['your_first_name'] );
    }
}
add_action( 'woocommerce_add_order_item_meta', 'tshirt_order_meta_handler', 1, 3 );

function tshirt_order_meta_handler_2( $item_id, $values, $cart_item_key ) {
    if( isset( $values['your_last_name'] ) ) {
        wc_add_order_item_meta( $item_id, "your_last_name", $values['your_last_name'] );
    }
}
add_action( 'woocommerce_add_order_item_meta', 'tshirt_order_meta_handler_2', 1, 4 );

function tshirt_order_meta_handler_3( $item_id, $values, $cart_item_key ) {
    if( isset( $values['your_email'] ) ) {
        wc_add_order_item_meta( $item_id, "your_email", $values['your_email'] );
    }
}
add_action( 'woocommerce_add_order_item_meta', 'tshirt_order_meta_handler_3', 1, 5 );



function tshirt_order_meta_handler_4( $item_id, $values, $cart_item_key ) {
    if( isset( $values['your_phone'] ) ) {
        wc_add_order_item_meta( $item_id, "your_phone", $values['your_phone'] );
    }
}
add_action( 'woocommerce_add_order_item_meta', 'tshirt_order_meta_handler_4', 1, 6 );



function tshirt_order_meta_handler_5( $item_id, $values, $cart_item_key ) {
    if( isset( $values['in_time'] ) ) {
        wc_add_order_item_meta( $item_id, "in_time", $values['in_time'] );
    }
}
add_action( 'woocommerce_add_order_item_meta', 'tshirt_order_meta_handler_5', 1, 7 );


function tshirt_order_meta_handler_6( $item_id, $values, $cart_item_key ) {
    if( isset( $values['out_time'] ) ) {
        wc_add_order_item_meta( $item_id, "out_time", $values['out_time'] );
    }
}
add_action( 'woocommerce_add_order_item_meta', 'tshirt_order_meta_handler_6', 1, 8 );

function tshirt_order_meta_handler_7( $item_id, $values, $cart_item_key ) {
    if( isset( $values['pick_up'] ) ) {
        wc_add_order_item_meta( $item_id, "pick_up", $values['pick_up'] );
    }
}
add_action( 'woocommerce_add_order_item_meta', 'tshirt_order_meta_handler_7', 1, 9 );

function tshirt_order_meta_handler_8( $item_id, $values, $cart_item_key ) {
    if( isset( $values['drop_off'] ) ) {
        wc_add_order_item_meta( $item_id, "drop_off", $values['drop_off'] );
    }
}
add_action( 'woocommerce_add_order_item_meta', 'tshirt_order_meta_handler_8', 1, 10 );

function tshirt_order_meta_handler_9( $item_id, $values, $cart_item_key ) {
    if( isset( $values['wc_bookings_field_start_date_month'] ) ) {
        wc_add_order_item_meta( $item_id, "wc_bookings_field_start_date_month", $values['wc_bookings_field_start_date_month'] );
    }
}
add_action( 'woocommerce_add_order_item_meta', 'tshirt_order_meta_handler_8', 1, 11 );


add_filter( 'woocommerce_product_tabs', 'helloacm_remove_product_review', 99);
function helloacm_remove_product_review($tabs) {
    unset($tabs['reviews']);
    return $tabs;
}


add_filter ( 'wc_add_to_cart_message', 'wc_add_to_cart_message_filter', 10, 2 );
function wc_add_to_cart_message_filter($message, $product_id = null)
 {
   $titles[] = get_the_title( $product_id );
   $komati = $item['drop_off'];
   $titles = array_filter( $titles );

   $added_text = sprintf( _n( '%%s', 's%s', sizeof( $titles ), 'woocommerce' ), wc_format_list_of_items( $titles ) );

   $message = sprintf( '%s <a href="%s" class="button">%s</a>&nbsp;<a href="%s" class="button">%s</a>',
$komati,
                   esc_html( $added_text ),
                   esc_url( wc_get_page_permalink( 'checkout' ) ),
                   esc_html__( 'Buy now', 'woocommerce' ),
                   esc_url( wc_get_page_permalink( 'cart' ) ),
                   esc_html__( 'View Baskett', 'woocommerce' ));

   return $message;
}


function get_time_data() {
  echo '<div class="test col-lg-12">';
  echo 'test';
  echo '</div>';
}
//add_action( 'woocommerce_before_add_to_cart_button', 'get_time_data', 19 );

//*Add custom redirection
add_action( 'template_redirect', 'wc_custom_redirect_after_purchase' );
function wc_custom_redirect_after_purchase() {
	global $wp;
  if ( is_checkout() && ! empty( $wp->query_vars['order-received'] ) ) {
		wp_redirect( 'http://coolcars.gr/thank-you/' );
		exit;
	}
}

add_action( 'woocommerce_order_status_completed', 'mark_confirm_bookings', 20, 1 );

function mark_confirm_bookings( $order_id ) {
  global $wpdb;
  $order    = new WC_Order( $order_id );
  $bookings = array();
  foreach ( $order->get_items() as $order_item_id => $item ) {
    if ( 'line_item' == $item['type'] ) {
      if ( !wc_booking_requires_confirmation( $item['product_id'] ) ) {
        $bookings = array_merge( $bookings, $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_booking_order_item_id' AND meta_value = %d", $order_item_id ) ) );
      }
    }
  }
  foreach ( $bookings as $booking_id ) {
    $booking = get_wc_booking( $booking_id );
    $booking->update_status('confirmed');
  }
}
