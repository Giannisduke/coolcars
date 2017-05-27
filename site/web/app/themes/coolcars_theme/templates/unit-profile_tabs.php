<?php $tempalate = get_template_directory_uri(); ?>

<ul id="clothing-nav" class="nav nav-tabs" role="tablist">

<li class="nav-item">
<a class="nav-link active" href="#home" id="home-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true"><img src="<?php echo $tempalate; ?>/dist/images/icon_p_profile.svg" class=""></a>
</li>

<li class="nav-item">
<a class="nav-link" href="#hats" role="tab" id="hats-tab" data-toggle="tab" aria-controls="hats"><img src="<?php echo $tempalate; ?>/dist/images/icon_p_notifications.svg" class=""></a>
</li>

<!-- Dropdown -->
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
<img src="<?php echo $tempalate; ?>/dist/images/icon_p_applications.svg" class="">
</a>
<div class="dropdown-menu">
<a class="dropdown-item" href="#dropdown-shoes" role="tab" id="dropdown-shoes-tab" data-toggle="tab" aria-controls="dropdownShoes">Shoes</a>
<a class="dropdown-item" href="#dropdown-boots" role="tab" id="dropdown-boots-tab" data-toggle="tab" aria-controls="dropdownBoots">Boots</a>
</div>
</li>

</ul>

<!-- Content Panel -->
<div id="clothing-nav-content" class="tab-content">

<div role="tabpanel" class="tab-pane fade in active" id="home" aria-labelledby="home-tab">
<p>Welcome home! Click on the tabs to see the content change.</p>
<?php gravity_form('Register-edit', false, false, false, '', false); ?>
</div>

<div role="tabpanel" class="tab-pane fade" id="hats" aria-labelledby="hats-tab">
<p>A hat is a head covering. It can be worn for protection against the elements, ceremonial reasons, religious reasons, safety, or as a fashion accessory.</p>
<?php if ( bp_has_activities( bp_ajax_querystring( 'activity' ) ) ) : ?>

	<?php if ( empty( $_POST['page'] ) ) : ?>

		<ul id="activity-stream" class="activity-list item-list">

	<?php endif; ?>

	<?php while ( bp_activities() ) : bp_the_activity(); ?>

		<?php bp_get_template_part( 'activity/entry' ); ?>

	<?php endwhile; ?>

	<?php if ( bp_activity_has_more_items() ) : ?>

		<li class="load-more">
			<a href="<?php bp_activity_load_more_link() ?>"><?php _e( 'Load More', 'buddypress' ); ?></a>
		</li>

	<?php endif; ?>

	<?php if ( empty( $_POST['page'] ) ) : ?>

		</ul>

	<?php endif; ?>

<?php else : ?>

	<div id="message" class="info">
		<p><?php _e( 'Sorry, there was no activity found. Please try a different filter.', 'buddypress' ); ?></p>
	</div>

<?php endif; ?>


</div>

<div role="tabpanel" class="tab-pane fade" id="dropdown-shoes" aria-labelledby="dropdown-shoes-tab">
<p>A shoe is an item of footwear intended to protect and comfort the human foot while doing various activities. Shoes are also used as an item of decoration.</p>
</div>

<div role="tabpanel" class="tab-pane fade" id="dropdown-boots" aria-labelledby="dropdown-boots-tab">
<p>A boot is a type of footwear and a specific type of shoe. Most boots mainly cover the foot and the ankle, while some also cover some part of the lower calf. Some boots extend up the leg, sometimes as far as the knee or even the hip.</p>
</div>

</div>
