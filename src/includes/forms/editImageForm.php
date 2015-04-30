 <?php 
    // Pull in current information from the DB
    // ====================================================
    $imageID = $_GET['MYSQL']['imageID']; 
    $localvars = localvars::getInstance();
    $db        = db::get($localvars->get('dbConnectionName'));
    $sql       = sprintf("SELECT imageAds.*, displayConditions.dateStart, displayConditions.dateEnd, displayConditions.weekdays, displayConditions.timeStart, displayConditions.timeEnd FROM imageAds LEFT JOIN displayConditions ON displayConditions.imageAdID = imageAds.ID WHERE imageAds.ID=".$imageID);
    $sqlResult = $db->query($sql);


    if ($sqlResult->error()) {
        print "ERROR GETTING ADS  -- the error -- " . $sqlResult->errorMsg(); 
        return FALSE;
    }    
    if ($sqlResult->rowCount() < 1) {
        print "NO ADS FOUND"; 
        return FALSE;
    }

    // Insert Time and Date Functions used by JS 
    // ========================================================================
    recurseInsert("includes/addDateTimeFunctions.php","php");    

    // Callbacks 
    // ========================================================================
    recurseInsert("includes/forms/callbacks.php", "php");

    // Create a new array for the data 
    // Pull the data into to multiple arrays so that it is easier to work with 
    // =========================================================================

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
        $displayAdRecords[$row['ID']]['display'][] = $tempDispArray; 
        
        // Create variables for more easily accessing the information in these rows 
        // Specifically looping through dipslay options and image info
        $imageInfoArray = $displayAdRecords[$row['ID']]['imageInfo'];
        $imageDisplayArray = $displayAdRecords[$row['ID']]['display']; 
    }



    // Looping through current options to make the display information to appear 
    // under the edit options on the form 
    // ==========================================================================================

    $weekdayArray = array();
    $startDates   = array(); 
    $endDates     = array(); 
    $startTime    = array(); 
    $endTime      = array();

    foreach($imageDisplayArray as $imageDisplay) {
        foreach($imageDisplay as $dispIndex => $dispValue) {
            if(!is_empty($dispValue) && ($dispIndex === "dateStart" || $dispIndex === "dateEnd")) {
                // Pull only good results into a new array to make an easier working group
                if ( $dispIndex === "dateStart") { 
                     array_push($startDates, $dispValue);
                } else {  
                     array_push($endDates, $dispValue); 
                }
            }
            if(!is_empty($dispValue && $dispIndex === "timeStart" || $dispIndex ==="timeEnd")) {
                // Pull into easier arrays to work with 
                 if ( $dispIndex === "timeStart") { 
                     array_push($startTime, $dispValue);
                } else {  
                     array_push($endTime, $dispValue); 
                }
            }
            if(!is_empty($dispValue && $dispIndex === "weekdays")) {
                $weekdaysTemp = explode(",", $dispValue); 
                for($I = 0; $I<count($weekdaysTemp); $I++){
                    $tempValue = $weekdaysTemp[$I]; 
                    // Check for Duplicates 
                    if(!in_array( $tempValue ,$weekdayArray)) { 
                        array_push($weekdayArray, $tempValue); // Push to array
                    }
                }
            }
        }
    }
    // Loop through the easy array and make the display conditions
    // then save to local var for re-use later 
    // $itDates is a variable to keep from using $I, it is just the number of iterations to loop through
    for ($itDates = 0; $itDates < count($startDates); $itDates++) {
        
        $startValue = $startDates[$itDates]; 
        $endValue   = $endDates[$itDates];
        
        $sMonth     = date('m', $startValue);
        $sDay       = date('d', $startValue);
        $sYear      = date('y', $startValue);
        
        $eMonth     = date('m', $endValue);
        $eDay       = date('d', $endValue);
        $eYear      = date('y', $endValue);
        
        $dbDateRanges .= addDateRanges( 
                        array(
                            'month'    => $sMonth, 
                            'day'      => $sDay, 
                            'year'     => $sYear, 
                            'endMonth' => $eMonth,
                            'endDay'   => $eDay,
                            'endYear'  => $eYear
                        )
                    );
    }
    // Set the DB Ranges toa  local var for use later 
    $localvars->set("exsistingDateRanges", $dbDateRanges); 

    // Loop through the array to make the display conditions for times 
    // Very similar to above 
    for($itTimes = 0; $itTimes < count($startTime); $itTimes++ ) {
        $startValue = $startTime[$itTimes]; 
        $endValue   = $endTime[$itTimes];

         $starthour = date("H", $startValue); 
         $startmin  = date("i", $startValue); 
         $endhour   = date("H", $endValue); 
         $endmin    = date("i", $endValue); 

         $dbTimeRanges .= addTimeRanges( 
                        array(
                            'startHour' => $starthour,
                            'startMin'  => $startmin,
                            'endHour'   => $endhour,
                            'endMin'    => $endmin
                        )
                    );
    }

    // Set the DB Ranges toa  local var for use later 
    $localvars->set("exsistingTimeRanges", $dbTimeRanges); 



// Call backs 
// ============================================================================

function processImageInfo() {
    // Take the Input Data
     $updateFormData = $_POST['MYSQL'];
     return false; 
}

// Form Creating and Updating Pulling information into the values fo the form
// ============================================================================

// Callback Logic for handling the image upload 
    if(!is_empty($_POST) || session::has('POST')) { 
        $processor = formBuilder::createProcessor(); // create processor
        $processor->setCallback('beforeInsert', 'processImageInfo'); // Callbacks 
        $processor->setCallback('afterInsert', 'adjustDisplayConditions');
        $processor->processPost();  // process form after callback is inserted 
    }

    $form      = formBuilder::createForm('editImage');

    $form->linkToDatabase(array(
        'table' => "imageAds"
    ));

    $form->insertTitle = "Edit Image";
    $form->editTitle   = "Edit Image";
    $form->updateTitle = "Edit Image";

    $imgURI = $displayAdRecords[$imageID]['imageInfo']['imageAd']; 

    $form->addField(
        array(
            'name'    => "ID",
            'label'   => "Table ID",
            'primary' => TRUE,
            'showIn'  => array(formBuilder::TYPE_INSERT),
            'type'    => 'hidden',
            'value'   => $imageID
        )
    );

    $form->addField(
        array(
            'name'   => "Current Image",
            'label'  => "Image Your Editing", 
            'type'   => "plaintext",
            'value'  => "<img src=" . $imgURI . ">"
        )
    );

    $form->addField(
        array(
            'name'            => "name",
            'label'           => "Image Name",
            'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE, formbuilder::TYPE_EDIT),
            'required'        => TRUE,
            'type'            => 'text',
            'duplicates'      => TRUE, 
            'fieldID'         => "imgName",
            'value'           => $imageInfoArray['name']
        )
    );

    $form->addField(
        array(
            'name'            => "enabled",
            'label'           => "Is this image being displayed now?",
            'showInEditStrip' => TRUE,
            'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE, formbuilder::TYPE_EDIT),
            'required'        => TRUE,
            'type'            => 'boolean',
            'duplicates'      => TRUE,
            'options'         => array("YES","N0"),
            'value'           => $imageInfoArray['enabled']
        )
    );

     $form->addField(
        array(
            'name'            => "priority",
            'label'           => "Is this iamge high priority?",
            'showInEditStrip' => TRUE,
            'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE, formbuilder::TYPE_EDIT),
            'required'        => TRUE,
            'type'            => 'boolean',
            'duplicates'      => TRUE,
            'options'         => array("YES","NO"),
            'value'           => $imageInfoArray['priority']
        )
    );

    $form->addField(
        array(
            'name'            => "altText",
            'label'           => "Please provide meaningful alt text.",
            'showInEditStrip' => TRUE,
            'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE, formbuilder::TYPE_EDIT),
            'required'        => TRUE,
            'type'            => 'textarea',
            'value'           => $imageInfoArray['altText']
        )
    );

    $form->addField(
        array(
            'name'            => "actionURL",
            'label'           => "Add a Link",
            'showInEditStrip' => TRUE,
            'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE, formbuilder::TYPE_EDIT),
            'required'        => TRUE,
            'type'            => 'URL',
            'value'           => $imageInfoArray['actionURL']
        )
    );


    if(!isnull($imageDisplayArray)) {
     $form->addField(
                array(
                    'name'   => "Date Ranges",
                    'label'  => "Add Dates Image Will Display", 
                    'type'   => "plaintext",
                    'value'  => "<a href='javascript:void(0);' class='addDateRange'> Add Date </a> | <a href='javascript:void(0);' class='deleteDateRange'> Remove Last Date </a>" . $localvars->get("exsistingDateRanges"),
                    'showIn' => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE)
                )
            );

            $form->addField(
                array(
                    'name'   => "Time Ranges",
                    'label'  => "Add Times Image Will Display", 
                    'type'   => "plaintext",
                    'value'  => "<a href='javascript:void(0);' class='addTimeRange'> Add Time </a> | <a href='javascript:void(0);' class='deleteTimeRange'> Remove Last Time Range </a>" . $localvars->get("exsistingTimeRanges"),
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
                    'showIn'  => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE),
                    'value' => $weekdayArray
                )
            );
        }

?>


