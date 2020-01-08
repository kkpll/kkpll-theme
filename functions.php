<?php

/*
 *
 * 定数定義
 *
 */

define('THEME_URL', get_template_directory_uri());
define('THEME_DIR', get_template_directory());


/*
 *
 * アイキャッチ有効化
 *
 */

add_theme_support('post-thumbnails');


/*
 *
 * CSSとJAVASCRIPTファイルの読み込み
 *
 */

add_action('wp_enqueue_scripts','my_wp_enqueue_scripts');
function my_wp_enqueue_scripts(){
    //CSS
    wp_enqueue_style('style.css', get_stylesheet_uri(),array(),filemtime(THEME_DIR.'/style.css'));
    wp_enqueue_style('breadcrumb.css', THEME_URL.'/css/breadcrumb.css',array('style.css'),filemtime(THEME_DIR.'/css/breadcrumb.css'));

    //JAVASCRIPT
    global $wp_scripts;
    $jquery = $wp_scripts->registered['jquery-core'];
    $jq_ver = $jquery->ver;
    $jq_src = $jquery->src;
    wp_deregister_script( 'jquery' );
    wp_deregister_script( 'jquery-core' );
    wp_register_script( 'jquery', false, array('jquery-core'), $jq_ver, true );
    wp_register_script( 'jquery-core', $jq_src, array(), $jq_ver, true );
}


/*
 *
 * カスタムメニュー設定
 *
 */

// register_nav_menus( array(
//     'global' => 'グローバルメニュー',
//     'sidebar' => 'サイドバー',
//     'footer1' => 'フッター１',
//     'footer2' => 'フッター２',
//     'footer3' => 'フッター３',
//     'footer4' => 'フッター４',
//
// ));

class SimpleMenuWalker extends Walker_Nav_Menu {
    function start_lvl( &$output, $depth = 0, $args = Array() ){$output .= "";}
    function end_lvl( &$output, $depth = 0, $args = Array() ) {$output .= "";}
    function start_el( &$output, $item, $depth = 0, $args = Array(), $id = 0 ) {
        if (in_array('menu-item-has-children', $item->classes)) {
            $indent = " ";
            $output .= "\n".'<li>';
            $output .= "<a href='".$item->url."'>".$item->title."</a>";
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


class Template{

    static $twig;

    static function register(){

        if( !self::$twig ){
            require_once( THEME_DIR.'/lib/twig/lib/Twig/Autoloader.php' );
            Twig_Autoloader::register();
        }

        $loader = new Twig_Loader_Filesystem( THEME_DIR . '/template' );

        self::$twig = new Twig_Environment($loader);

        //定数アクセス無効化
        $function = new Twig_SimpleFunction( 'constant', function() { return false; } );
        self::$twig->addFunction( $function );

        $function = new Twig_SimpleFunction( 'wp_head', 'wp_head' );
        self::$twig->addFunction( $function );

        $function = new Twig_SimpleFunction( 'wp_footer', 'wp_footer' );
        self::$twig->addFunction( $function );

        $function = new Twig_SimpleFunction( 'pagination', 'pagination' );
        self::$twig->addFunction( $function );

        $function = new Twig_SimpleFunction( 'settings_fields', 'settings_fields' );
        self::$twig->addFunction( $function );

        $function = new Twig_SimpleFunction( 'do_settings_sections', 'do_settings_sections' );
        self::$twig->addFunction( $function );

        $function = new Twig_SimpleFunction( 'submit_button', 'submit_button' );
        self::$twig->addFunction( $function );

        $function = new Twig_SimpleFunction( 'relatedPages', 'relatedPages' );
        self::$twig->addFunction( $function );

        return self::$twig;

    }

}

$twig = Template::register();



/*
 *
 * ページ送り
 *
 */

function pagination( $query = null, $prev_text = null, $next_text = null ){
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
    echo "<ul class='pagination'>";
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
 * 記事データ取得
 *
 */

 class Post{

     static $data;

     public static function get( $post_id = null ){

         self::$data = array();

         if( $post_id ){
             $id = $post_id;
         }else{
             global $post;
             $id = $post->ID;
         }

         self::$data['id']             = $id;
         self::$data['post_type']      = $post->post_type;
         self::$data['post_type_name'] = get_post_type_object( self::$data['post_type'] )->labels->singular_name;
         self::$data['permalink']      = get_the_permalink( $id );
         self::$data['title']          = get_the_title( $id );
         self::$data['content']        = get_the_content();
         self::$data['thumbnail']      = has_post_thumbnail( $id ) ? get_the_post_thumbnail_url( $id, 'medium' ) : NULL;
         self::$data['date']           = get_the_date( get_option( 'date_format' ), $id );
         self::$data['excerpt']        = $post->post_excerpt;
         self::$data['custom_field']   = get_post_meta( $id );
         self::$data['category']       = array();
         self::$data['tag']            = array();

         if( $taxs = get_post_taxonomies( $id ) ){
             foreach( (array)$taxs as $tax ){
                 if( $tax !== "post_format" ){
                     $taxonomy = get_taxonomy( $tax );
                     $taxonomy_type = $taxonomy->hierarchical ? 'category' : 'tag';
                     $terms = get_the_terms( $id, $tax );
                     if( $terms ){
                         foreach( (array)$terms as $term ){
                             self::$data[$taxonomy_type][$tax][$term->term_id] = array( 'name' => $term->name, 'slug'=> $term->slug, 'link' => get_term_link( $term->term_id ) );
                         }
                     }
                 }
             }
         }

         foreach( self::$data['category'] as $tax => $terms ){
             foreach( $terms as $term_id => $value ){
                 $parent_terms = get_ancestors( $term_id, $tax );
                 foreach( $parent_terms as $parent_term ){
                     unset( self::$data['category'][$tax][$parent_term] );
                 }
             }
         }

         return self::$data;

     }


     public static function getExcerpt( $length ){
         if( !self::$data['content'] ) return;
         $content = wp_strip_all_tags( self::$data['content'] );
         if( mb_strlen( $content ) <= $length ){
             return $content;
         }else{
             return mb_substr( $content, 0, $length )."...";
         }
     }

 }


/*
*
* パンくずリスト
*
*/

class Breadcrumb {

    public $queried;
    public $breadcrumb = array();

    function __construct(){
        $this->queried = get_queried_object();
        $this->add( get_bloginfo('name'), home_url('/') );
    }

    //カスタムポスト一覧ページ
    public function postTypeArchive(){
        $this->add( $this->queried->label." 記事一覧" );
    }

    //シングルページ
    public function single( $taxonomy_name = null ){
        $this->addPostTypeBreadCrumb( $this->queried->post_type );
        $post_id = $this->queried->ID;
        $taxonomy_name = $taxonomy_name ? $taxonomy_name : 'category';
        $taxonomy = get_taxonomy( $taxonomy_name );
        $is_category = $taxonomy->hierarchical ? true : false ;
        if( $is_category ){
            $terms = get_the_terms( $post_id, $taxonomy_name );
            if( $terms ){
                $parent_terms = array();
                foreach( $terms as $term ){
                    if( $term->parent ){
                        $parent_terms[] = $term->parent;
                    }
                }
                $child_terms = array();
                foreach( $terms as $term ){
                    $is_child = true;
                    $term_id = $term->term_id;
                    foreach( $parent_terms as $parent_term ){
                        if( $term_id == $parent_term ){
                            $is_child = false;
                            break;
                        }
                    }
                    if( $is_child ){
                        $child_terms[] = $term_id;
                    }
                }
                if( count( $child_terms ) === 1 ){
                    $terms = $this->getParents( $child_terms[0], $taxonomy_name );
                    $terms[] = $child_terms[0];
                    foreach( $terms as $term ){
                        $term = get_term_by( 'id', $term, $taxonomy_name );
                        $this->add( $term->name, get_term_link( $term->term_id ) );
                    }
                }
            }
        }
        $date = date("Y年n月j日",strtotime($this->queried->post_date));
        $this->add( "<small>".$date."</small>".$this->queried->post_title );

    }

    //カテゴリー・タクソノミー一覧ページ
    public function taxArchive(){
        $taxonomy_name = $this->queried->taxonomy;
        $taxonomy = get_taxonomy( $taxonomy_name );
        $registrated_post_type = $taxonomy->object_type[0];
        $this->addPostTypeBreadCrumb( $registrated_post_type );
        $is_category = $taxonomy->hierarchical ? true : false ;
        if( $is_category ){
            $terms = $this->getParents( $this->queried->term_id, $taxonomy_name );
            foreach( $terms as $term ){
                $term = get_term_by( 'id', $term, $taxonomy_name );
                $this->add( $term->name, get_term_link( $term->term_id ) );
            }
        }
        $this->add( $this->queried->name." 記事一覧" );
    }

    //タグ一覧ページ
    public function tagArchive(){
        $taxonomy = get_taxonomy( $this->queried->taxonomy );
        $registrated_post_type = $taxonomy->object_type[0];
        $this->addPostTypeBreadCrumb( $registrated_post_type );
        $this->add( $this->queried->name." 記事一覧" );
    }

    //固定ページ
    public function page(){
        $pages = $this->getParents( $this->queried->ID, 'page' );
        foreach( $pages as $page ){
            $page = get_post( $page );
            $this->add( $page->post_title, get_the_permalink( $page ) );
        }
        $this->add( $this->queried->post_title );
    }

    //検索結果ページ
    public function searchArchive(){
        $search_query = get_search_query();
        $this->add( "「 " . $search_query . " 」の検索結果" );
    }

    //４０４ページ
    public function notFound(){
        $search_query = get_search_query();
        $this->add( "<small>404 Not Found</small>このページは存在しません" );
    }


    //パンくずリストを出力
    public function echo(){
        ob_start();
        echo "<ul class='breadcrumb'>";
        foreach($this->breadcrumb as $key => $breadcrumb){
            if($key!=0){
                echo "<span class='slash'>/</span>";
            }
            echo "<li>";
            if($breadcrumb['link']){
                echo "<a href='".$breadcrumb['link']."'>".$breadcrumb['text']."</a>";
            }else{
                if($key==count($this->breadcrumb)-1){
                    echo "<h1>".$breadcrumb['text']."</h1>";
                }else{
                    echo "<span>".$breadcrumb['text']."</span>";
                }
            }
            echo "</li>";
        }
        echo "</ul>";
        return ob_get_clean();
    }

    //パンくずリストに追加
    private function add( $text = '', $link = '' ){
        array_push( $this->breadcrumb, array(
            'text' => $text,
            'link' => $link,
        ));
    }

    //カスタム投稿タイプをパンくずリストに追加
    private function addPostTypeBreadCrumb( $post_type ){
        $post_type = $post_type ? $post_type : $queried->post_type;
        if( $post_type !== 'post' ){
            $this->add( get_post_type_object( $post_type )->labels->singular_name, get_post_type_archive_link( $post_type ) );
        }
    }

    //カテゴリーの親を全て取得
    private function getParents( $term_id, $taxonomy_or_page ){
        $terms = get_ancestors( $term_id, $taxonomy_or_page );
        return array_reverse( $terms );
    }

}


/*
 *
 * 関連記事（シングルページ）
 *
 */

class RelatedPosts{

    static $posts = array();

    public static function get( $taxonomy_names, $length = null ) {

        if( !is_single() ) return;

        self::$posts = array();

        $post_id = get_the_ID();
        $data = Post::get( $post_id );

        $post_type = $data['post_type'];
        $length = $length ? $length : -1;

        $taxs = $taxonomy_names;

        if( is_string( $taxs ) ){
            $taxs = array();
            $taxs[] = $taxonomy_names;
        }

        //投稿タイプをチェック
        foreach( $taxs as $taxonomy_name ){

            if( $taxonomy_name === $post_type ){

                $taxs = array_diff($taxs,array($taxonomy_name));
                $taxs = array_values($taxs);

                $args = array(
                    'post_type'   => $post_type,
                    'post_status' => 'publish',
                    'exclude'     => $data['id'],
                    'numberposts' => $length,
                );

                $related_posts = get_posts( $args );

                if( $related_posts ){
                    $postdata = array();
                    foreach( $related_posts as $related_post ){
                        $postdata[] = array(
                            'title'     => $related_post->post_title,
                            'permalink' => get_permalink( $related_post->ID ),
                            'content'   => $related_post->post_content,
                            'excerpt'   => $related_post->post_excerpt
                        );
                    }
                    self::$posts[] = [ 'title' => $data['post_type_name'], 'data' => $postdata ];
                }
            }
        }

        //タクソノミーをチェック
        if( $cats = $data['category'] + $data['tag'] ){

            foreach( $taxs as $taxonomy_name ){

                foreach( $cats as $tax => $terms){

                    if( $tax === $taxonomy_name ){

                        foreach( $terms as $term_id => $value ){

                            $args = array(
                                'post_type'   => $post_type,
                                'post_status' => 'publish',
                                'exclude'     => $data['id'],
                                'numberposts' => $length,
                                'tax_query'   => array(
                                    array(
                                        'taxonomy' => $taxonomy_name,
                                        'field'    => 'slug',
                                        'terms'    => $value['slug'],
                                    )
                                ),
                            );

                            $related_posts = get_posts( $args );

                            if( $related_posts ){
                                $postdata = array();
                                foreach( $related_posts as $related_post ){
                                    $postdata[] = array(
                                        'title'     => $related_post->post_title,
                                        'permalink' => get_permalink( $related_post->ID ),
                                        'content'   => $related_post->post_content,
                                        'excerpt'   => $related_post->post_excerpt
                                    );
                                }
                                self::$posts[] = [ 'title' => $value['name'], 'data' => $postdata ];

                            }

                        }

                    }

                }

            }

        }

        return self::$posts;

    }

    public static function echo(){
        ob_start();
        foreach(self::$posts as $relatedposts){
            echo "<h5 class='relatedposts-title'>「".$relatedposts['title']."」の関連記事はこちら</h5>";
            echo "<ul class='relatedposts'>";
            foreach($relatedposts['data'] as $data){
                echo "<li>";
                echo "<a href='".$data['permalink']."'>";
                echo $data['title'];
                echo "</a>";
                echo "</li>";
            }
            echo "</ul>";
        }
        return ob_get_clean();
    }

}

function relatedPosts($taxonomy_names, $length = null){
    RelatedPosts::get($taxonomy_names, $length);
    return RelatedPosts::echo();
}


/*
 *
 * 関連記事（固定ページ）
 *
 */

class RelatedPages{

    static $post_id;
    static $title;
    static $pages = array();

    public static function get( $post_id=null ){

        if( !is_page() ) return;

        self::$pages = array();

        $post_id = $post_id ? $post_id : get_the_ID();
        self::$post_id = $post_id;

        $related_query = new WP_Query();
        $args = $related_query->query(array(
            'post_type' => 'page',
            'nopaging'  => 'true'
        ));

        $p = get_post(self::$post_id);

        if($parents = get_ancestors($p->ID, 'page')){
            $parents = array_reverse($parents);
            $target = $parents[0];
            $parent = get_post($target);
            $title = $parent->post_title;
        }else{
            $target = $post_id;
            $title  = $p->post_title;
        }

        self::$title = $title;

        self::get_page_children_loop($target, self::$pages);

        self::$pages = self::$pages[$target];

        return self::$pages;

    }

    public static function echo(){
        if(!self::$pages) return;
        echo "<h5 class='relatedposts-title'>「".self::$title."」の関連記事はこちら</h5>";
        echo "<ul class='relatedposts'>";
        self::echoList();
        echo "</ul>";
    }

    private function echoList($pages=null){
        $pages = $pages ? $pages : self::$pages;
        foreach($pages as $key => $page){
            $p = get_post($key);
            if($p->ID === self::$post_id){
                echo "<li><span>".$p->post_title."</span></li>";
            }else{
                echo "<li><a href='".get_the_permalink($key)."'>".$p->post_title."</a></li>";
            }
            if(is_array($page)){
                echo "<ul>";
                self::echoList($page);
                echo "</ul>";
            }
        }
    }

    private function get_page_children_loop( $post_id, &$args ){
        $pages = get_children( array('post_parent'=>$post_id,'post_type'=>'page') );

        if( $pages ){
            $args[$post_id] = array();
            foreach( $pages as $page ){
                if(get_children(array('post_parent'=>$page_id,'post_type'=>'page'))){
                    self::get_page_children_loop( $page->ID, $args[$post_id] );
                }
            }
        }else{
            $args[$post_id] = NULL;
        }
    }

}

function relatedPages(){
    RelatedPages::get();
    return RelatedPages::echo();
}


/*
 *
 * テンプレートに渡す記事を取得
 *
 */

function set_posts_data( &$posts=array(), $query=null ){

    if( !$query ){
        global $wp_query;
        $query = $wp_query;
    }

    if( $query->have_posts() ){

        while( $query->have_posts() ){
            $query->the_post();
            $data = Post::get();
            array_push( $posts, $data );
        }

        if( !$query->is_main_query() ){
            wp_reset_postdata();
        }

    }

    return $posts;

}


/*
*
* セキュリティ関連
*
*/

//WORDPRESSのバージョンを見せない
remove_action( 'wp_head', 'wp_generator' );

//著者一覧禁止
add_filter( 'author_rewrite_rules', '__return_empty_array' );
add_action( 'init', 'disable_author_archive' );
function disable_author_archive() {
    if( !empty( $_GET['author'] ) || preg_match('#/author/.+#', $_SERVER['REQUEST_URI']) ){
        wp_redirect(home_url());
        exit;
    }
}


require_once "functions/pre_get_posts.php";
require_once "functions/template_redirect.php";  //テンプレート振り分け
