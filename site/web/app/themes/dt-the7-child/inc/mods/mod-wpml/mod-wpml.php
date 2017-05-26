<?php
/**
 * WPML mod.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

require_once PRESSCORE_MODS_DIR . '/' . basename( dirname( __FILE__ ) ) . '/wpml-integration.php';

if ( ! class_exists( 'Presscore_WPML_Compatibility_Module', false ) ) {

	class Presscore_WPML_Compatibility_Module {

		public static function init() {

			/**
			 * Do not load wpml language switcher css.
			 */
			if ( ! defined( 'ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS' ) ) {
				define( 'ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true );
			}

			/**
			 * Dirty hack that fixes front page pagination with custom query.
			 */
			remove_action( 'template_redirect', 'wp_shortlink_header', 11, 0 );
			add_action( 'template_redirect', 'wp_shortlink_header',  11, 0 );

			/**
			 * Filter theme localized script.
			 */
			add_filter( 'presscore_localized_script', array( __CLASS__, 'localized_script_filter' ) );

			/**
			 * Enqueue dynamic stylesheets.
			 */
			add_filter( 'presscore_get_dynamic_stylesheets_list', array( __CLASS__, 'enqueue_dynamic_stylesheets' ) );

			/**
			 * Add editor.
			 */
			add_action( 'init', array( __CLASS__, 'enable_editor_for_post_types' ), 16 );

			/**
			 * Hide editor.
			 */
			add_action( 'admin_print_styles-post.php', array( __CLASS__, 'hide_editor_for_post_types' ) );
			add_action( 'admin_print_styles-post-new.php', array( __CLASS__, 'hide_editor_for_post_types' ) );

			/**
			 * Render language switcher.
			 */
			add_action( 'presscore_render_header_element-language', array( __CLASS__, 'render_header_language_switcher' ) );

			/**
			 * Add header layout elements.
			 */
			add_filter( 'header_layout_elements', array( __CLASS__, 'add_header_layout_elements' ) );

			/**
			 * Add lang attribute for header search form.
			 */
			add_action( 'presscore_header_searchform_fields', array( __CLASS__, 'add_header_searchform_lang' ) );

			/**
			 * Add parse query action before ajax response.
			 */
			add_action( 'presscore_before_ajax_response', array( __CLASS__, 'add_parse_query_action' ) );
		}

		public static function localized_script_filter( $args = array() ) {
			global $sitepress;
			if ( array_key_exists( 'ajaxurl', $args ) && is_object( $sitepress ) && method_exists( $sitepress, 'get_current_language' ) ) {
				$args['ajaxurl'] = esc_url( add_query_arg( array( 'lang' => $sitepress->get_current_language() ), $args['ajaxurl'] ) );
			}
			return $args;
		}

		public static function enable_editor_for_post_types() {
			add_post_type_support( 'dt_slideshow', 'editor' );
			add_post_type_support( 'dt_logos', 'editor' );
		}

		public static function hide_editor_for_post_types() {
			if ( in_array( get_post_type(), array( 'dt_slideshow', 'dt_logos' ) ) ) {
				wp_add_inline_style( 'dt-mb-magick', '#postdivrich { display: none; }' );
			}
		}

		public static function render_header_language_switcher() {
			do_action('icl_language_selector');
		}

		public static function add_header_layout_elements( $elements = array() ) {
			$elements['language'] = array( 'title' => _x( 'WPML language', 'theme-options', 'the7mk2' ), 'class' => '' );
			return $elements;
		}

		public static function enqueue_dynamic_stylesheets( $dynamic_stylesheets ) {
			$dynamic_stylesheets['wpml.less'] = array(
				'path' => PRESSCORE_THEME_DIR . '/css/wpml.less',
				'src' => PRESSCORE_THEME_URI . '/css/wpml.less',
				'fallback_src' => '',
				'deps' => array(),
				'ver' => wp_get_theme()->get( 'Version' ),
				'media' => 'all'
			);
			return $dynamic_stylesheets;
		}

		public static function add_header_searchform_lang() {
			if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
				echo '<input type="hidden" name="lang" value="' . ICL_LANGUAGE_CODE .'"/>';
			}
		}

		public static function add_parse_query_action() {
			if ( function_exists( 'presscore_wpml_parse_query_filter' ) ) {
				add_action( 'parse_query', 'presscore_wpml_parse_query_filter' );
			}
		}
	}

}

Presscore_WPML_Compatibility_Module::init();
