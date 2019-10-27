<?php

/*
 *
 * カスタムメニュー設定
 *
 */

register_nav_menus( array(
    'global' => 'グローバルメニュー',
    // 'column' => '企業法務コラム',
    // 'contents' => 'コンテンツ',
    // 'footer1' => 'フッター①',
    // 'footer2' => 'フッター②',
    // 'footer3' => 'フッター③',
    // 'footer4' => 'フッター④',
    // 'footer5' => 'フッター⑤',
));


//グローバルメニュー
class GlobalMenuWalker extends Walker_Nav_Menu {

    function start_lvl( &$output, $depth = 0, $args = Array() ){$output .= "";}
    function end_lvl( &$output, $depth = 0, $args = Array() ) {$output .= "";}
    function start_el( &$output, $item, $depth = 0, $args = Array(), $id = 0 ) {
        if (in_array('menu-item-has-children', $item->classes)) {
            $indent = " ";
            $output .= "\n".'<li><span class="slide_menu_btn"></span>';
            $output .= "<a class='sub-a' href='".$item->url."'>".$item->title."</a>";
            $output .= "\n" . $indent . '<ul class="sub-menu">';
        } else {
            $output .= '<li>';
            $output .= "<a href='".$item->url."'>".$item->title."</a>";
        }
    }
    function end_el( &$output, $item, $depth = 0, $args = Array() ) {
        if (in_array('menu-item-has-children', $item->classes)) {
            $output .= "\n".'</li></ul></li>';
        }
        else {
            $output .= "\n".'</li>';
        }
    }

}


//フッターメニュー①～⑤
class FooterMenuWalker extends Walker_Nav_Menu {

    function start_lvl( &$output, $depth = 0, $args = Array() ){$output .= "<ul>";}
    function end_lvl( &$output, $depth = 0, $args = Array() ) {$output .= "</ul>";}
    function start_el( &$output, $item, $depth = 0, $args = Array(), $id = 0 ) {$output .= "<li>".$this->create_a_tag($item, $depth, $args);}
    function end_el( &$output, $item, $depth = 0, $args = Array(), $id = 0 ) {$output .= '</li>';}

    private function create_a_tag($item, $depth, $args) {
        $href = ! empty( $item->url ) ? ' href="'.esc_attr( $item->url) .'"' : '';
        $item_output = sprintf( '<a%1$s>%2$s</a>',
            $href,
            apply_filters( 'the_title', $item->title, $item->ID)
        );
        return apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

}

//サイドメニュー
class SideMenuWalker extends Walker_Nav_Menu {
    function start_lvl( &$output, $depth = 0, $args = Array() ){$output .= "";}
    function end_lvl( &$output, $depth = 0, $args = Array() ) {$output .= "";}
    function start_el( &$output, $item, $depth = 0, $args = Array(), $id = 0 ) {
        if (in_array('menu-item-has-children', $item->classes)) {
            $indent = " ";
            $output .= "\n".'<li class="label-blue parent">';
            $output .= $this->create_a_tag($item, $depth, $args);
            $output .= "\n" . $indent . '<ul class="sub-menu">';
        } else {
            $output .= '<li class="label-blue child-'.$depth.'">';
            $output .= $this->create_a_tag($item, $depth, $args);
        }
    }
    function end_el( &$output, $item, $depth = 0, $args = Array() ) {
        if (in_array('menu-item-has-children', $item->classes)) {
            $output .= "\n".'</li></ul></li>';
        }
        else {
            $output .= "\n".'</li>';
        }
    }

    private function create_a_tag($item, $depth, $args) {
        $href = ! empty( $item->url ) ? ' href="'.esc_attr( $item->url) .'"' : '';
        $item_output = sprintf( '<a%1$s>%2$s</a>',
            $href,
            apply_filters( 'the_title', $item->title, $item->ID)
        );
        return apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

}


/*
 *
 * サイドバー設定（下層ページのみ）
 *
 */
// register_sidebar(array(
//     'name'          => '運営サイト（サイドメニュー）',
//     'id'            => 'sidebar',
//     'description'   => '',
//     'before_widget' => '',
//     'after_widget' => '',
//     'before_title' => '',
//     'after_title' => '',
// ));
