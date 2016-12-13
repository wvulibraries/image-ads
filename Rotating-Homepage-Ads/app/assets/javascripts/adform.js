$(document).on('change', '.file-upload-input', function(){
  $(this).parent('.file-upload').attr('data-text', $(this).val().replace(/.*(\/|\\)/, ''));
  readURL(this);
});

function readURL(input){
  if(input.files && input.files[0]) {
      var new_file = new FileReader();
      new_file.onload = function(event){
        var $image = $('<img>',{id:'theImg',src:event.target.result, style:"width:100%; height:auto;"});
        console.log($image);
        $('.image-preview').html($image);
      };
      new_file.readAsDataURL(input.files[0]);
  }
}
