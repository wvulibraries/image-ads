<?php 
   //  Do nothing in here, but recursively inserting this file will keep me from repeating myself 
   echo "PRINT";
?> 

 <!-- Add JS in the Lower Part of the Body Before the footer to keep from blocking render  -->
<!-- Running JS inside of the PHP File allows to echo php functions  -->
<script> 
    // Date Range Add and Removes
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
</script> 
