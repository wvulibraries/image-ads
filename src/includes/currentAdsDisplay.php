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
            'name'      => htmlSanitize($row['name']),
            'imageAd'   => $row['imageAd'],
            'ID'        => $row['ID'], 
            'enabled'   => $row['enabled'],
            'priority'  => $row['priority'],
            'altText'   => htmlSanitize($row['altText']),
            'actionURL' => htmlSanitize($row['actionURL']),
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
                print "<h2>" . $imgProperties . "</h2>";
            } 
            elseif($recordsIndex == "imageAd"){ 
                echo '<img src="', $imgProperties ,'"> ';  
            }
            elseif ($recordsIndex == "ID") {
                // do nothing so that it doesn't show that to the user
                $imgID = $imgProperties; 
            } 
            else if ($recordsIndex == "enabled"){
                print "<li>"; 
                if($imgProperties == 0 ){
                   print  "<div class='image-status disabled'><span class='display'> <i class='fa fa-eye-slash'></i> Disabled </span></div>";  
                } 
                else { 
                    print "<div class='image-status enabled'><span class='display'> <i class='fa fa-eye'></i> Enabled </span></div>";
                }
            }
            else if ($recordsIndex == "priority"){
                if($imgProperties == 0 ){
                   print  "<div class='image-priority low'> <span class='display'> <i class='fa fa-circle-thin'></i> Low Priority </span></div>";  
                } 
                else { 
                    print "<div class='image-priority high'><span class='display'> <i class='fa fa-exclamation-circle'></i> High Priority </span></div>";
                }
                print "</li>";
            } 
            else if ($recordsIndex == "altText" ) {
                print " <h3> Alt Text: </h3> <li>";  
                print $imgProperties . "</li>"; 
            }
            else if ($recordsIndex == "actionURL" ) {
                print " <h3> Link: </h3> <li>";  
                print "<a href='" . $imgProperties . "'>" . $imgProperties . "</a></li>"; 
            }
            else { 
                print "<li>" . $imgProperties . "</li>"; 
            }
        }        

        print " <h3> Display Dates: </h3> "; 
        
        foreach($imageRecords["display"] as $index => $displayRecords) { 
    
            foreach($displayRecords as $value => $dispRecord){ 
                // Print the Values but Only if they aren't Null and have a specific Time 
                if($value === "dateStart" && !isnull($dispRecord)) { 
                    print "<li>"; 
                    print "<span class='start-date-range'>";
                    print date("m/d/Y", $dispRecord) . " - "; 
                    print "</span>";
                } 
                elseif($value === "dateEnd" && !isnull($dispRecord)) {
                    print "<span class='end-date-range'>"; 
                    print date("m/d/Y", $dispRecord); 
                    print "</span> </li>";
                }
            }
        }

        print " <h3> Display Times: </h3> "; 
        
        foreach($imageRecords["display"] as $index => $displayRecords) { 
    
            foreach($displayRecords as $value => $dispRecord){ 
               if($value === "timeStart" && !isnull($dispRecord)) {
                    print "<li> <span class='start-time-range'>"; 
                    print date("h:i a", $dispRecord) .  " - "; 
                    print "</span>";
                }
                elseif($value === "timeEnd" && !isnull($dispRecord)) {
                    print "<span class='end-time-range'>"; 
                    print date("h:i a", $dispRecord); 
                    print "</span> </li>";
                } 
            }
        }

        print " <h3> Display Weekdays: </h3> "; 
        
        foreach($imageRecords["display"] as $index => $displayRecords) { 
            foreach($displayRecords as $value => $dispRecord){ 
               if($value === "weekdays" && !isnull($dispRecord)) { 
                    print "<li>" . $dispRecord . "</li>"; 
                }
            }
        }


        $baseDir = $localvars->get('baseDirectory'); 

        $editDir =  sprintf ("<a href='%s%s' class='%s'> EDIT IMAGE </a>",
                $baseDir,
                "/editImage/?imageID=$imgID",
                "edit button");

        $deleteDir =  sprintf ("<a href='%s%s' class='%s'> DELETE IMAGE </a>",
                $baseDir,
                "/deleteImage/?imageID=$imgID",
                "delete button");


        // Setup Buttons to pass the editing of the information into different forms
        print "<li>";
            print $editDir . " " . $deleteDir; 
        print "</li>";

        print "</ul>";
    }

?> 