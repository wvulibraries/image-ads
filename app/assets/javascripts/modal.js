$(document).on('turbolinks:load ready', function(){
  $('body').on('click', '.modal-toggle', function(event){
     event.preventDefault();
     var target = $(this).data('target');
     $(target).show();

     console.log(target);
  });

  $('body').on('click', '.modal', function(event){
     console.log(event.target);
     if( $(event.target).hasClass('modal') || $(event.target).parent().hasClass('close-modal') ){
         $('.modal').hide();
     }
  });
});

$(document).keyup(function(e) {
  event.preventDefault();
  if($('.modal').length){
    if (e.keyCode === 27){ $('.modal').hide(); } // esc key closes modal
  }
});
