<?php

namespace App\Controllers;

use App\Base;
use App\Callbacks\Sanitize;
use App\Callbacks\Page;

class Dashboard extends Base{

    public $sanitize;
    public $page;

    public function __construct(){

        parent::__construct();

        $this->sanitize = new Sanitize();
        $this->page = new Page();

    }

    public function admin_init(){

        register_setting(
            'fnsk_top_group',
            'fnsk_top_name',
            array( $this->sanitize, 'ctp' )
        );

        add_settings_section(
            'fnsk_top_section',
            'トップセクション',
            array( $this, 'render_top_section' ),
            'fnsk'
        );

        add_settings_field(
            'fnsk_top_field',
            'トップフィールド',
            array( $this, 'render_top_field' ),
            'fnsk',
            'fnsk_top_section'
        );

    }

    public function admin_menu(){

        add_menu_page(
            'FNSK',
            'Fnsk',
            'manage_options',
            'fnsk',
            array( $this->page, 'dashboard' )
        );

        add_submenu_page(
            'fnsk',
            'ダッシュボード',
            'ダッシュボード',
            'manage_options',
            'fnsk',
            array( $this->page, 'dashboard' )
        );

    }

    public function render_top_section(){
        echo "トップセクション";
    }

    public function render_top_field(){
        echo "<input type='text' name='fnsk_top_name' value='".get_option('fnsk_top_name')."' />";
    }


}
