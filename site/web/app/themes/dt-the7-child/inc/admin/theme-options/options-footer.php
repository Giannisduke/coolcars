<?php
/**
 * Footer.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

// page
$options[] = array(
	"page_title"	=> _x( "Footer &amp; Bottom bar", 'theme-options', 'the7mk2' ),
	"menu_title"	=> _x( "Footer &amp; Bottom bar", 'theme-options', 'the7mk2' ),
	"menu_slug"		=> "of-footer-menu",
	"type"			=> "page"
);

// header
$options[] = array( "name" => _x( 'Footer', 'theme-options', 'the7mk2' ), "type" => "heading" );

//////////////////
// Footer style //
//////////////////

$options[] = array( "name" => _x( "Footer style", "theme-options", 'the7mk2' ), "type" => "block_begin" );

	$options[] = array(
		"name"		=> _x( "Footer background &amp; lines", "theme-options", 'the7mk2' ),
		"id"		=> "footer-style",
		"std"		=> "content_width_line",
		"type"		=> "radio",
		"style"		=> "vertical",
		"options"	=> array(
			'content_width_line'	=> _x( "Content-width line", "theme-options", 'the7mk2' ),
			'full_width_line'		=> _x( "Full-width line", "theme-options", 'the7mk2' ),
			'transparent_bg_line'	=> _x( "Transparent background", "theme-options", 'the7mk2' ),
			'solid_background'		=> _x( "Solid background", "theme-options", 'the7mk2' )
		),
		'show_hide'	=> array(
			'solid_background'	=> "footer-solid-background-block"
		)
	);

	$options[] = array( "type" => "js_hide_begin", "class" => "footer-solid-background-block" );

		$options[] = array( "type" => "divider" );

		$options[] = array(
			"name"	=> _x( 'Color', 'theme-options', 'the7mk2' ),
			"id"	=> "footer-bg_color",
			"std"	=> "#1B1B1B",
			"type"	=> "color"
		);

		$options[] = array(
			'type' 			=> 'background_img',
			'name'			=> _x( 'Add background image', 'theme-options', 'the7mk2' ),
			'id'			=> 'footer-bg_image',
			'preset_images' => $backgrounds_footer_bg_image,
			'std' 			=> array(
				'image'			=> '',
				'repeat'		=> 'repeat',
				'position_x'	=> 'center',
				'position_y'	=> 'center',
			),
		);

		$options[] = array( "type" => "divider" );

		$options[] = array(
			"name"		=> _x( "Slide-out mode", "theme-options", 'the7mk2' ),
			"desc"		=> _x( '"Slide-out mode" isn\'t compatible with transparent/semitransparent content area background.', "theme-options", 'the7mk2' ),
			"id"		=> "footer-slide-out-mode",
			"std"		=> "0",
			"type"		=> "radio",
			"options"	=> $en_dis_options
		);

	$options[] = array( "type" => "js_hide_end" );

$options[] = array( "type" => "block_end" );

///////////////////////
// Footer font color //
///////////////////////

$options[] = array(	"name" => _x( 'Footer font color', 'theme-options', 'the7mk2' ), "type" => "block_begin" );

	$options[] = array(
		"name"	=> _x( 'Headers color', 'theme-options', 'the7mk2' ),
		"id"	=> "footer-headers_color",
		"std"	=> "#ffffff",
		"type"	=> "color"
	);

	$options[] = array(
		"name"	=> _x( 'Content color', 'theme-options', 'the7mk2' ),
		"id"	=> "footer-primary_text_color",
		"std"	=> "#828282",
		"type"	=> "color"
	);

$options[] = array(	"type" => "block_end");

///////////////////
// Footer layout //
///////////////////

$options[] = array( "name" => _x( "Footer layout", "theme-options", 'the7mk2' ), "type" => "block_begin" );

	$options[] = array(
		"name"		=> _x( "Footer top &amp; bottom paddings (px)", "theme-options", 'the7mk2' ),
		"id"		=> "footer-paddings-top-bottom",
		"std"		=> 44,
		"type"		=> "text",
		"class"		=> "mini",
		"sanitize"	=> "dimensions"
	);

	$options[] = array( "type" => "divider" );

	$options[] = array(
		"desc"		=> _x( "E.g. 20 pixel padding will give you 40 pixel gap between columns.", "theme-options", 'the7mk2' ),
		"name"		=> _x( "Paddings between footer columns (px)", "theme-options", 'the7mk2' ),
		"id"		=> "footer-paddings-columns",
		"std"		=> 44,
		"type"		=> "text",
		"class"		=> "mini",
		"sanitize"	=> "dimensions"
	);

	$options[] = array( "type" => "divider" );

	$options[] = array(
		"name"		=> _x( "Layout", "theme-options", 'the7mk2' ),
		"desc"		=> _x( 'E.g. "1/4+1/4+1/2"', "theme-options", 'the7mk2' ),
		"id"		=> "footer-layout",
		"std"		=> "1/4+1/4+1/4+1/4",
		"type"		=> "text",
		// "class"		=> "mini"
	);

	$options[] = array( "type" => "divider" );

	$options[] = array(
		"name"		=> _x( "Collapse to one column after (px)", "theme-options", 'the7mk2' ),
		"desc"		=> _x( "Won't have any effect if responsiveness is disabled.", "theme-options", 'the7mk2' ),
		"id"		=> "footer-collapse_after",
		"std"		=> 760,
		"type"		=> "text",
		"class"		=> "mini",
		"sanitize"	=> "dimensions"
	);

$options[] = array( "type" => "block_end" );

////////////////
// Bootom bar //
////////////////

$options[] = array( "name" => _x( "Bottom bar", "theme-options", 'the7mk2' ), "type" => "heading" );

//////////////////////
// Bottom bar style //
//////////////////////

$options[] = array( "name" => _x( "Bottom bar style", "theme-options", 'the7mk2' ), "type" => "block_begin" );

	$options[] = array(
		"name"		=> _x( "Bottom bar background &amp; lines", "theme-options", 'the7mk2' ),
		"id"		=> "bottom_bar-style",
		"std"		=> "content_width_line",
		"type"		=> "radio",
		"options"	=> array(
			'content_width_line'	=> _x( "Content-width line", "theme-options", 'the7mk2' ),
			'full_width_line'		=> _x( "Full-width line", "theme-options", 'the7mk2' ),
			'solid_background'		=> _x( "Solid background", "theme-options", 'the7mk2' )
		),
		'show_hide'	=> array(
			'solid_background'	=> "bottom-bar-solid-background-block"
		)
	);

	$options[] = array( "type" => "js_hide_begin", "class" => "bottom-bar-solid-background-block" );

		$options[] = array( "type" => "divider" );

		$options[] = array(
			"name"	=> _x( 'Color', 'theme-options', 'the7mk2' ),
			"id"	=> "bottom_bar-bg_color",
			"std"	=> "#ffffff",
			"type"	=> "color"
		);

		$options[] = array(
			'type' 			=> 'background_img',
			'id'			=> 'bottom_bar-bg_image',
			'name' 			=> _x( 'Add background image', 'theme-options', 'the7mk2' ),
			'preset_images' => $backgrounds_bottom_bar_bg_image,
			'std' 			=> $background_defaults,
		);

	$options[] = array( "type" => "js_hide_end" );

$options[] = array( "type" => "block_end" );

///////////////////////////
// Bottom bar font color //
///////////////////////////

$options[] = array(	"name" => _x( 'Bottom bar font color', 'theme-options', 'the7mk2' ), "type" => "block_begin" );

	$options[] = array(
		"name"	=> _x( 'Font color', 'theme-options', 'the7mk2' ),
		"id"	=> "bottom_bar-color",
		"std"	=> "#757575",
		"type"	=> "color"
	);

$options[] = array(	"type" => "block_end");

///////////////
// Text area //
///////////////

$options[] = array(	"name" => _x( 'Text area', 'theme-options', 'the7mk2' ), "type" => "block_begin" );

	$options[] = array(
		"name"		=> _x( 'Text area', 'theme-options', 'the7mk2' ),
		"id"		=> "bottom_bar-text",
		"std"		=> false,
		"type"		=> 'textarea'
	);

$options[] = array(	"type" => "block_end");
