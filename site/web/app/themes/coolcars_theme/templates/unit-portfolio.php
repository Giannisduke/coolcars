<?php

        echo '<section class="content-block" id="slide03">';
        echo '<div class="container-fluid no-padding">';
        echo '<div class="row legend">';
        echo '<header class="mx-auto centering">';
        echo '<h1 class="slideInUp slideInUp2">Internships</h1>';
        echo '<h2 class="slideInUp slideInUp2">View available inetrnships</h2>';
        echo '</header>';
        echo '</div>';
        echo '<svg class="uvc-svg-triangle" xmlns="http://www.w3.org/2000/svg" version="1.1" fill="#f8f8f9" width="100%" height="30" viewBox="0 0 0.156661 0.1" style="height: 30px;"><polygon points="0.156661,3.93701e-006 0.156661,0.000429134 0.117665,0.05 0.0783307,0.0999961 0.0389961,0.05 -0,0.000429134 -0,3.93701e-006 0.0783307,3.93701e-006 "></polygon></svg>';



        echo '<div class="container">';
        echo '<div class="container">';
        if( current_user_can('editor') || current_user_can('administrator') ) {
        echo '<div class="row">';
            echo '<div class="col-xs-12  menu-box">';
            echo '<button type="button" class="btn btn-primary btn-sm " data-toggle="modal" data-target="#add'.$id.'">';
            echo '' . __('New internship', 'sage') . '';
            echo '</button>';
            echo '</div>';

            echo '<div class="modal fade bd-example-modal-lg" id="add'.$id.'" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">';
            echo '<div class="modal-dialog" role="document">';
            echo '<div class="modal-content">';
            echo '<div class="modal-header">';
            echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
            echo '<span aria-hidden="true">&times;</span>';
            echo '</button>';
            echo '<h4 class="modal-title" id="myModalLabel">' . __('Add new Internship', 'sage') . '</h4>';
            echo '</div>';
            echo '<div class="modal-body">';
            echo gravity_form('New Internship', false, false, false, '', false);
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

        echo '</div>';
        }
        echo '</div>';

        echo '<div class="row" >';
        echo '<div class="col-sm-3 choices" id="sticky_trigger">';
        echo '<div id="sticky_item">';

        echo facetwp_display( 'facet', 'categories' );
        echo facetwp_display( 'facet', 'locations' );
        echo facetwp_display( 'facet', 'period' );
        echo '</div>';
          echo '</div>';

  echo '<div class="facetwp-template col-sm-9" data-name="internships">';


//$args = array(
	//'post_parent' => 0,
//	'post_type'   => 'any',
//	'numberposts' => -1,
	//'post_status' => 'any'
//;
//$children = get_children( $args );
//$count = count($children);
$args = array( 'post_type' => 'internship', 'posts_per_page' => 10, 'facetwp' => true, );
$query = new WP_Query( $args );
$nonce = wp_create_nonce('my_delete_post_nonce');
$matchdate = get_post_meta(get_the_ID(), 'start_date', true);
$new_matchdate = DateTime::createFromFormat('d-m-Y', $matchdate);

while ( $query->have_posts() ) : $query->the_post();

  echo '<article class="'. join( ' ', get_post_class() ) .' grid-item">';
    echo '<div class="row">';

    if( current_user_can('editor') || current_user_can('administrator') ) {
    echo '<div class="col-xs-12 text-xs-right">';
    //echo ''.get_template_part('templates/entry-admin').'';
    echo '<a href="'.get_permalink( $post->post_parent ).'">';
      echo get_the_title( $post->post_parent );
      echo '</a>';
  echo '<button type="button" class="btn btn-primary btn-sm button-edit" data-href='.get_permalink( $id ).' data-toggle="modal" data-target="#edit'.$id.'">';
    echo '' . __('Edit', 'sage') . '';
    echo '</button>';


    echo '<button type="button" class="btn btn-primary btn-sm " data-toggle="modal" data-target="#'.$id.'">';
    echo '' . __('Delete', 'sage') . '';
    echo '</button>';
    echo '</div>';
    echo '<div class="modal fade" id="'.$id.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
    echo '<div class="modal-dialog" role="document">';
    echo '<div class="modal-content">';
    echo '<div class="modal-header">';
    echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
    echo '<span aria-hidden="true">&times;</span>';
    echo '</button>';
    echo '<h4 class="modal-title" id="myModalLabel">Are you sure you want to delete: '.get_the_title( $id ).'?</h4>';
    echo '</div>';
    echo '<div class="modal-body">';
    echo  '<button type="button" data-dismiss="modal" class="delete-post btn btn-primary" ';
    echo 'data-id=' . get_the_ID() . '';
    echo ' data-nonce=' . wp_create_nonce('my_delete_post_nonce') . ' ';
    echo '>test</a>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    echo '<div class="modal fade" id="edit'.$id.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
    echo '<div class="modal-dialog" role="document">';
    echo '<div class="modal-content">';
    echo '<div class="modal-header">';
    echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
    echo '<span aria-hidden="true">&times;</span>';
    echo '</button>';
    echo '<h4 class="modal-title" id="myModalLabel">Edit: '.get_the_title( $id ).'?</h4>';
    echo '</div>';
    echo '<div class="modal-body editform">';
    //echo do_shortcode("[gravityform id=2 title=true description=true ajax=false]");

    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';



  }
    echo '<div class="container">';
  echo '<div class="row">';
    echo '<div class="col-xs-12 in_title">';
    echo '<h3><a href="'.get_permalink( $id ).'">'.get_the_title( $id ).'</a></h3>';
    echo '</div>';
    echo '</div>';
    echo '<div class="row">';
    echo '<div class="col-xs-12 internship_info">';
    echo internship_cat_term_list( array ( 'id' => get_the_ID(), 'taxonomy' => 'internship_cat' ) );
    echo internship_lc_term_list( array ( 'id' => get_the_ID(), 'taxonomy' => 'internship_lc' ) );
    echo internship_lang_term_list( array ( 'id' => get_the_ID(), 'taxonomy' => 'internship_lang' ) );

  //  echo wp_strip_all_tags(get_the_term_list( get_the_ID(), 'internship_lang', ' ', ' , ', ' ') );
    echo '<span class="date-small">';
    echo date_i18n( get_option( 'date_format' ), strtotime( get_post_meta(get_the_ID(), 'start_date', TRUE) ) );
    echo ' - ';
    echo date_i18n( get_option( 'date_format' ), strtotime( get_post_meta(get_the_ID(), 'end_date', TRUE) ) );
//  echo '<span class="date-big">';
//    echo get_post_meta(get_the_ID(), 'days', TRUE);
//    echo '<span class="date-small">';
//    echo '' . __('Days', 'sage') . '';
//    echo '</span>';
  //    echo '</span>';
        echo '</span>';
    echo '</div>';
    echo '</div>';
    echo '<hr class="style1">';


  echo '<div class="row">';
  echo '<div class="col-xs-9">';
  echo '<div class="post-content">';
  the_excerpt();
  echo '</div>';
  echo '</div>';
  echo '<div class="col-xs-3 centering">';

  if( is_user_logged_in() ){
  echo '<button type="button" class="btn btn-outline-primary button-application" data-href='.get_permalink( $id ).' data-toggle="modal" data-target="#apply'.$id.'">';
  echo  '' . __('Apply', 'sage') . '';
  echo '</button>';
//  echo $count;
  echo '<div class="modal fade" id="apply'.$id.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
  echo '<div class="modal-dialog" role="document">';
  echo '<div class="modal-content">';
  echo '<div class="modal-header">';
  echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
  echo '<span aria-hidden="true">&times;</span>';
  echo '</button>';
  echo '<h4 class="modal-title" id="myModalLabel">'.get_the_title( $id ).'</h4>';
  echo '</div>';
  echo '<div class="modal-body newapplication">';
  echo do_shortcode("[gravityform id=4 title=false description=true ajax=false]");
  echo '</div>';
  echo '<div class="modal-footer">';
  echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
  echo '<button type="button" class="btn btn-primary">Save changes</button>';
  echo '</div>';
  echo '</div>';
  echo '</div>';
  echo '</div>';

}
else{

  echo '<button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#'.$id.'">';
  echo  '' . __('Register', 'sage') . '';
  echo '</button>';
  echo '<div class="modal fade" id="'.$id.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
  echo '<div class="modal-dialog" role="document">';
  echo '<div class="modal-content">';
  echo '<div class="modal-header">';
  echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
  echo '<span aria-hidden="true">&times;</span>';
  echo '</button>';
  echo '<h4 class="modal-title" id="myModalLabel">'.get_the_title( $id ).'</h4>';
  echo '</div>';
  echo '<div class="modal-body">';
  echo '<h3><a href="'.get_permalink( $id ).'">'.get_the_title( $id ).'</a></h3>';
  echo '</div>';
  echo '<div class="modal-footer">';
  echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
  echo '<button type="button" class="btn btn-primary">Save changes</button>';
  echo '</div>';
  echo '</div>';
  echo '</div>';
  echo '</div>';
} echo '</div>';
  echo '</div>';
  echo '</article>';

endwhile;
  echo '</div>'; //isotope-grid
  echo '</div>';
  echo '</div>';
    echo '</div>';
  echo '</section>';
