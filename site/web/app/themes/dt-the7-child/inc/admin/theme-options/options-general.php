<?php
/**
 * General.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Heading definition.
 */
$options[] = array( "name" => _x('Appearance', 'theme-options', 'the7mk2'), "type" => "heading" );

	/**
	 * Style.
	 */
	$options[] = array( "name" => _x("Style", "theme-options", 'the7mk2'), "type" => "block_begin" );

		// radio
		$options[] = array(
			"name"		=> _x( "Style", "theme-options", 'the7mk2' ),
			"id"		=> "general-style",
			"std"		=> "ios7",
			"type"		=> "radio",
			"options"	=> array(
				"ios7" => _x( "iOS 7  style", "theme-options", 'the7mk2' ),
				"minimalistic" => _x( "Minimalist style", "theme-options", 'the7mk2' ),
				"material" => _x( "Material design style", "theme-options", 'the7mk2' )
			)
		);

	$options[] = array( "type" => "block_end" );

	/**
	 * Layout.
	 */
	$options[] = array(	"name" => _x('Layout', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// text
		$options[] = array(
			"desc"		=> '',
			"name"		=> _x( 'Content width (in "px" or "%")', 'theme-options', 'the7mk2' ),
			"id"		=> "general-content_width",
			"std"		=> '1200px', 
			"type"		=> "text",
			"sanitize"	=> 'css_width'
		);

		// radio
		$options[] = array(
			"name"		=> _x('Layout', 'theme-options', 'the7mk2'),
			"id"		=> 'general-layout',
			"std"		=> 'wide',
			"type"		=> 'radio',
			"options"	=> presscore_themeoptions_get_general_layout_options(),
			"show_hide"	=> array( "boxed" => true )
		);

		// hidden area
		$options[] = array( "type" => "js_hide_begin" );

			// text
			$options[] = array(
				"desc"		=> '',
				"name"		=> _x( 'Box width (in "px" or "%")', 'theme-options', 'the7mk2' ),
				"id"		=> "general-box_width",
				"std"		=> '1320px', 
				"type"		=> "text",
				"sanitize"	=> 'css_width'
			);

		$options[] = array( "type" => "js_hide_end" );

		// title
		$options[] = array(
			"type" => 'title',
			"name" => _x('Background under the box', 'theme-options', 'the7mk2')
		);

		// colorpicker
		$options[] = array(
			"name"	=> _x( 'Background color', 'theme-options', 'the7mk2' ),
			"id"	=> "general-boxed_bg_color",
			"std"	=> "#ffffff",
			"type"	=> "color"
		);

		// background_img
		$options[] = array(
			'type' 			=> 'background_img',
			'id' 			=> 'general-boxed_bg_image',
			'name' 			=> _x( 'Add background image', 'theme-options', 'the7mk2' ),
			'preset_images' => $backgrounds_general_boxed_bg_image,
			'std' 			=> array(
				'image'			=> '',
				'repeat'		=> 'repeat',
				'position_x'	=> 'center',
				'position_y'	=> 'center'
			),
		);

		// checkbox
		$options[] = array(
			"name"      => _x( 'Fullscreen ', 'theme-options', 'the7mk2' ),
			"id"    	=> 'general-boxed_bg_fullscreen',
			"type"  	=> 'checkbox',
			'std'   	=> 0
		);

		// Fixed background
		$options[] = array(
			"name"      => _x( 'Fixed background ', 'theme-options', 'the7mk2' ),
			"id"    	=> 'general-boxed_bg_fixed',
			"type"  	=> 'checkbox',
			'std'   	=> 0
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * Background.
	 */
	$options[] = array(	"name" => _x('Background', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// colorpicker
		$options[] = array(
			"name"	=> _x( 'Color', 'theme-options', 'the7mk2' ),
			"id"	=> "general-bg_color",
			"std"	=> "#252525",
			"type"	=> "color"
		);

		// slider
		$options[] = array(
			"name"      => _x( 'Opacity', 'theme-options', 'the7mk2' ),
			"desc"      => _x( '"Opacity" isn\'t compatible with slide-out footer', 'theme-options', 'the7mk2' ),
			"id"        => "general-bg_opacity",
			"std"       => 100, 
			"type"      => "slider"
		);

		// background_img
		$options[] = array(
			'name' 			=> _x( 'Add background image', 'theme-options', 'the7mk2' ),
			'id' 			=> 'general-bg_image',
			'preset_images' => $backgrounds_general_bg_image,
			'std' 			=> array(
				'image'			=> '',
				'repeat'		=> 'repeat',
				'position_x'	=> 'center',
				'position_y'	=> 'center'
			),
			'type'			=> 'background_img'
		);

		// checkbox
		$options[] = array(
			"name"      => _x( 'Fullscreen', 'theme-options', 'the7mk2' ),
			"id"    	=> 'general-bg_fullscreen',
			"type"  	=> 'checkbox',
			'std'   	=> 0
		);

		// Fixed background
		$options[] = array(
			"type"  	=> 'checkbox',
			"id"    	=> 'general-bg_fixed',
			"name"      => _x( 'Fixed background', 'theme-options', 'the7mk2' ),
			"desc"      => _x( '"Fixed" setting isn\'t compatible with "overlapping" title area style.', 'theme-options', 'the7mk2' ),
			'std'   	=> 0
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * Content boxes background.
	 */
	$options[] = array(	"name" => _x('Content boxes background', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// radio
		$options["general-content_boxes_bg_mode"] = array(
			"name"		=> _x( "Background", "theme-options", 'the7mk2' ),
			"id"		=> "general-content_boxes_bg_mode",
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
			$options["general-content_boxes_solid_bg_color"] = array(
				"name"	=> "&nbsp;",
				"id"	=> "general-content_boxes_solid_bg_color",
				"std"	=> "#FFFFFF",
				"type"	=> "color"
			);

		$options[] = array( "type" => "js_hide_end" );

	$options[] = array(	"type" => "block_end");

	/**
	 * Text.
	 */
	$options[] = array(	"name" => _x('Text', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// colorpicker
		$options[] = array(
			"desc" => '',
			"name"	=> _x( 'Headers color', 'theme-options', 'the7mk2' ),
			"id"	=> "content-headers_color",
			"std"	=> "#252525",
			"type"	=> "color"
		);

		// colorpicker
		$options[] = array(
			"desc" => '',
			"name"	=> _x( 'Text color', 'theme-options', 'the7mk2' ),
			"id"	=> "content-primary_text_color",
			"std"	=> "#686868",
			"type"	=> "color"
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * Color Accent.
	 */
	$options[] = array(	"name" => _x('Color Accent', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// radio
		$options["general-accent_color_mode"] = array(
			"name"		=> _x( "Accent color", "theme-options", 'the7mk2' ),
			"id"		=> "general-accent_color_mode",
			"std"		=> "color",
			"type"		=> "radio",
			"show_hide"	=> array(
				'color' 	=> "general-accent_color_mode-color",
				'gradient'	=> "general-accent_color_mode-gradient"
			),
			"options"	=> array(
				"color"		=> _x( 'Solid Color', 'theme-options', 'the7mk2' ),
				"gradient"	=> _x( 'Gradient', 'theme-options', 'the7mk2' )
			)
		);

		// hidden area
		$options[] = array( "type" => "js_hide_begin", "class" => "general-accent_color_mode general-accent_color_mode-color" );

			// colorpicker
			$options["general-accent_bg_color"] = array(
				"name"	=> "&nbsp;",
				"id"	=> "general-accent_bg_color",
				"std"	=> "#D73B37",
				"type"	=> "color"
			);

		$options[] = array( "type" => "js_hide_end" );

		// hidden area
		$options[] = array( "type" => "js_hide_begin", "class" => "general-accent_color_mode general-accent_color_mode-gradient" );

			// colorpicker
			$options["general-accent_bg_color_gradient"] = array(
				"name"	=> "&nbsp;",
				"id"	=> "general-accent_bg_color_gradient",
				"std"	=> array( '#ffffff', '#000000' ),
				"type"	=> "gradient"
			);

		$options[] = array( "type" => "js_hide_end" );

	$options[] = array(	"type" => "block_end");

	/**
	 * Border radius.
	 */
	$options[] = array(	"name" => _x('Border radius', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// input
		$options[] = array(
			"name"		=> _x( 'Border Radius (px)', 'theme-options', 'the7mk2' ),
			"id"		=> 'general-border_radius',
			"std"		=> '8',
			"type"		=> 'text',
			"sanitize"	=> 'dimensions'
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * Icons.
	 */
	$options[] = array(	"name" => _x('Icons', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// input
		$options['general-icons_style'] = array(
			"name"		=> _x( 'Icons', 'theme-options', 'the7mk2' ),
			"id"		=> 'general-icons_style',
			"std"		=> 'light',
			"type"		=> 'radio',
			"options"	=> array(
				"light"		=> _x( 'Light', 'theme-options', 'the7mk2' ),
				"bold"		=> _x( 'Bold', 'theme-options', 'the7mk2' )
			)
		);

	$options[] = array(	"type" => "block_end");

/**
 * Heading definition.
 */
$options[] = array( "name" => _x("Custom CSS", "theme-options", 'the7mk2'), "type" => "heading" );

	/**
	 * Custom css
	 */
	$options[] = array(	"name" => _x('Custom CSS', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// textarea
		$options[] = array(
			"settings"	=> array( 'rows' => 16 ),
			"id"		=> "general-custom_css",
			"std"		=> false,
			"type"		=> 'textarea',
			"sanitize"	=> 'without_sanitize'
		);

	$options[] = array(	"type" => "block_end");


/**
 * Heading definition.
 */
$options[] = array( "name" => _x("Advanced", "theme-options", 'the7mk2'), "type" => "heading" );

	/**
	 * Responsive.
	 */
	$options[] = array(	"name" => _x('Responsiveness', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// radio
		$options[] = array(
			"name"		=> _x('Responsive layout', 'theme-options', 'the7mk2'),
			"id"		=> 'general-responsive',
			"std"		=> '1',
			"type"		=> 'radio',
			'show_hide'	=> array( '1' => true ),
			"options"	=> $en_dis_options
		);

		// hidden area
		$options[] = array( "type" => "js_hide_begin" );

			$options[] = array( "type" => "divider" );

			// input
			$options[] = array(
				"name"		=> _x( "Collapse content to one column after (px)", "theme-options", 'the7mk2' ),
				"desc"		=> _x( "does not affect VC columns", "theme-options", 'the7mk2' ),
				"id"		=> "general-responsiveness-treshold",
				"std"		=> 800,
				"type"		=> "text",
				"class"		=> "mini",
				"sanitize"	=> "dimensions"
			);

		$options[] = array( "type" => "js_hide_end" );


	$options[] = array(	"type" => "block_end");

	/**
	 * High-DPI (retina) images.
	 */
	$options[] = array(	"name" => _x('High-DPI (retina) images', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// radio
		$options[] = array(
			"name"		=> _x('High-DPI (retina) images', 'theme-options', 'the7mk2'),
			"id"		=> 'general-hd_images',
			"std"		=> 'srcset_based',
			"type"		=> 'radio',
			"options"	=> array(
				'disabled' => _x('Disabled', 'theme-options', 'the7mk2'),
				'logos_only' => _x('Site logos only', 'theme-options', 'the7mk2'),
				'srcset_based' => _x('Srcset (recommended; widely used, though [now] not W3C valid)', 'theme-options', 'the7mk2'),
				'cookie_based' => _x('Generate on server (not recommended; will not work with caching plugins)', 'theme-options', 'the7mk2'),
			)
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * Smooth scroll.
	 */
	$options[] = array(	"name" => _x('Smooth scroll', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// radio
		$options[] = array(
			"name"		=> _x('Enable "scroll-behaviour: smooth" for next gen browsers', 'theme-options', 'the7mk2'),
			"id"		=> 'general-smooth_scroll',
			"std"		=> 'on',
			"type"		=> 'radio',
			"options"	=> array(
				'on'			=> _x( 'Yes', 'theme-options', 'the7mk2' ),
				'off'			=> _x( 'No', 'theme-options', 'the7mk2' ),
				'on_parallax'	=> _x( 'On only on pages with parallax', 'theme-options', 'the7mk2' )
			)
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * Beautiful loading.
	 */
	$options[] = array( "name" => _x("Beautiful loading", "theme-options", 'the7mk2'), "type" => "block_begin" );

		$options[] = array(
			"name"		=> _x( "Beautiful loading", "theme-options", 'the7mk2' ),
			"id"		=> "general-beautiful_loading",
			"std"		=> "accent",
			"type"		=> "radio",
			"options"	=> array(
				"disabled" => _x( "Disabled", "theme-options", 'the7mk2' ),
				"light" => _x( "Light", "theme-options", 'the7mk2' ),
				"accent" => _x( "Accent", "theme-options", 'the7mk2' )
			)
		);

	$options[] = array( "type" => "block_end" );
	

	/**
	 * Slugs
	 */
	$options[] = array( "name" => _x("Slugs", "theme-options", 'the7mk2'), "type" => "block_begin" );

		// input
		$options[] = array(
			"name"		=> _x("Portfolio slug", "theme-options", 'the7mk2'),
			"id"		=> "general-post_type_portfolio_slug",
			"std"		=> "project",
			"type"		=> "text",
			"class"		=> "mini"
		);

		// input
		$options[] = array(
			"name"		=> _x("Albums slug", "theme-options", 'the7mk2'),
			"id"		=> "general-post_type_gallery_slug",
			"std"		=> "dt_gallery",
			"type"		=> "text",
			"class"		=> "mini"
		);

		// input
		$options[] = array(
			"name"		=> _x("Team slug", "theme-options", 'the7mk2'),
			"id"		=> "general-post_type_team_slug",
			"std"		=> "dt_team",
			"type"		=> "text",
			"class"		=> "mini"
		);

	$options[] = array( "type" => "block_end" );

	/**
	 * Contact form sends emails to:.
	 */
	$options[] = array( "name" => _x("Contact form sends emails to:", "theme-options", 'the7mk2'), "type" => "block_begin" );

		// input
		$options[] = array(
			"name"		=> '&nbsp;',
			"id"		=> "general-contact_form_send_mail_to",
			"std"		=> "",
			"type"		=> "text",
			"sanitize"	=> 'email'
			// "class"		=> "mini",
		);

	$options[] = array( "type" => "block_end" );

	/**
	 * Plugins notifications.
	 */
	$options[] = array( "name" => _x("Plugins notifications", "theme-options", 'the7mk2'), "type" => "block_begin" );

		// checkbox
		$options[] = array(
			"name"      => _x( 'Silence plugins activation notifications', 'theme-options', 'the7mk2' ),
			"id"    	=> 'general-hide_plugins_notifications',
			"type"  	=> 'checkbox',
			'std'   	=> 1
		);

	$options[] = array( "type" => "block_end" );

	/**
	 * Tracking code
	 */
	$options[] = array(	"name" => _x('Tracking code (e.g. Google analytics) or arbitrary JavaScript', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// textarea
		$options[] = array(
			"settings"	=> array( 'rows' => 16 ),
			"id"		=> "general-tracking_code",
			"std"		=> false,
			"type"		=> 'textarea',
			"sanitize"	=> 'without_sanitize'
		);

	$options[] = array(	"type" => "block_end");
