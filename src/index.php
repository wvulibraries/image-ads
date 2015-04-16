
<?php 
	require_once "includes/engine/engineHeader.php";
    templates::display('header'); 
?>

<header> 
    <h1> Advertising Manager Home </h1>
</header> 

<section> 
    <?php 
         // Insert DipslayCurrentAds Logic 
        recurseInsert("includes/currentAdsDisplay.php", "php");
    ?>
</section>

<section> 
    <a href="addNewImage.php" class="button"> Add New Image </a>
</section>

<?php
    // Add JS in the Lower Part of the Body Before the footer to keep from blocking render 
    include 'includes/jsIncludes.php'; 
    templates::display('footer'); 
?>