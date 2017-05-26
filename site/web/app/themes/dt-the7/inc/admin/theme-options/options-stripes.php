<?php
/**
 * Stripes.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Page definition.
 */
$options[] = array(
		"page_title"	=> _x( "Stripes", 'theme-options', 'the7mk2' ),
		"menu_title"	=> _x( "Stripes", 'theme-options', 'the7mk2' ),
		"menu_slug"		=> "of-stripes-menu",
		"type"			=> "page"
);

foreach ( presscore_themeoptions_get_stripes_list() as $suffix=>$stripe ) {

	/**
	 * Heading definition.
	 */
	$options[] = array( "name" => $stripe['title'], "type" => "heading" );

	/**
	 * Stripe.
	 */
	$options[] = array(	"name" => $stripe['title'], "type" => "block_begin" );

		//*************************************************************************************************
		// Background
		//*************************************************************************************************

		// title
		$options[] = array( "type" => 'title', "name" => _x('Background', 'theme-options', 'the7mk2') );

		// colorpicker
		$options[] = array(
			"name"	=> _x( 'Color', 'theme-options', 'the7mk2' ),
			"id"	=> "stripes-stripe_{$suffix}_color",
			"std"	=> $stripe['bg_color'],
			"type"	=> "color"
		);

		$bg_array_name = "backgrounds_stripes_stripe_{$suffix}_bg_image";

		if ( isset( $$bg_array_name ) ) {

			// background_img
			$options[] = array(
				'name' 			=> _x( 'Add background image', 'theme-options', 'the7mk2' ),
				'id' 			=> "stripes-stripe_{$suffix}_bg_image",
				'std' 			=> $stripe['bg_img'],
				'type' 			=> 'background_img',
				'preset_images' => $$bg_array_name,
				'fields'		=> array(),
			);

		} else {

			// info
			$options[] = array(
				"desc"      => 'Array ' . $bg_array_name . ' does not exist. See /inc/admin/options.php.',
				"type"  	=> 'info',
			);

		}

		//*************************************************************************************************
		// Content boxes background
		//*************************************************************************************************

		// divider
		$options[] = array( "type" => 'divider' );

		// title
		$options[] = array( "type" => 'title', "name" => _x('Content boxes background', 'theme-options', 'the7mk2') );

		// radio
		$options[] = array(
			"name"		=> _x( "Background", "theme-options", 'the7mk2' ),
			"id"		=> "stripes-stripe_{$suffix}_content_boxes_bg_mode",
			"std"		=> "transparent",
			"type"		=> "radio",
			"show_hide"	=> array( 'solid'	=> true ),
			"options"	=> array(
				"transparent"	=> _x( 'Transparent background', 'theme-options', 'the7mk2' ),
				"solid"			=> _x( 'Solid background', 'theme-options', 'the7mk2' )
			)
		);

		// hidden area
		$options[] = array( "type" => "js_hide_begin" );

			// colorpicker
			$options[] = array(
				"name"	=> "&nbsp;",
				"id"	=> "stripes-stripe_{$suffix}_content_boxes_solid_bg_color",
				"std"	=> "#FFFFFF",
				"type"	=> "color"
			);

		$options[] = array( "type" => "js_hide_end" );

		//*************************************************************************************************
		// Text
		//*************************************************************************************************

		// divider
		$options[] = array( "type" => 'divider' );

		// title
		$options[] = array( "type" => 'title', "name" => _x( 'Text', 'theme-options', 'the7mk2' ) );

		// colorpicker
		$options[] = array(
			"desc" => '',
			"name"	=> _x( 'Headers color', 'theme-options', 'the7mk2' ),
			"id"	=> "stripes-stripe_{$suffix}_headers_color",
			"std"	=> $stripe['text_header_color'],
			"type"	=> "color"
		);

		// colorpicker
		$options[] = array(
			"desc"	=> '',
			"name"	=> _x( 'Text color', 'theme-options', 'the7mk2' ),
			"id"	=> "stripes-stripe_{$suffix}_text_color",
			"std"	=> $stripe['text_color'],
			"type"	=> "color"
		);

	$options[] = array(	"type"  => "block_end");

}
