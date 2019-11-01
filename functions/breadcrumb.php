<?php

class CustomBreadcrumb {

    public $queried;
    public $breadcrumb = array();

    function __construct(){

        $this->queried = get_queried_object();

        $this->addBreadcrumb( get_bloginfo('name'), home_url('/') );

    }


    /*
    *
    * カスタム投稿
    *
    */

    public function getPostTypePageInfo(){

        $this->addBreadcrumb( $this->queried->label );

    }


    /*
    *
    * シングルページ
    *
    */

    private function __loop( $term_id, $taxonomy, &$args ){

        if( $terms = get_term_children( $term_id, $taxonomy ) ){

            //$args[$term_id] = array();

            foreach( $terms as $term ){

                $args[$term_id][$term] = NULL;

                $this->__loop( $term, $taxonomy, $args[$term_id] );

            }

        }

    }


    public function getSinglePageInfo(){

        $this->addPostTypeBreadCrumb( $this->queried->post_type );

        $args = array();

        $post_id = $this->queried->ID;

        $taxs = get_post_taxonomies( $post_id );

        $terms = get_terms( $taxs, array( 'parent' => 0 ) );

        $post_terms             = array();
        $post_terms['category'] = array();
        $post_terms['tag']      = array();

        foreach( $terms as $term ){

            if( $terms = get_the_terms( $post_id, $term->taxonomy ) ){

                foreach( $terms as $term){

                    $taxonomy = get_taxonomy( $term->taxonomy );

                    if( $taxonomy->hierarchical ){

                        $parents = $this->getParents( $term->term_id, $term->taxonomy );
                        //array_push( $parents, $term->term_id );

                        $parents = $parents ? array( $parents[0] => $parents ) : '' ;

                        array_push( $post_terms['category'], array(
                            'id'      => $term->term_id,
                            'text'    => $term->name,
                            'parents' => $parents,
                        ));

                    }else{

                        array_push( $post_terms['tag'], $term->name );

                    }

                }

            }

        }

        //var_dump($post_terms);

        $parents = array();

        foreach( $post_terms['category'] as $category ){

            if( is_array( $category['parents'] ) ){

                $key = array_keys( $category['parents'] )[0];

                $parents[$key] = $category['parents'][$key];

            }

        }

        var_dump($parents);






        //
        //
        // foreach( $terms as $term ){
        //
        //     $taxonomy = get_taxonomy( $term->taxonomy );
        //
        //     if( $taxonomy->hierarchical ){
        //
        //         $this->__loop( $term->term_id, $term->taxonomy, $args );
        //
        //     }
        //
        // }




        // $result   = ['ここに入る'];
        // $search   = 4;
        // $iterator = new RecursiveIteratorIterator(
        //     new RecursiveArrayIterator(
        //         $args,
        //         RecursiveIteratorIterator::CHILD_FIRST
        //     )
        // );
        //
        // foreach($iterator as $key=>$value){
        //    if($search==$key)
        //    {
        //       $result[] = $key;
        //    }
        // }
        //
        //var_dump($result);







        // $terms = wp_get_post_terms( $post_id, $taxs );
        //
        // $checks = array();
        //
        // foreach($terms as $term){
        //     array_push($checks,$term->term_id);
        // }
        //
        // var_dump($args);

        $this->addBreadcrumb( $this->queried->post_title );

    }







    /*
    *
    * カテゴリー・タクソノミー
    *
    */

    public function getTaxPageInfo(){

        $taxonomy_name = $this->queried->taxonomy;

        $taxonomy = get_taxonomy( $taxonomy_name );
        $registrated_post_type = $taxonomy->object_type[0];
        $this->addPostTypeBreadCrumb( $registrated_post_type );

        $is_category = $taxonomy->hierarchical ? true : false ;

        if( $is_category ){

            $terms = $this->getParents( $this->queried->term_id, $taxonomy_name );

            foreach( $terms as $term ){

                $term = get_term_by( 'id', $term, $taxonomy_name );
                $this->addBreadcrumb( $term->name, get_term_link( $term->term_id ) );

            }

        }

        $this->addBreadcrumb( $this->queried->name );

    }


    /*
    *
    * タグ
    *
    */

    public function getTagPageInfo(){

        $taxonomy = get_taxonomy( $this->queried->taxonomy );
        $registrated_post_type = $taxonomy->object_type[0];
        $this->addPostTypeBreadCrumb( $registrated_post_type );

        $this->addBreadcrumb( $this->queried->name );

    }


    /*
    *
    * 固定
    *
    */

    public function getPagePageInfo(){

        $pages = $this->getParents( $this->queried->ID, 'page' );

        foreach( $pages as $page ){

            $page = get_post( $page );
            $this->addBreadcrumb( $page->post_title, get_the_permalink( $page ) );

        }

        $this->addBreadcrumb( $this->queried->post_title );

    }


    /*
    *
    * パンくずリスト出力
    *
    */

    public function generateHtml() {

    }



    /*
    *
    * 以下プライベート関数
    *
    */

    private function addBreadcrumb( $text = '', $link = '' ){

        array_push( $this->breadcrumb, array(
            'text' => $text,
            'link' => $link,
        ));

    }

    private function addPostTypeBreadCrumb( $post_type ){

        $post_type = $post_type ? $post_type : $queried->post_type;

        if( $post_type !== 'post' ){

            $this->addBreadcrumb( get_post_type_object( $post_type )->labels->singular_name, get_post_type_archive_link( $post_type ) );

        }

    }

    private function getParents( $term_id, $taxonomy_or_page ){

        $terms = get_ancestors( $term_id, $taxonomy_or_page );
        return array_reverse( $terms );

    }


}
