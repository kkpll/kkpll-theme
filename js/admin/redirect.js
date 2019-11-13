(function($){

    //リダイレクト行を追加
    $("#add-redirect-btn").on( 'click', function(e){

        e.preventDefault();

        var next_index;

        $('.fnsk_redirect_field td div').each(function(index){
            $(this).find('input').attr('name', 'fnsk_redirect_name[' + index + '][]');
            next_index = index;
        });

        next_index ++;

        $('.fnsk_redirect_field td').append(
            "<div class='redirect-row'>"
                + "<input type='text' name='fnsk_redirect_name[" + next_index +  "][]' value='' />"
                + "<input type='text' name='fnsk_redirect_name[" + next_index +  "][]' value='' />"
                + "<a href='#' class='delete-redirect-btn'>[削除]</a>"
            + "</div>"
        );

    });


    //リダイレクト行を削除
    $(document).on( 'click', '.delete-redirect-btn', function(e){

        e.preventDefault();

        $(this).parent('.redirect-row').remove();

        $('.fnsk_redirect_field td div').each(function(index){
            $(this).find('input').attr('name', 'fnsk_redirect_name[' + index + '][]');
        });

    });



})(jQuery);
