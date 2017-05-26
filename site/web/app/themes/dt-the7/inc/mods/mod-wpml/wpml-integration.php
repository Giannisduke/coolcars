<?php 

function presscore_wpml_get_current_language() {
    global $sitepress;
    $current_lang = false;
    if ( is_object( $sitepress ) && method_exists( $sitepress, 'get_current_language' ) ) {
        $current_lang = $sitepress->get_current_language();
    }
    return $current_lang;
}

// HOME URL
// USAGE: replace references to the blog home url such as:
// - get_option('home')
// - bloginfo('home')
// - bloginfo('url')
// - get_bloginfo('url')
// - etc...
// with wpml_get_home_url()
// * IMPORTANT: Most themes also add a trailing slash (/) to the URL. This function already includes it, so don't add the slash when using it.
function presscore_wpml_get_home_url(){
    if(function_exists('icl_get_home_url')){
        return icl_get_home_url();
    }else{
        return rtrim( home_url(), '/') . '/';
    }
}



// LANGUAGE SELECTOR
// USAGE place this on the single.php, page.php, index.php etc... - inside the loop
// function wpml_content_languages($args)
// args: skip_missing, before, after
// defaults: skip_missing = 1, before =  __('This post is also available in: '), after = ''
function presscore_wpml_content_languages($args=''){
    parse_str($args);
    if(function_exists('icl_get_languages')){
        $languages = icl_get_languages($args);
        if(1 < count($languages)){
            echo isset($before) ? $before : __('This post is also available in: ', 'the7mk2');
            foreach($languages as $l){
                if(!$l['active']) $langs[] = '<a href="'.$l['url'].'">'.$l['translated_name'].'</a>';
            }
            echo join(', ', $langs);
            echo isset($after) ? $after : '';
        }    
    }
} 


// LINKS TO SPECIFIC ELEMENTS
// USAGE
// args: $element_id, $element_type='post', $link_text='', $optional_parameters=array(), $anchor='', $echoit = true
function presscore_wpml_link_to_element($element_id, $element_type='post', $link_text='', $optional_parameters=array(), $anchor='', $echoit = true){
    if(!function_exists('icl_link_to_element')){    
        switch($element_type){
            case 'post':
            case 'page':
                $ret = '<a href="'.get_permalink($element_id).'">';
                if($anchor){
                    $ret .= $anchor;
                }else{
                    $ret .= get_the_title($element_id);
                }
                $ret .= '<a>'; 
                break;
            case 'tag':
            case 'post_tag':
                $tag = get_term_by('id', $element_id, 'tag', ARRAY_A);
                $ret = '<a href="'.get_tag_link($element_id).'">' . $tag->name . '</a>';
            case 'category':
                $ret = '<a href="'.get_tag_link($element_id).'">' . get_the_category_by_ID($element_id) . '</a>';
            default: $ret = '';           
        }
        if($echoit){
            echo $ret;
        }else{
            return $ret;
        }        
    }else{
        return icl_link_to_element($element_id, $element_type, $link_text, $optional_parameters, $anchor, $echoit);
    }        
}

// Languages links to display in the footer
//
function presscore_wpml_languages_list($skip_missing=0, $div_id = "footer_language_list"){
    if(function_exists('icl_get_languages')){
        $languages = icl_get_languages('skip_missing='.intval($skip_missing));
        if(!empty($languages)){
            echo '<div id="'.$div_id.'"><ul>';
            foreach($languages as $l){
                echo '<li>';
                if(!$l['active']) echo '<a href="'.$l['url'].'">';
                echo '<img src="'.$l['country_flag_url'].'" alt="'.$l['language_code'].'" />';
                if(!$l['active']) echo '</a>';
                if(!$l['active']) echo '<a href="'.$l['url'].'">';
                echo $l['native_name'];
                if(!$l['active']) echo ' ('.$l['translated_name'].')';
                if(!$l['active']) echo '</a>';
                echo '</li>';
            }
            echo '</ul></div>';
        }
    }
}

function presscore_wpml_languages_selector(){
    do_action('icl_language_selector');    
}

function presscore_wpml_t($context, $name, $original_value){
    if(function_exists('icl_t')){
        return icl_t($context, $name, $original_value);
    }else{
        return $original_value;
    }
}

function presscore_wpml_register_string($context, $name, $value){
    if(function_exists('icl_register_string') && trim($value)){
        icl_register_string($context, $name, $value);
    }    
}

function presscore_wpml_get_object_id($element_id, $element_type='post', $return_original_if_missing=false, $ulanguage_code=null){
    if(function_exists('icl_object_id')){
        return icl_object_id($element_id, $element_type, $return_original_if_missing, $ulanguage_code);
    }else{
        return $element_id;
    }    
}

function presscore_wpml_default_link($anchor){
    global $sitepress;
    $qv = false;
    
    if(is_single()){
        $qv = 'p=' . get_the_ID();
    }elseif(is_page()){
        $qv = 'page_id=' . get_the_ID();
    }elseif(is_tag()){
        $tag = &get_term(intval( get_query_var('tag_id') ), 'post_tag', OBJECT, 'display');        
        $qv = 'tag=' . $tag->slug;
    }elseif(is_category()){        
        $qv = 'cat=' . get_query_var('cat');
    }elseif(is_year()){        
        $qv = 'year=' . get_query_var('year');
    }elseif(is_month()){        
        $qv = 'm=' . get_query_var('year') . sprintf('%02d', get_query_var('monthnum'));
    }elseif(is_day()){        
        $qv = 'm=' . get_query_var('year') . sprintf('%02d', get_query_var('monthnum')) . sprintf('%02d', get_query_var('day'));
    }elseif(is_search()){        
        $qv = 's=' . get_query_var('s');
    }elseif(is_tax()){
        $qv = get_query_var('taxonomy') . '=' . get_query_var('term');        
    }
    
    if(false !== strpos(wpml_get_home_url(),'?')){
        $url_glue = '&';
    }else{
        $url_glue = '?';
    }
    
    if($qv){
        $link = '<a href="' .  $sitepress->language_url($sitepress->get_default_language()) . $url_glue . $qv . '" rel="nofollow">' . esc_html($anchor) . '</a>';
    }else{
        $link = '';
    } 

    return $link;
}

function presscore_wpml_parse_query_filter( $q ) {
    global $wpdb, $sitepress;

    $current_language = $sitepress->get_current_language();
    $default_language = $sitepress->get_default_language();

    if ( $current_language != $default_language ) {
        $cat_array = array();

        // cat
        if ( isset( $q->query_vars[ 'cat' ] ) && !empty( $q->query_vars[ 'cat' ] ) ) {
            $cat_array = array_map( 'intval', array_map( 'trim', explode( ',', $q->query_vars[ 'cat' ] ) ) );
        }

        // category_name
        if ( isset( $q->query_vars[ 'category_name' ] ) && !empty( $q->query_vars[ 'category_name' ] ) ) {
            $categories = explode(",", $q->query_vars[ 'category_name' ] );
            $cat_array = array();
            foreach ($categories as $category) {
                $category = trim($category);
                if ($category == "") { // it happens for category_name = "some-cat,some-cat-2,", with comma at end
                    continue; 
                }
                $cat = get_term_by( 'slug', preg_replace( '#((.*)/)#', '', $category), 'category' );
                if ( !$cat ) {
                    $cat = get_term_by( 'name', $category, 'category' );
                }
                if ( isset($cat) && is_object($cat) && $cat->term_id ) {
                    $cat_array[] = $cat->term_id;
                }
            }
            if (empty($cat_array)) {
                $q->query_vars[ 'p' ] = -1;
            }
        }

        // category_and
        if ( isset( $q->query_vars[ 'category__and' ] ) && !empty( $q->query_vars[ 'category__and' ] ) ) {
            $cat_array = $q->query_vars[ 'category__and' ];
        }
        // category_in
        if ( isset( $q->query_vars[ 'category__in' ] ) && !empty( $q->query_vars[ 'category__in' ] ) ) {
            $cat_array = array_unique( array_merge( $cat_array, array_map( 'intval', $q->query_vars[ 'category__in' ] ) ) );
        }
        // category__not_in
        if ( isset( $q->query_vars[ 'category__not_in' ] ) && !empty( $q->query_vars[ 'category__not_in' ] ) ) {
            $__cats = array();
            foreach ( $q->query_vars[ 'category__not_in' ] as $key => $val ) {
                $__cats[ $key ] = - 1 * intval( $val );
            }
            $cat_array = array_unique( array_merge( $cat_array, $__cats ) );
        }

        if ( !empty( $cat_array ) ) {
            $translated_ids = array();
            foreach ( $cat_array as $c ) {
                if ( intval( $c ) < 0 ) {
                    $sign = -1;
                } else {
                    $sign = 1;
                }
                $translated_ids[ ] = $sign * intval( icl_object_id( abs( $c ), 'category', true ) );
            }

            //cat
            if ( isset( $q->query_vars[ 'cat' ] ) && !empty( $q->query_vars[ 'cat' ] ) ) {
                $q->query_vars[ 'cat' ] = join( ',', $translated_ids );
            }

            // category_name
            if ( isset( $q->query_vars[ 'category_name' ] ) && !empty( $q->query_vars[ 'category_name' ] ) ) {
                $_ctmp                            = get_term_by( 'id', $translated_ids[ 0 ], 'category' );
                $q->query_vars[ 'category_name' ] = $_ctmp->slug;
            }
            // category__and
            if ( isset( $q->query_vars[ 'category__and' ] ) && !empty( $q->query_vars[ 'category__and' ] ) ) {
                $q->query_vars[ 'category__and' ] = $translated_ids;
            }
            // category__in
            if ( isset( $q->query_vars[ 'category__in' ] ) && !empty( $q->query_vars[ 'category__in' ] ) ) {
                $__translated_in = array();
                foreach ( $translated_ids as $key => $t_id ) {
                    if ( $t_id > 0 ) {
                        $__translated_in[ $key ] = $t_id;
                    }
                }
                $q->query_vars[ 'category__in' ] = $__translated_in;
            }
            // category__not_in
            if ( isset( $q->query_vars[ 'category__not_in' ] ) && !empty( $q->query_vars[ 'category__not_in' ] ) ) {
                $__translated_not_in = array();
                foreach ( $translated_ids as $key => $t_id ) {
                    if ( $t_id < 0 ) {
                        $__translated_not_in[ $key ] = $t_id;
                    }
                }
                $q->query_vars[ 'category__not_in' ] = $__translated_not_in;
            }

        }

        // TAGS
        $tag_array = array();
        // tag
        $tag_glue = '';
        if ( isset( $q->query_vars[ 'tag' ] ) && !empty( $q->query_vars[ 'tag' ] ) ) {
            if ( false !== strpos( $q->query_vars[ 'tag' ], ' ' ) ) {
                $tag_glue = '+';
                $exp      = explode( ' ', $q->query_vars[ 'tag' ] );
            } else {
                $tag_glue = ',';
                $exp      = explode( ',', $q->query_vars[ 'tag' ] );
            }
            foreach ( $exp as $e ) {
                $tag_array[ ] = $wpdb->get_var( $wpdb->prepare( "SELECT x.term_id FROM $wpdb->terms t
                    JOIN $wpdb->term_taxonomy x ON t.term_id=x.term_id WHERE x.taxonomy='post_tag' AND t.slug=%s", $e ) );
            }
            $_tmp = array_unique( $tag_array );
            if ( count( $_tmp ) == 1 && empty( $_tmp[ 0 ] ) ) {
                $tag_array = array();
            }
        }
        // tag_id
        if ( isset( $q->query_vars[ 'tag_id' ] ) && !empty( $q->query_vars[ 'tag_id' ] ) ) {
            $tag_array = array_map( 'trim', explode( ',', $q->query_vars[ 'tag_id' ] ) );
        }

        // tag__and
        if ( isset( $q->query_vars[ 'tag__and' ] ) && !empty( $q->query_vars[ 'tag__and' ] ) ) {
            $tag_array = $q->query_vars[ 'tag__and' ];
        }
        // tag__in
        if ( isset( $q->query_vars[ 'tag__in' ] ) && !empty( $q->query_vars[ 'tag__in' ] ) ) {
            $tag_array = $q->query_vars[ 'tag__in' ];
        }
        // tag__not_in
        if ( isset( $q->query_vars[ 'tag__not_in' ] ) && !empty( $q->query_vars[ 'tag__not_in' ] ) ) {
            $tag_array = $q->query_vars[ 'tag__not_in' ];
        }
        // tag_slug__in
        if ( isset( $q->query_vars[ 'tag_slug__in' ] ) && !empty( $q->query_vars[ 'tag_slug__in' ] ) ) {
            foreach ( $q->query_vars[ 'tag_slug__in' ] as $t ) {
                if ( $tg = $wpdb->get_var( $wpdb->prepare( "
                            SELECT x.term_id FROM $wpdb->terms t
                            JOIN $wpdb->term_taxonomy x ON t.term_id=x.term_id
                            WHERE x.taxonomy='post_tag' AND t.slug=%s", $t ) )
                ) {
                    $tag_array[ ] = $tg;
                }
            }
        }

        // tag_slug__and
        if ( isset( $q->query_vars[ 'tag_slug__and' ] ) && !empty( $q->query_vars[ 'tag_slug__and' ] ) ) {
            foreach ( $q->query_vars[ 'tag_slug__and' ] as $t ) {
                $tag_array[ ] = $wpdb->get_var( $wpdb->prepare( "SELECT x.term_id FROM $wpdb->terms t
                    JOIN $wpdb->term_taxonomy x ON t.term_id=x.term_id WHERE x.taxonomy='post_tag' AND t.slug=%s", $t ) );
            }
        }

        if ( !empty( $tag_array ) ) {
            $translated_ids = array();
            foreach ( $tag_array as $c ) {
                if ( intval( $c ) < 0 ) {
                    $sign = -1;
                } else {
                    $sign = 1;
                }
                $_tid              = intval( icl_object_id( abs( $c ), 'post_tag', true ) );
                $translated_ids[ ] = $sign * $_tid;
            }
        }


        if ( !empty( $translated_ids ) ) {
            //tag
            if ( isset( $q->query_vars[ 'tag' ] ) && $q->query_vars[ 'tag' ] !== "" ) {
                $slugs                  = $wpdb->get_col( "SELECT slug
                                                           FROM $wpdb->terms
                                                           WHERE term_id IN (" . wpml_prepare_in($translated_ids, '%d') . ")" );
                $q->query_vars[ 'tag' ] = join( $tag_glue, $slugs );
            }
            //tag_id
            if ( isset( $q->query_vars[ 'tag_id' ] ) && !empty( $q->query_vars[ 'tag_id' ] ) ) {
                $q->query_vars[ 'tag_id' ] = join( ',', $translated_ids );
            }
            // tag__and
            if ( isset( $q->query_vars[ 'tag__and' ] ) && !empty( $q->query_vars[ 'tag__and' ] ) ) {
                $q->query_vars[ 'tag__and' ] = $translated_ids;
            }
            // tag__in
            if ( isset( $q->query_vars[ 'tag__in' ] ) && !empty( $q->query_vars[ 'tag__in' ] ) ) {
                $q->query_vars[ 'tag__in' ] = $translated_ids;
            }
            // tag__not_in
            if ( isset( $q->query_vars[ 'tag__not_in' ] ) && !empty( $q->query_vars[ 'tag__not_in' ] ) ) {
                $q->query_vars[ 'tag__not_in' ] = array_map( 'abs', $translated_ids );
            }
            // tag_slug__in
            if ( isset( $q->query_vars[ 'tag_slug__in' ] ) && !empty( $q->query_vars[ 'tag_slug__in' ] ) ) {
                $q->query_vars[ 'tag_slug__in' ] = $wpdb->get_col( "SELECT slug
                                                           FROM $wpdb->terms
                                                           WHERE term_id IN (" . wpml_prepare_in($translated_ids, '%d') . ")" );
            }
            // tag_slug__and
            if ( isset( $q->query_vars[ 'tag_slug__and' ] ) && !empty( $q->query_vars[ 'tag_slug__and' ] ) ) {
                $q->query_vars[ 'tag_slug__and' ] = $wpdb->get_col( "SELECT slug
                                                           FROM $wpdb->terms
                                                           WHERE term_id IN (" . wpml_prepare_in($translated_ids, '%d') . ")" );
            }
        }

        // POST & PAGES
        $post_type = !empty( $q->query_vars[ 'post_type' ] ) ? $q->query_vars[ 'post_type' ] : 'post';
        if(!is_array($post_type)) {
            $post_type = (array)$post_type;
        }

        // page_id
        if ( isset( $q->query_vars[ 'page_id' ] ) && !empty( $q->query_vars[ 'page_id' ] ) ) {
            $q->query_vars[ 'page_id' ] = icl_object_id( $q->query_vars[ 'page_id' ], 'page', true );
            $q->query                   = preg_replace( '/page_id=[0-9]+/', 'page_id=' . $q->query_vars[ 'page_id' ], $q->query );
        }

        // Adjust included IDs adjusting them with translated element, if present
        if ( isset( $q->query_vars[ 'include' ] ) && !empty( $q->query_vars[ 'include' ] ) ) {
            $include_arr          = is_array( $q->query_vars[ 'include' ] ) ? $q->query_vars[ 'include' ] : explode( ',', $q->query_vars[ 'include' ] );
            $include_arr_adjusted = array();
            foreach ( $include_arr as $include_arr_id ) {
                $include_arr_adjusted[ ] = icl_object_id( $include_arr_id, get_post_type($include_arr_id), true );
            }
            $q->query_vars[ 'include' ] = is_array( $q->query_vars[ 'include' ] ) ? $include_arr_adjusted : implode( ',', $include_arr_adjusted );
        }

        // Adjust excluded IDs adjusting them with translated element, if present
        if ( isset( $q->query_vars[ 'exclude' ] ) && !empty( $q->query_vars[ 'exclude' ] ) ) {
            $exclude_arr          = is_array( $q->query_vars[ 'exclude' ] ) ? $q->query_vars[ 'exclude' ] : explode( ',', $q->query_vars[ 'exclude' ] );
            $exclude_arr_adjusted = array();
            foreach ( $exclude_arr as $exclude_arr_id ) {
                $exclude_arr_adjusted[ ] = icl_object_id( $exclude_arr_id, get_post_type($exclude_arr_id), true );
            }
            $q->query_vars[ 'exclude' ] = is_array( $q->query_vars[ 'exclude' ] ) ? $exclude_arr_adjusted : implode( ',',  $exclude_arr_adjusted );
        }

        // Adjust post id
        if ( isset( $q->query_vars[ 'p' ] ) && !empty( $q->query_vars[ 'p' ] ) ) {
            $q->query_vars[ 'p' ] = icl_object_id( $q->query_vars[ 'p' ], $post_type[0], true );
        }

        // Adjust name
        if ( $sitepress->is_translated_post_type($post_type[0]) && isset( $q->query_vars[ 'name' ] ) && !empty( $q->query_vars[ 'name' ] ) ) {
            $pid_prepared = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name=%s AND post_type=%s", array($q->query_vars[ 'name' ], $post_type[0]));
            $pid = $wpdb->get_var( $pid_prepared );
            if ( !empty( $pid ) ) {
                $q->query_vars[ 'p' ] = icl_object_id( $pid, $post_type[0], true );
                unset( $q->query_vars[ 'name' ] );
            }
        }

        // Adjust page name
        if ( isset( $q->query_vars[ 'pagename' ] ) && !empty( $q->query_vars[ 'pagename' ] ) ) {
            // find the page with the page name in the current language.
            $pid = $wpdb->get_var( $wpdb->prepare( "
                            SELECT ID
                            FROM $wpdb->posts p
                            JOIN {$wpdb->prefix}icl_translations t
                            ON p.ID = t.element_id AND element_type='post_page'
                            WHERE p.post_name=%s AND t.language_code = %s
                            ", $q->query_vars[ 'pagename' ], $current_language ) );

            if ( $pid ) {
                $q->query_vars[ 'page_id' ] = $pid;
                // We have found the page id
                unset( $q->query_vars[ 'pagename' ] );
                if ( $q->query_vars[ 'page_id' ] == get_option( 'page_for_posts' ) ) {
                    // it's the blog page.
                    $q->is_page       = false; // $wp_query->is_page       = false;
                    $q->is_home       = true; // $wp_query->is_home       = true;
                    $q->is_posts_page = true; // $wp_query->is_posts_page = true;
                }
            }
        }
        // post__in
        if ( isset( $q->query_vars[ 'post__in' ] ) && !empty( $q->query_vars[ 'post__in' ] ) ) {
            $pid = array();
            foreach ( $q->query_vars[ 'post__in' ] as $p ) {
                if ( $post_type ) {
                    foreach ( $post_type as $pt ) {
                        $pid[ ] = icl_object_id( $p, $pt, true );
                    }
                }
            }
            $q->query_vars[ 'post__in' ] = $pid;
        }
        // post__not_in
        if ( isset( $q->query_vars[ 'post__not_in' ] ) && !empty( $q->query_vars[ 'post__not_in' ] ) ) {
            $pid = array();
            foreach ( $q->query_vars[ 'post__not_in' ] as $p ) {
                if ( $post_type ) {
                    foreach ( $post_type as $pt ) {
                        $pid[ ] = icl_object_id( $p, $pt, true );
                    }
                }
            }
            $q->query_vars[ 'post__not_in' ] = $pid;
        }
        // post_parent
        if ( isset( $q->query_vars[ 'post_parent' ] ) && !empty( $q->query_vars[ 'post_parent' ] ) && $q->query_vars[ 'post_type' ] != 'attachment' ) {
            if (  $post_type ) {
                $_parent_type                   = $wpdb->get_var( $wpdb->prepare( "SELECT post_type FROM {$wpdb->posts} WHERE ID=%d", $q->query_vars[ 'post_parent' ] ) );
                $q->query_vars[ 'post_parent' ] = icl_object_id( $q->query_vars[ 'post_parent' ], $_parent_type, true );
            }
        }

        // custom taxonomies
        if ( isset( $q->query_vars[ 'taxonomy' ] ) && $q->query_vars[ 'taxonomy' ] ) {
            $tax_id = $wpdb->get_var( $wpdb->prepare( "SELECT term_id FROM {$wpdb->terms} WHERE slug=%s", $q->query_vars[ 'term' ] ) );
            if ( $tax_id ) {
                $translated_tax_id = icl_object_id( $tax_id, $q->query_vars[ 'taxonomy' ], true );
            }
            if ( isset( $translated_tax_id ) ) {
                $q->query_vars[ 'term' ]                  = $wpdb->get_var( $wpdb->prepare( "SELECT slug FROM {$wpdb->terms} WHERE term_id = %d", $translated_tax_id ) );
                $q->query[ $q->query_vars[ 'taxonomy' ] ] = $q->query_vars[ 'term' ];
            }
        }

        //TODO: [WPML 3.3] Discuss this. Why WP assumes it's there if query vars are altered? Look at wp-includes/query.php line #2468 search: if ( $this->query_vars_changed ) {
        if ( !isset( $q->query_vars[ 'meta_query' ] ) ) {
            $q->query_vars[ 'meta_query' ] = array();
        }

        if ( isset( $q->query_vars[ 'tax_query' ] ) && is_array( $q->query_vars[ 'tax_query' ] ) ) {
            foreach ( $q->query[ 'tax_query' ] as $num => $fields ) {

                if ( ! isset( $fields[ 'terms' ] ) ) {
                    continue;
                }

                if ( is_array( $fields[ 'terms' ] ) ) {

                    foreach ( $fields[ 'terms' ] as $term ) {
                        $taxonomy = get_term_by( $fields[ 'field' ], $term, $fields[ 'taxonomy' ] );
                        if ( is_object( $taxonomy ) ) {
                            if ( $fields[ 'field' ] == 'id' ) {
                                $field = isset($taxonomy->term_id) ? $taxonomy->term_id : null;
                            } else {
                                $field = isset($taxonomy->{$fields[ 'field' ]}) ? $taxonomy->{$fields[ 'field' ]} : null;
                            }

                            $tmp    = $q->query[ 'tax_query' ][ $num ][ 'terms' ];
                            $tmp    = array_diff( (array)$tmp, array( $term ) ); // removes from array element with original value
                            $tmp[ ] = $field;
                            //Reindex array
                            $q->query[ 'tax_query' ][ $num ][ 'terms' ] = array_values( $tmp );

                            if (isset($q->tax_query->queries[ $num ][ 'terms' ])) {
                                $tmp    = $q->tax_query->queries[ $num ][ 'terms' ];
                            } else {
                                $tmp = array(); // clean $tmp variable
                            }
                            $tmp    = array_diff( (array)$tmp, array( $term ) ); // see above
                            $tmp[ ] = $field;
                            //Reindex array
                            $q->tax_query->queries[ $num ][ 'terms' ] = array_values( $tmp );

                            $tmp    = $q->query_vars[ 'tax_query' ][ $num ][ 'terms' ];
                            $tmp    = array_diff( (array)$tmp, array( $term ) ); // see above
                            $tmp[ ] = $field;
                            //Reindex array
                            $q->query_vars[ 'tax_query' ][ $num ][ 'terms' ] = array_values( $tmp );

                            unset( $tmp );
                        }
                    }
                } else if ( is_string( $fields[ 'terms' ] ) ) {
                    $taxonomy = get_term_by( $fields[ 'field' ], $fields[ 'terms' ], $fields[ 'taxonomy' ] );
                    if ( is_object( $taxonomy ) ) {

                        $field = isset($taxonomy->{$fields[ 'field' ]}) ? $taxonomy->{$fields[ 'field' ]} : null;

                        $q->query[ 'tax_query' ][ $num ][ 'terms' ] = $field;

                        $q->tax_query->queries[ $num ][ 'terms' ][ 0 ] = $field;

                        $q->query_vars[ 'tax_query' ][ $num ][ 'terms' ] = $field;
                    }
                }
            }
        }
    }

    return $q;
}
