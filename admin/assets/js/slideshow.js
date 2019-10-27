function Slideshow(args){

    this.element = document.getElementById(args.id);
    this.margin_left = args.width;
    this.interval = args.interval;
    this.duration = args.duration;
    this.auto = args.auto;
    this.last_time = 0;

    this.element.getElementsByTagName('button')[0].addEventListener('click',function(e){
        this.last_time = 0;
        this.slide(this.CONST.DIRECTION.PREV);
    }.bind(this));

    this.element.getElementsByTagName('button')[1].addEventListener('click',function(e){
        this.last_time = 0;
        this.slide(this.CONST.DIRECTION.NEXT);
    }.bind(this));

    var images = this.element.getElementsByClassName('slideshow__images')[0];
    var first_image = images.children[0];
    var last_image = images.children[images.children.length-1];
    var last_image_copy = last_image.cloneNode(true);

    images.insertBefore(last_image_copy, images.children[0]);
    images.removeChild(last_image);

    first_image = this.element.getElementsByClassName('slideshow__images')[0].children[0];

    first_image.style.transitionDuration = this.duration + 's';
    first_image.style.marginLeft = '-' + this.margin_left + '%';

    images.style.opacity = 1;

    if(this.auto){
        this.loop();
    }

}

Slideshow.prototype.CONST = {

    DIRECTION : {
        NEXT : -1,
        PREV : 1
    },

}

Slideshow.prototype.slide = function(direction){

    var images = this.element.getElementsByClassName('slideshow__images')[0];
    var first_image = images.children[0];

    console.log('CLICK!');


    if( direction === this.CONST.DIRECTION.NEXT ){

        var first_image_copy = first_image.cloneNode(true);
        first_image_copy.style.marginLeft = 0;
        first_image_copy.style.transitionDuration = 'none';
        images.appendChild(first_image_copy);
        images.removeChild(first_image);

    }else if( direction === this.CONST.DIRECTION.PREV ){

        var last_image = images.children[images.children.length-1];
        var last_image_copy = last_image.cloneNode(true);
        first_image.style.marginLeft = 0;
        images.insertBefore(last_image_copy, images.children[0]);
        images.removeChild(last_image);
    }

    first_image = this.element.getElementsByClassName('slideshow__images')[0].children[0];
    first_image.style.transitionDuration = this.duration + 's';
    first_image.style.marginLeft = '-' + this.margin_left + '%';

}

Slideshow.prototype.loop = function(){

    var self = this;

    (function loop(timestamp){

        self.timer = requestAnimationFrame(loop);

        if( ( timestamp - self.last_time )/1000 > self.interval ){
            self.slide(self.CONST.DIRECTION.NEXT);
            self.last_time = timestamp;
        }

    })();

}

// //実行
// var slideshow01 = new Slideshow({
//     id       : 'sd',
//     interval : 5,
//     width    : (100/4),
//     duration : 1,
//     auto     : true,
// });
