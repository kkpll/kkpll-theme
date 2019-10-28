<?php

/*
 *
 * 記事データ取得後の処理
 *
 */

add_action( 'template_redirect', 'my_template_redirect' );
function my_template_redirect() {

	$queried = get_queried_object();

    $template   = 'index';

    $title      = '';
    $h1_title   = '';
    $h2_title   = '';

    $posts      = array();
	$posts      = set_posts_data($posts);

	$query      = null; //ページ送り用

    //パンくずリスト
    $breadcrumb = array();
    array_push( $breadcrumb, array( 'name' => 'HOME', 'link' => home_url()) ); //トップページ

	if ( is_home() ) {

        $template = 'index';
        $title = 'トップページ';


	} else if ( is_category() || is_tag() || is_tax() ) {

        $taxonomy = get_taxonomy( $queried->taxonomy );
        $registrated_post_type = $taxonomy->object_type[0];
        if( $registrated_post_type !== 'post' ){
            array_push( $breadcrumb, array( 'name' => get_post_type_object( $registrated_post_type )->labels->singular_name, 'link' => home_url('/' . $registrated_post_type . '/')) );
        }

        array_push( $breadcrumb, array( 'name' => $queried->name."一覧" ) );

        $template = 'archive';

        $title = $queried->name;
        $h1_title = $queried->name;

        global $wp_query;
        $query = $wp_query;


    } else if ( is_post_type_archive() ) {

        $template = 'archive';

        $title = $queried->label;
        $h1_title = $queried->label;

        array_push( $breadcrumb, array( 'name' => $queried->label."一覧" ) );

	} else if ( is_single() ) {

        $post_type = $queried->post_type;
        if( $post_type !=='post' ){
            array_push( $breadcrumb, array( 'name' => get_post_type_object( $post_type )->labels->singular_name, 'link' => get_post_type_archive_link($post_type) ) );
        }
        array_push( $breadcrumb, array( 'name' => $queried->post_title ) );

	} else if ( is_page() ) {

        $template = 'index';

        $title = $queried->post_title;
        $h1_title = '固定ページ';
        $h2_title = $queried->post_title;

	} else if ( is_search() ) {

    } else if ( is_404() ) {

	    $template = '404';
        $title = '記事が見つかりませんでした';

	} else if ( is_author() || is_date() ) {

	    exit();

	}

    //テンプレートにデータを渡して書き出す
    global $twig;
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
