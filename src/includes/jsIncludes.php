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
           addEventsToDom();
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

           }
           else {
              if(theFile[0].size >= maxFileSize && theFile[0].type.match('image.*')) {
                 imgcontainer.html("<div class='has-error'>Filesize is too large.  Please use a smaller image.</div>");
              }
              else {
                 imgcontainer.html("<div class='has-error'> You can only upload images, please try again!</div>");
              }
              fileUpload.replaceWith(fileUpload = fileUpload.clone(true));
           }

        });
    }

    $(function() {
        addEventsToDom();
    });

    function addEventsToDom(){
        if($('.inputs').length){
            createDateRangesArray();
            $('.start_date').change(createDateRangesArray);
            $('.end_date').change(createDateRangesArray);
        }
        if($('.times').length) {
            createTimeTest();
            $('.time_start').change(createTimeTest);
            $('.time_end').change(createTimeTest);
        }
        //console.log('Events Function: ' + 'Inputs Length -' + $('.inputs').length );
    }

    function createDateRangesArray(){
        var dateRanges = [];
        $('.inputs').each(function() {
            var startDates = dateElmArray($(this).children('.start_date'));
            var endDates   = dateElmArray($(this).children('.end_date'));
            var tempCheck  = startDates.toString() + ", " + endDates.toString();

            if(startDates >= endDates) {
                //console.log("Issues with the start date");
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
                //console.log("fixed");
                $(this).removeClass("error");
                $(this).children('.has-error').hide();
                $('input:submit').removeAttr('disabled', 'disabled').removeClass('disabled');
            }

            dateRanges.push(tempCheck);
            //console.log(dateRanges);
        });
    }

    function createTimeTest(){
        var startRanges = [];
        var endRanges = [];
        $('.times').each(function() {
            var startTimes     = timeElmArray($(this).children('.time_start'));
            var endTimes       = timeElmArray($(this).children('.time_end'));
            var tempCheckStart = startTimes.toString();
            var tempCheckEnd   = endTimes.toString();

            if(startTimes >= endTimes) {
                $(this).addClass("error");
                $(this).append("<div class='has-error'> Your start time can't be before your end time! <br><br> </div>")
                $('input:submit').attr('disabled', 'disabled').addClass('disabled');
            }
            else if(checkOverlappingTimes(startRanges, endRanges, startTimes, endTimes)) {
                $(this).addClass("error");
                $(this).append("<div class='has-error'> Time ranges overlap. <br><br> </div>")
                $('input:submit').attr('disabled', 'disabled').addClass('disabled');
            }
            else {
                $(this).removeClass("error");
                $(this).children('.has-error').hide();
                $('input:submit').removeAttr('disabled', 'disabled').removeClass('disabled');
            }

            startRanges.push(tempCheckStart);
            endRanges.push(tempCheckEnd);
        });
    }

    function checkOverlappingTimes(startArray, endArray, startTime, endTime){
        if(startArray.length === endArray.length) {
            for(var i = 0; i <= startArray.length; i++) {
                if((startArray[i] <= endTime) && (startTime <= endArray[i])) {
                    return true;
                }
                else {
                    return false;
                }
            }
        }
    }

    function timeElmArray(elm){
        var tempArray   = [],
            returnArray = [];

        elm.each(function() {
            tempArray.push(this.value);
        });

        for(var i = 0; i <= tempArray.length; i+=2) {
            var hour = tempArray.shift();
            var min  = tempArray.shift();

            var timestamp = hour + min;
            return timestamp;
        }
    }

    function dateElmArray(elm) {
        var tempArray   = [],
            returnArray = [];

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


</script>
