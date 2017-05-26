<?php
/**
 * Portfolio shortcode.
 *
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'DT_Shortcode_Portfolio', false ) ) {

	class DT_Shortcode_Portfolio extends DT_Masonry_Posts_Shortcode {

		protected $shortcode_name = 'dt_portfolio';
		protected $post_type = 'dt_portfolio';
		protected $taxonomy = 'dt_portfolio_category';

		public static function get_instance() {
			static $instance = null;
			if ( null === $instance ) {
				$instance = new self();
			}
			return $instance;
		}

		protected function __construct() {
			add_shortcode( $this->shortcode_name, array( $this, 'shortcode' ) );
		}

		public function shortcode( $atts, $content = null ) {
			parent::setup( $atts, $content );

			// vc inline dummy
			if ( $this->vc_is_inline ) {
				$terms_title = _x( 'Display categories', 'vc inline dummy', 'the7mk2' );

				return $this->vc_inline_dummy( array(
					'class' => 'dt_vc-portfolio_masonry',
					'title' => _x( 'Portfolio Masonry & Grid', 'vc inline dummy', 'the7mk2' ),
					'fields' => array(
						$terms_title => presscore_get_terms_list_by_slug( array( 'slugs' => $this->atts['category'], 'taxonomy' => $this->taxonomy ) )
					)
				) );
			}

			return $this->shortcode_html(); 
		}

		protected function shortcode_html() {

			$dt_query = $this->get_posts_by_terms( array(
				'orderby' => $this->atts['orderby'],
				'order' => $this->atts['order'],
				'number' => $this->atts['number'],
				'select' => $this->atts['select'],
				'category' => $this->atts['category']
			) );

			$output = '';
			if ( $dt_query->have_posts() ) {

				$this->backup_post_object();
				$this->backup_theme_config();
				$this->setup_config();

				$output = $this->the_loop( array(
					'masonry_container_class' => array( 'wf-container', 'dt-portfolio-shortcode' ),
					'post_template_callback' => array( $this, 'post_template' ),
					'query' => $dt_query,
					'full_width' => $this->atts['full_width'],
					'select' => $this->atts['select'],
					'show_filter' => $this->atts['show_filter'],
					'posts_per_page' => $this->atts['posts_per_page']
				) );

				// cleanup
				$this->restore_theme_config();
				$this->restore_post_object();

			}

			return $output;
		}

		protected function post_template() {
			presscore_populate_portfolio_config();

			dt_get_template_part( 'portfolio/masonry/portfolio-masonry-post' );
		}

		protected function sanitize_attributes( &$atts ) {
			$attributes = shortcode_atts( array(
				'type' => 'masonry',
				'category' => '',
				'order' => 'desc',
				'orderby' => 'date',
				'number' => '12',
				'show_title' => '',
				'show_excerpt' => '',
				'show_details' => '',
				'show_link' => '',
				'show_zoom' => '',
				'show_categories' => '',
				'show_date' => '',
				'show_author' => '',
				'show_comments' => '',
				'columns' => '2',
				'column_width' => '370',
				'padding' => '20',
				'descriptions' => 'under_image',
				'hover_bg_color' => 'accent',
				'bg_under_projects' => 'with_paddings',
				'content_aligment' => 'left',
				'hover_animation' => 'fade',
				'hover_content_visibility' => 'on_hover',
				'loading_effect' => 'none',
				'proportion' => '',
				'same_width' => 'false',
				'full_width' => '',
				'show_filter' => '',
				'show_orderby' => '',
				'show_order' => '',
				'posts_per_page' => '-1'
			), $atts );

			// sanitize attributes
			$attributes['type'] = sanitize_key( $attributes['type'] );
			$attributes['loading_effect'] = sanitize_key( $attributes['loading_effect'] );

			$attributes['order'] = apply_filters('dt_sanitize_order', $attributes['order']);
			$attributes['orderby'] = apply_filters('dt_sanitize_orderby', $attributes['orderby']);
			$attributes['number'] = apply_filters('dt_sanitize_posts_per_page', $attributes['number']);
			$attributes['posts_per_page'] = apply_filters('dt_sanitize_posts_per_page', $attributes['posts_per_page'], $attributes['number']);

			if ( $attributes['category'] ) {
				$attributes['category'] = presscore_sanitize_explode_string( $attributes['category'] );
				$attributes['select'] = 'only';
			} else {
				$attributes['select'] = 'all';
			}

			$attributes['show_title'] = apply_filters('dt_sanitize_flag', $attributes['show_title']);
			$attributes['show_excerpt'] = apply_filters('dt_sanitize_flag', $attributes['show_excerpt']);

			$attributes['show_details'] = apply_filters('dt_sanitize_flag', $attributes['show_details']);
			$attributes['show_link'] = apply_filters('dt_sanitize_flag', $attributes['show_link']);
			$attributes['show_zoom'] = apply_filters('dt_sanitize_flag', $attributes['show_zoom']);

			$attributes['show_categories'] = apply_filters('dt_sanitize_flag', $attributes['show_categories']);
			$attributes['show_date'] = apply_filters('dt_sanitize_flag', $attributes['show_date']);
			$attributes['show_author'] = apply_filters('dt_sanitize_flag', $attributes['show_author']);
			$attributes['show_comments'] = apply_filters('dt_sanitize_flag', $attributes['show_comments']);

			$attributes['show_filter'] = apply_filters('dt_sanitize_flag', $attributes['show_filter']);
			$attributes['show_orderby'] = apply_filters('dt_sanitize_flag', $attributes['show_orderby']);
			$attributes['show_order'] = apply_filters('dt_sanitize_flag', $attributes['show_order']);

			$attributes['columns'] = absint($attributes['columns']);

			$attributes['descriptions'] = sanitize_key($attributes['descriptions']);
			$attributes['descriptions'] = str_replace('hover', 'hoover', $attributes['descriptions']);

			$attributes['hover_bg_color'] = sanitize_key($attributes['hover_bg_color']);
			$attributes['bg_under_projects'] = sanitize_key($attributes['bg_under_projects']);

			$attributes['content_aligment'] = in_array($attributes['content_aligment'], array('centre', 'center')) ? 'center' : 'left';

			$attributes['hover_animation'] = sanitize_key($attributes['hover_animation']);
			$attributes['hover_content_visibility'] = sanitize_key($attributes['hover_content_visibility']);
			$attributes['hover_content_visibility'] = str_replace('hover', 'hoover', $attributes['hover_content_visibility']);

			$attributes['same_width'] = apply_filters('dt_sanitize_flag', $attributes['same_width']);

			$attributes['full_width'] = apply_filters('dt_sanitize_flag', $attributes['full_width']);
			$attributes['padding'] = intval($attributes['padding']);
			$attributes['column_width'] = intval($attributes['column_width']);

			if ( $attributes['proportion'] ) {
				$wh = array_map( 'absint', explode(':', $attributes['proportion']) );
				if ( 2 == count($wh) && !empty($wh[0]) && !empty($wh[1]) ) {
					$attributes['proportion'] = array( 'width' => $wh[0], 'height' => $wh[1] );
				} else {
					$attributes['proportion'] = absint( $attributes['proportion'] );
				}
			}

			return $attributes;
		}

		protected function setup_config() {
			$config = &$this->config;
			$atts = &$this->atts;

			$config->set( 'load_style', 'default' );
			$config->set( 'template', 'portfolio' );
			$config->set( 'post.preview.buttons.details.enabled', false );

			$config->set( 'layout', $atts['type'] );
			$config->set( 'item_padding', $atts['padding'] );
			$config->set( 'all_the_same_width', $atts['same_width'] );

			$config->set( 'show_links', $atts['show_link'] );
			$config->set( 'show_titles', $atts['show_title'] );
			$config->set( 'show_details', $atts['show_details'] );
			$config->set( 'show_excerpts', $atts['show_excerpt'] );
			$config->set( 'show_zoom', $atts['show_zoom'] );

			$config->set( 'image_layout', $atts['proportion'] ? 'resize' : 'original' );
			$config->set( 'thumb_proportions', $atts['proportion'] );

			$config->set( 'post.preview.width.min', $atts['column_width'] );
			$config->set( 'post.preview.description.style', $atts['descriptions'] );
			$config->set( 'post.preview.description.alignment', $atts['content_aligment'] );
			$config->set( 'post.preview.hover.animation', $atts['hover_animation'] );
			$config->set( 'post.preview.hover.color', $atts['hover_bg_color'] );
			$config->set( 'post.preview.hover.content.visibility', $atts['hover_content_visibility'] );
			$config->set( 'post.preview.load.effect', $atts['loading_effect'], 'fade_in' );

			if ( 'under_image' == $atts['descriptions'] ) {
				$config->set( 'post.preview.background.enabled', ! in_array( $atts['bg_under_projects'], array( 'disabled', '' ) ) );
				$config->set( 'post.preview.background.style', $atts['bg_under_projects'] );
			} else {
				$config->set( 'post.preview.background.enabled', false );
				$config->set( 'post.preview.background.style', false );
			}

			$config->set( 'post.meta.fields.date', $atts['show_date'] );
			$config->set( 'post.meta.fields.categories', $atts['show_categories'] );
			$config->set( 'post.meta.fields.comments', $atts['show_comments'] );
			$config->set( 'post.meta.fields.author', $atts['show_author'] );

			$config->set( 'template.columns.number', $atts['columns'] );
			$config->set( 'template.posts_filter.terms.enabled', $atts['show_filter'] );
			$config->set( 'template.posts_filter.orderby.enabled', $atts['show_orderby'] );
			$config->set( 'template.posts_filter.order.enabled', $atts['show_order'] );
			$config->set( 'order', $atts['order'] );
			$config->set( 'orderby', $atts['orderby'] );
		}
	}

	// create shortcode
	DT_Shortcode_Portfolio::get_instance();

}
