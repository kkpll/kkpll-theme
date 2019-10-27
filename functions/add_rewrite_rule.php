<?php

/*
 *
 * リライトルール追加
 *
 */

// function add_seminar_query_vars($vars){
// 	$vars[] = 'seminar_status';
//     $vars[] = 'field';
//     $vars[] = 'industry';
// 	return $vars;
// }
//
// add_filter( 'query_vars', 'add_seminar_query_vars');
//
// add_action( 'init', function(){
// 	//開催予定セミナー一覧
// 	add_rewrite_rule( "^seminar/status/open/?$", "index.php?post_type=seminar&seminar_status=open", 'top' );
//     add_rewrite_rule('^seminar/status/open/page/([0-9]{1,})/?$','index.php?post_type=seminar&seminar_status=open&paged=$matches[1]','top');
// 	//開催終了セミナー一覧
// 	add_rewrite_rule( "^seminar/status/close/?$", "index.php?post_type=seminar&seminar_status=close", 'top' );
//     add_rewrite_rule('^seminar/status/close/page/([0-9]{1,})/?$','index.php?post_type=seminar&seminar_status=close&paged=$matches[1]','top');
//     //解決事例・相談事例一覧
//     add_rewrite_rule( "^([^/]*)/(field|industry)/([^/]*)/?$", 'index.php?post_type=$matches[1]&$matches[2]=$matches[3]', 'top' );
//     add_rewrite_rule( "^([^/]*)/(field|industry)/([^/]*)/page/([0-9]{1,})/?$", 'index.php?post_type=$matches[1]&matches[2]=$matches[3]&paged=$matches[4]', 'top' );
//
// });
