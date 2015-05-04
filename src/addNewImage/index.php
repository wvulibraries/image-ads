<?php 
	require_once "../engineHeader.php";
    templates::display('header'); 
    // Insert Form Definintions 
    recurseInsert("../includes/forms/imageForm.php","php"); 
?>

<header> 
    <h1> Add New Image </h1>
</header> 

<section>
    <div id="UploadImageForm">
	   {form name="imageAdForm" display="form"}
    </div>
</section>

<?php
    // Add JS in the Lower Part of the Body Before the footer to keep from blocking render 
    include '../includes/jsIncludes.php'; 
    templates::display('footer'); 
?>