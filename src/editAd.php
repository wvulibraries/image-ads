<?php
    require_once "engineHeader.php";
    templates::display('header'); 
    recurseInsert("includes/forms/ImageForm.php","php");    

    // EDIT THE FORM VALUES OF THE FORMBUILDER FORM 
?>

<header> 
    <h1> Editing Image and Display Props </h1>
</header> 

<section>
    <div id="editImage">
        {form name="imageAdForm" display="form"} 
    </div>
</section>

<section> 
    <a href="addNewImage.php" class="button"> Add New Image </a>
    <a href="index.php" class="button"> Back to Home </a>
</section>

<?php
    templates::display('footer'); 
    recurseInsert("includes/jsIncludes.php","php");
?>


