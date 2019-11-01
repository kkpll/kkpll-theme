<?php

/*
 *
 * テンプレート初期化
 *
 */


class Template{

    static $twig;

    static function register(){

        $loader = new \Twig\Loader\FilesystemLoader( get_template_directory().'/template' );

        $options = array(
            'strict_variables' => false,
            'debug' => false,
            'cache'=> false
        );

        self::$twig = new \Twig\Environment( $loader,$options );

        $function = new \Twig\TwigFunction( 'wp_head', 'wp_head' );
        self::$twig->addFunction( $function );

        $function = new \Twig\TwigFunction( 'wp_footer', 'wp_footer' );
        self::$twig->addFunction( $function );

        $function = new \Twig\TwigFunction( 'pagination', 'pagination' );
        self::$twig->addFunction( $function );

        //定数アクセス無効化
        $function = new \Twig\TwigFunction( 'constant', function() { return false; } );
        self::$twig->addFunction( $function );

        return self::$twig;

    }

}
