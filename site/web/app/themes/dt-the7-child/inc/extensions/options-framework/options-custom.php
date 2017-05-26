<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Widgetareas theme-options filter.
 */
function optionsframework_widgetareas_interface( $output, $value ) {

    // Name
    $output .= '<label for="widgetareas-name">' . _x('Sidebar name', 'theme-options', 'the7mk2') . '</label>';
    $output .= '<input type="text" id="widgetareas-name" class="of_fields_gen_title" value=""/>';

    // Description
	$output .= '<label for="widgetareas-description">' . _x('Sidebar description (optional)', 'theme-options', 'the7mk2') . '</label>';
    $output .= '<textarea id="widgetareas-description"></textarea>';    

    // Button
    $output .= '<button id="widgetareas-add" class="of_fields_gen_add">' . _x('Update', 'theme-options', 'the7mk2') . '</button>';

    return $output;
}

/**
 * Widgetareas ajax handler.
 */
function optionsframework_widgetareas_ajax() {
	$action = empty($_POST['type']) ? '' : $_POST['type'];
	$nonce = empty($_POST['waNonce']) ? '' : $_POST['waNonce'];
 	$wa_id = empty($_POST['waId']) ? 0 : absint($_POST['waId']);
 	$wa_title = empty($_POST['waTitle']) ? '' : $_POST['waTitle'];
 	$wa_desc = empty($_POST['waDesc']) ? '' : $_POST['waDesc'];

	// check to see if the submitted nonce matches with the
	// generated nonce we created earlier
	if ( ! wp_verify_nonce( $nonce, 'options-framework-nonce' ) ) {		
		die ( 'Busted!');
 	}

	// ignore the request if the current user doesn't have
	// sufficient permissions
	if ( current_user_can( 'edit_theme_options' ) ) {
 		
 		$response = array( 'success' => false );
		$wa_array = of_get_option('widgetareas', array());

		if ( 'get' == $action && $wa_id ) {

			if ( $wa_array && isset($wa_array[ $wa_id ]) ) {

				$response['title'] = $wa_array[ $wa_id ]['title'];
				$response['desc'] = $wa_array[ $wa_id ]['desc'];
				$response['success'] = true;
			}
		} else if ( 'update' == $action && $wa_title ) {

			$known_options = get_option( 'optionsframework', array() );
			$saved_options = get_option( $known_options['id'], array() );
			
			if ( isset($saved_options['widgetareas']) ) {
				$wa_array = $saved_options['widgetareas'];
				
				// Get field id
				if ( !$wa_id ) { $wa_id = $wa_array['next_id']++; }
				
				// Update/Add new field
				$wa_array[ $wa_id ] = array(
					'title' => $wa_title,
					'desc'	=> $wa_desc
				);

				// Sanitize
				$saved_options['widgetareas'] = apply_filters('of_sanitize_widgetareas', $wa_array);

				// Update options
				$response['success'] = update_option($known_options['id'], $saved_options);
				$response['id'] = $wa_id;
			}
		}

		// generate the response
		$response = json_encode($response);
 
		// response output
		header( "Content-Type: application/json" );
		echo $response;
	}
 
	// IMPORTANT: don't forget to "exit"
	exit;
}
add_action('wp_ajax_process_widgetarea', 'optionsframework_widgetareas_ajax');

// get google fonts list
function dt_get_google_fonts_list( $get_defaults = false ) {
	$default_lst = optionsframework_get_web_fonts_defaults();
	if ( $get_defaults ) { return $default_lst; }

	$fonts_lst = $default_lst;
	return $fonts_lst;
}

// get images for options framework
function dt_get_images_in( $dir = '', $one_img_dir = '', $basedir = false ){
    $noimage = '/images/noimage_small.jpg';

    if ( ! $basedir ) {
		$basedir = dirname(__FILE__) . '/../../../';
	}

    $dirname = $basedir .$dir;
    $res = $full_dir = $thumbs_dir = array();

    // full dir
    if ( file_exists($dirname. '/full') && $handle = opendir( $dirname. '/full') ) {
        while (false !== ($file = readdir($handle))) {
            if (preg_match('/\.(jpeg|jpg|png|gif)$/', $file)) {
                $f_name = preg_split( '/\.[^.]+$/', $file );
                $full_dir[$f_name[0]] = $file;
            }
        }
        closedir($handle);
    }
    unset($file);
    
    // thumbs dir
    if ( file_exists($dirname. '/thumbs') && $handle = opendir( $dirname. '/thumbs') ) {
        while (false !== ($file = readdir($handle))) {
            if ( preg_match('/\.(jpeg|jpg|png|gif)$/', $file) ) {
                $f_name = preg_split( '/\.[^.]+$/', $file );
                $thumbs_dir[$f_name[0]] = $file;
            }
        }
        closedir($handle);
    }
    unset($file);
    asort($full_dir);

    foreach( $full_dir as $name=>$file ){
        $full_link = '/' . $dir . '/full/' . $file;
    	$thumb_link = $full_link;
        if( array_key_exists( $name, $thumbs_dir ) ){
            $thumb_link = '/' . $dir . '/thumbs/' . $thumbs_dir[$name];
        }else {
            $one_img = explode('.', $name);
            $file_name = $basedir . $one_img_dir . '/' . $one_img[0];

            if ( count($one_img) > 1 && $one_img[0] != $name && $one_img_dir && file_exists($file_name . '.png') ) {
                $thumb_link = '/'.$one_img_dir.'/'.$one_img[0].'.png';
            }

            if ( count($one_img) > 1 && $one_img[0] != $name && $one_img_dir && file_exists($file_name . '.jpg') ) {
                $thumb_link = '/'.$one_img_dir.'/'.$one_img[0].'.jpg';
            }
        }

        $res[$full_link] = $thumb_link;
    }
    
    return $res;
}

/* find option pages in array */
function optionsframework_options_page_filter( $item ) {
    if( isset($item['type']) && 'page' == $item['type'] ) {
        return true;
    }
    return false;
}

/* find options for current page */
function optionsframework_options_for_page_filter( $item ) {
    static $bingo = false;
    static $found_main = false;

    if ( $item == 0 ) { $bingo = $found_main = false; }

    if( !isset($_GET['page']) ) {
        if( !isset($_POST['_wp_http_referer']) ) {
            return true;
        }else {
            $arr = array();
            wp_parse_str($_POST['_wp_http_referer'], $arr);
            $current = current($arr);
        }
    }else {
        $current = $_GET['page'];
    }

    if( 'options-framework' == $current && !$found_main ) {
        $bingo = true;
        $found_main = true;
    }

    if( isset($item['type']) && 'page' == $item['type'] && $item['menu_slug'] == $current ) {
        $bingo = true;
        return false;
    }elseif( isset($item['type']) && 'page' == $item['type'] ) {
        $bingo = false;
    }

    return $bingo;
}

function optionsframework_get_presets_list () {

	return apply_filters( 'optionsframework_get_presets_list', array() );
}

function optionsframework_presets_data( $id ) {
    static $presets = null;

    if ( null === $presets ) {
		
		$presets = array();

		foreach ( optionsframework_get_presets_list() as $fname=>$thumb ) {
			
			$file = OPTIONS_FRAMEWORK_PRESETS_DIR . $fname . '.php';
			
			if ( is_readable( $file ) ) {
				include_once( $file );
			}
		}
	}

    if ( isset( $presets[ $id ] ) ) {
        return $presets[ $id ];
    }

    return array();
}

/**
 * Web Fonts defaults.
 *
 * @return array
 */
function optionsframework_get_web_fonts_defaults() {

	$web_fonts_list = array();
	if ( is_admin() && !( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		$web_fonts_list = include trailingslashit( dirname( __FILE__ ) ) . 'web-fonts.php';
	}

	return apply_filters( 'optionsframework_get_web_fonts_defaults', $web_fonts_list );
}
