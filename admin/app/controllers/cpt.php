<?php

namespace App\Controllers;

use App\Base;
use App\Callbacks\Sanitize;
use App\Callbacks\Page;
use App\Callbacks\Field;
use App\Components\Csv;

class Cpt extends Base{

    public $sanitize;
    public $page;
    public $field;
    public $csv;

    public function __construct(){

        parent::__construct();

        $this->sanitize = new Sanitize();
        $this->page = new Page();
        $this->field = new Field();
        $this->csv = new Csv('cpt');

    }

    public function admin_menu(){

        add_submenu_page(
            'fnsk',
            'カスタム投稿',
            'カスタム投稿',
            'manage_options',
            'fnsk_cpt_page',
            array( $this->page, 'cpt' )
        );

    }

    public function admin_init(){

        register_setting(
            'fnsk_cpt_group',
            'fnsk_cpt_name',
            array( $this->sanitize, 'cpt')
        );

        add_settings_section(
            'fnsk_cpt_section',
            'カスタム投稿セクション',
            array( $this, 'render_cpt_section' ),
            'fnsk_cpt_page'
        );

        add_settings_field(
            'fnsk_cpt_field',
            'カスタム投稿フィールド',
            array( $this->field, 'cpt' ),
            'fnsk_cpt_page',
            'fnsk_cpt_section'
        );

        //CSVインポート
        register_setting(
            'fnsk_cpt_group',
            'fnsk_cpt_csv_name',
            array( $this->sanitize, 'cpt')
        );

        add_settings_field(
            'fnsk_cpt_csv_field',
            'CSV読み込み',
            array( $this->field, 'csv' ),
            'fnsk_cpt_page',
            'fnsk_cpt_section'
        );

    }

    public function render_cpt_section(){
        echo "カスタム投稿セクション";
    }

}
