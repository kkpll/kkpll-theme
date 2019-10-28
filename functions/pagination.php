<?php

/*
 *
 * ページ送り
 *
 */

function pagination( $query = null, $prev_text = null, $next_text = null ){

    global $wp_query;

    $current_query = $query ? $query : $wp_query ;

    $big = 999999999;

    $args =	array(
        'type' => 'array',
        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'current' => max( 1, get_query_var('paged') ),
        'total' => $current_query->max_num_pages,
        'prev_text' => $prev_text ? $prev_text : "前のページへ",
        'next_text' => $next_text ? $next_text : "次のページへ",
    );

    $pager = paginate_links( $args );

    if( !$pager ) return;

    echo "<ul class='pagination'>";

    foreach( $pager as $page ){

        if ( strpos( $page, 'next' ) != false ){

            echo "<li class='next'>" . $page . "</li>";

        } elseif ( strpos( $page, 'prev' ) != false ){

            echo "<li class='prev'>" . $page . "</li>";

        } elseif ( strpos( $page, 'current' ) != false ){

            echo "<li class='current'>" . $page . "</li>";

        } elseif ( strpos( $page, 'dots' ) != false ){

            echo "<li class='dots'>" . $page . "</li>";

        } else {

            echo "<li>" . $page . "</li>";

        }

    }

    echo "</ul>";

}
