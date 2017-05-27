<?php

/**
 * Get a booking object
 * @param  int $id
 * @return object
 */
function get_wc_booking( $id ) {
	return new WC_Booking( $id );
}

/**
 * Santiize and format a string into a valid 24 hour time
 * @return string
 */
function wc_booking_sanitize_time( $raw_time ) {
	$time = wc_clean( $raw_time );
	$time = date( 'H:i', strtotime( $time ) );
	return $time;
}

/**
 * Returns true if the product is a booking product, false if not
 * @return bool
 */
function is_wc_booking_product( $product ) {
	if ( empty( $product->product_type ) ) {
		return false;
	}

	$booking_product_types = apply_filters( 'woocommerce_bookings_product_types', array( 'booking' ) );
	if ( in_array( $product->product_type, $booking_product_types ) ) {
		return true;
	}

	return false;
}

/**
 * Convert key to a nice readable label
 * @param  string $key
 * @return string
 */
function get_wc_booking_data_label( $key, $product ) {
	$labels = apply_filters( 'woocommerce_bookings_data_labels', array(
			'type'     => ( $product->wc_booking_resouce_label ? $product->wc_booking_resouce_label : __( 'Booking Type', 'woocommerce-bookings' ) ),
			'date'     => __( 'Booking Date', 'woocommerce-bookings' ),
			'time'     => __( 'Booking Time', 'woocommerce-bookings' ),
			'duration' => __( 'Duration', 'woocommerce-bookings' ),
			'persons'  => __( 'Person(s)', 'woocommerce-bookings' )
	) );

	if ( ! array_key_exists( $key, $labels ) ) {
		return $key;
	}

	return $labels[ $key ];
}

/**
 * Returns a list of booking statuses.
 *
 * @since 1.9.13 Add new parameter that allows globalised status strings as part of the array.
 * @param  string $context An optional context (filters) for user or cancel statuses
 * @param boolean $include_translation_strings. Defaults to false. This introduces status translations text string. In future (2.0) should default to true.
 * @return array $statuses
 */
function get_wc_booking_statuses( $context = 'fully_booked', $include_translation_strings = false ) {

	if ( 'user' === $context ) {
		$statuses = apply_filters( 'woocommerce_bookings_for_user_statuses', array(
			'unpaid'               => __( 'Unpaid','woocommerce-bookings' ),
			'pending-confirmation' => __( 'Pending Confirmation','woocommerce-bookings' ),
			'confirmed'            => __( 'Confirmed','woocommerce-bookings' ),
			'paid'                 => __( 'Paid','woocommerce-bookings' ),
			'cancelled'            => __( 'Cancelled','woocommerce-bookings' ),
			'complete'             => __( 'Complete','woocommerce-bookings' ),
		) );
	} else if ( 'cancel' === $context ) {
		$statuses = apply_filters( 'woocommerce_valid_booking_statuses_for_cancel', array(
			'unpaid'               => __( 'Unpaid','woocommerce-bookings' ),
			'pending-confirmation' => __( 'Pending Confirmation','woocommerce-bookings' ),
			'confirmed'            => __( 'Confirmed','woocommerce-bookings' ),
			'paid'                 => __( 'Paid','woocommerce-bookings' ),
		) );
	} else if ( 'scheduled' === $context ) {
		$statuses = apply_filters( 'woocommerce_bookings_scheduled_statuses', array(
			'paid'                 => __( 'Paid','woocommerce-bookings' ),
		) );
	} else {
		$statuses = apply_filters( 'woocommerce_bookings_fully_booked_statuses', array(
			'unpaid'               => __( 'Unpaid','woocommerce-bookings' ),
			'pending-confirmation' => __( 'Pending Confirmation','woocommerce-bookings' ),
			'confirmed'            => __( 'Confirmed','woocommerce-bookings' ),
			'paid'                 => __( 'Paid','woocommerce-bookings' ),
			'complete'             => __( 'Complete','woocommerce-bookings' ),
			'in-cart'              => __( 'In Cart','woocommerce-bookings' ),
		) );
	}

	if ( class_exists( 'WC_Deposits' ) ) {
		$statuses['wc-partial-payment'] = __( 'Partially Paid','woocommerce-deposits' );
	}
	// backwards compatibility
	return $include_translation_strings ? $statuses : array_keys( $statuses );
}

/**
 * Validate and create a new booking manually.
 *
 * @see WC_Booking::new_booking() for available $new_booking_data args
 * @param  int $product_id you are booking
 * @param  array $new_booking_data
 * @param  string $status
 * @param  boolean $exact If false, the function will look for the next available block after your start date if the date is unavailable.
 * @return mixed WC_Booking object on success or false on fail
 */
function create_wc_booking( $product_id, $new_booking_data = array(), $status = 'confirmed', $exact = false ) {
	// Merge booking data
	$defaults = array(
		'product_id'  => $product_id, // Booking ID
		'start_date'  => '',
		'end_date'    => '',
		'resource_id' => '',
	);

	$new_booking_data = wp_parse_args( $new_booking_data, $defaults );
	$product          = wc_get_product( $product_id );
	$start_date       = $new_booking_data['start_date'];
	$end_date         = $new_booking_data['end_date'];
	$max_date         = $product->get_max_date();
	$qty = 1;

	if ( 'yes' === $product->wc_booking_person_qty_multiplier && ! empty ( $new_booking_data['persons'] ) ) {
		if ( is_array( $new_booking_data['persons'] ) ) {
			$qty = array_sum( $new_booking_data['persons'] );
		} else {
			$qty = $new_booking_data['persons'];
			$new_booking_data['persons'] = array( $qty );
		}
	}

	// If not set, use next available
	if ( ! $start_date ) {
		$min_date   = $product->get_min_date();
		$start_date = strtotime( "+{$min_date['value']} {$min_date['unit']}", current_time( 'timestamp' ) );
	}

	// If not set, use next available + block duration
	if ( ! $end_date ) {
		$end_date = strtotime( "+{$product->wc_booking_duration} {$product->wc_booking_duration_unit}", $start_date );
	}

	$searching = true;
	$date_diff = $end_date - $start_date;

	while( $searching ) {

		$available_bookings = $product->get_available_bookings( $start_date, $end_date, $new_booking_data['resource_id'], $qty );

		if ( $available_bookings && ! is_wp_error( $available_bookings ) ) {

			if ( ! $new_booking_data['resource_id'] && is_array( $available_bookings ) ) {
				$new_booking_data['resource_id'] = current( array_keys( $available_bookings ) );
			}

			$searching = false;

		} else {
			if ( $exact )
				return false;

			$start_date += $date_diff;
			$end_date   += $date_diff;

			if ( $end_date > strtotime( "+{$max_date['value']} {$max_date['unit']}" ) )
				return false;
		}
	}

	// Set dates
	$new_booking_data['start_date'] = $start_date;
	$new_booking_data['end_date']   = $end_date;

	// Create it
	$new_booking = get_wc_booking( $new_booking_data );
	$new_booking ->create( $status );

	return $new_booking;
}

/**
 * Check if product/booking requires confirmation.
 *
 * @param  int $id Product ID.
 *
 * @return bool
 */
function wc_booking_requires_confirmation( $id ) {
	$product = wc_get_product( $id );

	if (
		is_object( $product )
		&& is_wc_booking_product( $product )
		&& $product->requires_confirmation()
	) {
		return true;
	}

	return false;
}

/**
 * Check if the cart has booking that requires confirmation.
 *
 * @return bool
 */
function wc_booking_cart_requires_confirmation() {
	$requires = false;

	if ( ! empty ( WC()->cart->cart_contents ) ) {
		foreach ( WC()->cart->cart_contents as $item ) {
			if ( wc_booking_requires_confirmation( $item['product_id'] ) ) {
				$requires = true;
				break;
			}
		}
	}

	return $requires;
}

/**
 * Check if the order has booking that requires confirmation.
 *
 * @param  WC_Order $order
 *
 * @return bool
 */
function wc_booking_order_requires_confirmation( $order ) {
	$requires = false;

	if ( $order ) {
		foreach ( $order->get_items() as $item ) {
			if ( wc_booking_requires_confirmation( $item['product_id'] ) ) {
				$requires = true;
				break;
			}
		}
	}

	return $requires;
}

/**
 * Get timezone string.
 *
 * inspired by https://wordpress.org/plugins/event-organiser/
 *
 * @return string
 */
function wc_booking_get_timezone_string() {
	$timezone = wp_cache_get( 'wc_bookings_timezone_string' );

	if ( false === $timezone ) {
		$timezone   = get_option( 'timezone_string' );
		$gmt_offset = get_option( 'gmt_offset' );

		// Remove old Etc mappings. Fallback to gmt_offset.
		if ( ! empty( $timezone ) && false !== strpos( $timezone, 'Etc/GMT' ) ) {
			$timezone = '';
		}

		if ( empty( $timezone ) && 0 != $gmt_offset ) {
			// Use gmt_offset
			$gmt_offset   *= 3600; // convert hour offset to seconds
			$allowed_zones = timezone_abbreviations_list();

			foreach ( $allowed_zones as $abbr ) {
				foreach ( $abbr as $city ) {
					if ( $city['offset'] == $gmt_offset ) {
						$timezone = $city['timezone_id'];
						break 2;
					}
				}
			}
		}

		// Issue with the timezone selected, set to 'UTC'
		if ( empty( $timezone ) ) {
			$timezone = 'UTC';
		}

		// Cache the timezone string.
		wp_cache_set( 'wc_bookings_timezone_string', $timezone );
	}

	return $timezone;
}

/**
 * Get bookable product resources.
 *
 * @param int $product_id product ID.
 *
 * @return array Resources objects list.
 */
function wc_booking_get_product_resources( $product_id ) {
	global $wpdb;

	$resources = array();
	$posts     = $wpdb->get_results(
		$wpdb->prepare( "
			SELECT posts.ID, posts.post_title
			FROM {$wpdb->prefix}wc_booking_relationships AS relationships
				LEFT JOIN $wpdb->posts AS posts
				ON posts.ID = relationships.resource_id
			WHERE relationships.product_id = %d
			ORDER BY sort_order ASC
		", $product_id )
	);

	foreach ( $posts as $resource ) {
		$resources[] = new WC_Product_Booking_Resource( $resource, $product_id );
	}

	return $resources;
}

/**
 * Get bookable product resource by ID.
 *
 * @param int $product_id product ID.
 * @param int $resource_id resource ID
 *
 * @return array Resources object.
 */
function wc_booking_get_product_resource( $product_id, $resource_id ) {
	global $wpdb;

	$resources = array();
	$posts     = $wpdb->get_results(
		$wpdb->prepare( "
			SELECT posts.ID, posts.post_title
			FROM {$wpdb->prefix}wc_booking_relationships AS relationships
				LEFT JOIN $wpdb->posts AS posts
				ON posts.ID = relationships.resource_id
			WHERE relationships.product_id = %d
			ORDER BY sort_order ASC
		", $product_id )
	);

	$found = false;
	foreach ( $posts as $resource ) {
		if ( $resource->ID == $resource_id ) {
			$found = $resource;
			$found = new WC_Product_Booking_Resource( $found, $product_id );
		}
	}

	return $found;
}

/**
 * @return mixed|string|void
 * @sine 1.9.10
 * @return string
 */
function get_wc_booking_rules_explanation() {
	return __( 'Rules with lower priority numbers will override rules with a higher priority (e.g. 9 overrides 10 ). Ordering is only applied within the same priority and higher order overrides lower order.', 'woocommerce-bookings' );
}

function get_wc_booking_priority_explanation() {
	return __( 'Rules with lower priority numbers will override rules with a higher priority (e.g. 9 overrides 10 ). Global rules take priority over product rules which take priority over resource rules. By using priority numbers you can execute rules in different orders.', 'woocommerce-bookings' );
}
