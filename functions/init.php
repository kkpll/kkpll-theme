<?php

/*
 *
 * テンプレート初期化
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
 * アイキャッチ設定
 *
 */

add_theme_support('post-thumbnails');
set_post_thumbnail_size( 400, 300, true );


/*
 *
 * 抜粋文
 *
 */

add_post_type_support( 'page', 'excerpt' );
