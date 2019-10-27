<?php

require_once "admin/kkpll.php";

require_once "functions/twig.php";

require_once "functions/add_rewrite_rule.php";//リライトルール追加
require_once "functions/pre_get_posts.php";
require_once "functions/template_redirect.php";//テンプレート振り分け
require_once "functions/wp_enqueue_scripts.php";//CSSやJAVASCRIPTの読み込み

require_once "functions/thumbnail.php";//サムネイル
require_once "functions/exerpt.php";//抜粋文
require_once "functions/menu.php";//グローバル・サイドメニュー
require_once "functions/pagination.php"; //ページ送り関数

require_once "functions/utils.php"; //その他の関数
require_once "functions/actions.php";//その他のアクションフック
require_once "functions/filters.php"; //その他のフィルターフック
