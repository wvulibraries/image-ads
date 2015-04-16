
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
    // Add JS in the Lower Part of the Body Before the footer to keep from blocking render 
    include 'includes/jsIncludes.php'; 
    templates::display('footer'); 
?>