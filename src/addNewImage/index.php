<?php
	require_once "../engineHeader.php";
    templates::display('header');
    recurseInsert("../includes/forms/imageForm.php","php");
?>

<header>
    <h1> Add New Image </h1>
</header>

<section>
    {local var="feedbackStatus"}
    <div id="UploadImageForm">
	   {form name="imageAdForm" display="form"}
    </div>
</section>

<?php
    include '../includes/jsIncludes.php';
    templates::display('footer');
?>