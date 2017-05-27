<div class="row internship_info_in">
<?php
echo internship_cat_term_list_in( array ( 'id' => get_the_ID(), 'taxonomy' => 'internship_cat' ) );
echo internship_lc_term_list_in( array ( 'id' => get_the_ID(), 'taxonomy' => 'internship_lc' ) );
echo internship_lang_term_list_in( array ( 'id' => get_the_ID(), 'taxonomy' => 'internship_lang' ) );
?>
</div>
