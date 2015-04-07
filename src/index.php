
<?php 
	require_once "includes/engineHeader.php";
    templates::display('header'); 
    // Insert Form Definintions 
    recurseInsert("includes/imageForm.php","php"); 
    // Insert DipslayCurrentAds Logic 
    recurseInsert("includes/currentAdsDisplay.php", "php"); 
?>

<header> 
    <h1> Advertising Manager Home </h1>
</header> 

<section>
    <h2> Playspace for Testing date and time setup </h2>
    <?php 

//       // Setup Date Variables
//        $currentMonth = date(F); // current month 
//        $currentDay   = date(j); // day of the month 1-31
//        $dayOfWeek    = date(l); // day of the week
//        $currentYear  = date(Y); // year 
//    
//        // Unbroken Date 
//        $today = date("F j, Y, g:i a"); 
//
//        // Timestamps 
//        $currentTime = date("g:i a");
//        
//    
//       // Test Date Variables 
//        print $currentMonth . "<br/>";  
//        print $currentDay . "<br/>";  
//        print $dayOfWeek . "<br/>";  
//        print $currentYear . "<br/>"; 
//        print $currentTime . "<br/>"; 
//        print $today; 
//
//        
//        // Need to Look at the Users input and makes sure we can transfer it to a certain set of concepts
//        // If Enabled is False display conditions mean nothing



    ?> 
    
    <h2> Current Advertisements </h2>
    <p> List all of the current ads here. </p>
    //{local var="displayAllAds"}
    
    <br/><br/>
    <br/><br/>
    
    <h2> Search Current Ads </h2>
    <p> Search Filtering of all the Current Ads, probably will need JS to do this. </p>
    
    
    {{ PHP Template Here }} 
    
    <br/><br/>
    <br/><br/>
    
    <div id="UploadImageForm">
	   {form name="imageAdForm" display="form" addGet="true"}
        
        

       {form name="imageAdForm" display="edit" expandable="true" addGet="true"}
    </div>
    
    <div>
	</div>

</section>


<?php
    // Add JS in the Lower Part of the Body Before the footer to keep from blocking render 
    include 'includes/jsIncludes.php'; 
    templates::display('footer'); 
?>

