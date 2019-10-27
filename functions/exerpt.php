<?php

add_post_type_support( 'page', 'excerpt' );

function my_excerpt_more($post) {
    return '';
}
add_filter('excerpt_more', 'my_excerpt_more');

function my_excerpt_length( $length ) {
  return 100;
}
add_filter( 'excerpt_length', 'my_excerpt_length', 999 );
