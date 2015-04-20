
<?php 
	require_once "includes/engine/engineHeader.php";
    templates::display('header'); 
    // Insert Form Definintions 
    recurseInsert("includes/forms/imageForm.php","php"); 
?>

<header> 
    <h1> Add New Image </h1>
</header> 

<section>
    <div id="UploadImageForm">
	   {form name="imageAdForm" display="form"}
       {form name="imageAdForm" display="edit" expandable="true" addGet="true"}
    </div>
</section>

<!-- 
===================================================================
// Need to insert some JS Here that shows the preivew of the image 
// in the formbuilder form so before the post the user 
// has the option to see what they have posted.
===================================================================
--> 


<?php
    // Add JS in the Lower Part of the Body Before the footer to keep from blocking render 
    include 'includes/jsIncludes.php'; 
    templates::display('footer'); 
?>