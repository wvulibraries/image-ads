
<?php 
	require_once "includes/engine/engineHeader.php";
    templates::display('header'); 
    // Insert Form Definintions 
    recurseInsert("includes/forms/dispOptionsForm.php","php");
?>

<header> 
    <h1> Display Options Page </h1>
</header> 

<section>
    <div class="display-options">
        {form name="displayOptions" display="form" addGet="true"}
    </div>  
  <!--   <a href="javascript:void(0);" class="button moreConditions"> Add More Conditions </a> -->
</section>


<?php
    templates::display('footer'); 
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
