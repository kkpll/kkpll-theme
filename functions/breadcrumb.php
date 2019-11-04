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

    public function getSinglePageInfo(){

        $this->addPostTypeBreadCrumb( $this->queried->post_type );

        $post_id = $this->queried->ID;

        $taxs = get_post_taxonomies( $post_id );

        $mt = new MyTerms( 'product_category', $post_id );

        $terms = $mt->getTaxTerms();
        $terms = $mt->getPostTerms();

        $mt->createTermList();


        // $tax_terms = $mt->getTaxTerms();
        // $mt->scanArray( $tax_terms, function( $key, $value ) use ( $term_IDs ){
        //
        //     if( in_array( $key, $term_IDs ) ){
        //         echo $key;
        //         echo "<br><br>";
        //     }
        //
        // });










        // $taxs = get_post_taxonomies( $post_id );
        //
        // $post_terms = array( 'category' => array(), 'tag' => array() );
        //
        // foreach( $taxs as $tax ){
        //
        //     if( $terms = get_the_terms( $post_id, $tax ) ){
        //
        //         $post_terms['category'][$tax] = array();
        //
        //         foreach( $terms as $term ){
        //
        //             $taxonomy = get_taxonomy( $tax );
        //
        //             if( $taxonomy->hierarchical ){
        //
        //                 $parents = $this->getParents( $term->term_id, $term->taxonomy );
        //
        //                 array_push( $parents, $term->term_id );
        //
        //                 array_push( $post_terms['category'][$tax], array( 'id' => $term->term_id, 'text' => $term->name, 'parents' => $parents ) );
        //
        //             }else{
        //
        //                 array_push( $post_terms['tag'][$tax], $term->term_id );
        //
        //             }
        //
        //         }
        //
        //     }
        //
        // }
        //
        // $breadcrumb = array();
        //
        // foreach( $post_terms['category'] as $value ){
        //
        //     $prev = array();
        //
        //     foreach( $value as $next ){
        //
        //         if( !$prev ){
        //
        //             $prev = $next['parents'];
        //
        //             $breadcrumb[$next['text']] = $next['parents'];
        //
        //         }else if( $prev[0] === $next['parents'][0] ){
        //
        //             if( count( $prev ) <= count( $next['parents'] ) ){
        //
        //                 $breadcrumb[$next['text']] = $prev = $next['parents'];
        //
        //             }
        //
        //         }
        //
        //     }
        //
        // }
        //
        // var_dump( $breadcrumb );






        // foreach( $terms as $term ){
        //
        //     if( $terms = get_the_terms( $post_id, $term->taxonomy ) ){
        //
        //         foreach( $terms as $term){
        //
        //             $taxonomy = get_taxonomy( $term->taxonomy );
        //
        //             if( $taxonomy->hierarchical ){
        //
        //                 $parents = $this->getParents( $term->term_id, $term->taxonomy );
        //
        //                 $parents = $parents ? $parents : '' ;
        //
        //                 array_push( $post_terms['category'], array(
        //                     'id'      => $term->term_id,
        //                     'text'    => $term->name,
        //                     'parents' => $parents,
        //                 ));
        //
        //             }else{
        //
        //                 array_push( $post_terms['tag'], $term->name );
        //
        //             }
        //
        //         }
        //
        //     }
        //
        // }
        //
        // $parents = array();
        //
        // foreach( $post_terms['category'] as $category ){
        //
        //     if( is_array( $category['parents'] ) ){
        //
        //         $keys = array_keys( $parents );
        //         $parent = $category['parents'][0];
        //
        //         if( !$parents ){
        //
        //             $parents[$parent] = $category['parents'];
        //
        //         }else if( in_array( $parent, $keys ) ){
        //
        //             foreach( $parents[$parent] as $p ){
        //
        //                 if( count($p) < count( $category['parents'] ) ){
        //
        //                     $parents[$parent] = $category['parents'];
        //
        //                 }else if( count($p) === count( $category['parents'] ) ){
        //
        //                     $parents[$parent] = $category['parents'];
        //
        //                 }
        //
        //             }
        //
        //         }
        //
        //     }
        //
        // }
        //
        // var_dump($parents);







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
