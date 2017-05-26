<?php
/**
 * Shortcode attributes
 * @var $atts
 * @var $type
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Facebook
 */
extract( shortcode_atts( array(
	'type' => 'standard',
	'url'  => '',
	'like' => 'post'
), $atts ) );

if ( empty( $url ) ) {
	if ( isset( $like ) && 'page' === $like && function_exists( 'presscore_config' ) && presscore_config()->get( 'page_id' ) ) {
		$url = get_permalink( presscore_config()->get( 'page_id' ) );
	} else {
		$url = get_permalink();
	}
}

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'fb_like wpb_content_element fb_type_' . $type, $this->settings['base'], $atts );

$output = '<div class="' . esc_attr( $css_class ) . '"><iframe src="http://www.facebook.com/plugins/like.php?href='
          . $url . '&amp;layout='
          . $type . '&amp;show_faces=false&amp;action=like&amp;colorscheme=light" scrolling="no" frameborder="0" allowTransparency="true"></iframe></div>'
          . $this->endBlockComment( $this->getShortcode() ) . "\n";

echo $output;