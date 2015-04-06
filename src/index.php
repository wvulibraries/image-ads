
<?php 
	require_once("includes/engineHeader.php"); 
    templates::display('header'); 
    // Insert Form Definintions 
     recurseInsert("includes/imageForm.php","php"); 
?>

<header> 
    <h1> Advertising Manager Home </h1>
</header> 

<section>
    <h2> Current Advertisements </h2>
    <p> List all of the current ads here. </p>
    
    {{ PHP Template Here }} 
    
    <br/><br/>
    <br/><br/>
    
    <h2> Search Current Ads </h2>
    <p> Search Filtering of all the Current Ads, probably will need JS to do this. </p>
    
    
    {{ PHP Template Here }} 
    
    <br/><br/>
    <br/><br/>
    
    <div id="UploadImageForm">
	   //{form name="imageAdForm" display="form" addGet="true"}
    </div>
    
    <div>
	</div>

</section>


<?php
    // Add JS in the Lower Part of the Body Before the footer to keep from blocking render 
    include 'includes/jsIncludes.php'; 
    templates::display('footer'); 
?>

