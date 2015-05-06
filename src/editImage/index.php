<?php
    require_once "../engineHeader.php";
    templates::display('header');
    recurseInsert("../includes/forms/ImageForm.php","php");
?>

<header>
    <h1> Editing Image and Display Props </h1>
</header>

<section>
    {local var="feedbackStatus"}
    <div id="editImage">
        {form name="imageAdForm" display="form"}
    </div>
</section>

<?php
    templates::display('footer');
    recurseInsert("includes/jsIncludes.php","php");
?>


