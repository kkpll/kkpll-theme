<?php

/*
 *
 * 記事データ取得後の処理
 *
 */

add_action( 'template_redirect', 'my_template_redirect' );
function my_template_redirect() {

    $template   = '';

    $title      = '';
    $h2_title   = '';

	//記事取得
	$query = null;
    $posts = array();
	$posts = set_posts_data( $posts, $query );

    //パンくずリスト
	$breadcrumb = new Breadcrumb();
	$queried = $breadcrumb->queried;

	if ( is_home() ) {

        $template = 'index';
        $title = 'トップページ';

	} else if ( is_category() ) {

		$template = 'archive';

		$breadcrumb->taxArchive();

        $title = $queried->name;

	} else if ( is_tag() ){

		$template = 'archive';

		$breadcrumb->tagArchive();

        $title = $queried->name;

	} else if( is_tax() ){

		$template = 'archive';

		$breadcrumb->taxArchive();

        $title = $queried->name;

	} else if ( is_post_type_archive() ) {

		$template = 'archive';

		$breadcrumb->postTypeArchive();

        $title = $queried->label;

	} else if ( is_single() ) {

		$template = 'single';

		$breadcrumb->single();

		$title = $queried->post_title;
		$h2_title = $queried->post_title;
		$related_posts = relatedPosts('category');

	} else if ( is_page() ) {

		$template = 'page';

		$breadcrumb->page();

        $title = $queried->post_title;
        $h2_title = $queried->post_title;

	} else if ( is_search() ) {

    } else if ( is_404() ) {

	    $template = '404';
		$breadcrumb->notFound();

	} else if ( is_author() || is_date() ) {
	    exit();
	}

	if(!$template){
		$template = '404';
	}

	if(!$query){
		global $wp_query;
		$query = $wp_query;
	}

	global $twig;
	echo $twig->render( $template.'.html', array(
		'breadcrumb'=> $breadcrumb->echo(),
		'head_title'=> $title,
		'h2_title'  => $h2_title,
		'posts'     => $posts,
		'query'     => $query,
		'related_posts' => isset($related_posts) ? $related_posts : null,
		)
	);

	exit();


}
