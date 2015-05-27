<?php
   //  Do nothing in here, but recursively inserting this file will keep me from repeating myself
?>

 <!-- Add JS in the Lower Part of the Body Before the footer to keep from blocking render  -->
<!-- Running JS inside of the PHP File allows to echo php functions  -->
<script>
    // Date Range Add and Removes
    if($('.addDateRange').length){
        $('.addDateRange').click(function(){
           $('.addDateRange').parent().append('<?php echo addDateRanges(); ?>');
        });

        $('.deleteDateRange').click(function(){
            $('.inputs:last-child').remove();
        });

        // Time Range Add and Removes
        $('.addTimeRange').click(function(){
             $('.addTimeRange').parent().append('<?php echo addTimeRanges(); ?>');
        });

        $('.deleteTimeRange').click(function(){
           $('.times:last-child').remove();
        });
    }

    if($('#DeleteButton').length){
        $('#DeleteButton').click(function(event){
            event.preventDefault();
            location.href = '{local var="deleteButtonLink"}';
        });
    }

    if($('#UploadImageForm').length) {
        var fileUpload  = $('#imageAd');

        fileUpload.parent().append('<div class="imagepreview"></div>');
        var imgcontainer = $('.imagepreview');

        fileUpload.change(function(){
           var theFile      = this.files,
               maxFileSize  = "1000000",
               fileLength   = theFile.length;

           if(fileLength && theFile[0].type.match('image.*') && (theFile[0].size < maxFileSize)) {
                imgcontainer.html('<img id="imgPrev" alt="preview for image that is going to be uploaded"/>');

                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imgPrev').attr('src', e.target.result);
                }
                reader.readAsDataURL(theFile[0]);

           } else {
              if(theFile[0].size >= maxFileSize && theFile[0].type.match('image.*')) {
                 imgcontainer.html("<div class='error'>Filesize is too large.  Please use a smaller image.</div>");
              } else {
                 imgcontainer.html("<div class='error'> You can only upload images, please try again!</div>");
              }
              fileUpload.replaceWith(fileUpload = fileUpload.clone(true));
           }

        });

    }
</script>
