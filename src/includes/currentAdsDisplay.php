<?php
    
    //DB Connection and SQL Statements    
    $localvars = localvars::getInstance();
    $db        = db::get($localvars->get('dbConnectionName')); // TELL WHAT DB TO CONNECT TO
	$sql       = sprintf("SELECT * FROM imageAds LEFT JOIN displayConditions ON displayConditions.imageAdID = imageAds.ID");
	$sqlResult = $db->query($sql);
    $data      = NULL;  
    $URLpath = "http://$_SERVER[HTTP_HOST]/admin/image_manager";
    
	if ($sqlResult->error()) {
		print "ERROR GETTING ADS  -- the error -- " . $sqlResult->error(); 
		return(FALSE);
	}
    
	if ($sqlResult->rowCount() < 1) {
		print "NO ADS FOUND"; 
		return FALSE;
	}

    // Build an Arry out of the Data 
    // This array will make each record a single record, but allow it to have multiple displays

    // $displayAdArray = array();
    // while ($row = $sqlResult->fetch()) {
    //     // Populate an array for all the image ads


    //     // $optionsArray = array();
    //     // $optionsArray['dateStart'] = $row['dateStart'];

    //     // $temp = array( 
    //     //     'name' => $row['name']
    //     //     );

    //     // $foo[$row['imageAdID']][] = $temp;

    //     // $foo[$row['imageAdID']]['options'][] = $optionsArray;

    // }
    
    // print "<pre>";
    // var_dump($foo);
    // print "</pre>";

    $displayAdRecords = array(); 
    while($row = $sqlResult->fetch()) {
        
        // A placeholder array that will be used for the basic info from the imageAds Table
        $tempAdArray = array(
            'name' => $row['name'], 
            'enabled' => $row['enabled'],
            'priority' => $row['priority'],
            'altText' => $row ['altText'],
            'actionURL' => $row['actionURL'],
            'imageAd' => $row['imageAd']
        ); 

        // The display Options Temp Array
        $tempDispArray = array(
            'dateStart' => $row['dateStart'],
            'dateEnd' => $row['dateEnd'],
            'timeStart' => $row['timeStart'],
            'timeEnd' => $row['timeEnd'],
            'weekdays' => $row['weekdays']
        );

        $displayAdRecords[$row['imageAdID']]["imageInfo"] = $tempAdArray; 
        $displayAdRecords[$row['imageAdID']][] = $tempDispArray;  
    }  

    print "<pre>";
    var_dump($displayAdRecords);
    print "</pre>";   



    // Working on displaying our current Ads and also setting up the information to render out on a website 
    //$localvars->set("displayAllAds",);

    //getAllAds(); 
?> 