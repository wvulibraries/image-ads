<?php 
// callback functions 
//=========================================
function adjustDisplayConditions(){
    // Take the Input Data
     $dispCondData = $_POST['MYSQL'];

     // Adjust the Date Ranges 
     $startDates =  adjustDates($dispCondData['dateStart']); 
     $endDates = adjustDates($dispCondData['dateEnd']); 

    // Adjust Mins to make them look better
    //  $startMins    = adjustMins($dispCondData["timeStart_min"]);
    //  $endMins      = adjustMins($dispCondData["timeEnd_min"]);
     
    // Make Adjustments to the Time Stuff 
    //  $theStartTime = $dispCondData["timeStart_hour"] . ":" . $startMins . $dispCondData["timeStart_ampm"];
    //  $theEndTime   = $dispCondData["timeEnd_hour"] . ":" . $endMins . $dispCondData["timeEnd_ampm"];  
     
    // // DO THIS SOON
    //  if($theEndTime == $theStartTime) { 
    //     // Throw a Form Error and Send Feedback to the User 
    //  }
    
    // // Start to refactor the input Data
    // // Put the Input data into a new array, array should match the mysql information
     $databaseData              = array(); 
     $databaseData["dateStart"] = $startDates;
     $databaseDate["dateEnd"]   = $endDates;  
    //  $databaseData["timeStart"] = $theStartTime; 
    //  $databaseData["timeEnd"]   = $theEndTime;


      print "<pre>"; 
      var_dump($databaseData);
      print "</pre>"; 


     return $databaseData; 
}


// ReUseable Function to adjust the Time and add an extra 0 to the mins
function adjustMins($time){    
    if($time<10) { 
        $time = "0".$time; 
    }
    return $time; 
}

function adjustDates($formData){ 
   $returnDates = array(); 
   // Loop through the array 3 numbers at a time 
   // pull out the first value of each 
   for($i=0; $i<=count($formData); $i+=3) { 
        $month = array_shift($formData); 
        $day   = array_shift($formData); 
        $year  = array_shift($formData); 

        $newDateString = $month . "/" . $day . "/" . $year; 
        array_push($returnDates, mktime($newDateString)); 
    }
    return serialize($returnDates); 
}


// Making Date ranges into a function for JS to create new ones on the fly. 
// Engine Setups for making dropdown menus 
function addDateRanges() {
    $date = new date;
    // Date and Time Dropdown built by engine 
    $startDateRange = $date->dateDropDown(array("id"=>"start_date","formname"=>"dateStart[]","monthdformat"=>"mon"));
    $endDateRange   = $date->dateDropDown(array("id"=>"end_date","formname"=>"dateEnd[]","monthdformat"=>"mon"));
    // Return JS
    return sprintf('<div class="inputs"> <strong> Start Date : </strong> <br/> %s <br/> <strong> End Date : </strong> <br/> %s <br/><br/> </div>', 
        $startDateRange,
        $endDateRange
    );
}

function addTimeRanges() {
    $date = new date;
    // Engine Time Dropdowns & Return them for JS
    $startTime      = $date->timeDropDown(array("formname" => "timeStart[]",)); 
    $endTime        = $date->timeDropDown(array("formname" => "timeEnd[]")); 
    // Return for JS
    return sprintf('<div class="times"> %s <br/><br/> %s <br/><br/> </div>', 
        $startTime,
        $endTime
    );
}

// Callback Logic for handling the image upload 
if(!is_empty($_POST) || session::has('POST')) { 
    // Run the Processor 
    // ========================================
    $processor = formBuilder::createProcessor(); 
    // Set the Callback functions to fire from the callbacks.php file
    // =========================================
    // Parameter Types ($trigger, $callback) 
    $processor->setCallback('beforeInsert', 'adjustDisplayConditions');
    $processor->processPost(); 
}


$localvars = localvars::getInstance();
$form      = formBuilder::createForm('displayOptions');
$imageId   = $_GET['MYSQL']['imageID']; 


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