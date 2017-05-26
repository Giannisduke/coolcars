<?php
/**
 * Plugins list.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

return array(

	// Visual Composer
	array(
		'name' => 'WPBakery Visual Composer',
		'slug' => 'js_composer',
		'source' => PRESSCORE_PLUGINS_DIR . '/js_composer.zip',
		'required' => false,
		'version' => '4.11.2.1',
		'force_activation' => false,
		'force_deactivation' => false
	),

	// DT Dummy
	array(
		'name' => 'DT Dummy',
		'slug' => 'dt-dummy',
		'source' => PRESSCORE_PLUGINS_DIR . '/dt-dummy.zip',
		'required' => false,
		'version' => '1.1.3',
		'force_activation' => false,
		'force_deactivation' => false
	),

	// Revolution slider
	array(
		'name' => 'Revolution Slider',
		'slug' => 'revslider',
		'source' => PRESSCORE_PLUGINS_DIR . '/revslider.zip',
		'required' => false,
		'version' => '5.2.3.5',
		'force_activation' => false,
		'force_deactivation' => false
	),

	// Go Pricing config
	array(
		'name' => 'GO Pricing Tables',
		'slug' => 'go_pricing',
		'source' => PRESSCORE_PLUGINS_DIR . '/go_pricing.zip',
		'required' => false,
		'version' => '3.1.1',
		'force_activation' => false,
		'force_deactivation' => false
	),

	// LayerSlider config
	array(
		'name' => 'LayerSlider WP',
		'slug' => 'LayerSlider',
		'source' => PRESSCORE_PLUGINS_DIR . '/layerslider.zip',
		'required' => false,
		'version' => '5.6.2',
		'force_activation' => false,
		'force_deactivation' => false
	),

	// Ultimate VC Addons
	array(
		'name' => 'Ultimate Addons for Visual Composer',
		'slug' => 'Ultimate_VC_Addons',
		'source' => PRESSCORE_PLUGINS_DIR . '/Ultimate_VC_Addons.zip',
		'required' => false,
		'version' => '3.13.7',
		'force_activation' => false,
		'force_deactivation' => false
	),

	array(
		'name' => 'Contact Form 7',
		'slug' => 'contact-form-7',
		'required' => false
	),

	array(
		'name' => 'Recent Tweets Widget',
		'slug' => 'recent-tweets-widget',
		'required' => false
	)
);
