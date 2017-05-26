<?php
/**
 * Share buttons.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Page definition.
 */
$options[] = array(
		"page_title"	=> _x( "Share Buttons", 'theme-options', 'the7mk2' ),
		"menu_title"	=> _x( "Share Buttons", 'theme-options', 'the7mk2' ),
		"menu_slug"		=> "of-likebuttons-menu",
		"type"			=> "page"
);

/**
 * Heading definition.
 */
$options[] = array( "name" => _x('Share Buttons', 'theme-options', 'the7mk2'), "type" => "heading" );

	/**
	 * Share buttons settings.
	 */
	$options[] = array(	"name" => _x( "Share buttons appearance", "theme-options", 'the7mk2' ), "type" => "block_begin" );

		// radio
		$options[] = array(
			"name"		=> _x( "Share buttons visibility", "theme-options", 'the7mk2' ),
			"id"		=> "social_buttons-visibility",
			"std"		=> "on_hover",
			"type"		=> "radio",
			"options"	=> array(
				"on_hover" => _x( "Show on hover", "theme-options", 'the7mk2' ),
				"allways" => _x( "Always visible", "theme-options", 'the7mk2' )
			)
		);

	$options[] = array(	"type" => "block_end");

$share_buttons_titles = array(
	'post' => _x( 'Share this post', 'theme options', 'the7mk2' ),
	'portfolio_post' => _x( 'Share this post', 'theme options', 'the7mk2' ),
	'photo' => _x( 'Share this image', 'theme options', 'the7mk2' ),
	'page' => _x( 'Share this page', 'theme options', 'the7mk2' ),
);

foreach ( presscore_themeoptions_get_template_list() as $id=>$desc ) {

	/**
	 * Share buttons.
	 */
	$options[] = array(	"name" => $desc, "type" => "block_begin" );

		// input
		$options[] = array(
			"name"		=> _x( 'Button title', 'theme options', 'the7mk2' ),
			"id"		=> "social_buttons-{$id}-button_title",
			"std"		=> ( isset( $share_buttons_titles[ $id ] ) ? $share_buttons_titles[ $id ] : '' ),
			"type"		=> "text"
		);

		$options[] = array( "type" => "divider" );

		// social_buttons
		$options[] = array(
			"id"		=> 'social_buttons-' . $id,
			"std"		=> array(),
			"type"		=> 'social_buttons',
		);

	$options[] = array(	"type" => "block_end");

}
