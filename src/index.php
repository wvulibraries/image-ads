
<?php 
	require_once "includes/engineHeader.php";
    templates::display('header'); 
    
    // Insert Form Definintions 
    recurseInsert("includes/imageForm.php","php"); 

    $db      = db::get($localvars->get('dbConnectionName')); // TELL WHAT DB TO CONNECT TO
    $sql     = sprintf("SELECT * FROM imageAds ORDER BY ID"); // SELECT ALL COLUMNS IN IMAGEADS AND ORDER THEM BY THE NAME
    
    //print "LOCAL VARS: " . $localvars->get('dbConnectionName'); 
    // USE THE SQL STATEMENT, AND THE DATABASE NAME TO MAKE THE CONNECTION AND PULL THE INFORMATION
    $result = $db->query($sql);    // caused PHP Fatal Error non-object
    
    // TESTING THE LOGIC ABOVE 
    if(!$result) { 
        die("Something went wrong with the database connection, please refresh your screen and try again. " . mysql_error());  // ERROR GOT NO RESULTS 
    } else { 
        // echo "Results Found";    
    }

  /*
   while($row = $result->fetch()) { 
       foreach($row as $entry) {
            print "\n <br/>" . $entry . "\n";    
       }
   } */

?>

<header> 
    <h1> Advertising Manager Home </h1>
</header> 

<section>
    <h2> Current Advertisements </h2>
    <p> List all of the current ads here. </p>
    
    <?php 
      
      
    ?>
    
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

