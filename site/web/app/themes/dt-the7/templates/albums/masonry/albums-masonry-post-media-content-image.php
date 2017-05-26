<?php
/**
 * Portfolio post media content part for image
 *
 * @since 1.0.0
 * @package vogue
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div class="project-list-media">

	<div class="buttons-on-img">

		<?php
		$config = Presscore_Config::get_instance();

		$media_items = $config->get( 'post.media.library' );

		if ( !$media_items ) {
			$media_items = array();
		}

		// add thumbnail to attachments list
		if ( has_post_thumbnail() && ! in_array( get_post_thumbnail_id(), $media_items ) ) {
			array_unshift( $media_items, get_post_thumbnail_id() );
		}

		// if pass protected - show only cover image
		if ( $media_items && post_password_required() ) {
			$media_items = array( $media_items[0] );
		}

		$show_mini_images = $config->get( 'post.preview.mini_images.enabled' );
		$open_post = ( 'post' == $config->get( 'post.open_as' ) );
		if ( $open_post ) {

			if ( $show_mini_images ) {
				$media_items = array_slice( $media_items, 0, 4);
			} else {
				$media_items = array( $media_items[0] );
			}

		}

		// get attachments data
		$attachments_data = presscore_get_attachment_post_data( $media_items );
		$only_one_attachment = count( $attachments_data ) == 1;
		$exclude_cover = !$config->get( 'post.media.featured_image.enabled' ) && has_post_thumbnail();

		// if there are one image in gallery
		if ( $only_one_attachment ) {
			$exclude_cover = false;
			$show_mini_images = false;
		}

		$class = array( 'rollover-click-target', 'rollover' );
		if ( !$show_mini_images ) {
			$class[] = 'rollover-zoom';
		}

		$gallery_args = array(
			'share_buttons'			=> true,
			'attachments_count'		=> false,
			'video_icon'			=> false,
			'class'					=> $class,
			'exclude_cover'			=> $exclude_cover,
			'show_preview_on_hover' => $show_mini_images,
			'title_img_options'		=> presscore_set_image_dimesions(),
		);

		// open album post instead lightbox gallery
		if ( $open_post ) {
			$gallery_args['title_image_args'] = array( 'href' => get_permalink(), 'class' => 'rollover-click-target rollover go-to' );
		}

		echo presscore_get_images_gallery_hoovered( $attachments_data, $gallery_args );
		?>

	</div>

</div>
