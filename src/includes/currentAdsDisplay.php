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

    // print "<pre>";
    // var_dump($row);
    // print "</pre>";

        
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

        // print "<pre>";
        // var_dump($tempAdArray);
        // print "</pre>";




        // The display Options Temp Array
        // Need to add something to search for if Null or Not Null 

        $tempDispArray = array(
            'dateStart' => $row['dateStart'],
            'dateEnd'   => $row['dateEnd'],
            'timeStart' => $row['timeStart'],
            'timeEnd'   => $row['timeEnd'],
            'weekdays'  => $row['weekdays']
        );

        // @TODO 
            // I think this can be made less diving to get to information 
            // Look at ways to take down the number of nested arrays 

        $displayAdRecords[$row['imageAdID']]["imageInfo"][] = $tempAdArray; 
        
        // Use Is null to check to make sure the display options aren't showing
        // switched to is empty for empty form fields since its valid to have no
        // items entered in the field. 

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
            $displayAdRecords[$row['imageAdID']][][] = $tempDispArray; 
        }
    }  

    print "<pre>";
    var_dump($displayAdRecords);
    print "</pre>";   

    foreach($displayAdRecords as $imageRecords) {
        // Loop through the first Array which is really a placeholder for all arrays
        //var_dump($imageRecords);
       
        // $image Records is going to have an array with the value of more arrays
        // they will be the number of total images in the db
        foreach($imageRecords as $image) {
            // print "<pre>";
            // var_dump($image);
            // print "</pre>";

            foreach($image as $record){
                // print "<pre>";
                // var_dump($record);
                // print "</pre>";

                print "<ul class='current-images'>";
                    foreach($record as $I => $imgProperties) { 
                        if($I == "ID") { 
                            // Do nothing  
                        } elseif ($I == "imageAd") {
                            // Do Nothing
                        } else { 
                            print "<li>"; 
                            print $imgProperties; 
                            print "</li>";
                        }

                    }

                    print "<li>";
                        print "<a href='#'> EDIT IMAGE </a> |";
                        print "<a href='#'> DELETE IMAGE </a> |";
                        print "<a href='displayOptions.php?imageID=$record[ID]&imageName=$record[name]'> ADD DISPLAY PROPERTIES </a>"; 
                    print "</li>";

                print "</ul>";
            }
        }
    }
   
?> 