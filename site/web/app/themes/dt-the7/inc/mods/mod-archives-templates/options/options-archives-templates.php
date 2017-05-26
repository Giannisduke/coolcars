<?php
/**
 * Archives settings
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Page definition.
 */
$options[] = array(
		"page_title"	=> _x( "Archives", "theme-options", 'the7mk2' ),
		"menu_title"	=> _x( "Archives", "theme-options", 'the7mk2' ),
		"menu_slug"		=> "of-archives-templates-menu",
		"type"			=> "page"
);

/**
 * Heading definition.
 */
$options[] = array( "name" => _x("Archives", "theme-options", 'the7mk2'), "type" => "heading" );

	/**
	 * Author.
	 */
	$options[] = array( "name" => _x("Author", "theme-options", 'the7mk2'), "type" => "block_begin" );

		// select
		$options[] = array(
			"name"		=> _x( 'Author archive template', 'theme-options', 'the7mk2' ),
			"id"		=> 'template_page_id_author',
			"type"		=> 'pages_list'
		);

	$options[] = array( "type" => "block_end" );

	/**
	 * Date.
	 */
	$options[] = array( "name" => _x("Date", "theme-options", 'the7mk2'), "type" => "block_begin" );

		// select
		$options[] = array(
			"name"		=> _x( 'Date archive template', 'theme-options', 'the7mk2' ),
			"id"		=> 'template_page_id_date',
			"type"		=> 'pages_list'
		);

	$options[] = array( "type" => "block_end" );

	/**
	 * Blog archives.
	 */
	$options[] = array(	"name" => _x('Blog archives', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// select
		$options[] = array(
			"name"		=> _x( 'Blog category template', 'theme-options', 'the7mk2' ),
			"id"		=> 'template_page_id_blog_category',
			"type"		=> 'pages_list'
		);

		$options[] = array(
			"name"		=> _x( 'Blog tags template', 'theme-options', 'the7mk2' ),
			"id"		=> 'template_page_id_blog_tags',
			"type"		=> 'pages_list'
		);

	$options[] = array(	"type" => "block_end");

/**
 * Heading definition.
 */
$options[] = array( "name" => _x("Search", "theme-options", 'the7mk2'), "type" => "heading" );

	/**
	 * Search.
	 */
	$options[] = array( "name" => _x("Search", "theme-options", 'the7mk2'), "type" => "block_begin" );

		// select
		$options[] = array(
			"name"		=> _x( 'Search page', 'theme-options', 'the7mk2' ),
			"id"		=> 'template_page_id_search',
			"type"		=> 'pages_list'
		);

	$options[] = array( "type" => "block_end" );

/**
 * Heading definition.
 */
$options[] = array( "name" => _x("Portfolio", "theme-options", 'the7mk2'), "type" => "heading" );

	/**
	 * Portfolio.
	 */
	$options[] = array( "name" => _x("Portfolio archives", "theme-options", 'the7mk2'), "type" => "block_begin" );

		// select
		$options[] = array(
			"name"		=> _x( 'Portfolio category template', 'theme-options', 'the7mk2' ),
			"id"		=> 'template_page_id_portfolio_category',
			"type"		=> 'pages_list'
		);

	$options[] = array( "type" => "block_end" );

/**
 * Heading definition.
 */
$options[] = array( "name" => _x("Albums", "theme-options", 'the7mk2'), "type" => "heading" );

	/**
	 * Albums.
	 */
	$options[] = array( "name" => _x("Albums archives", "theme-options", 'the7mk2'), "type" => "block_begin" );

		// select
		$options[] = array(
			"name"		=> _x( 'Albums category template', 'theme-options', 'the7mk2' ),
			"id"		=> 'template_page_id_gallery_category',
			"type"		=> 'pages_list'
		);

	$options[] = array( "type" => "block_end" );
