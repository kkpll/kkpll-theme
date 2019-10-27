<?php

namespace App\Controllers;

use App\Base;
use App\Components\Csv;
use App\Callbacks\Page;
use App\Callbacks\Field;
use App\Callbacks\Sanitize;

class Redirect extends Base{

    public $csv;
    public $page;
    public $field;
    public $sanitize;

    public function __construct(){

        parent::__construct();

        $this->csv = new Csv('redirect');
        $this->page = new Page();
        $this->field = new Field();
        $this->sanitize = new Sanitize();

    }

    public function admin_menu(){

        $page = add_submenu_page(
            'fnsk',
            'リダイレクト設定',
            'リダイレクト設定',
            'manage_options',
            'fnsk_redirect_page',
            array( $this->page, 'redirect' )
        );

    }

    public function admin_init(){

        register_setting(
            'fnsk_redirect_group',
            'fnsk_redirect_name',
            array( $this->sanitize, 'redirect' )
        );

        add_settings_section(
            'fnsk_redirect_section',
            '',
            '',
            'fnsk_redirect_page'
        );

        add_settings_field(
            'fnsk_redirect_field',
            'リダイレクト設定フィールド',
            array($this->field, 'redirect' ),
            'fnsk_redirect_page',
            'fnsk_redirect_section',
            array(
                'class' => 'fnsk_redirect_field'
            )
        );

    }

    public function admin_enqueue_scripts(){
        wp_enqueue_script( 'redirect.js', $this->plugin_url . '/assets/js/admin/redirect.js', array( 'jquery' ), filemtime( $this->plugin_path . '/assets/js/admin/redirect.js' ), true );
    }



}
