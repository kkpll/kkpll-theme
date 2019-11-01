<?php

/*
 *
 * 記事データ取得後の処理
 *
 */

add_action( 'template_redirect', 'my_template_redirect' );
function my_template_redirect() {

	$queried = get_queried_object();

    $template   = '';

    $title      = '';
    $h1_title   = '';
    $h2_title   = '';

    $posts      = array();
	$posts      = set_posts_data($posts);

	$query      = null; //ページ送り用

    //パンくずリスト
    $breadcrumb = array();
    array_push( $breadcrumb, array( 'name' => 'HOME', 'link' => home_url()) ); //トップページ

	$twig = Template::register();

	$breadcrumb = new CustomBreadcrumb();

	if ( is_home() ) {

        $template = 'index';
        $title = 'トップページ';


	} else if ( is_category() ) {

		$breadcrumb->getTaxPageInfo();

        $template = 'archive';

        $title = $queried->name;
        $h1_title = $queried->name;

        global $wp_query;
        $query = $wp_query;

	} else if ( is_tag() ){

		$breadcrumb->getTagPageInfo();

		$template = 'archive';

        $title = $queried->name;
        $h1_title = $queried->name;

		global $wp_query;
        $query = $wp_query;

	} else if( is_tax() ){

		$breadcrumb->getTaxPageInfo();

        $template = 'archive';

        $title = $queried->name;
        $h1_title = $queried->name;

        global $wp_query;
        $query = $wp_query;

	} else if ( is_post_type_archive() ) {

		$breadcrumb->getPostTypePageInfo();

        $template = 'archive';

        $title = $queried->label;
        $h1_title = $queried->label;

	} else if ( is_single() ) {

		$breadcrumb->getSinglePageInfo();

		$template = 'index';

		$title = $queried->post_title;
		$h1_title = 'シングル';
		$h2_title = $queried->post_title;


	} else if ( is_page() ) {

		$breadcrumb->getPagePageInfo();

        $template = 'index';

        $title = $queried->post_title;
        $h1_title = '固定ページ';
        $h2_title = $queried->post_title;

	} else if ( is_search() ) {

    } else if ( is_404() ) {

	    $template = '404';

	} else if ( is_author() || is_date() ) {

	    exit();

	}

	//var_dump($breadcrumb->breadcrumb);

	$template = $template ? $template : '404';

	echo $twig->render( $template.'.html', array(
		'head_title'=> $title,
		'breadcrumb'=> $breadcrumb,
		'h1_title'  => $h1_title,
		'h2_title'  => $h2_title,
		'posts'     => $posts,
		'query'     => $query,
		)
	);

	exit();




}
