<?php

/*
 *
 * 再帰的に子カテゴリーを取得する
 *
 */

 class CustomCategory {

     public static function getAllChildren( $taxonomy ){

         $args = array();

         $terms = get_terms( $taxonomy, array( 'hide_empty' => 0, 'parent' => 0 ) );

         foreach( $terms as $term ){

             self::__get_term_children_loop( $term->term_id, $taxonomy, $args );

         }

         return $args;

     }

     public static function getParentTerms( $taxonomy, $terms = array() ){

         $args = array();

         $terms = get_terms( $taxonomy, array( 'parent' => 0 ) );

         foreach( $terms as $term ){

             self::__get_term_children_loop( $term->term_id, $taxonomy, $args );

         }

         return $args;

     }

     private function __get_term_children_loop( $term_id, $taxonomy, &$args ){

         $terms = get_term_children( $term_id, $taxonomy );

         if($terms){

             $args[$term_id] = array();

             foreach($terms as $term){

                 $args[$term_id][$term] = NULL;

                 self::__get_term_children_loop( $term, $taxonomy, $args[$term_id] );

             }

         }else{

             $args[$term_id] = NULL;

         }

     }

     private function __check_term_loop( $term_id, $taxonomy, &$args, $check_terms ){

         $terms = get_term_children( $term_id, $taxonomy );

         if($terms){

             $args[$term_id] = array();

             foreach($terms as $term){

                 $args[$term_id][$term] = NULL;

                 foreach( $check_terms as $ct ){
                     if( $ct === $term ){


                         break;

                     }
                 }

                 self::__get_term_children_loop( $term, $taxonomy, $args[$term_id] );

             }

         }else{

             $args[$term_id] = NULL;

         }

     }


 }
