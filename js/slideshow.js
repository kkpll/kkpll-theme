(function(){

        function Slideshow(args){

            this.id          = args.id;
            this.margin_left = 0;
            this.interval    = args.interval || 7;
            this.duration    = args.duration || 1;
            this.autoplay    = args.autoplay === undefined ? true : args.autoplay;
            this.last_time   = 0;
            this.is_ready    = false;

            this.init();

        }

        Slideshow.prototype.CONST = {

            DIRECTION : {
                NEXT : -1,
                PREV : 1
            },

            TRANSITION_EVENTS : [
                "webkitTransitionEnd",
                "mozTransitionEnd",
                "oTransitionEnd",
                "transitionend"
            ],

        }

        Slideshow.prototype.init = function(){

            var slideshow = document.getElementById( this.id );
            var images = slideshow.getElementsByClassName('slideshow__images')[0];

            images.addEventListener( 'mouseover', function(){
                this.is_ready = false;
            }.bind(this));

            images.addEventListener( 'mouseleave', function(){
                this.is_ready = true;
            }.bind(this));

            slideshow.getElementsByClassName( 'slideshow__buttons_button' )[0].addEventListener( 'click' ,function(e){
                this.slide( this.CONST.DIRECTION.PREV );
            }.bind(this));

            slideshow.getElementsByClassName( 'slideshow__buttons_button' )[1].addEventListener( 'click' ,function(e){
                this.slide( this.CONST.DIRECTION.NEXT );
            }.bind(this));

            if( this.autoplay ) this.loop();

            this.is_ready = true;

        }


        Slideshow.prototype.slide = function( direction ){

            if( this.is_ready ){
                this.is_ready = false;
            }else{
                return;
            }

            var slideshow = document.getElementById( this.id );
            var images = slideshow.getElementsByClassName('slideshow__images')[0];

            var current_margin_left = parseInt( images.children[0].style.marginLeft );

            var next_margin_left;
            var remove_image;

            //次へスライドの場合
            if( direction === this.CONST.DIRECTION.NEXT ){

                var copy_image = images.children[0].cloneNode(true);
                images.appendChild( copy_image );

                next_margin_left = '-' + this.margin_left;

                remove_image = images.children[0];

            //前へスライドの場合
            } else if( direction === this.CONST.DIRECTION.PREV ) {

                var copy_image = images.children[images.children.length - 1].cloneNode(true);
                images.insertBefore(copy_image, images.children[0]);
                copy_image.style.marginLeft = '-' + this.margin_left + '%';

                next_margin_left = 0 ;

                remove_image = images.children[images.children.length - 1];

            }

             //0.1秒後にスライド実行
             var target_image = images.children[0];
             target_image.classList.add( 'transition' );
             setTimeout( function(){ target_image.style.marginLeft = next_margin_left + '%'; }, 100 );

             //スライド終了後の処理
             this.CONST.TRANSITION_EVENTS.forEach( function( transitionend ){

                 target_image.addEventListener( transitionend, function(event){

                     if( direction === this.CONST.DIRECTION.PREV ) {
                         var target_image_copy = target_image.cloneNode(true)
                         images.insertBefore( target_image_copy, images.children[0] );
                         images.removeChild( target_image );
                     }

                     images.removeChild( remove_image );

                     //要素のスタイルを初期化
                     Array.prototype.forEach.call( images.children, function(image,index) {
                         image.classList.remove( 'transition' );
                     });

                     this.is_ready = true;

                 }.bind( this ), true );

             }.bind( this ) );

        }

        Slideshow.prototype.loop = function(){

            var self = this;

            (function loop(timestamp){

                requestAnimationFrame(loop);

                if( ( timestamp - self.last_time )/1000 > self.interval ){
                    self.slide(self.CONST.DIRECTION.NEXT);
                    self.last_time = timestamp;
                }

            })();

        }


        //実行部分
        var slideshows = [];

        var slideshow01 = new Slideshow({
            id         : 'sd',
            interval   : 2,
            duration   : 1,
            autoplay   : false,
        });

        slideshows.push(slideshow01);

        //ロードとリサイズ時の処理
        var timer = false;

        ['resize','load'].forEach(function(e){

            window.addEventListener(e,function(){

                if ( timer !== false ) {

                    clearTimeout(timer);

                    slideshows.forEach(function(slideshow){

                        if(slideshow.is_ready){
                            slideshow.is_ready = false;
                        }

                    });

                };

                timer = setTimeout(function() {

                    slideshows.forEach( function( slideshow ) {

                        var ss    = document.getElementById( slideshow.id );
                        var image = ss.getElementsByClassName('slideshow__images__image')[0];

                        slideshow.margin_left = (image.clientWidth / ss.clientWidth)*100 ;
                        slideshow.is_ready    = true;

                        console.log( slideshow.margin_left );

                    } );

                }, 200 );

            });

        });



})();
