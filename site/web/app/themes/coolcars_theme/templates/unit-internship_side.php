<div class="col-xs-12 menu-box">
<div class="date-small side">
<?php
echo date_i18n( get_option( 'date_format' ), strtotime( get_post_meta(get_the_ID(), 'start_date', TRUE) ) );
echo ' - ';
echo date_i18n( get_option( 'date_format' ), strtotime( get_post_meta(get_the_ID(), 'end_date', TRUE) ) );
?>
</div>
</div>

<?php get_template_part('templates/unit-internship_apply'); ?>
<hr class="style1">
<?php get_template_part('templates/unit-internship_parentphoto'); ?>
<?php
if( current_user_can('editor') || current_user_can('administrator') ) {
get_template_part('templates/unit-child_application');
}
?>
