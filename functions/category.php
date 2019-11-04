<?php

class MyTerms{

    public $taxs = array();

    public $post_id = '';

    public $tax_terms;

    public $post_terms;


    function __construct( $taxs = array(), $post_id = NULL ){

        $this->taxs = $taxs;

        $this->post_id = $post_id;

    }


    public function createTermList(){

        $term_ID_arr = array();

        foreach($this->post_terms as $term){
            $term_ID_arr[] = $term->term_id;
        }

        // $this->scanArray( $this->tax_terms, function( $key, $value ) use ( $term_ID_arr ){
        //
        //     if( in_array( $key, $term_ID_arr ) ){
        //
        //         $value = "ここです";
        //     }
        //
        // });

        $a = array_filter($this->tax_terms,function($key,$value){
            var_dump( $key );

        });






    }


    public function registerTaxs( $taxs ){

        if( is_array( $taxs ) || is_string( $taxs ) ){

            $this->$taxs = $taxs;

        }

    }

    public function registerPostId( $post_id ){

        $this->post_id = $post_id;

    }


    public function getTaxTerms( $taxs = array() ){

        $this->tax_terms = array();

        $taxs = $taxs ? $taxs : $this->taxs;

        $tax_terms = get_terms( $this->taxs, array( 'parent' => 0, 'hide_empty' => false ) );

        foreach( $tax_terms as $term ){

            $this->get_term_children_loop( $term->term_id, $taxs, $this->tax_terms );

        }

        return $this->tax_terms;

    }


    public function getPostTerms( $post_id = NULL, $tax = NULL ){

        $post_id = $post_id ? $post_id : $this->post_id ;

        if( !$tax ){

            $tax = is_array( $this->taxs ) ? $this->taxs[0] : $this->taxs ;

        }

        $this->post_terms = get_the_terms( $post_id, $tax );

        return $this->post_terms;

    }

    public function scanArray( &$array, $callback ){

        foreach( (array)$array as $key => $value ){

            if ( is_callable( $callback ) ) {

                call_user_func( $callback, $key, $value );

            }

            if( is_array( $value ) ){

                $this->scanArray( $value, $callback );

            }

        }

    }

     private function get_term_children_loop( $term_id, $taxonomy, &$args ){

         $terms = get_term_children( $term_id, $taxonomy );

         if( $terms ){

             $args[$term_id] = array();

             foreach( $terms as $term ){

                 $term = get_term_by( 'id', $term, $taxonomy );

                 if( $term->parent === $term_id ){

                      $args[$term_id][$term->term_id] = array();

                      $this->get_term_children_loop( $term->term_id, $taxonomy,  $args[$term_id] );

                 }

             }

         }else{

             $args[$term_id] = NULL;

         }

     }

 }
