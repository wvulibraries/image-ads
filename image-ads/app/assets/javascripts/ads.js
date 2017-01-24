$(document).on('ready turbolinks:load', function(){
  if($('.ad-cards').length){
    $(window).on('load resize', function(){
      var tallest = 0;
      var actionHeight = $('.card-action').height() + 10;
        $('.ad-cards > li').each(function(){
            $(this).css('height','auto');
            if( ($(this).height() + actionHeight) > tallest){
              tallest = $(this).height() + actionHeight;
            }
        });

      $('.ad-cards > li').height(tallest);
    }).resize();
  }
});
