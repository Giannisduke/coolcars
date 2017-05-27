<?php
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
