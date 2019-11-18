<?php

class SlideshowCallback {

    static function render_page(){
        global $twig;
        echo $twig->render( 'admin/slideshow_page.html' );
    }

    static function render_section(){
        global $twig;
        echo $twig->render( 'admin/slideshow_section.html' );
    }

    static function render_field(){

        //delete_option('fnsk_slideshow');

        $slideshows = get_option( 'fnsk_slideshow' );

        $args = array( 'public' => true, '_builtin' => false );
        $post_types = get_post_types( $args );
        array_unshift( $post_types, 'post');

        $pts = array();

        foreach( $post_types as $post_type ){

            $obj = get_post_type_object($post_type);

            $terms = array();

            if( $post_type=='post' ){
                $terms = get_terms(array('post_tag','category'),array('hide_empty'=>false));
            }

            if( $taxonomies = $obj->taxonomies ){

                $tmp = get_terms( $taxonomies, array( 'hide_empty'=>false ) );

                if($terms){
                    array_merge( $terms, $tmp );
                }else{
                    $terms = $tmp;
                }
            }

            $pts[] = array(
                'slug'  => $post_type,
                'label' => $obj->labels->singular_name,
                'terms' => $terms,
            );

        }

        global $twig;
        echo $twig->render( 'admin/slideshow_field.html', array( 'slideshows' => $slideshows, 'post_types' => $pts ));
    }

    static function sanitize($input){
        return $input;
    }


}
