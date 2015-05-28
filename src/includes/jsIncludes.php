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
                 imgcontainer.html("<div class='has-error'>Filesize is too large.  Please use a smaller image.</div>");
              } else {
                 imgcontainer.html("<div class='has-error'> You can only upload images, please try again!</div>");
              }
              fileUpload.replaceWith(fileUpload = fileUpload.clone(true));
           }

        });
    }

    if($('.inputs').length){
        createDateRangesArray();
        $('.start_date').change(createDateRangesArray);
        $('.end_date').change(createDateRangesArray);
    }

    function createDateRangesArray(){
        var dateRanges = [];
        $('.inputs').each(function() {
            var startDates = dateElmArray($(this).children('.start_date'));
            var endDates   = dateElmArray($(this).children('.end_date'));
            var tempCheck = startDates.toString() + ", " + endDates.toString();

            if(startDates >= endDates) {
                console.log("Issues with the start date");
                $(this).addClass("error");
                $(this).append("<div class='has-error'> Your start date can't be before your end date! <br><br> </div>")
                $('input:submit').attr('disabled', 'disabled').addClass('disabled');
            }
            else if($.inArray(tempCheck, dateRanges) > -1){
                $(this).addClass("error");
                $(this).append("<div class='has-error'> You can't have the same date multiple times! </div> ");
                $('input:submit').attr('disabled', 'disabled').addClass('disabled');
            }
            else {
                console.log("fixed");
                $(this).removeClass("error");
                $(this).children('.has-error').hide();
                $('input:submit').removeAttr('disabled', 'disabled').removeClass('disabled');
            }

            dateRanges.push(tempCheck);
            console.log(dateRanges);
        });
    }

    function dateElmArray(elm) {
        var tempArray   = [];
        var returnArray = [];

        elm.each(function() {
            tempArray.push(this.value);
        });

        for(var i = 0; i<=tempArray.length; i+=3){
            var month = tempArray.shift();
            var day   = tempArray.shift();
            var year  = tempArray.shift();
            var timeStamp = month +"-"+ day +"-"+ year;
            return Date.parse(timeStamp)/1000;
        }
    }

    // function compareArrays(array1, array2) {
    //     if(array1.length === array2.length) {
    //         var i = array1.length;
    //         while(i--){
    //             if(array1[i] === array2[i]) {
    //                 return false;
    //             }
    //         }
    //         return true;
    //     }
    // }

</script>
