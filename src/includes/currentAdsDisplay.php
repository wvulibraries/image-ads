<?php
    
    //DB Connection and SQL Statements    
    $localvars = localvars::getInstance();
    $db        = db::get($localvars->get('dbConnectionName')); // TELL WHAT DB TO CONNECT TO
	$sql       = sprintf("SELECT imageAds.*, displayConditions.dateStart, displayConditions.dateEnd, displayConditions.weekdays, displayConditions.timeStart, displayConditions.timeEnd FROM imageAds LEFT JOIN displayConditions ON displayConditions.imageAdID = imageAds.ID");
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

    $displayAdRecords = array(); 
    while($row = $sqlResult->fetch()) {
        
        // A placeholder array that will be used for the basic info from the imageAds Table
        $tempAdArray = array(
            'ID'        => $row['ID'],
            'name'      => $row['name'], 
            'enabled'   => $row['enabled'],
            'priority'  => $row['priority'],
            'altText'   => $row ['altText'],
            'actionURL' => $row['actionURL'],
            'imageAd'   => $row['imageAd']
        ); 

        // The display Options Temp Array
        // Need to add something to search for if Null or Not Null 

        $tempDispArray = array(
            'dateStart' => $row['dateStart'],
            'dateEnd'   => $row['dateEnd'],
            'timeStart' => $row['timeStart'],
            'timeEnd'   => $row['timeEnd'],
            'weekdays'  => $row['weekdays']
        );

        
        $displayAdRecords[$row['ID']]["imageInfo"] = $tempAdArray; 
        
        // Create Boolean to Test conditions from 
        $hasDisplayOptions =  false; 
        // loop through temp disp array and see if the items are null 
        foreach($tempDispArray as $I=>$V) { 
            if(!is_empty($V)) {
                $hasDisplayOptions = true; 
            } else { 
                $hasDisplayOptions = false; 
            }
        }
        // Check the Boolean 
        // If True then add the stuff to the display records 
        // This is done so that it is only added once 
        if($hasDisplayOptions) {
            $displayAdRecords[$row['ID']]['display'][] = $tempDispArray; 
        }
    }   

    foreach($displayAdRecords as $imageRecords) {
        // Loop through the first Array which is really a placeholder for all arrays
        print "<pre>";
        print "<h2>IMAGE RECORDS</h2>"; 
        var_dump($imageRecords);
        print "</pre>";

        print "<ul class='current-images'>";

        // Loop through interior of arrays to get information such as image properties
        foreach($imageRecords["imageInfo"] as $imgProperties) {
            print $imgProperties;  
        }

        // Loop through the display conditions 
        foreach($imageRecords["display"] as $imgDisplay) { 
            foreach($imgDisplay as $displayProperty){
                print $displayProperty ."</br>";
            }
        } 

        // Setup Buttons to pass the editing of the information into different forms
        print "<li>";
            print "<a href='#'> EDIT IMAGE </a> |";
            print "<a href='#'> DELETE IMAGE </a> |";
            print "<a href='displayOptions.php?imageID=$record[ID]&imageName=$record[name]'> ADD DISPLAY PROPERTIES </a>"; 
        print "</li>";

        print "</ul>";
    }
   
?> 