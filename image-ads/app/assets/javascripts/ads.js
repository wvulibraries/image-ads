$(document).on('ready turbolinks:load', function(){
  if($('.ad-cards').length){
    $(window).on('load resize', function(){
      var tallest = 0;
        $('.ad-cards > li').each(function(){
            $(this).css('height','auto');
            if( ($(this).height() + 75) > tallest){
              tallest = $(this).height() + 75;
            }
        });

      $('.ad-cards > li').height(tallest);
    }).resize();
  }
});
