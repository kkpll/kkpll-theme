<?php

namespace App;

class Base{

    public $plugin_path;
    public $plugin_url;
    public $plugin_prefix;

    public function __construct(){

        $this->plugin_path = BASE_DIR;
        $this->plugin_url = BASE_DIR_URI;
        $this->plugin_prefix = 'fnsk';

    }

}
