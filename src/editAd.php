<?php
    require_once "includes/engine/engineHeader.php";
    templates::display('header'); 
    recurseInsert("includes/forms/imageForm.php","php");    

    // EDIT THE FORM VALUES OF THE FORMBUILDER FORM 
?>

<header> 
    <h1> Editing Image and Display Props </h1>
</header> 

<section>
    <div id="UploadImageForm">
       <!-- {form name="imageAdForm" display="insert"}
        {form name="imageAdForm" display="edit"} -->
       {form name="imageAdForm" display="insertUpdateForm"}
    </div>
</section>


