<?php
    
    //DB Connection and SQL Statements    
    $localvars = localvars::getInstance();
    $db        = db::get($localvars->get('dbConnectionName')); // TELL WHAT DB TO CONNECT TO
	$sql       = sprintf("SELECT imageAds.*, displayConditions.dateStart, displayConditions.dateEnd, displayConditions.weekdays, displayConditions.timeStart, displayConditions.timeEnd FROM imageAds LEFT JOIN displayConditions ON displayConditions.imageAdID = imageAds.ID");
	$sqlResult = $db->query($sql);
    $data      = NULL;  
    $URLpath = "http://$_SERVER[HTTP_HOST]/admin/image_manager";

    // Testing the SQL Stuff
	if ($sqlResult->error()) {
		print "ERROR GETTING ADS  -- the error -- " . $sqlResult->errorMsg(); 
		return FALSE;
	}
    
	if ($sqlResult->rowCount() < 1) {
		print "NO ADS FOUND"; 
		return FALSE;
	}

    $displayAdRecords = array();  // Create an Array to parse the data in there 

    // Look at the stuff in the DB and actually fetch it 
    while($row = $sqlResult->fetch()) {
        
        // A placeholder array that will be used for the basic info from the imageAds Table
        $tempAdArray = array(
            'ID'        => $row['ID'],
            'name'      => htmlSanitize($row['name']), 
            'enabled'   => $row['enabled'],
            'priority'  => $row['priority'],
            'altText'   => htmlSanitize($row['altText']),
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
        $displayAdRecords[$row['ID']]['display'][] = $tempDispArray; 
        
        // // Create Boolean to Test conditions from 
        // $hasDisplayOptions =  true; 


        // // loop through temp disp array and see if the items are null 
        // foreach($tempDispArray as $I=>$V) { 
        //     if(!is_empty($V)) {
        //         $hasDisplayOptions = true; 
        //     } else { 
        //         $hasDisplayOptions = false; 
        //     }
        // }
        // // Check the Boolean 
        // // If True then add the stuff to the display records 
        // // This is done so that it is only added once 
        // if($hasDisplayOptions) {
        //     $displayAdRecords[$row['ID']]['display'][] = $tempDispArray; 
        // }
    }   

    
    foreach($displayAdRecords as $imageRecords) {
        $imgID; // Create Varible for the ID
        $imgName; // Creat Variable for the Name 

        // Loop through the first Array which is really a placeholder for all arrays
        print "<ul class='current-images'>";

        // Loop through interior of arrays to get information such as image properties
        foreach($imageRecords["imageInfo"] as $recordsIndex => $imgProperties) {
            if($recordsIndex == "name") {
                $imgName = $imgProperties; 
                print "<li> <h2>" . $imgProperties . "</h2></li>";
            } 
            elseif($recordsIndex == "imageAd"){ 
                echo '<img src="', $imgProperties ,'"> ';  
            }
            elseif ($recordsIndex == "ID") {
                // do nothing so that it doesn't show that to the user
                $imgID = $imgProperties; 
            } 
            else { 
                print "<li>" . $imgProperties . "</li>"; 
            }
        }
        // Check to make sure that the there are records for the iamgeRecords
        // If not we don't want them in our array because they will make the display look funny
        // if (!is_null($imageRecords["display"])) { 
        //      // Loop through the display conditions 
        //     foreach($imageRecords["display"] as $imgDisplay) { 
        //         print $imgDisplay;
        //     } 
        // }
        

        foreach($imageRecords["display"] as $index => $displayRecords) { 

    
            foreach($displayRecords as $value => $dispRecord){ 
                // print "Values  - " . $value . "<br>";
                // print "Display Options - " . $dispRecord . "<br>"; 
                // print "<pre>";
                // var_dump($dispRecord);
                // print "</pre>"; 

                // Print the Values but Only if they aren't Null and have a specific Time 
                if($value === "dateStart" && !isnull($dispRecord)) { 
                    print "<li> <strong> Display Dates: </strong>"; 
                    print "<span class='start-date-range'>";
                    print date("m/d/Y", $dispRecord) . " - "; 
                    print "</span>";
                } 
                elseif($value === "dateEnd" && !isnull($dispRecord)) {
                    print "<span class='end-date-range'>"; 
                    print date("m/d/Y", $dispRecord); 
                    print "</span> </li>";
                }
                elseif($value === "timeStart" && !isnull($dispRecord)) {
                    print "<li> <strong> Display Times: </strong> <span class='start-time-range'>"; 
                    print date("h:i a", $dispRecord) .  " - "; 
                    print "</span>";
                }
                elseif($value === "timeEnd" && !isnull($dispRecord)) {
                    print "<span class='end-time-range'>"; 
                    print date("h:i a", $dispRecord); 
                    print "</span> </li>";
                }
                elseif($value === "weekdays" && !isnull($dispRecord)) { 
                    print "<li><strong> Display on Weekdays </strong>" . $dispRecord . "</li>"; 
                }
           
            }
        }

        // Setup Buttons to pass the editing of the information into different forms
        print "<li>";
            print "<a href='editAd.php?imageID=$imgID'> EDIT IMAGE </a> |";
            print "<a href='deleteAd.php?imageID=$imgID'> DELETE IMAGE </a> |";
            print "<a href='displayOptions.php?imageID=$imgID&imageName=$imgName'> ADD DISPLAY PROPERTIES </a>"; 
        print "</li>";

        print "</ul>";
    }

?> 