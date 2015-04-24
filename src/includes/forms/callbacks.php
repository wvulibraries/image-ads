<?php
// callback functions 
//=========================================
function adjustDisplayConditions(){
    // Take the Input Data
     $dispCondData = $_POST['MYSQL'];

    // Run the function to handle the display conditions for start dates 
     insertingDates($dispCondData); 
     insertingTimes($dispCondData);
     insertWeekdays($dispCondData);

      return false; 
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




?> 