<?php
/**
 * Microsite meta boxes.
 * @since presscore 2.2
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

$nav_menus = get_terms( 'nav_menu' );
$nav_menus_clear = array( 0 => _x('Primary location menu', 'backend metabox', 'the7mk2'), -1 => _x('Default menu', 'backend metabox', 'the7mk2') );

foreach ( $nav_menus as $nav_menu ) {
	$nav_menus_clear[ $nav_menu->term_id ] = $nav_menu->name;
}

$logo_field_title = _x('Logo', 'backend metabox', 'the7mk2');
$logo_hd_field_title = _x('High-DPI (retina) logo', 'backend metabox', 'the7mk2');

$prefix = '_dt_microsite_';

$DT_META_BOXES[] = array(
	'id'		=> 'dt_page_box-microsite',
	'title' 	=> _x('Microsite', 'backend metabox', 'the7mk2'),
	'pages' 	=> array( 'page' ),
	'context' 	=> 'side',
	'priority' 	=> 'default',
	'fields' 	=> array(

		// Page layout
		array(
			'name'    	=> _x('Page layout:', 'backend metabox', 'the7mk2'),
			'id'      	=> "{$prefix}page_layout",
			'type'    	=> 'radio',
			'std'		=> 'full',
			'options'	=> array(
				'wide' => _x('full-width', 'backend metabox', 'the7mk2'),
				'boxed' => _x('boxed', 'backend metabox', 'the7mk2')
			)
		),

		// Hide contemt
		array(
			'name' => _x('Hide:', 'backend metabox', 'the7mk2'),
			'id'   => "{$prefix}hidden_parts",
			'type' => 'checkbox_list',
			'options' => array(
				'header' => _x('header &amp; top bar', 'backend metabox', 'the7mk2'),
				'floating_menu' => _x('floating menu', 'backend metabox', 'the7mk2'),
				'content' => _x('content area', 'backend metabox', 'the7mk2'),
				'bottom_bar' => _x('bottom bar', 'backend metabox', 'the7mk2')
			),
			'top_divider'	=> true
		),

		// Enable beautiful page loading
		array(
			'name'    		=> _x('Beautiful loading:', 'backend metabox', 'the7mk2'),
			'id'      		=> "{$prefix}page_loading",
			'type'    		=> 'radio',
			'std'			=> 'accent',
			'options'		=>array(
				'disabled' => _x( 'Disabled', 'backend metabox', 'the7mk2' ),
				'light' => _x( 'Light', 'backend metabox', 'the7mk2' ),
				'accent' => _x( 'Accent', 'backend metabox', 'the7mk2' )
			),
			'top_divider'	=> true
		),

		// ------------------ Bottom logo
		array(
			'type' => 'heading',
			'name' => _x( 'Logo in bottom line', 'backend metabox', 'the7mk2' ),
			'id'   => 'bottom_logo_heading', // Not used but needed for plugin
		),

			// Regular logo
			array(
				'name'				=> $logo_field_title,
				'id'               => "{$prefix}bottom_logo_regular",
				'type'             => 'image_advanced_mk2',
				'max_file_uploads'	=> 1
			),

			// HD logo
			array(
				'name'				=> $logo_hd_field_title,
				'id'               => "{$prefix}bottom_logo_hd",
				'type'             => 'image_advanced_mk2',
				'max_file_uploads'	=> 1
			),

		// ------------------ Floating logo
		array(
			'type' => 'heading',
			'name' => _x( 'Floating menu', 'backend metabox', 'the7mk2' ),
			'id'   => 'floating_logo_heading', // Not used but needed for plugin
		),

			// Regular logo
			array(
				'name'				=> $logo_field_title,
				'id'               => "{$prefix}floating_logo_regular",
				'type'             => 'image_advanced_mk2',
				'max_file_uploads'	=> 1
			),

			// HD logo
			array(
				'name'				=> $logo_hd_field_title,
				'id'               => "{$prefix}floating_logo_hd",
				'type'             => 'image_advanced_mk2',
				'max_file_uploads'	=> 1
			),

		// ------------------ Favicon
		array(
			'type' => 'heading',
			'name' => _x( 'Favicon', 'backend metabox', 'the7mk2' ),
			'id'   => 'favicon_heading', // Not used but needed for plugin
		),

			array(
				'id'               => "{$prefix}favicon",
				'type'             => 'image_advanced_mk2',
				'max_file_uploads'	=> 1
			),

		// Link
		array(
			'name'	=> _x('Target link:', 'backend metabox', 'the7mk2'),
			'id'    => "{$prefix}logo_link",
			'type'  => 'text',
			'std'   => '',
			'top_divider'	=> true
		),

		// Primary menu list
		array(
			'name'     		=> _x('Primary menu:','backend metabox', 'the7mk2'),
			'id'       		=> "{$prefix}primary_menu",
			'type'     		=> 'select',
			'options'  		=> $nav_menus_clear,
			'std'			=> 0,
			'top_divider'	=> true
		),

		// Custom CSS
		array(
			'name'	=> _x('Custom CSS','backend metabox', 'the7mk2'),
			'id'	=> "{$prefix}custom_css",
			'type'	=> 'textarea',
			'cols'	=> 20,
			'rows'	=> 4,
			'top_divider'	=> true
		),

	),
	'only_on'	=> array( 'template' => array('template-microsite.php') ),
);