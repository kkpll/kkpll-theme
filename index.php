<?php get_header(); ?>

<h1>FNSK THEME</h1>

<section>

    <div id="sd" class="slideshow">
        <div class="slideshow__images">
            <div class="slideshow__images__image">画像１</div>
            <div class="slideshow__images__image">画像２</div>
            <div class="slideshow__images__image">画像３</div>
            <div class="slideshow__images__image">画像４</div>
            <div class="slideshow__images__image">画像５</div>
        </div>
        <div class="slideshow__buttons">
            <button>PREV</button>
            <button >NEXT</button>
        </div>
    </div>
    <style>

        .slideshow{
            width:100%;
        }

        .slideshow__images{
            width:100%;
            display:flex;
            overflow: hidden;
            position:relative;
        }

        .slideshow__images__image{
            position:relative;
            min-width:calc( 100%/5 );
            flex:0 0 calc( 100%/5 );
            background-color:lightgray;
            transition-property:all;
        }

        .slideshow__images__image:before{
            content:"";
            display: block;
            padding-top:30%;
        }

        .slideshow__buttons{
            position:fixed;
            bottom:0;
            left:0;
            display: flex;
            justify-content:space-between;
            width:100%;
        }

    </style>

    <script>

            function Slideshow(args){

                this.margin_left = Math.floor(100/args.col.pc);
                this.interval = args.interval || 7;
                this.duration = args.duration || 1;
                this.autoplay = args.autoplay === undefined ? true : args.autoplay;
                this.last_time = 0;
                this.is_ready = true;

                this.element = document.getElementById(args.id);

                this.element.addEventListener( 'mouseover', function(){
                    this.is_ready = false;
                }.bind(this));

                this.element.addEventListener( 'mouseleave', function(){
                    this.is_ready = true;
                }.bind(this));

                this.element.getElementsByTagName( 'button' )[0].addEventListener( 'click' ,function(e){
                    this.slide(this.CONST.DIRECTION.PREV);
                }.bind(this));

                this.element.getElementsByTagName( 'button' )[1].addEventListener( 'click' ,function(e){
                    this.slide(this.CONST.DIRECTION.NEXT);
                }.bind(this));

                var images = this.element.getElementsByClassName('slideshow__images')[0];
                var last_image = images.children[images.children.length-1];
                var last_image_copy = last_image.cloneNode(true);
                var first_image = images.children[0];
                var first_image_copy = first_image.cloneNode(true);
                images.insertBefore( last_image_copy, images.children[0] );
                images.appendChild( first_image_copy );
                images.children[0].style.marginLeft = '-' + this.margin_left + '%';

                if( this.autoplay ) this.loop();

            }

            Slideshow.prototype.CONST = {
                DIRECTION : {
                    NEXT : -1,
                    PREV : 1
                },
            }

            Slideshow.prototype.slide = function(direction){

                // if( !this.is_ready ) return;
                //
                // this.is_ready = false;

                var images = this.element.getElementsByClassName('slideshow__images')[0];
                var first_image = images.children[0];
                var remove_image;
                var copy_image;
                var target_image;

                if( direction === this.CONST.DIRECTION.NEXT ){

                    remove_image = first_image;
                    copy_image = remove_image.cloneNode(true);
                    copy_image.style.marginLeft = 0;
                    copy_image.style.transitionDuration = 'none';
                    images.appendChild(copy_image);
                    images.removeChild(remove_image);

                }else if( direction === this.CONST.DIRECTION.PREV ){

                    remove_image = images.children[images.children.length-1];
                    copy_image = remove_image.cloneNode(true);
                    first_image.style.marginLeft = 0;
                    images.insertBefore(copy_image, images.children[0]);
                    images.removeChild(remove_image);

                }

                 var target_image = images.children[0];
                 target_image.style.transitionDuration = this.duration + 's';
                 target_image.style.marginLeft         = '-' + this.margin_left + '%';

                 this.is_ready = true;

                 // if( direction === this.CONST.DIRECTION.PREV ){
                 //     target_image.addEventListener( 'transitionend', function removeImage(){
                 //         images.removeChild(remove_image);
                 //         target_image.removeEventListener( 'transitionend', removeImage, true );
                 //         this.is_ready = true;
                 //     }.bind(this),true);
                 // }

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
            var slideshow01 = new Slideshow({
                id       : 'sd',
                interval : 2,
                col    : { pc : 5 , sp : 1 },
                duration : 1,
                autoplay : false,
            });

    </script>

</section>



<?php get_footer(); ?>
