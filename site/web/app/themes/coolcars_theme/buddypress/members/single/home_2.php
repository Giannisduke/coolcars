<div class="container profile">
<div class="row">
<div class="col-xs-3 flex-items-xs-center">
<div class="row">
	<div class="col-xs-12 menu-box profile-avatar">
	<a href="<?php bp_displayed_user_link(); ?>">

		<?php bp_displayed_user_avatar( 'type=full' ); ?>

	</a>
</div>

<div class="col-xs-12 menu-box">
	<?php if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() ) : ?>
		<h5 class="user-nicename"><?php bp_displayed_user_mentionname(); ?></h5>
	<?php endif; ?>
</div>
<div class="col-xs-12 menu-box">
<?php bp_last_activity( bp_displayed_user_id() ); ?>
</div>

</div>
</div>
<div class="col-xs-9">
<?php get_template_part('templates/unit-profile_tabs') ?>
</div>
</div> <!-- .row -->
</div> <!-- .container -->




<?php
/**
 * BuddyPress - Members Home
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<div id="buddypress" class="container">
	<div class="row">
		<?php

		/**
		 * Fires before the display of member home content.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_before_member_home_content' ); ?>


			<div id="item-header" role="complementary" class="col-xs-3 flex-items-xs-center">

				<?php
				/**
				 * If the cover image feature is enabled, use a specific header
				 */
				if ( bp_displayed_user_use_cover_image_header() ) :
					bp_get_template_part( 'members/single/cover-image-header' );
				else :
					bp_get_template_part( 'members/single/member-header' );
				endif;
				?>

			</div><!-- #item-header -->


		<div class="col-xs-9 test">
			<div id="item-nav">
				<div class="item-list-tabs no-ajax" id="object-nav" aria-label="<?php esc_attr_e( 'Member primary navigation', 'buddypress' ); ?>" role="navigation">
					<ul>

						<?php bp_get_displayed_user_nav(); ?>

						<?php

						/**
						 * Fires after the display of member options navigation.
						 *
						 * @since 1.2.4
						 */
						do_action( 'bp_member_options_nav' ); ?>

					</ul>
				</div>
			</div><!-- #item-nav -->
			<div class="col-xs-12">
				<div id="item-body">

					<?php

					/**
					 * Fires before the display of member body content.
					 *
					 * @since 1.2.0
					 */
					do_action( 'bp_before_member_body' );

					if ( bp_is_user_front() ) :
						bp_displayed_user_front_template_part();

					elseif ( bp_is_user_activity() ) :
						bp_get_template_part( 'members/single/activity' );

					elseif ( bp_is_user_blogs() ) :
						bp_get_template_part( 'members/single/blogs'    );

					elseif ( bp_is_user_friends() ) :
						bp_get_template_part( 'members/single/friends'  );

					elseif ( bp_is_user_groups() ) :
						bp_get_template_part( 'members/single/groups'   );

					elseif ( bp_is_user_messages() ) :
						bp_get_template_part( 'members/single/messages' );

					elseif ( bp_is_user_profile() ) :
						bp_get_template_part( 'members/single/profile'  );

					elseif ( bp_is_user_forums() ) :
						bp_get_template_part( 'members/single/forums'   );

					elseif ( bp_is_user_notifications() ) :
						bp_get_template_part( 'members/single/notifications' );

					elseif ( bp_is_user_settings() ) :
						bp_get_template_part( 'members/single/settings' );

					// If nothing sticks, load a generic template
					else :
						bp_get_template_part( 'members/single/plugins'  );

					endif;

					/**
					 * Fires after the display of member body content.
					 *
					 * @since 1.2.0
					 */
					do_action( 'bp_after_member_body' ); ?>

				</div><!-- #item-body -->
			</div>
		</div>








	<?php

	/**
	 * Fires after the display of member home content.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_member_home_content' ); ?>

</div><!-- .row -->
</div><!-- #buddypress -->