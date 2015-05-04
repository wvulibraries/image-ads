<?php
    require_once "includes/engine/engineHeader.php";
    templates::display('header'); 
    recurseInsert("includes/forms/editForm.php", "php");
    // recurseInsert("includes/forms/editImageForm.php","php");    


    // EDIT THE FORM VALUES OF THE FORMBUILDER FORM 
?>

<header> 
    <h1> Editing Image and Display Props </h1>
</header> 

<section>
    <div id="editImage">
      <!--  {form name="imageAdForm" display="edit"} -->
      {form name="editAdForm" display="form"} 
      <!-- {form name="editImage" display="insert"} --> 
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


