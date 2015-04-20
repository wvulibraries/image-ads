<?php

// glob vars  
//=========================================
$localvars = localvars::getInstance();
$form      = formBuilder::createForm('displayOptions');
$imageId   = $_GET['MYSQL']['imageID']; 
$db  = db::get($localvars->get('dbConnectionName')); // TELL WHAT DB TO CONNECT TO

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

// Making Date ranges into a function for JS to create new ones on the fly. 
// Engine Setups for making dropdown menus 
function addDateRanges() {
    $date = new date;
    // Date and Time Dropdown built by engine 
    $startMonth = $date->dropdownMonthSelect(1, TRUE, array("id"=>"start_date","name"=>"dateStart[]"));
    $startDay   = $date->dropdownDaySelect(TRUE, array("id"=>"start_date","name"=>"dateStart[]")); 
    $startYear  = $date->dropdownYearSelect(0,5, TRUE, array("id"=>"start_date","name"=>"dateStart[]"));
    
    $endMonth = $date->dropdownMonthSelect(1, TRUE, array("id"=>"end_date","name"=>"dateEnd[]"));
    $endDay   = $date->dropdownDaySelect(TRUE, array("id"=>"end_date","name"=>"dateEnd[]")); 
    $endYear  = $date->dropdownYearSelect(0,5, TRUE, array("id"=>"end_date","name"=>"dateEnd[]"));
    
    $startDateRange = $startMonth . "/" . $startDay . "/" . $startYear; 
    $endDateRange   = $endMonth . "/" . $endDay . "/" . $endYear;
    // Return JS
    return sprintf('<div class="inputs"> <strong> Start Date : </strong> <br/> %s <br/> <strong> End Date : </strong> <br/> %s <br/><br/> </div>', 
        $startDateRange,
        $endDateRange
    );
}


// TIME FUNCTIONS FOR CALLBACKS 
// ===========================================

function addTimeRanges() {
    $date = new date;

    // Define Dropdwons 
    $startHour = $date->dropdownHourSelect(TRUE, TRUE, array("name" => "timeStart[]")); 
    $startMin  = $date->dropdownMinuteSelect(TRUE, TRUE, array("name" => "timeStart[]"));  

    $endHour   = $date->dropdownHourSelect(TRUE, TRUE, array("name" => "timeEnd[]")); 
    $endMin    = $date->dropdownMinuteSelect(TRUE, TRUE, array("name" => "timeEnd[]")); 

    // Engine Time Dropdowns & Return them for JS
    $startTime = $startHour . " : " . $startMin . "mins"; 
    $endTime   = $endHour . " : " . $endMin . "mins"; 

    // Return for JS
    return sprintf('<div class="times"><strong> Start Time : </strong> <br/>  %s <br/><strong> End Time : </strong> <br/>  %s <br/><br/> </div>', 
        $startTime,
        $endTime
    );
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



// Inserting Weeday Values 
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



// FORM LOGIC FOR FORM BUILDER  
// ===========================================

// Callback Logic for handling the image upload 
if(!is_empty($_POST) || session::has('POST')) { 
    // Run the Processor 
    $processor = formBuilder::createProcessor(); 
    // Set the Callback functions to fire from the callbacks.php file
    $processor->setCallback('beforeInsert', 'adjustDisplayConditions');
    $processor->processPost(); 
}


//  Setup the form itself & Add form fields
//  Built using Engines Form Builder 
$form->linkToDatabase(array(
    'table'            => "displayConditions"
));

    $form->insertTitle = "Add Image Display Options";
    $form->editTitle   = "Edit Image Display Options";

        $form->addField(
            array(
                'name'     => "imageAdID", 
                'label'    => "Image ID",
                'type'     => "hidden",
                'value'    => $_GET['MYSQL']['imageID']
            )
        );
   
        $form->addField(
            array(
                'name'   => "Date Ranges",
                'label'  => "Add Dates Image Will Display", 
                'type'   => "plaintext",
                'value'  => "<a href='javascript:void(0);' class='addDateRange'> Add Date </a> | <a href='javascript:void(0);' class='deleteDateRange'> Remove Last Date </a>  ",
                'showIn' => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE)
            )
        );

        $form->addField(
            array(
                'name'   => "Time Ranges",
                'label'  => "Add Times Image Will Display", 
                'type'   => "plaintext",
                'value'  => "<a href='javascript:void(0);' class='addTimeRange'> Add Time </a> | <a href='javascript:void(0);' class='deleteTimeRange'> Remove Last Time Range </a>  ",
                'showIn' => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE)
            )
        );
            
        $form->addField(
            array(
                'name'    => "weekdays",
                'label'   => "Days of the Week",
                'type'    => "checkbox",
                'options' => array(
                                'Monday'    => "Monday",
                                'Tuesday'   => 'Tuesday',
                                'Wednesday' => "Wednesday",
                                'Thursday'  => "Thursday", 
                                'Friday'    => "Friday", 
                                'Saturday'  => "Saturday", 
                                'Sunday'    => "Sunday" 
                              ), 
                'showIn'  => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE)
            )
        );
            
            

?> 