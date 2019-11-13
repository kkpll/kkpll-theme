<?php

//WORDPRESSのバージョンを非表示
remove_action( 'wp_head', 'wp_generator' );

//ユーザー名を見せない
add_filter( 'author_rewrite_rules', '__return_empty_array' );
add_action( 'init', 'disable_author_archive' );
function disable_author_archive() {
    if( $_GET['author'] || preg_match('#/author/.+#', $_SERVER['REQUEST_URI']) ){
        wp_redirect(home_url());
        exit;
    }
}
