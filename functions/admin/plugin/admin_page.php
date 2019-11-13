<?php

interface AdminPage
{
    public function admin_init();
    public function admin_menu();
    public function admin_enqueue_scripts();

}
