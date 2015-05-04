
<?php 
	require_once "engineHeader.php";
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

<?php
    templates::display('footer'); 
?>