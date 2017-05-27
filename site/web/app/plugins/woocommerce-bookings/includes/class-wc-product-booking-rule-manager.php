<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that parses and returns rules for bookable products
 */
class WC_Product_Booking_Rule_Manager {

	/**
	 * Get a range and put value inside each day
	 *
	 * @param  string $from
	 * @param  string $to
	 * @param  mixed $value
	 * @return array
	 */
	private static function get_custom_range( $from, $to, $value ) {
		$availability = array();
		$from_date    = strtotime( $from );
		$to_date      = strtotime( $to );

		if ( empty( $to ) || empty( $from ) || $to_date < $from_date ) {
			return;
		}
		// We have at least 1 day, even if from_date == to_date
		$numdays = 1 + ( $to_date - $from_date ) / 60 / 60 / 24;

		for ( $i = 0; $i < $numdays; $i ++ ) {
			$year  = date( 'Y', strtotime( "+{$i} days", $from_date ) );
			$month = date( 'n', strtotime( "+{$i} days", $from_date ) );
			$day   = date( 'j', strtotime( "+{$i} days", $from_date ) );

			$availability[ $year ][ $month ][ $day ] = $value;
		}

		return $availability;
	}

	/**
	 * Get a range and put value inside each day
	 *
	 * @param  string $from
	 * @param  string $to
	 * @param  mixed $value
	 * @return array
	 */
	private static function get_months_range( $from, $to, $value ) {
		$months = array();
		$diff   = $to - $from;
		$diff   = ( $diff < 0 ) ? 12 + $diff : $diff;
		$month  = $from;

		for ( $i = 0; $i <= $diff; $i ++ ) {
			$months[ $month ] = $value;

			$month ++;

			if ( $month > 52 ) {
				$month = 1;
			}
		}

		return $months;
	}

	/**
	 * Get a range and put value inside each day
	 *
	 * @param  string $from
	 * @param  string $to
	 * @param  mixed $value
	 * @return array
	 */
	private static function get_weeks_range( $from, $to, $value ) {
		$weeks = array();
		$diff  = $to - $from;
		$diff  = ( $diff < 0 ) ? 52 + $diff : $diff;
		$week  = $from;

		for ( $i = 0; $i <= $diff; $i ++ ) {
			$weeks[ $week ] = $value;

			$week ++;

			if ( $week > 52 ) {
				$week = 1;
			}
		}

		return $weeks;
	}

	/**
	 * Get a range and put value inside each day
	 *
	 * @param  string $from
	 * @param  string $to
	 * @param  mixed $value
	 * @return array
	 */
	private static function get_days_range( $from, $to, $value ) {
		$day_of_week  = $from;
		$diff         = $to - $from;
		$diff         = ( $diff < 0 ) ? 7 + $diff : $diff;
		$days         = array();

		for ( $i = 0; $i <= $diff; $i ++ ) {
			$days[ $day_of_week ] = $value;

			$day_of_week ++;

			if ( $day_of_week > 7 ) {
				$day_of_week = 1;
			}
		}

		return $days;
	}

	/**
	 * Get a range and put value inside each day
	 *
	 * @param  string $from
	 * @param  string $to
	 * @param  mixed $value
	 * @return array
	 */
	private static function get_time_range( $from, $to, $value, $day = 0 ) {
		return array(
			'from' => $from,
			'to'   => $to,
			'rule' => $value,
			'day'  => $day,
		);
	}

	/**
	 * Get a time range for a set of custom dates
	 * @param  string $from_date
	 * @param  string $to_date
	 * @param  string $from_time
	 * @param  string $to_time
	 * @param  mixed $value
	 * @return array
	 */
	private static function get_time_range_for_custom_date( $from_date, $to_date, $from_time, $to_time, $value ) {
		$time_range = array(
			'from' => $from_time,
			'to'   => $to_time,
			'rule' => $value,
		);
		return self::get_custom_range( $from_date, $to_date, $time_range );
	}

	/**
	 * Get duration range
	 * @param  [type] $from
	 * @param  [type] $to
	 * @param  [type] $value
	 * @return [type]
	 */
	private static function get_duration_range( $from, $to, $value ) {
		return array(
			'from' => $from,
			'to'   => $to,
			'rule' => $value,
			);
	}

	/**
	 * Get Persons range
	 * @param  [type] $from
	 * @param  [type] $to
	 * @param  [type] $value
	 * @return [type]
	 */
	private static function get_persons_range( $from, $to, $value ) {
		return array(
			'from' => $from,
			'to'   => $to,
			'rule' => $value,
			);
	}

	/**
	 * Get blocks range
	 * @param  [type] $from
	 * @param  [type] $to
	 * @param  [type] $value
	 * @return [type]
	 */
	private static function get_blocks_range( $from, $to, $value ) {
		return array(
			'from' => $from,
			'to'   => $to,
			'rule' => $value,
			);
	}

	/**
	 * Process and return formatted cost rules
	 * @param  $rules array
	 * @return array
	 */
	public static function process_cost_rules( $rules ) {
		$costs = array();
		$index = 1;
		// Go through rules
		foreach ( $rules as $key => $fields ) {
			if ( empty( $fields['cost'] ) && empty( $fields['base_cost'] ) && empty( $fields['override_block'] ) ) {
				continue;
			}

			$cost           = apply_filters( 'woocommerce_bookings_process_cost_rules_cost', $fields['cost'], $fields, $key );
			$modifier       = $fields['modifier'];
			$base_cost      = apply_filters( 'woocommerce_bookings_process_cost_rules_base_cost', $fields['base_cost'], $fields, $key );
			$base_modifier  = $fields['base_modifier'];
			$override_block = apply_filters( 'woocommerce_bookings_process_cost_rules_override_block', ( isset( $fields['override_block'] ) ? $fields['override_block'] : '' ), $fields, $key );

			$cost_array = array(
				'base'     => array( $base_modifier, $base_cost ),
				'block'    => array( $modifier, $cost ),
				'override' => $override_block,
			);

			$type_function = self::get_type_function( $fields['type'] );
			if ( 'get_time_range_for_custom_date' === $type_function ) {
				$type_costs = self::$type_function( $fields['from_date'], $fields['to_date'], $fields['from'], $fields['to'], $cost_array );
			} else {
				$type_costs = self::$type_function( $fields['from'], $fields['to'], $cost_array );
			}

			// Ensure day gets specified for time: rules
			if ( strrpos( $fields['type'], 'time:' ) === 0 && 'time:range' !== $fields['type'] ) {
				list( , $day ) = explode( ':', $fields['type'] );
				$type_costs['day'] = absint( $day );
			}

			if ( $type_costs ) {
				$costs[ $index ] = array( $fields['type'], $type_costs );
				$index ++;
			}
		}

		return $costs;
	}

	/**
	 * Returns a function name (for this class) that returns our time or date range
	 * @param  string $type rule type
	 * @return string       function name
	 */
	public static function get_type_function( $type ) {
		if ( 'time:range' === $type ) {
			return 'get_time_range_for_custom_date';
		}
		return strrpos( $type, 'time:' ) === 0 ? 'get_time_range' : 'get_' . $type . '_range';
	}

	/** 
	 * Process and return formatted availability rules 
	 * @param  $rules array 
	 * @param string $level. Resource, Product or Globally 
	 * @return array 
	 */
	public static function process_availability_rules( $rules, $level ) {
		$processed_rules = array();

		if ( empty( $rules ) ) {
			return $processed_rules;
		}

		// See what types of rules we have before getting the rules themselves
		$rule_types = array();

		foreach ( $rules as $fields ) {
			if ( empty( $fields['bookable'] ) ) {
				continue;
			}
			$rule_types[] = $fields['type'];
		}
		$rule_types = array_filter( $rule_types );

		// Go through rules
		foreach ( $rules as $order_on_product => $fields ) {
			if ( empty( $fields['bookable'] ) ) {
				continue;
			}

			$type_function = self::get_type_function( $fields['type'] );
			$bookable = 'yes' === $fields['bookable'] ? true : false;
			if ( 'get_time_range_for_custom_date' === $type_function ) {
				$type_availability = self::$type_function( $fields['from_date'], $fields['to_date'], $fields['from'], $fields['to'],$bookable );
			} else {
				$type_availability = self::$type_function( $fields['from'], $fields['to'], $bookable );
			}

			$priority = intval( ( isset( $fields['priority'] ) ? $fields['priority'] : 10 ) );

			// Ensure day gets specified for time: rules
			if ( strrpos( $fields['type'], 'time:' ) === 0 && 'time:range' !== $fields['type'] ) {
				list( , $day ) = explode( ':', $fields['type'] );
				$type_availability['day'] = absint( $day );
			}

			if ( $type_availability ) {
				$processed_rules[] = array(
					'type'     => $fields['type'],
					'range'    => $type_availability,
					'priority' => $priority,
					'level'    => $level,
					'order'    => $order_on_product,
				);
			}
		}

		return $processed_rules;
	}

	/**
	 * Get the minutes that should be available based on the rules and the date to check.
	 *
	 * The minutes are returned in a range from the start incrementing minutes right up to the last available minute.
	 *
	 * @since 1.9.14 moved from WC_Product_Booking.
	 *
	 * @param array $rules
	 * @param int $check_date
	 *
	 * @return array $bookable_minutes
	 */
	public static function get_minutes_from_rules( $rules, $check_date ) {
		$bookable_minutes = array();
		foreach ( $rules as $rule ) {
			$data_for_rule = array(
				'is_bookable' => false,
				'minutes'     => array(),
			);
			if ( strpos( $rule['type'], 'time' ) > -1 ) {
				$data_for_rule = self::get_rule_minutes_for_time( $rule, $check_date );
			} elseif ( 'days' === $rule['type'] ) {
				$data_for_rule = self::get_rule_minutes_for_days( $rule, $check_date );
			} elseif ( 'weeks' === $rule['type'] ) {
				$data_for_rule = self::get_rule_minutes_for_weeks( $rule, $check_date );
			} elseif ( 'months' === $rule['type'] ) {
				$data_for_rule = self::get_rule_minutes_for_months( $rule, $check_date );
			} elseif ( 'custom' === $rule['type'] ) {
				$data_for_rule = self::get_rule_minutes_for_custom( $rule, $check_date );
			}

			if ( $data_for_rule['is_bookable'] ) {
				// If this time range is bookable, add to bookable minutes
				$bookable_minutes = array_merge( $bookable_minutes, $data_for_rule['minutes'] );
			} else {
				// If this time range is not bookable, remove from bookable minutes
				$bookable_minutes = array_diff( $bookable_minutes, $data_for_rule['minutes'] );
			}
		}

		array_unique( $bookable_minutes );
		sort( $bookable_minutes );
		return $bookable_minutes;
	}

	/**
	 * Get minutes from rules for a time rule type.
	 *
	 * @since 1.9.14
	 * @param $rule
	 * @param $check_date
	 *
	 * @return array
	 */
	public static function get_rule_minutes_for_time( $rule, $check_date ) {

		$minutes = array( 'is_bookable' => false, 'minutes' => array() );
		$type    = $rule['type'];
		$range   = $rule['range'];

		$year        = date( 'Y', $check_date );
		$month       = date( 'n', $check_date );
		$day         = date( 'd', $check_date );
		$day_of_week = date( 'N', $check_date );

		$day_modifier = 0;

		if ( 'time:range' === $type ) { // type: date range with time

			if ( ! isset( $range[ $year ][ $month ][ $day ] ) ) {
				return  $minutes;
			} else {
				$range = $range[ $year ][ $month ][ $day ];
			}

			$from                   = $range['from'];
			$to                     = $range['to'];
			$minutes['is_bookable'] = $range['rule'];

		} elseif ( strpos( $rule['type'], 'time:' ) > -1 ) { // type: single week day with time

			if (  $day_of_week != $range['day'] ) {
				return  $minutes;
			}

			$from                   = $range['from'];
			$to                     = $range['to'];
			$minutes['is_bookable'] = $range['rule'];

		} else {  // type: time all week per day

			$from                   = $range['from'];
			$to                     = $range['to'];
			$minutes['is_bookable'] = $range['rule'];

		}

		$from_hour    = absint( date( 'H', strtotime( $from ) ) );
		$from_min     = absint( date( 'i', strtotime( $from ) ) );
		$to_hour      = absint( date( 'H', strtotime( $to ) ) );
		$to_min       = absint( date( 'i', strtotime( $to ) ) );

		// If "to" is set to midnight, it is safe to assume they mean the end of the day
		// php wraps 24 hours to "12AM the next day"
		if ( 0 === $to_hour ) {
			$to_hour = 24;
		}

		$minute_range = array( ( ( $from_hour * 60 ) + $from_min ) + $day_modifier, ( ( $to_hour * 60 ) + $to_min ) + $day_modifier );
		$merge_ranges = array();

		// if first time in range is larger than second, we
		// assume they want to go over midnight
		if ( $minute_range[0] > $minute_range[1] ) {
			$merge_ranges[] = array( $minute_range[0], 1440 );
			// fix for https://github.com/woothemes/woocommerce-bookings/issues/710
			$merge_ranges[] = array( $minute_range[0], ( 1440 + $minute_range[1] ) );
		} else {
			$merge_ranges[] = array( $minute_range[0], $minute_range[1] );
		}

		foreach ( $merge_ranges as $range ) {
				// Add ranges to minutes this rule affects.
				$minutes['minutes'] = array_merge( $minutes['minutes'], range( $range[0], $range[1] ) );
		}

		return $minutes;
	}

	/**
	 * Get minutes from rules for days rule type.
	 *
	 * @since 1.9.14
	 * @param $rule
	 * @param $check_date
	 *
	 * @return array
	 */
	public static function get_rule_minutes_for_days( $rule, $check_date ) {
		$_rules      = $rule['range'];
		$minutes     = array();
		$is_bookable = false;
		$day_of_week = intval( date( 'N', $check_date ) );

		if ( isset( $_rules[ $day_of_week ] ) ) {
			$minutes     = range( 0, 1440 );
			$is_bookable = $_rules[ $day_of_week ];
		}

		return array( 'is_bookable' => $is_bookable, 'minutes' => $minutes );
	}

	/**
	 * Get minutes from rules for a weeks rule type.
	 *
	 * @since 1.9.14
	 * @param $rule
	 * @param $check_date
	 *
	 * @return array
	 */
	public static function get_rule_minutes_for_weeks( $rule, $check_date ) {

		$range       = $rule['range'];
		$week_number = date( 'W', $check_date );
		$minutes     = array();
		$is_bookable = false;

		if ( isset( $range[ $week_number ] ) ) {
			$minutes     = range( 0, 1440 );
			$is_bookable = $range[ $week_number ];
		}

		return array( 'is_bookable' => $is_bookable, 'minutes' => $minutes );
	}

	/**
	 * Get minutes from rules for a months rule type.
	 *
	 * @since 1.9.14
	 * @param $rule
	 * @param $check_date
	 *
	 * @return array
	 */
	public static function get_rule_minutes_for_months( $rule, $check_date ) {

		$range       = $rule['range'];
		$month       = date( 'n', $check_date );
		$minutes     = array();
		$is_bookable = false;
		if ( isset( $range[ $month ] ) ) {
			$minutes     = range( 0, 1440 );
			$is_bookable = $range[ $month ];
		}

		return array( 'is_bookable' => $is_bookable, 'minutes' => $minutes );
	}

	/**
	 * Get minutes from rules for custom rule type.
	 * @since 1.9.14
	 * @param $rule
	 * @param $check_date
	 *
	 * @return array
	 */
	public static function get_rule_minutes_for_custom( $rule, $check_date ) {

		$range = $rule['range'];
		$year  = date( 'Y', $check_date );
		$month = date( 'n', $check_date );
		$day   = date( 'd', $check_date );

		$minutes     = array();
		$is_bookable = false;
		if ( isset( $range[ $year ][ $month ][ $day ] ) ) {
			$minutes     = range( 0, 1440 );
			$is_bookable = $range[ $year ][ $month ][ $day ];
		}

		return array( 'is_bookable' => $is_bookable, 'minutes' => $minutes );
	}
}
