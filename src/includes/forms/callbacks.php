<?php

// Process New Image Callback 
// ========================================
function processNewImage() { 
 $localvars = localvars::getInstance(); 

 // Grab the POST Data 
 $imgForm = $_POST['MYSQL'];

 // print "<pre>"; 
 // var_dump($imgForm);
 // print "</pre>";

 // new array for the image info 
    $imgInfo = array(   
        "__formID"  => $imgForm["__formID"],
        "name"      => $imgForm['name'],
        "enbaled"   => $imgForm['enabled'], 
        "priority"  => $imgForm['priority'],
        "altText"   => $imgForm['altText'],
        "actionURL" => $imgForm['actionURL'],
        "altText"   => $imgForm['altText']
    ); 

 // new array for the display conditions 
    $imgDisplayConditions = array(
        "dateStart" => $imgForm["dateStart"],
        "dateEnd"   => $imgForm["dateEnd"],
        "timeStart" => $imgForm["timeStart"],
        "timeEnd"   => $imgForm["timeEnd"],
        "weekdays"  => $imgForm["weekdays"]
    ); 

    $localvars->set("displayConditionsTemp", $imgDisplayConditions);

 // Check if there is a file 
    if(isset($_FILES['imageAd'])) { 
      // if file exsists then look at the info and pass that into the 
      // upload function -- upload function will test the image 
       $imageInfo = $_FILES['imageAd']; 
       $imageData = imageUpload($imageInfo); 
    } else { 
        // No image was loaded or something wasn't right
        echo "Fail!"; 
        return FALSE;
    }

    // Check to see if the Display Options exsist to run that information on the same callback 
    // If it does we are going to go to the do run the commented function below 
    // Needs to happen after the insert of the imageAds so I can then pull the imageAd ID
    // addDisplayConditions($imgInfo)

    $imgInfo['imageAd'] = $imageData; // set the image to go back with the post 
    return $imgInfo;
}   


// Process the Display Information 
// after the Insert has happened 
// ========================================
function processDisplayInformation($processor) { 
   //  Need to figure out how to get the ID of the last submitted item  
    $localvars = localvars::getInstance();
    $db  = db::get($localvars->get('dbConnectionName')); 

    // Get the ID of the last insert 
    // Format the data from the local var 
    // Add the ID to the data 
    $imageID = $processor->insertID; 
    $imgDisplayConditions = $localvars->get("displayConditionsTemp");
    $imgDisplayConditions['imageAdID'] = $imageID;

    //  Run the functions that will process each bit of data indpendently 
    addDisplayConditions($imgDisplayConditions); 

}



// Processing the Image and Uploading It 
// ========================================
function imageUpload($filedata){ 
    // Test The Image Stuff 
    $maxFileSize = 1000000; // 1mb  
    $fileTypesAllowed = array("image/gif", "image/png", "image/jpeg", "image/jpg");  

    $theImageData = base64_encode(file_get_contents($filedata['tmp_name']));
    $theImageMimeType = $filedata['type']; 
    $theImageDataURI = "data:" . $theImageMimeType . ";" . 'base64,' . $theImageData; 

    // Test to see if the image isn't too big & is an image 
    if($filedata['size'] < $maxFileSize && in_array($filedata['type'], $fileTypesAllowed)) { 
        return $theImageDataURI;
    } else {
        echo "No Image was uploaded!"; 
        return FALSE; 
    }
}


// DISPLAY OPTIONS SUBMISSIONS 
//=========================================
function addDisplayConditions($dispCondData){
    if(!isnull($dispCondData)) { 
        // Run the function to handle the display conditions for start dates 
         insertingDates($dispCondData); 
         insertingTimes($dispCondData);
         insertWeekdays($dispCondData);
    }
}

// DATE FUNCTIONS FOR CALLBACKS 
// ===========================================
function insertingDates($formInfo){ 
    // Adjust them with to Unix timestamps
    $startDates =  adjustDates($formInfo["dateStart"]); 
    $endDates = adjustDates($formInfo["dateEnd"]);

    // DB Stuff 
    $localvars = localvars::getInstance();
    $db  = db::get($localvars->get('dbConnectionName'));

    // We dont' want to add complete null stuff 
    if(!$startDates == NULL) { 

        // Setup the DB query 
        $sql = sprintf("INSERT INTO displayConditions (imageAdID,dateStart,dateEnd, weekdays,timeStart,timeEnd) VALUES (?,?,?,?,?,?)");

        // Loop through the Date Ranges and Push them into the DB 
        for($dateIteration = 0; $dateIteration < count($startDates); $dateIteration++) { 
            $insertSQL = array($formInfo['imageAdID'],$startDates[$dateIteration],$endDates[$dateIteration], NULL, NULL, NULL);
            $db->query($sql, $insertSQL); 
        }
    }
}

function adjustDates($formData){ 
   $returnDates = array(); 
   // Loop through the array 3 numbers at a time 
   // pull out the first value of each 
   // count the number of items in the array outside of the for loop
   // if not the loop is always short after the second loop
   $numofIterations = count($formData); 

   for($i=0; $i<$numofIterations; $i+=3) { 
        $month = array_shift($formData); 
        $day   = array_shift($formData); 
        $year  = array_shift($formData); 

        // $newDateString = $month . "/" . $day . "/" . $year; 
        $unixDate = mktime(0,0,0,$month,$day,$year);
        // $unixDate = date("U", strtotime($newDateString));
        array_push($returnDates, $unixDate); 
    }
    return $returnDates;
}

// Adjust Time Into Time Stamps 
function adjustTimes($formInfo){
    $returnTimes = array(); // array to send info back to the insert function 
    $numOfTimes  = count($formInfo); 

    // Loop through the times by 2 so that I can grab hour and minute
    for($i=0; $i < $numOfTimes;  $i+=2){
        $hour = array_shift($formInfo); 
        $min  = array_shift($formInfo);

        $theTimes = mktime($hour,$min,0,0,0,0); 
        array_push($returnTimes, $theTimes); 
    }

    return $returnTimes;
}

// Insert Time into the DB  
function insertingTimes($formData){ 
    $startTimes = adjustTimes($formData["timeStart"]); 
    $endTimes = adjustTimes($formData["timeEnd"]); 

    // DB Stuff 
    $localvars = localvars::getInstance();
    $db  = db::get($localvars->get('dbConnectionName'));

    // We dont' want to add complete null stuff 
    if(!$startTimes == NULL) { 
        // Setup the DB query 
        $sql = sprintf("INSERT INTO displayConditions (imageAdID,dateStart,dateEnd, weekdays,timeStart,timeEnd) VALUES (?,?,?,?,?,?)");
        // Loop through the Date Ranges and Push them into the DB 
        for($timeIt = 0; $timeIt < count($startTimes); $timeIt++) { 
            $insertSQL = array($formData['imageAdID'], NULL, NULL, NULL, $startTimes[$timeIt], $endTimes[$timeIt]);
            $db->query($sql, $insertSQL); 
        }
    }
}

// Inserting Weekday Values 
// ===========================================
function insertWeekdays($formData) { 
    // DB Stuff 
    $localvars = localvars::getInstance();
    $db  = db::get($localvars->get('dbConnectionName'));

    if(!$formData['weekdays'] == NULL) { 
        $sql = sprintf("INSERT INTO displayConditions (imageAdID,dateStart,dateEnd, weekdays,timeStart,timeEnd) VALUES (?,?,?,?,?,?)");
        
        $weekArrays = implode(",", $formData['weekdays']); 
        $insertSQL = array($formData['imageAdID'], NULL, NULL, $weekArrays, NULL, NULL);
        $db->query($sql,$insertSQL);
    }
}

// Process Updating Certain Rows of Data 
// ===========================================

// Note that there has to be a better way of processing the update using the 
// functions that are already made for inserting 
// There should also be some magic button that turns an insert form into an update form
// that would make the form builder asset much better and easier to use in these
// situations 

function processUpdate() { 
    // Get the Posted Data 
    $editedFormData = $_POST['MYSQL'];

    // Image ID
    $imageID = $_GET['MYSQL']['imageID']; 

    // new array for the image info 
    $imgInfo = array(   
        "__formID"  => $editedFormData["__formID"],
        "name"      => $editedFormData['name'],
        "enabled"   => $editedFormData['enabled'], 
        "priority"  => $editedFormData['priority'],
        "altText"   => $editedFormData['altText'],
        "actionURL" => $editedFormData['actionURL']
    ); 

 // new array for the display conditions 
    $imgDisplayConditions = array(
        "dateStart" => $editedFormData["dateStart"],
        "dateEnd"   => $editedFormData["dateEnd"],
        "timeStart" => $editedFormData["timeStart"],
        "timeEnd"   => $editedFormData["timeEnd"],
        "weekdays"  => $editedFormData["weekdays"]
    ); 

    // Send off the info to be processed by other functions 
    updateImageAd($imgInfo,$imageID); 
    updateImageDispOptions($imgDisplayConditions, $imageID);
    // return $imgInfo;
}

function updateImageAd($data,$id) { 
  // DB Stuff 
    $localvars = localvars::getInstance();
    $db  = db::get($localvars->get('dbConnectionName'));

  // SQL
    if(!isnull($data)) {
        $sql = sprintf("UPDATE `imageAds` SET `name` = ?, `enabled` = ?, `priority` = ? , `altText` = ?, `actionURL` = ? WHERE `ID` = %s", $id);
        $sqlArray = array($data['name'],$data['enabled'],$data['priority'],$data['altText'],$data['actionURL']); 
        $sqlResult = $db->query($sql,$sqlArray); 
    }
    if($sqlResult) { 
        echo " Results Updated "; 
    } else { 
        echo "Fail!";
    }
}

// Avoiding Repition 
// Deleting  Records  &  Adding the new ones for the display Options 
function updateImageDispOptions($formInfo, $id){ 
    // DB Stuff 
    $localvars = localvars::getInstance();
    $db  = db::get($localvars->get('dbConnectionName'));

    // Delete the Records So that we don't have to try and guess what is new and what is old data
    $sql = sprintf("DELETE FROM displayConditions WHERE `imageAdID` = %s", $id);
    $sqlResult = $db->query($sql);     
    
    if($sqlResult->error()) {
        errorHandle::newError(__FUNCTION__."() - " . $sqlResult->errorMsg(), errorHandle::DEBUG);
        errorHandle::errorMsg(getResultMessage("systemsPolicyError")); 
        return false; 
    }
    else { 
        // set the id into the data 
        $formInfo['imageAdID'] = $id; 
        addDisplayConditions($formInfo);  
    }
}

?> 