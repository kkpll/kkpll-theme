<?php


/*
 *
 * 投稿スラッグ自動ID化
 *
 */

function auto_post_slug( $slug, $post_ID, $post_status, $post_type ){
    $slug = ($post_type!=="page" && ( $post_type=="post" || $post_type=="column" || $post_type=="seminar" || $post_type=="case" || $post_type=="solution" || $post_type=="books")) ? $post_ID : $slug;
    return $slug;
}
add_filter( 'wp_unique_post_slug', 'auto_post_slug', 10, 4  );
