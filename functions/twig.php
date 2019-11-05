<?php

/*
 *
 * テンプレート初期化
 *
 */


class Template{

    static $twig;

    static function register(){

        require_once(get_template_directory().'/twig/lib/Twig/Autoloader.php');
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem( get_template_directory().'/template' );
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

        //定数アクセス無効化
        $function = new Twig_SimpleFunction( 'constant', function() { return false; } );
        self::$twig->addFunction( $function );

        return self::$twig;

    }

}
