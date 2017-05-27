<?php
/**
 * Custom AJAX Functions
 * Don't always need AJAX on a site, but when you do, use ajax.php.
 * Uncomment things below as needed.
 * Note: Example javascript for an AJAX code in _main.js
 */



// Attach to our standard script enqueue and create a localized script
// containing needed url/nonce variables for AJAX processing.
add_action('wp_enqueue_scripts', 'roots_ajax_scripts', 99);
function roots_ajax_scripts() {
  // Setup a localized script with relevant url and nonce information.
  wp_localize_script( 'roots_scripts', 'rootsAjax', array(
      'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
      'exampleNonce' => wp_create_nonce( 'roots-example-nonce' ),
      )
  );
}



// Add action for not-logged-in user.
add_action( 'wp_ajax_nopriv_example_ajax', 'roots_example_ajax' );
// Add action for logged-in user.
add_action( 'wp_ajax_example_ajax', 'roots_example_ajax' );

// Example function for performing whatever back-end actions are
// expected when the AJAX call is run.
function roots_example_ajax() {

  // Ignore request if there is no nonce sent or it isn't verified.
  $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : null;
  if(!$nonce || !wp_verify_nonce( $nonce, 'roots-example-nonce' )) exit;

  // Perform whatever actions & such you want here. Create a response
  // array to mirror our expected JSON object.
  $response = array();

  // Wrap the response and output it.
  roots_ajax_response($response);

}



// When doing a json return for any front-end data we need the proper
// header type and to json encode our response array.
function roots_ajax_response($response = null) {

  // End AJAX return if no data.
  if($response == null) exit;

  // Proper headers for json output.
  header('Content-Type: application/json');

  // Output our response.
  echo json_encode($response);

  exit;

}


