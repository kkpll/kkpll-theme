<?php

/*
 *
 * 記事データをセットして返す
 *
 */

 function set_posts_data( &$posts=array(), $query=null ){

     if( !$query ){
         global $wp_query;
         $query = $wp_query;
     }

     if( $query->have_posts() ){

         while( $query->have_posts() ){
             $query->the_post();

             $data = get_the_post_data();

             array_push( $posts, $data );

         }

         if( !$query->is_main_query() ){
             wp_reset_postdata();
         }
     }

     return $posts;

 }




/*
 *
 * 記事データ取得
 *
 */

function get_the_post_data( $post_id = NULL ){

    $return = array();

    global $post;

    $id = $post_id ? $post_id : $post->ID;

    $return['id']             = $id;
    $return['post_type']      = $post->post_type;
    $return['post_type_name'] = get_post_type_object( $return['post_type'] )->labels->singular_name;
    $return['permalink']      = get_the_permalink( $id );
    $return['title']          = get_the_title( $id );
    $return['content']        = get_the_content( $id );
    $return['thumbnail']      = has_post_thumbnail( $id ) ? get_the_post_thumbnail_url( $id, 'medium' ) : NULL;
    $return['date']           = get_the_date( get_option( 'date_format' ), $id ); //日付
    $return['excerpt']        = $post->post_excerpt; //抜粋
    $return['custom_field']   = get_post_meta( $id );
    $return['category']       = array();
    $return['tag']            = array();

    if( $taxs = get_post_taxonomies( $id ) ){
        foreach((array)$taxs as $tax){
            if($tax !== "post_format"){
                $taxonomy = get_taxonomy($tax);
                $taxonomy_type = $taxonomy->hierarchical ? 'category' : 'tag';
                $terms = get_the_terms($id, $tax);
                if( $terms ){
                    $return[$taxonomy_type][$tax] = array();
                    foreach ( (array)$terms as $term ){
                        array_push($return[$taxonomy_type][$tax], array('name' => $term->name, 'slug'=> $term->slug,'link' => get_term_link($term->term_id)));
                    }
                }
            }
        }
    }

    return $return;

}

/*
 *
 * 関連記事
 *
 */

function get_the_related_posts(){

    global $post;

    $return = array();

    if ( is_single() ){

        $post_type = get_post_type();
        $post_type_object = get_post_type_object($post_type);

        if($post_type == "post"){
            $categories = get_the_category();
            $title = $categories[0]->cat_name;
            $related_posts = get_posts(array('category__in' => array($categories[0]->cat_ID), 'exclude' => $post->ID, 'numberposts' => -1));
        }else{
            $title = esc_html($post_type_object->labels->singular_name);
            $related_posts = get_posts(array('post_type' => $post_type, 'exclude' => $post->ID ,'numberposts' => -1));
        }
        $excerpt = get_the_excerpt();

        if($related_posts){
            $return['title'] = $title;
            $return['posts'] = array();
            foreach($related_posts as $related_post){
                array_push($return['posts'],array('title'=>$related_post->post_title,'permalink'=>get_permalink($related_post->ID),'content'=>$related_post->post_content,'excerpt'=>$related_post->post_excerpt));
            }
        }

    }elseif( is_page() ){

        $related_query = new WP_Query();
        $args = $related_query->query(array(
            'post_type' => 'page',
            'nopaging'  => 'true'
        ));

        if($parent_id = $post->post_parent ){
            $target = $parent_id;
            $title = get_post($parent_id)->post_title;
        }else{
            $target = $post->ID;
            $title = $post->post_title;
        }
        $excerpt = $post->post_excerpt;

        $related_posts = get_page_children($target, $args);

        if($related_posts) {
            $return['title'] = $title;
            $return['posts'] = array();
            foreach ($related_posts as $related_post) {
                array_push($return['posts'],array('title'=>$related_post->post_title,'permalink'=>get_permalink($related_post->ID),'content'=>$related_post->post_content,'excerpt'=>$related_post->post_excerpt));
            }
        }
    }

    return $return;
}
