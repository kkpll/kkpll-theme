<?php

namespace App;

class Base{

    public $plugin_path;
    public $plugin_url;
    public $plugin_prefix;

    public function __construct(){

        $this->theme_path     = get_template_directory();
        $this->theme_url      = get_template_directory_uri();
        $this->plugin_path   = $this->theme_path . "/admin";
        $this->plugin_url    = $this->theme_url . "/admin";
        $this->plugin_prefix = 'kkpll';

    }

}
