<?php
/**
 * Templates settings
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Page definition.
 */
$options[] = array(
		"page_title"	=> _x( "Blog, Portfolio, Gallery", "theme-options", 'the7mk2' ),
		"menu_title"	=> _x( "Blog, Portfolio, Gallery", "theme-options", 'the7mk2' ),
		"menu_slug"		=> "of-blog-and-portfolio-menu",
		"type"			=> "page"
);

//////////
// Blog //
//////////

/**
 * Heading definition.
 */
$options[] = array( "name" => _x("Blog post", "theme-options", 'the7mk2'), "type" => "heading" );

	/**
	 * Author info in posts
	 */
	$options[] = array(	"name" => _x('Author info in posts', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// checkbox
		$options[] = array(
			"name"      => _x( 'Show author info in blog posts', 'theme-options', 'the7mk2' ),
			"id"    	=> 'general-show_author_in_blog',
			"type"  	=> 'radio',
			'std'   	=> 1,
			"options"	=> $yes_no_options
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * Previous &amp; next buttons
	 */
	$options[] = array(	"name" => _x('Previous &amp; next buttons', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// checkbox
		$options[] = array(
			"name"      => _x( 'Show in blog posts', 'theme-options', 'the7mk2' ),
			"id"    	=> 'general-next_prev_in_blog',
			"type"  	=> 'radio',
			'std'   	=> 1,
			"options"	=> $yes_no_options
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * Back button.
	 */
	$options[] = array(	"name" => _x('Back button', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// radio
		$options[] = array(
			"desc"		=> '',
			"name"		=> _x('Back button', 'theme-options', 'the7mk2'),
			"id"		=> 'general-show_back_button_in_post',
			"std"		=> '0',
			"type"		=> 'radio',
			"options"	=> $en_dis_options,
			"show_hide"	=> array( '1' => true ),
		);

		// hidden area
		$options[] = array( 'type' => 'js_hide_begin' );

			// select
			$options[] = array(
				"name"		=> _x( 'Choose page', 'theme-options', 'the7mk2' ),
				"id"		=> 'general-post_back_button_target_page_id',
				"type"		=> 'pages_list'
			);

		$options[] = array( 'type' => 'js_hide_end' );

	$options[] = array(	"type" => "block_end");

	/**
	 * Meta information.
	 */
	$options[] = array(	"name" => _x('Meta information', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// radio
		$options[] = array(
			"desc"		=> '',
			"name"		=> _x('Meta information', 'theme-options', 'the7mk2'),
			"id"		=> 'general-blog_meta_on',
			"std"		=> '1',
			"type"		=> 'radio',
			"options"	=> $en_dis_options,
			"show_hide"	=> array( '1' => true ),
		);

		// hidden area
		$options[] = array( 'type' => 'js_hide_begin' );

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Date', 'theme-options', 'the7mk2' ),
				"id"    	=> 'general-blog_meta_date',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Author', 'theme-options', 'the7mk2' ),
				"id"    	=> 'general-blog_meta_author',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Categories', 'theme-options', 'the7mk2' ),
				"id"    	=> 'general-blog_meta_categories',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Comments', 'theme-options', 'the7mk2' ),
				"id"    	=> 'general-blog_meta_comments',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Tags', 'theme-options', 'the7mk2' ),
				"id"    	=> 'general-blog_meta_tags',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

		$options[] = array( 'type' => 'js_hide_end' );

	$options[] = array(	"type" => "block_end");

	/**
	 * Related posts.
	 */
	$options[] = array(	"name" => _x('Related posts', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// radio
		$options[] = array(
			"desc"		=> '',
			"name"		=> _x('Related posts', 'theme-options', 'the7mk2'),
			"id"		=> 'general-show_rel_posts',
			"std"		=> '0',
			"type"		=> 'radio',
			"options"	=> $en_dis_options,
			"show_hide"	=> array( '1' => true ),
		);

		// hidden area
		$options[] = array( 'type' => 'js_hide_begin' );

			// input
			$options[] = array(
				"name"		=> _x( 'Title', 'theme-options', 'the7mk2' ),
				"id"		=> 'general-rel_posts_head_title',
				"std"		=> __('Related posts', 'the7mk2'),
				"type"		=> 'text',
			);

			// input
			$options[] = array(
				"name"		=> _x( 'Maximum number of related posts', 'theme-options', 'the7mk2' ),
				"id"		=> 'general-rel_posts_max',
				"std"		=> 6,
				"type"		=> 'text',
				// number
				"sanitize"	=> 'ppp'
			);

		$options[] = array( 'type' => 'js_hide_end' );

	$options[] = array(	"type" => "block_end");


///////////////
// Portfolio //
///////////////

/**
 * Heading definition.
 */
$options[] = array( "name" => _x("Portfolio post", "theme-options", 'the7mk2'), "type" => "heading" );

	/**
	 * Prev / Next buttons.
	 */
	$options[] = array(	"name" => _x('Previous &amp; next buttons', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// radio
		$options[] = array(
			"name"      => _x( 'Show in portfolio posts', 'theme-options', 'the7mk2' ),
			"id"    	=> 'general-next_prev_in_portfolio',
			"type"  	=> 'radio',
			'std'   	=> 1,
			"options"	=> $yes_no_options,
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * Back button.
	 */
	$options[] = array(	"name" => _x('Back button', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// radio
		$options[] = array(
			"desc"		=> '',
			"name"		=> _x('Back button', 'theme-options', 'the7mk2'),
			"id"		=> 'general-show_back_button_in_project',
			"std"		=> '0',
			"type"		=> 'radio',
			"options"	=> $en_dis_options,
			"show_hide"	=> array( '1' => true ),
		);

		// hidden area
		$options[] = array( 'type' => 'js_hide_begin' );

			// select
			$options[] = array(
				"name"		=> _x( 'Choose page', 'theme-options', 'the7mk2' ),
				"id"		=> 'general-project_back_button_target_page_id',
				"type"		=> 'pages_list'
			);

		$options[] = array( 'type' => 'js_hide_end' );

	$options[] = array(	"type" => "block_end");

	/**
	 * Meta information.
	 */
	$options[] = array(	"name" => _x('Meta information', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// radio
		$options[] = array(
			"desc"		=> '',
			"name"		=> _x('Meta information', 'theme-options', 'the7mk2'),
			"id"		=> 'general-portfolio_meta_on',
			"std"		=> '1',
			"type"		=> 'radio',
			"options"	=> $en_dis_options,
			"show_hide"	=> array( '1' => true ),
		);

		// hidden area
		$options[] = array( 'type' => 'js_hide_begin' );

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Date', 'theme-options', 'the7mk2' ),
				"id"    	=> 'general-portfolio_meta_date',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Author', 'theme-options', 'the7mk2' ),
				"id"    	=> 'general-portfolio_meta_author',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Categories', 'theme-options', 'the7mk2' ),
				"id"    	=> 'general-portfolio_meta_categories',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Number of comments', 'theme-options', 'the7mk2' ),
				"id"    	=> 'general-portfolio_meta_comments',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

		$options[] = array( 'type' => 'js_hide_end' );

	$options[] = array(	"type" => "block_end");

	/**
	 * Related projects.
	 */
	$options[] = array(	"name" => _x('Related projects', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// radio
		$options[] = array(
			"desc"		=> '',
			"name"		=> _x('Related projects', 'theme-options', 'the7mk2'),
			"id"		=> 'general-show_rel_projects',
			"std"		=> '0',
			"type"		=> 'radio',
			"options"	=> $en_dis_options,
			"show_hide"	=> array( '1' => true ),
		);

		// hidden area
		$options[] = array( 'type' => 'js_hide_begin' );

			// title
			$options[] = array(
				"name"		=> _x( 'Title', 'theme-options', 'the7mk2' ),
				"id"		=> 'general-rel_projects_head_title',
				"std"		=> __('Related projects', 'the7mk2'),
				"type"		=> 'text',
			);

			// show title
			$options[] = array(
				"name"		=> _x('Show titles', 'theme-options', 'the7mk2'),
				"id"		=> 'general-rel_projects_title',
				"std"		=> '1',
				"type"		=> 'checkbox'
			);

			// show excerpt
			$options[] = array(
				"name"		=> _x('Show excerpts', 'theme-options', 'the7mk2'),
				"id"		=> 'general-rel_projects_excerpt',
				"std"		=> '1',
				"type"		=> 'checkbox'
			);

			// show date
			$options[] = array(
				"name"		=> _x('Show date', 'theme-options', 'the7mk2'),
				"id"		=> 'general-rel_projects_info_date',
				"std"		=> '1',
				"type"		=> 'checkbox'
			);

			// show author
			$options[] = array(
				"name"		=> _x('Show author', 'theme-options', 'the7mk2'),
				"id"		=> 'general-rel_projects_info_author',
				"std"		=> '1',
				"type"		=> 'checkbox'
			);

			// show comments
			$options[] = array(
				"name"		=> _x('Show number of comments', 'theme-options', 'the7mk2'),
				"id"		=> 'general-rel_projects_info_comments',
				"std"		=> '1',
				"type"		=> 'checkbox'
			);

			// show categories
			$options[] = array(
				"name"		=> _x('Show categories', 'theme-options', 'the7mk2'),
				"id"		=> 'general-rel_projects_info_categories',
				"std"		=> '1',
				"type"		=> 'checkbox'
			);

			// show link
			$options[] = array(
				"name"		=> _x('Show links', 'theme-options', 'the7mk2'),
				"id"		=> 'general-rel_projects_link',
				"std"		=> '1',
				"type"		=> 'checkbox'
			);

			// show zoom
			$options[] = array(
				"name"		=> _x('Show zoom', 'theme-options', 'the7mk2'),
				"id"		=> 'general-rel_projects_zoom',
				"std"		=> '1',
				"type"		=> 'checkbox'
			);

			// show details
			$options[] = array(
				"name"		=> _x('Show "Details" button', 'theme-options', 'the7mk2'),
				"id"		=> 'general-rel_projects_details',
				"std"		=> '1',
				"type"		=> 'checkbox'
			);

			// posts per page
			$options[] = array(
				"name"		=> _x( 'Maximum number of projects posts', 'theme-options', 'the7mk2' ),
				"id"		=> 'general-rel_projects_max',
				"std"		=> 12,
				"type"		=> 'text',
				// number
				"sanitize"	=> 'ppp'
			);

			////////////////////////////////////
			// Related projects dimensions //
			////////////////////////////////////

			// input
			$options[] = array(
				"name"		=> _x( 'Related posts height for fullwidth posts (px)', 'theme-options', 'the7mk2' ),
				"id"		=> 'general-rel_projects_fullwidth_height',
				"std"		=> 210,
				"type"		=> 'text',
				// number
				"sanitize"	=> 'ppp'
			);

			// radio
			$options[] = array(
				"name"		=> _x('Related posts width for fullwidth posts', 'theme-options', 'the7mk2'),
				"id"		=> 'general-rel_projects_fullwidth_width_style',
				"std"		=> 'prop',
				"type"		=> 'radio',
				"options"	=> $prop_fixed_options,
				"show_hide"	=> array( 'fixed' => true ),
			);

			// hidden area
			$options[] = array( 'type' => 'js_hide_begin' );

				// input
				$options[] = array(
					"name"		=> _x( 'Width (px)', 'theme-options', 'the7mk2' ),
					"id"		=> 'general-rel_projects_fullwidth_width',
					"std"		=> '210',
					"type"		=> 'text',
					// number
					"sanitize"	=> 'ppp'
				);

			$options[] = array( 'type' => 'js_hide_end' );

			// input
			$options[] = array(
				"name"		=> _x( 'Related posts height for posts with sidebar (px)', 'theme-options', 'the7mk2' ),
				"id"		=> 'general-rel_projects_height',
				"std"		=> 180,
				"type"		=> 'text',
				// number
				"sanitize"	=> 'ppp'
			);

			// radio
			$options[] = array(
				"name"		=> _x( 'Related posts width for posts with sidebar', 'theme-options', 'the7mk2' ),
				"id"		=> 'general-rel_projects_width_style',
				"std"		=> 'prop',
				"type"		=> 'radio',
				"options"	=> $prop_fixed_options,
				"show_hide"	=> array( 'fixed' => true ),
			);

			// hidden area
			$options[] = array( 'type' => 'js_hide_begin' );

				// input
				$options[] = array(
					"name"		=> _x( 'Width (px)', 'theme-options', 'the7mk2' ),
					"id"		=> 'general-rel_projects_width',
					"std"		=> '180',
					"type"		=> 'text',
					// number
					"sanitize"	=> 'ppp'
				);

			$options[] = array( 'type' => 'js_hide_end' );

		$options[] = array( 'type' => 'js_hide_end' );

	$options[] = array(	"type" => "block_end");

////////////
// Albums //
////////////

/**
 * Heading definition.
 */
$options[] = array( "name" => _x("Gallery post", "theme-options", 'the7mk2'), "type" => "heading" );

	/**
	 * Previous &amp; next buttons
	 */
	$options[] = array(	"name" => _x('Previous &amp; next buttons', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// checkbox
		$options[] = array(
			"name"      => _x( 'Show in gallery albums', 'theme-options', 'the7mk2' ),
			"id"    	=> 'general-next_prev_in_album',
			"type"  	=> 'radio',
			'std'   	=> 1,
			"options"	=> $yes_no_options
		);

	$options[] = array(	"type" => "block_end");

	/**
	 * Back button.
	 */
	$options[] = array(	"name" => _x('Back button', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// radio
		$options[] = array(
			"desc"		=> '',
			"name"		=> _x('Back button', 'theme-options', 'the7mk2'),
			"id"		=> 'general-show_back_button_in_album',
			"std"		=> '0',
			"type"		=> 'radio',
			"options"	=> $en_dis_options,
			"show_hide"	=> array( '1' => true ),
		);

		// hidden area
		$options[] = array( 'type' => 'js_hide_begin' );

			// select
			$options[] = array(
				"name"		=> _x( 'Choose page', 'theme-options', 'the7mk2' ),
				"id"		=> 'general-album_back_button_target_page_id',
				"type"		=> 'pages_list'
			);

		$options[] = array( 'type' => 'js_hide_end' );

	$options[] = array(	"type" => "block_end");

	/**
	 * Meta information.
	 */
	$options[] = array(	"name" => _x('Meta information', 'theme-options', 'the7mk2'), "type" => "block_begin" );

		// radio
		$options[] = array(
			"desc"		=> '',
			"name"		=> _x('Meta information', 'theme-options', 'the7mk2'),
			"id"		=> 'general-album_meta_on',
			"std"		=> '1',
			"type"		=> 'radio',
			"options"	=> $en_dis_options,
			"show_hide"	=> array( '1' => true ),
		);

		// hidden area
		$options[] = array( 'type' => 'js_hide_begin' );

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Date', 'theme-options', 'the7mk2' ),
				"id"    	=> 'general-album_meta_date',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Author', 'theme-options', 'the7mk2' ),
				"id"    	=> 'general-album_meta_author',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Categories', 'theme-options', 'the7mk2' ),
				"id"    	=> 'general-album_meta_categories',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

			// checkbox
			$options[] = array(
				"desc"  	=> '',
				"name"      => _x( 'Comments', 'theme-options', 'the7mk2' ),
				"id"    	=> 'general-album_meta_comments',
				"type"  	=> 'checkbox',
				'std'   	=> 1
			);

		$options[] = array( 'type' => 'js_hide_end' );

	$options[] = array(	"type" => "block_end");
