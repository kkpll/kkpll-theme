<?php
add_action( 'template_redirect', 'my_template_redirect' );
function my_template_redirect() {

	$queried = get_queried_object();
    // var_dump($queried);

    //テンプレートに渡す各種データ
    $template   = 'index';
    $head_title = '';
    $h1_title   = '';
    $h2_title   = '';
    $posts      = array();
    $query      = null;

    //パンくずリスト
    $breadcrumb = array();
    array_push( $breadcrumb, array( 'name' => 'HOME', 'link' => home_url()) );

	if ( is_home() ) {

        $template = 'index';
        $head_title = 'トップページ';
        $posts      = array(
                array('title' => '記事１', 'date' => '20190101'),
                array('title' => '記事２', 'date' => '20190102'),
        );

	} else if ( is_category() || is_tag() || is_tax() ) {

        //カスタム投稿タイプに登録されているタクソノミーだったらカスタム投稿一覧へのリンクをパンくずリストに入れる処理
        $taxonomy = get_taxonomy( $queried->taxonomy );
        $registrated_post_type = $taxonomy->object_type[0];
        if( $registrated_post_type !== 'post' ){
            array_push( $breadcrumb, array( 'name' => get_post_type_object( $registrated_post_type )->labels->singular_name, 'link' => home_url('/' . $registrated_post_type . '/')) );
        }

        array_push( $breadcrumb, array( 'name' => $queried->name."一覧" ) );

        $template = 'archive';
        $head_title = $queried->name;
        $h1_title = $queried->name;

        global $wp_query;
        $query = $wp_query;


    } else if ( is_post_type_archive() ) {

        $template = 'archive';
        $head_title = $queried->label;
        $h1_title = $queried->label;
        array_push( $breadcrumb, array( 'name' => $queried->label."一覧" ) );

	} else if ( is_single() ) {

        $post_type = $queried->post_type;
        if( $post_type !=='post' ){
            array_push( $breadcrumb, array( 'name' => get_post_type_object( $post_type )->labels->singular_name, 'link' => home_url('/' . $post_type . '/')) );
        }
        array_push( $breadcrumb, array( 'name' => $queried->post_title ) );

	} else if ( is_page() ) {
        $template = 'index';
        $head_title = $queried->post_title;
        $h1_title = '固定ページ';
        $h2_title = $queried->post_title;
    } else if ( is_search() ) {

    } else if ( is_404() ) {
        $template = '404';
        $head_title = '記事が見つかりませんでした';
    } else if ( is_author() || is_date() ) {
        exit();
    }

    //テンプレートにデータを渡して書き出す
    global $twig;
    echo $twig->render( $template.'.html', array(
        'head_title'=> $head_title,
        'h1_title'  => $h1_title,
        'h2_title'  => $h2_title,
        'posts'     => $posts,
        'query'     => $query,
        )
    );
    exit();

}

////記事表示数を変更するときは以下のように
// function my_home_category( $query ) {
//     if ( $query->is_post_type_archive( 'solution' ) ) {
//         $query->set( 'posts_per_page', '1' );
//     }
// }
// add_action( 'pre_get_posts', 'my_home_category' );