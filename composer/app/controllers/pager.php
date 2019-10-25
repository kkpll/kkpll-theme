<?php

namespace App\Controllers;

class Pager extends Base{

    public function pagination( $query ){

        global $wp_query;

        $current_query = $query ? $query : $wp_query ;

        $big = 999999999;

        $args =	array(
            'type' => 'array',
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'current' => max( 1, get_query_var('paged') ),
            'total' => $current_query->max_num_pages,
            'prev_text' => '< 前のページ',
            'next_text' => '次のページ >'
        );

        $pager = paginate_links( $args );

        echo "<ul class='pager'>";

        foreach( $pager as $page ){

            if ( strpos( $page, 'next' ) != false ){

                echo "<li class='next'>" . $page . "</li>";

            } elseif ( strpos( $page, 'prev' ) != false ){

                echo "<li class='prev'>" . $page . "</li>";

            } elseif ( strpos( $page, 'current' ) != false ){

                echo "<li class='current'>" . $page . "</li>";

            } elseif ( strpos( $page, 'dots' ) != false ){

                echo "<li class='dots'>" . $page . "</li>";

            } else {

                echo "<li>" . $page . "</li>";

            }

        }

        echo "</ul>";

    }

    public function pre_get_posts( $query ){

            if ( is_admin() ) {
                return;
            }

            $taxonomies = get_taxonomies(array('_builtin'=>false));
            $my_taxonomies = array();
            foreach($taxonomies as $taxonomy){
                array_push($my_taxonomies, $taxonomy);
            }

            $categories = get_categories();
            $my_categories = array();
            foreach($categories as $category){
                array_push($my_categories, $category->slug);
            }

            $posttypes = get_post_types(array('_builtin'=>false));
            $my_posttypes = array();
            foreach($posttypes as $posttype){
                array_push($my_posttypes, $posttype);
            }

            if( $query->is_category($my_categories) || $query->is_tax($my_taxonomies) || $query->is_post_type_archive($my_posttypes)){
                $query->set( 'posts_per_page', get_option('posts_per_page') );
            }

        }

    }

    public function enqueue_scripts(){
        wp_enqueue_style( 'pager.css', $this->plugin_url . 'assets/css/pager.css', array(), filemtime( $this->plugin_path . 'assets/css/pager.css' ));
    }





}
