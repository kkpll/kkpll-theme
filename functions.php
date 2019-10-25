<?php


require_once "composer/fnsk.php";


/*
 *
 * CSSとJAVASCRIPTファイルの読み込み
 *
 */

function load_css_and_js(){

    // //CSS
    wp_enqueue_style( 'style.css', get_stylesheet_uri() );
    // wp_enqueue_style( 'bootstrap.css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css' );
    // wp_enqueue_style( 'fontawesome.css', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
    // wp_enqueue_style( 'my-style.css', get_template_directory_uri() . '/assets/css/style.css', array('bootstrap.css','fontawesome.css'), filemtime(get_template_directory() . '/assets/css/style.css'));
    // wp_enqueue_style( 'o-1910.css', get_template_directory_uri() . '/assets/css/o-1910.css');

    //JAVASCRIPT
    global $wp_scripts;
    $jquery = $wp_scripts->registered['jquery-core'];
    $jq_ver = $jquery->ver;
    $jq_src = $jquery->src;
    wp_deregister_script( 'jquery' );
    wp_deregister_script( 'jquery-core' );
    wp_register_script( 'jquery', false, array('jquery-core'), $jq_ver, true );
    wp_register_script( 'jquery-core', $jq_src, array(), $jq_ver, true );
    // wp_enqueue_script('popper.min.js',"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js",array('jquery'),$jq_ver,true);
    // wp_enqueue_script('bootstrap.min.js',"https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js",array('popper.min.js'),$jq_ver,true);
    // wp_enqueue_script('sliderPro.min.js',get_template_directory_uri()."/assets/js/jquery.sliderPro.min.js",array('bootstrap.min.js'),$jq_ver,true);
    // wp_enqueue_script('grace.js',get_template_directory_uri()."/assets/js/grace.js",array('bootstrap.min.js'),filemtime(get_template_directory()."/assets/js/grace.js"),true);
    // wp_enqueue_script('sidemenu.js',get_template_directory_uri()."/assets/js/sidemenu.js",array('jquery'),filemtime(get_template_directory()."/assets/js/sidemenu.js"),true);

}
add_action( 'wp_enqueue_scripts', 'load_css_and_js' );


/*
 *
 * アイキャッチ設定
 *
 */

add_theme_support('post-thumbnails');
set_post_thumbnail_size( 400, 300, true );


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



/*
 *
 * カスタムメニュー設定
 *
 */

register_nav_menus( array(
    'global' => 'グローバルメニュー',
    // 'column' => '企業法務コラム',
    // 'contents' => 'コンテンツ',
    // 'footer1' => 'フッター①',
    // 'footer2' => 'フッター②',
    // 'footer3' => 'フッター③',
    // 'footer4' => 'フッター④',
    // 'footer5' => 'フッター⑤',
));


//グローバルメニュー
class GlobalMenuWalker extends Walker_Nav_Menu {

    function start_lvl( &$output, $depth = 0, $args = Array() ){$output .= "";}
    function end_lvl( &$output, $depth = 0, $args = Array() ) {$output .= "";}
    function start_el( &$output, $item, $depth = 0, $args = Array(), $id = 0 ) {
        if (in_array('menu-item-has-children', $item->classes)) {
            $indent = " ";
            $output .= "\n".'<li><span class="slide_menu_btn"></span>';
            $output .= "<a class='sub-a' href='".$item->url."'>".$item->title."</a>";
            $output .= "\n" . $indent . '<ul class="sub-menu">';
        } else {
            $output .= '<li>';
            $output .= "<a href='".$item->url."'>".$item->title."</a>";
        }
    }
    function end_el( &$output, $item, $depth = 0, $args = Array() ) {
        if (in_array('menu-item-has-children', $item->classes)) {
            $output .= "\n".'</li></ul></li>';
        }
        else {
            $output .= "\n".'</li>';
        }
    }

}


//フッターメニュー①～⑤
class FooterMenuWalker extends Walker_Nav_Menu {

    function start_lvl( &$output, $depth = 0, $args = Array() ){$output .= "<ul>";}
    function end_lvl( &$output, $depth = 0, $args = Array() ) {$output .= "</ul>";}
    function start_el( &$output, $item, $depth = 0, $args = Array(), $id = 0 ) {$output .= "<li>".$this->create_a_tag($item, $depth, $args);}
    function end_el( &$output, $item, $depth = 0, $args = Array(), $id = 0 ) {$output .= '</li>';}

    private function create_a_tag($item, $depth, $args) {
        $href = ! empty( $item->url ) ? ' href="'.esc_attr( $item->url) .'"' : '';
        $item_output = sprintf( '<a%1$s>%2$s</a>',
            $href,
            apply_filters( 'the_title', $item->title, $item->ID)
        );
        return apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

}

//サイドメニュー
class SideMenuWalker extends Walker_Nav_Menu {
    function start_lvl( &$output, $depth = 0, $args = Array() ){$output .= "";}
    function end_lvl( &$output, $depth = 0, $args = Array() ) {$output .= "";}
    function start_el( &$output, $item, $depth = 0, $args = Array(), $id = 0 ) {
        if (in_array('menu-item-has-children', $item->classes)) {
            $indent = " ";
            $output .= "\n".'<li class="label-blue parent">';
            $output .= $this->create_a_tag($item, $depth, $args);
            $output .= "\n" . $indent . '<ul class="sub-menu">';
        } else {
            $output .= '<li class="label-blue child-'.$depth.'">';
            $output .= $this->create_a_tag($item, $depth, $args);
        }
    }
    function end_el( &$output, $item, $depth = 0, $args = Array() ) {
        if (in_array('menu-item-has-children', $item->classes)) {
            $output .= "\n".'</li></ul></li>';
        }
        else {
            $output .= "\n".'</li>';
        }
    }

    private function create_a_tag($item, $depth, $args) {
        $href = ! empty( $item->url ) ? ' href="'.esc_attr( $item->url) .'"' : '';
        $item_output = sprintf( '<a%1$s>%2$s</a>',
            $href,
            apply_filters( 'the_title', $item->title, $item->ID)
        );
        return apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

}


/*
 *
 * サイドバー設定（下層ページのみ）
 *
 */
// register_sidebar(array(
//     'name'          => '運営サイト（サイドメニュー）',
//     'id'            => 'sidebar',
//     'description'   => '',
//     'before_widget' => '',
//     'after_widget' => '',
//     'before_title' => '',
//     'after_title' => '',
// ));


/*
 *
 * 記事データ取得
 *
 */

function get_the_post_data( $post_id=null ){

    $return = array();

    global $post;

    $id = $post_id ? $post_id : $post->ID;
    $return['post_type'] = $post->post_type;;
    $post_type_obj = get_post_type_object($return['post_type']);
    $return['post_type_name'] = $post_type_obj->labels->singular_name;
    $return['permalink'] = get_the_permalink($id); //パーマリンク
    $return['title'] = get_the_title($id); //タイトル
    $return['content'] = get_the_content($id); //本文
    $return['thumbnail'] = has_post_thumbnail($id) ? get_the_post_thumbnail_url($id,'medium') : NULL; //サムネイルURL
    $return['date'] = get_the_date(get_option('date_format'),$id); //日付
    $return['excerpt'] = $post->post_excerpt; //抜粋
    $return['custom_field'] = get_post_meta($id);

    $return['category'] = array();
    $return['tag'] = array();

    //記事が持つタクソノミーをすべて取得
    if($taxs = get_post_taxonomies($id)){
        foreach((array)$taxs as $tax){
            if($tax !== "post_format"){ //post_formatだけ取り除く
                $taxonomy = get_taxonomy($tax);
                $taxonomy_type = $taxonomy->hierarchical ? 'category' : 'tag';
                $return[$taxonomy_type][$tax] = array();
                $terms = get_the_terms($id, $tax);
                if($terms){//この記事に使われていたら
                    foreach ((array)$terms as $term){
                        array_push($return[$taxonomy_type][$tax], array('name' => $term->name, 'slug'=> $term->slug,'link' => get_term_link($term->term_id)));
                    }
                }else{
                    unset($return[$taxonomy_type][$tax]);
                }
            }
        }
    }

    $return['id'] = $id;

    return $return;

}

/*
 *
 * 関連記事
 *
 */

function get_the_related_posts(){

    global $post;

    $return = array();

    if (is_single()){

        $post_type = get_post_type();
        $post_type_object = get_post_type_object($post_type);

        if($post_type == "post"){
            $categories = get_the_category();
            $title = $categories[0]->cat_name;
            $related_posts = get_posts(array('category__in' => array($categories[0]->cat_ID), 'exclude' => $post->ID, 'numberposts' => -1));
        }else{
            $title = esc_html($post_type_object->labels->singular_name);
            $related_posts = get_posts(array('post_type' => $post_type, 'exclude' => $post->ID ,'numberposts' => -1));
        }
        $excerpt = get_the_excerpt();

        if($related_posts){
            $return['title'] = $title;
            $return['posts'] = array();
            foreach($related_posts as $related_post){
                array_push($return['posts'],array('title'=>$related_post->post_title,'permalink'=>get_permalink($related_post->ID),'content'=>$related_post->post_content,'excerpt'=>$related_post->post_excerpt));
            }
        }

    }elseif(is_page()){

        $related_query = new WP_Query();
        $args = $related_query->query(array(
            'post_type' => 'page',
            'nopaging'  => 'true'
        ));

        if($parent_id = $post->post_parent ){
            $target = $parent_id;
            $title = get_post($parent_id)->post_title;
        }else{
            $target = $post->ID;
            $title = $post->post_title;
        }
        $excerpt = $post->post_excerpt;

        $related_posts = get_page_children($target, $args);

        if($related_posts) {
            $return['title'] = $title;
            $return['posts'] = array();
            foreach ($related_posts as $related_post) {
                array_push($return['posts'],array('title'=>$related_post->post_title,'permalink'=>get_permalink($related_post->ID),'content'=>$related_post->post_content,'excerpt'=>$related_post->post_excerpt));
            }
        }
    }

    return $return;
}


/*
 *
 * 抜粋文
 *
 */

add_post_type_support( 'page', 'excerpt' );

function my_excerpt_more($post) {
    return '';
}
add_filter('excerpt_more', 'my_excerpt_more');

function twpp_change_excerpt_length( $length ) {
  return 100;
}
add_filter( 'excerpt_length', 'twpp_change_excerpt_length', 999 );


/*
 *
 * ページネーション設定
 *
 */
// function set_posts_per_page($query) {
//
//     if ( is_admin() ) {
//         return;
//     }
//
//     $taxonomies = get_taxonomies(array('_builtin'=>false));
//     $my_taxonomies = array();
//     foreach($taxonomies as $taxonomy){
//         array_push($my_taxonomies, $taxonomy);
//     }
//
//     $categories = get_categories();
//     $my_categories = array();
//     foreach($categories as $category){
//         array_push($my_categories, $category->slug);
//     }
//
//     $posttypes = get_post_types(array('_builtin'=>false));
//     $my_posttypes = array();
//     foreach($posttypes as $posttype){
//         array_push($my_posttypes, $posttype);
//     }
//
//     if( $query->is_category($my_categories) || $query->is_tax($my_taxonomies) || $query->is_post_type_archive($my_posttypes)){
//         $query->set( 'posts_per_page', get_option('posts_per_page') );
//     }
//
// }
//
// add_action('pre_get_posts', 'set_posts_per_page' );


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


/*
 *
 * ページ送り
 *
 */
function pager( $query = null, $prev_text=null, $next_text=null ){

    global $wp_query;

    $current_query = $query ? $query : $wp_query ;

    $big = 999999999;

    $args =	array(
        'type' => 'array',
        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'current' => max( 1, get_query_var('paged') ),
        'total' => $current_query->max_num_pages,
        'prev_text' => $prev_text ? $prev_text : "前のページへ",
        'next_text' => $next_text ? $next_text : "次のページへ",
    );

    $pager = paginate_links( $args );

    if( !$pager ) return;

    echo "<ul class='pager'>";

    foreach( $pager as $page ){

        if ( strpos( $page, 'next' ) != false ){

            echo "<li class='next'>" . $page . "</li>";

        } elseif ( strpos( $page, 'prev' ) != false ){

            echo "<li class='prev'>" . $page . "</li>";

        } elseif ( strpos( $page, 'current' ) != false ){

            echo "<li class='current'>" . $page . "</li>";

        } elseif ( strpos( $page, 'dots' ) != false ){

            echo "<li class='dots'>" . $page . "</li>";

        } else {

            echo "<li>" . $page . "</li>";

        }

    }

    echo "</ul>";

}

/*
 *
 * フロントエンド用テンプレート
 *
 */

$loader = new \Twig\Loader\FilesystemLoader( get_template_directory().'/template' );
$options = array(
    'strict_variables' => false,
    'debug' => false,
    'cache'=> false
);
$twig = new \Twig\Environment($loader,$options);

$function = new \Twig\TwigFunction( 'wp_head', 'wp_head' );
$twig->addFunction( $function );

$function = new \Twig\TwigFunction( 'wp_footer', 'wp_footer' );
$twig->addFunction( $function );

$function = new \Twig\TwigFunction( 'pager', 'pager' );
$twig->addFunction( $function );



/*
 *
 * フロントエンド用コントローラー
 *
 */

add_action( 'template_redirect', 'front_controller' );
function front_controller() {

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
