<?php
/**
 * Gets bookings
 */
class WC_Bookings_Controller {

	/**
	 * Return all bookings for a product in a given range
	 * @param  timestamp $start_date
	 * @param  timestamp $end_date
	 * @param  int product_or_resource_id
	 * @return array of bookings
	 */
	public static function get_bookings_in_date_range( $start_date, $end_date, $product_or_resource_id = '', $check_in_cart = true ) {
		$transient_name = 'book_dr_' . md5( http_build_query( array( $start_date, $end_date, $product_or_resource_id, $check_in_cart, WC_Cache_Helper::get_transient_version( 'bookings' ) ) ) );

		if ( false === ( $booking_ids = get_transient( $transient_name ) ) ) {
			$booking_ids = self::get_bookings_in_date_range_query( $start_date, $end_date, $product_or_resource_id, $check_in_cart );
			set_transient( $transient_name, $booking_ids, DAY_IN_SECONDS * 30 );
		}

		// Get objects
		$bookings = array();

		foreach ( $booking_ids as $booking_id ) {
			$bookings[] = get_wc_booking( $booking_id );
		}

		return $bookings;
	}

	/**
	 * Return an array of unbookable buffer days
	 * @param  int $product_id
	 * @return array Days that are buffer days and therefor should be unbookable
	 */
	public static function find_buffer_day_blocks( $product_id ) {
		$booked = WC_Bookings_Controller::find_booked_day_blocks( $product_id );
		$fully_booked_days = $booked['fully_booked_days'];
		$buffer_days = array();

		$buffer_period = get_post_meta( $product_id, '_wc_booking_buffer_period', true );

		foreach ( $fully_booked_days as $date => $data ) {
			$next_day = strtotime( '+1 day', strtotime( $date ) );

			if ( array_key_exists( date( 'Y-n-j', $next_day ), $fully_booked_days ) ) {
				continue;
			}

			// x days after
			for ( $i = 1; $i < $buffer_period + 1; $i++ ) {
				$buffer_day = date( 'Y-n-j', strtotime( "+{$i} day", strtotime( $date ) ) );
				$buffer_days[ $buffer_day ] = $buffer_day;
			}
		}

		if ( 'yes' === get_post_meta( $product_id, '_wc_booking_apply_adjacent_buffer', true ) ) {
			foreach ( $fully_booked_days as $date => $data ) {
				$previous_day = strtotime( '-1 day', strtotime( $date ) );

				if ( array_key_exists( date( 'Y-n-j', $previous_day ), $fully_booked_days ) ) {
					continue;
				}

				// x days before
				for ( $i = 1; $i < $buffer_period + 1; $i++ ) {
					$buffer_day = date( 'Y-n-j', strtotime( "-{$i} day", strtotime( $date ) ) );
					$buffer_days[ $buffer_day ] = $buffer_day;
				}
			}
		}
		return $buffer_days;
	}

	/**
	 * Finds days which are partially booked & fully booked already
	 * @param  int $product_id
	 * @return array( 'partially_booked_days', 'fully_booked_days' )
	 */
	public static function find_booked_day_blocks( $product_id ) {
		$product = wc_get_product( $product_id );

		$fully_booked_days     = array();
		$partially_booked_days = array();
		$find_bookings_for     = array( $product->id );
		$resource_count        = 0;

		if ( $product->has_resources() ) {
			foreach (  $product->get_resources() as $resource ) {
				$find_bookings_for[] = $resource->ID;
				$resource_count ++;
			}
		}


		//Today Fully / Partially booked?
		$blocks_in_range  = $product->get_blocks_in_range( strtotime( 'midnight' ), strtotime( 'tomorrow midnight -1 min' ) );
		$available_blocks = $product->get_available_blocks( $blocks_in_range );
		$existing_bookings = WC_Bookings_Controller::get_bookings_for_objects( $find_bookings_for );
		$today    = strtotime( 'today midnight' );
		$bookings_today = self::get_bookings_on_date( $existing_bookings, $today );
		if ( ! empty( $bookings_today ) && count( $bookings_today ) >= $product->get_available_quantity() ) {
			$current_time = current_time( 'timestamp' );
			if ( sizeof( $available_blocks ) < sizeof( $blocks_in_range ) ) {
				$partially_booked_days[ date( 'Y-n-j', $current_time ) ][0] = true;
			}
			if ( ! $available_blocks ) {
				$fully_booked_days[ date( 'Y-n-j', $current_time ) ][0] = true;
			}
		}

		foreach ( $existing_bookings as $existing_booking ) {
			$start_date  = $existing_booking->start;
			$end_date    = $existing_booking->is_all_day() ? strtotime( 'tomorrow midnight', $existing_booking->end ) : $existing_booking->end;
			$resource_id = $existing_booking->get_resource_id();
			$check_date  = $start_date; // Take it from the top

			// Loop over all booked days in this booking
			while ( $check_date < $end_date ) {
				$js_date = date( 'Y-n-j', $check_date );

				if ( $check_date < current_time( 'timestamp' ) ) {
					$check_date = strtotime( '+1 day', $check_date );
					continue;
				}

				if ( $product->has_resources() ) {

					// Skip if we've already found this resource is unavailable
					if ( ! empty( $fully_booked_days[ $js_date ][ $resource_id ] ) ) {
						$check_date = strtotime( '+1 day', $check_date );
						continue;
					}

					$blocks_in_range  = $product->get_blocks_in_range( strtotime( 'midnight', $check_date ), strtotime( 'tomorrow midnight -1 min', $check_date ), array(), $resource_id );
					$available_blocks = $product->get_available_blocks( $blocks_in_range, array(), $resource_id );

					if ( sizeof( $available_blocks ) < sizeof( $blocks_in_range ) ) {
						$partially_booked_days[ $js_date ][ $resource_id ] = true;

						if ( 1 === $resource_count || sizeof( $partially_booked_days[ $js_date ] ) === $resource_count ) {
							$partially_booked_days[ $js_date ][0] = true;
						}
					}

					if ( ! $available_blocks ) {
						$fully_booked_days[ $js_date ][ $resource_id ] = true;

						if ( 1 === $resource_count || sizeof( $fully_booked_days[ $js_date ] ) === $resource_count ) {
							$fully_booked_days[ $js_date ][0] = true;
						}
					}

					if ( in_array( $product->get_duration_unit(), array( 'day' ) ) ) {
						foreach ( $blocks_in_range as $date ) {
							$partially_booked_days[ date( 'Y-n-j', $date ) ][0] = true;
						}
					}
				} else {

					// Skip if we've already found this product is unavailable
					if ( ! empty( $fully_booked_days[ $js_date ] ) ) {
						$check_date = strtotime( '+1 day', $check_date );
						continue;
					}

					$blocks_in_range  = $product->get_blocks_in_range( strtotime( 'midnight', $check_date ), strtotime( 'tomorrow midnight -1 min', $check_date ) );
					$available_blocks = $product->get_available_blocks( $blocks_in_range );

					if ( sizeof( $available_blocks ) < sizeof( $blocks_in_range ) ) {
						$partially_booked_days[ $js_date ][0] = true;
					}

					$bookings_on_check_date = self::get_bookings_on_date( $existing_bookings, $check_date );
					if ( ! $available_blocks
					     && count( $bookings_on_check_date ) >= $product->get_available_quantity(  ) ) {
						$fully_booked_days[ $js_date ][0] = true;
					}

					if ( in_array( $product->get_duration_unit(), array( 'day' ) ) ) {
						foreach ( $blocks_in_range as $date ) {
							$partially_booked_days[ date( 'Y-n-j', $date ) ][0] = true;
						}
					}

					if ( in_array( $product->get_duration_unit(), array( 'hour', 'minute' ) ) ) {
						$partially_booked_days[ $js_date ][0] = true;
					}
				}
				$check_date = strtotime( '+1 day', $check_date );
			}
		}

		$booked_day_blocks = array(
			'partially_booked_days' => $partially_booked_days,
			'fully_booked_days'     => $fully_booked_days,
		);

		/**
		 * Filter the booked day blocks calculated per project.
		 * @since 1.9.13
		 *
		 * @param array $booked_day_blocks {
		 *  @type array $partially_booked_days
		 *  @type array $fully_booked_days
		 * }
		 * @param WC_Product $product
		 */
		return apply_filters( 'woocommerce_bookings_booked_day_blocks', $booked_day_blocks, $product );
	}

	/**
	 * Loop through given bookings to find those that are on or over lap the given date.
	 *
	 * @since 1.9.14
	 * @param  array $bookings
	 * @param  string $date
	 *
	 * @return array of booking ids
	 */
	public static function get_bookings_on_date( $bookings, $date ) {
		$bookings_on_date = array();
		foreach ( $bookings as $booking ) {
			if ( $booking->start <= $date && $booking->start >= $date ) {
				$bookings_on_date[] = $booking;
			}
		}
		return $bookings_on_date;
	}
	/**
	 * Return all bookings for a product in a given range - the query part (no cache)
	 * @param  int $product_id
	 * @param  timestamp $start_date
	 * @param  timestamp $end_date
	 * @param  int product_or_resource_id
	 * @return array of booking ids
	 */
	private static function get_bookings_in_date_range_query( $start_date, $end_date, $product_or_resource_id = '', $check_in_cart = true ) {
		global $wpdb;

		if ( $product_or_resource_id ) {
			if ( get_post_type( $product_or_resource_id ) === 'bookable_resource' ) {
				$product_meta_key_q    = ' AND idmeta.meta_key = "_booking_resource_id" AND idmeta.meta_value = "' . absint( $product_or_resource_id ) . '" ';
				$product_meta_key_join = " LEFT JOIN {$wpdb->postmeta} as idmeta ON {$wpdb->posts}.ID = idmeta.post_id ";
			} else {
				$product_meta_key_q    = ' AND idmeta.meta_key = "_booking_product_id" AND idmeta.meta_value = "' . absint( $product_or_resource_id ) . '" ';
				$product_meta_key_join = " LEFT JOIN {$wpdb->postmeta} as idmeta ON {$wpdb->posts}.ID = idmeta.post_id ";
			}
		} else {
			$product_meta_key_join = '';
			$product_meta_key_q    = '';
		}

		$booking_statuses = get_wc_booking_statuses();

		if ( ! $check_in_cart ) {
			$booking_statuses = array_diff( $booking_statuses, array( 'in-cart' ) );
		}

		$booking_ids = $wpdb->get_col( $wpdb->prepare( "
			SELECT ID FROM {$wpdb->posts}
			LEFT JOIN {$wpdb->postmeta} as startmeta ON {$wpdb->posts}.ID = startmeta.post_id
			LEFT JOIN {$wpdb->postmeta} as endmeta ON {$wpdb->posts}.ID = endmeta.post_id
			LEFT JOIN {$wpdb->postmeta} as daymeta ON {$wpdb->posts}.ID = daymeta.post_id
			" . $product_meta_key_join . "

			WHERE post_type = 'wc_booking'
			AND post_status IN ( '" . implode( "','", array_map( 'esc_sql', $booking_statuses ) ) . "' )
			AND startmeta.meta_key = '_booking_start'
			AND endmeta.meta_key   = '_booking_end'
			AND daymeta.meta_key   = '_booking_all_day'
			" . $product_meta_key_q . "
			AND (
				(
					startmeta.meta_value < %s
					AND endmeta.meta_value > %s
					AND daymeta.meta_value = '0'
				)
				OR
				(
					startmeta.meta_value <= %s
					AND endmeta.meta_value >= %s
					AND daymeta.meta_value = '1'
				)
			)
		", date( 'YmdHis', $end_date ), date( 'YmdHis', $start_date ), date( 'Ymd000000', $end_date ), date( 'Ymd000000', $start_date ) ) );

		return apply_filters( 'woocommerce_bookings_in_date_range_query', $booking_ids );
	}

	/**
	 * Gets bookings for product ids and resource ids
	 * @param  array  $ids
	 * @param  array  $status
	 * @return array of WC_Booking objects
	 */
	public static function get_bookings_for_objects( $ids = array(), $status = array( 'confirmed', 'paid', 'in-cart' ) ) {
		$transient_name = 'book_fo_' . md5( http_build_query( array( $ids, $status, WC_Cache_Helper::get_transient_version( 'bookings' ) ) ) );
		$bookings = array();

		if ( false === ( $booking_ids = get_transient( $transient_name ) ) ) {
			$booking_ids = self::get_bookings_for_objects_query( $ids, $status );
			set_transient( $transient_name, $booking_ids, DAY_IN_SECONDS * 30 );
		}

		if ( empty( $booking_ids ) ) {
			return $bookings;
		}

		foreach ( $booking_ids as $booking_id ) {
			$bookings[] = get_wc_booking( $booking_id );
		}

		return $bookings;
	}

	/**
	 * Gets bookings for product ids and resource ids
	 * @param  array  $ids
	 * @param  array  $status
	 * @return array of WC_Booking objects
	 */
	public static function get_bookings_for_objects_query( $ids, $status ) {
		global $wpdb;

		if ( class_exists( 'WC_Deposits' ) ) {
			$status[] = 'wc-partial-payment';
		}
		$booking_ids = $wpdb->get_col( "
			SELECT ID FROM {$wpdb->posts}
			LEFT JOIN {$wpdb->postmeta} as _booking_product_id ON {$wpdb->posts}.ID = _booking_product_id.post_id
			LEFT JOIN {$wpdb->postmeta} as _booking_resource_id ON {$wpdb->posts}.ID = _booking_resource_id.post_id
			WHERE post_type = 'wc_booking'
			AND post_status IN ('" . implode( "','", $status ) . "')
			AND _booking_product_id.meta_key = '_booking_product_id'
			AND _booking_resource_id.meta_key = '_booking_resource_id'
			AND (
				_booking_product_id.meta_value IN ('" . implode( "','", array_map( 'absint', $ids ) ) . "')
				OR _booking_resource_id.meta_value IN ('" . implode( "','", array_map( 'absint', $ids ) ) . "')
			)
		" );

		return $booking_ids;
	}

	/**
	 * Gets bookings for a resource
	 *
	 * @param  int $resource_id ID
	 * @param  array  $status
	 * @return array of WC_Booking objects
	 */
	public static function get_bookings_for_resource( $resource_id, $status = array( 'confirmed', 'paid' ) ) {
		$booking_ids = get_posts( array(
			'numberposts'   => -1,
			'offset'        => 0,
			'orderby'       => 'post_date',
			'order'         => 'DESC',
			'post_type'     => 'wc_booking',
			'post_status'   => $status,
			'fields'        => 'ids',
			'no_found_rows' => true,
			'meta_query'    => array(
				array(
					'key'     => '_booking_resource_id',
					'value'   => absint( $resource_id ),
				),
			),
		) );

		$bookings    = array();

		foreach ( $booking_ids as $booking_id ) {
			$bookings[] = get_wc_booking( $booking_id );
		}

		return $bookings;
	}

	/**
	 * Gets bookings for a product by ID
	 *
	 * @param int $product_id The id of the product that we want bookings for
	 * @return array of WC_Booking objects
	 */
	public static function get_bookings_for_product( $product_id, $status = array( 'confirmed', 'paid' ) ) {
		$booking_ids = get_posts( array(
			'numberposts'   => -1,
			'offset'        => 0,
			'orderby'       => 'post_date',
			'order'         => 'DESC',
			'post_type'     => 'wc_booking',
			'post_status'   => $status,
			'fields'        => 'ids',
			'no_found_rows' => true,
			'meta_query' => array(
				array(
					'key'     => '_booking_product_id',
					'value'   => absint( $product_id ),
				),
			),
		) );

		$bookings    = array();

		foreach ( $booking_ids as $booking_id ) {
			$bookings[] = get_wc_booking( $booking_id );
		}

		return $bookings;
	}

	/**
	 * Get latest bookings
	 *
	 * @param int $numberitems Number of objects returned (default to unlimited)
	 * @param int $offset The number of objects to skip (as a query offset)
	 * @return array of WC_Booking objects
	 */
	public static function get_latest_bookings( $numberitems = -1, $offset = 0 ) {
		$booking_ids = get_posts( array(
			'numberposts' => $numberitems,
			'offset'      => $offset,
			'orderby'     => 'post_date',
			'order'       => 'DESC',
			'post_type'   => 'wc_booking',
			'post_status' => get_wc_booking_statuses(),
			'fields'      => 'ids',
		) );

		$bookings = array();

		foreach ( $booking_ids as $booking_id ) {
			$bookings[] = get_wc_booking( $booking_id );
		}

		return $bookings;
	}

	/**
	 * Gets bookings for a user by ID
	 *
	 * @param  int   $user_id    The id of the user that we want bookings for
	 * @param  array $query_args The query arguments used to get booking IDs
	 * @return array             Array of WC_Booking objects
	 */
	public static function get_bookings_for_user( $user_id, $query_args = null ) {
		$query_args = wp_parse_args(
			$query_args,
			array(
				'numberposts'   => -1,
				'offset'        => 0,
				'orderby'       => 'post_date',
				'order'         => 'DESC',
				'fields'        => 'ids',
				'post_type'     => 'wc_booking',
				'post_status'   => get_wc_booking_statuses( 'user' ),
				'no_found_rows' => true,
				'meta_query'    => array(
					array(
						'key'     => '_booking_customer_id',
						'value'   => absint( $user_id ),
						'compare' => 'IN',
					),
				),
			)
		);

		$bookings = array();
		foreach ( get_posts( $query_args ) as $booking_id ) {
			$bookings[] = get_wc_booking( $booking_id );
		}

		return $bookings;
	}
}
