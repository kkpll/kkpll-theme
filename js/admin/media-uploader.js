(function(){

    var file_btns      = document.querySelectorAll('input[type=file]');
    var media_uploader = wp.media.editor.send.attachment;


    //ファイルアップローダーボタンをメディアアップローダーボタンに変える
    Array.prototype.forEach.call( file_btns, function( file_btn ){

        var media_container = document.createElement( 'div' );
        media_container.className = 'media_container'
        var btn = document.createElement( 'button' );
        btn.type ='button';
        btn.className = 'button insert-media media_btn';
        btn.innerHTML = 'メディアを追加';
        media_container.appendChild(btn);
        var view = document.createElement( 'div' );
        view.className = 'media_view';
        media_container.appendChild( view );
        file_btn.parentNode.replaceChild( media_container, file_btn );

    });


    //各メディアアップローダーボタン設定
    var media_btns = document.getElementsByClassName('media_btn');
    Array.prototype.forEach.call(media_btns,function(media_btn){

        media_btn.onclick = function(e){

           e.preventDefault();

           var self = e.target;

           wp.media.editor.send.attachment = function( props, attachment ){
               if( callback ) callback( self, attachment );
           }

           wp.media.editor.open();

           return false;

       };

    });


    //ダブルクリックでプレビュー画像を削除
    document.ondblclick = function(e){

        if( e.target.className === 'media_image' ){

            e.target.parentNode.remove();

        }

    };


    //画像選択後コールバック
    function callback( btn, image ){

        var url = image.url;
        var media_container = btn.parentNode;
        var media_view = media_container.getElementsByClassName('media_view')[0];

        var img = document.createElement('img');
        img.src = url;
        img.className = 'media_image';
        var input = document.createElement('input');
        input.name = 'images[]';
        input.type = 'hidden';
        input.value = url;
        var image_container = document.createElement('p');
        image_container.className = 'image_container';
        image_container.appendChild(img);
        image_container.appendChild(input);
        media_view.appendChild(image_container);

    }


})();
