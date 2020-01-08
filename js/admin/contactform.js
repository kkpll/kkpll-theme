(function($){

    //新規作成
    $('#fnsk_contactform_add').on('click',function(e){

        e.preventDefault();

        var fields = document.getElementsByClassName('fnsk_info_field');
        var key = fields.length;

        $('.form-table > tbody').append( renderField(key) );

    });


    //新規作成のHTMLを出力
    function renderField(key){
        return '<tr><th scope="row">お問い合わせフォーム</th><td><div class="fnsk_contactform_field">'
                    + '<p class="fnsk_contactform_inputs">'
                        + '<label>呼び出しキー</label><input type="text" name="fnsk_info[' + key + '][key]" value="" class="fnsk_info_input_key" /><br>'
                        + '<label>ラベル</label><input type="text" name="fnsk_info[' + key + '][label]" value="新しい情報" class="fnsk_info_input_label regular-text" /><br>'
                        + '<label>値</label><input type="text" name="fnsk_info[' + key + '][value]" value="" class="fnsk_info_input_value regular-text" />'
                    + '</p>'
                    + '<p class="fnsk_info_delete"><a href="#" class="fnsk_contactform_delete_btn">削除する</a></p>'
                + '</div></td></tr>'
    }


    //入力イベント
    $(document).on('keyup',function(e){

        var t = e.target;
        var key = $(t).val();

        if( t.className.indexOf('fnsk_info_input_key') != -1 ){

            var p   = t.parentNode;
            var pp  = t.parentNode.parentNode;

            $(t).attr('name', 'fnsk_info[' + key + '][key]');
            $(p).find('.fnsk_info_input_label').attr('name', 'fnsk_info[' + key + '][label]');
            $(p).find('.fnsk_info_input_value').attr('name', 'fnsk_info[' + key + '][value]');
            $(pp).attr('data-key', key);

        }else if( t.className.indexOf('fnsk_info_input_label') != -1 ){

            $(t).parents('tr').find('th').text( key );

        }

    });

    //削除ボタン
    $(document).on('click','.fnsk_contactform_delete_btn',function(e){
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    //jquery sortable

    $('.form-part').draggable({
         connectToSortable: '.form-table td',
         helper: 'clone',
         revert: 'invalid',
    });

    $('.form-table td').sortable({
        revert:true,
    });

    $('.form-table td').on('sortupdate', sortupdate);

    //削除ボタン
    $(document).on('click','.delete-form-part-btn',function(e){
        e.target.parents('.form-part').remove();
        $('.form-table td').trigger('sortupdate');
    });

    function sortupdate( event, ui ){
        var numbers = $(this).sortable("toArray");
        var data = [];
        for(var id of numbers){
            data[id] = [];
        }
        console.log( $(ui.item).attr('id', numbers.length ) );
    }


})(jQuery);
