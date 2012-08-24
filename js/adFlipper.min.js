(function($){
    $.fn.adFlipper = function(options){
        var settings = $.extend({}, $.fn.adFlipper.option, options);

        return this.each(function(){
            // Hook - beforeLoad
            __debug('START!');

            settings.beforeLoad();

            // Wrap all the slides in our slideContent div
            var sliderElem = $(this);
            sliderElem.children().addClass('slideContent');
            // sliderElem.wrapInner('<div class="slideContents">'); // edit mike
            var slideContents = $('#rotatingImgs', sliderElem);
            // var slideContents = $('.slideContents', sliderElem); // edit mike

            // Loop through all the slides, add the slideContent class, and find the larges img size (and set that as the container size)
            var maxWidth=0, maxHeight=0;
            if(typeof(settings.width) == 'undefined' || typeof(settings.height) == 'undefined'){
                $(this).children(':not(.clearer)').each(function(){
                    var imgElem   = $(this).find('img');
                    if(imgElem.length != 0){
                        if(imgElem.width() > maxWidth)   maxWidth  = imgElem.width();
                        if(imgElem.height() > maxHeight) maxHeight = imgElem.height();
                    }
                });
            }

            // Apple the maxWidth, and maxHeight to the container
            if(typeof(settings.width)  != 'undefined')  maxWidth  = settings.width;
            if(typeof(settings.height) != 'undefined') maxHeight = settings.height;
            slideContents.width(maxWidth);
            slideContents.height(maxHeight);

            // Define some vars we'll be needing
            var slides = $('.slideContent',sliderElem),
                total  = slides.children().size(),
                paused = false,
                playInterval, currentIndex=0, paginationElem;

            // Build pagination stuff
            if(settings.pagination){
                paginationElem = $('<ul class="'+settings.paginationClass+'"></ul>');
                if (settings.prependPagination) {
                    sliderElem.prepend(paginationElem);
                } else {
                    sliderElem.append(paginationElem);
                }
                // for each slide create a list item and link
                slides.each(function(i,n){
                    var paginationNode = $('<li><a href="javascript:;">'+(i+1)+'</a></li>');
                    paginationNode.click(function(){
                        pause();
                        goto(i);
                        play();
                    });
                    paginationElem.append(paginationNode);
                });
                // paginationElem.after('<div style="clear: left;"></div>'); // Edit Mike
                slideContents.height( slideContents.height()+$('.'+settings.paginationClass+' li').height()+5  );
            }

            // Show the 1st slide
            goto(0);

            // Hook - slidesLoaded
            settings.slidesLoaded();

            // is there only one slide?
            if(total == 1) return false;
            // If pauseOnHover is true, register play() and pause()
            if(settings.pauseOnHover){
                __debug("pauseOnHover");
                slides.each(function(i,n){
                    $(n).hover(function(){pause()}, function(){play()});
                });
            }

            function play() {
                __debug("play()");
                if(paused){
                    stop(); // This resets the timmer, otherwise odd things will happen when 2 events fire out of sync
                    start();
                    paused = false;
                }
            }
            function pause(){
                __debug("pause()");
                if(!paused){
                    stop();
                    paused = true;
                }
            }
            function next() {
                __debug("next()");
                if(!paused){
                    currentIndex++;
                    if(currentIndex > total-1) currentIndex=0;
                    goto(currentIndex);
                    start();
                }
            }
            function prev() {
                __debug("prev()");
                if(!paused){
                    currentIndex--;
                    if(currentIndex < 0) currentIndex=total-1;
                    goto(currentIndex);
                    start();
                }
            }
            function goto(slide){
                __debug("goto()");

                if(slide < 0) slide = 0;
                if(slide > total-1) slide = total-1;
                currentIndex = slide;

                // Hook - beforeChange
                settings.beforeChange();
                // Change the slide content
                $('.slideContent:visible', sliderElem).hide();
                $('.slideContent:eq('+currentIndex+')', sliderElem).show();
                // Change the slide pagination
                if(settings.pagination){
                    $('.'+settings.paginationClass+' li.current', sliderElem).removeClass('current');
                    $('.'+settings.paginationClass+' li:eq('+currentIndex+')', sliderElem).addClass('current');
                    $('.slideContent:eq('+currentIndex+')', sliderElem).show();
                }
                // Hook - afterChange
                settings.afterChange();
            }
            function start(){
                __debug("start()");

                paused = false;
                // create the interval timmer
                playInterval = setTimeout(function() {
                    __debug('Timer Tick');
                    next();
                }, settings.pauseTime);
                // store time for later
                sliderElem.data('interval',playInterval);
            }
            function stop(slide){
                __debug("stop()");

                paused = true;
                // Clear the interval timer
                clearTimeout(sliderElem.data('interval'));
                // If slideID was given, show it (this lets us set which slide to stop on
                if(typeof(slide) != "undefined") goto(slide);
            }
            function __debug(msg){
                if(settings.debug && typeof(console) != "undefined") console.log(msg);
            }

            // Autoplay
            if(settings.autoPlay) start();
        });
    };


    $.fn.adFlipper.option = {
        debug: false,
        autoPlay: true,
        pauseTime: 3000,
        pauseOnHover: true,
        pagination: false,
        prependPagination: false,
        paginationClass: 'adFlipperPagination',
        beforeChange: function(){},
        afterChange: function(){},
        beforeLoad: function(){},
        afterLoad: function(){},
        slidesLoaded: function(){}
    };

})(jQuery);