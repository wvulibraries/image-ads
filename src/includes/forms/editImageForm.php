 <?php 
    // Pull in current information from the DB
    // ====================================================
    $imageID = $_GET['MYSQL']['imageID']; 
    $localvars = localvars::getInstance();
    $db        = db::get($localvars->get('dbConnectionName'));
    $sql       = sprintf("SELECT imageAds.*, displayConditions.dateStart, displayConditions.dateEnd, displayConditions.weekdays, displayConditions.timeStart, displayConditions.timeEnd FROM imageAds LEFT JOIN displayConditions ON displayConditions.imageAdID = imageAds.ID WHERE imageAds.ID=".$imageID);
    $sqlResult = $db->query($sql);

    $localvars->set("buildDateTimes", "CrazyText");

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


    // Functions for building out the current date and time back into form options and setting them in the form fields
    // ================================================================================================================

    // function formatMonthSelectMenu($month) {
    //     // Rebuild Forms 
    //     $dateMonths        = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    //     $selectMenuOptions = array("id" => "start_date", "name" => "dateStart[]"); 

    //     // Rebuild Select Menu using the options and dates above 
    //     $theMenu = sprintf('<select %s>', "name='dateStart[]' id='state_date'");

    //     // Loop through array, but forget the blank space
    //     for($I=1; $I<count($dateMonths); $I++) {
    //         $theMenu .= sprintf('<option value="%s" %s> %s </option>', 
    //                                 $dateMonths[$I],
    //                                 ($dateMonths[$I] == $month)?"selected":"",
    //                                 $dateMonths[$I]
    //                            );
    //     }
    //     $theMenu .= sprintf('</select>');

    //     return $theMenu; 
    // }

    // function formatDaySelectMenu($day) {
    //     $days = 31; // Max Number of Days in a Month 
    //     // Select menu Build 
    //     $theMenu = sprintf('<select %s>', "name='dateStart[]' id='state_date'");
    //     // Loop through the days starting at day 1 because you can't have 0 days
    //     for($I=1; $I<=$days; $I++) {
    //         $theMenu .= sprintf('<option value="%s" %s> %s </option>', 
    //                                 $I, 
    //                                 ($I == $day)?"selected":"", 
    //                                 $I
    //                                 //($I < 10)?"0".$I:$I
    //                              );
    //     }
    //     $theMenu .= sprintf('</select>');
    //     return $theMenu; 
    // }

    // function formatYearSelectMenu($year) {
       
    //     $getCurrentYear = date("Y"); 
    //     $yearOptions = array(); 

    //     if($year <= $getCurrentYear) { 
    //         $yearsTemp = ($year - 1);            
    //         for($J = 5; $J >= 0;  $J--){ 
    //             $yearsTemp += 1;
    //             array_push($yearOptions,$yearsTemp); 
    //         }
    //     }
        
    //     $theMenu = sprintf('<select %s>', "name='dateStart[]' id='state_date'");
    //     // Loop through the days starting at day 1 because you can't have 0 days
    //     for($I=0; $I<=count($yearOptions); $I++) {
    //         $theMenu .= sprintf('<option value="%s" %s> %s </option>', 
    //                                 $yearOptions[$I], 
    //                                 ($yearOptions[$I] == $year)?"selected":"", 
    //                                 $yearOptions[$I]
    //                              );
    //     }
    //     $theMenu .= sprintf('</select>');
    //     return $theMenu; 
    // }

    // function formatTime(){ 

    // }


    $numOfDisplayConditions = count($imageDisplayArray);
    $weekdayArray = array();

        foreach($imageDisplayArray as $imageDisplay) {
            foreach($imageDisplay as $dispIndex => $dispValue) {
                if(!is_empty($dispValue) && $dispIndex === "dateStart") {
    
                    //print $dispIndex . " - " . $dispValue . "<br>"; 
                    // Seperate the date into values for the select menus 
                    $month = date('m', $dispValue);
                    $day   = date('d', $dispValue); 
                    $year  = date('Y', $dispValue); 
                
                    // Looking for solution using the date class in engineAPI 
                     $date    = new date; 
                     $ddMonth = $date->dropdownMonthSelect(1,$month,array());
                     $ddDay   = $date->dropdownDaySelect($day,array()); 
                     $ddYear  = $date->dropdownYearSelect(-1,5,$year,array());

                     print $ddMonth . $ddDay . $ddYear . " - "; 
                }
                if(!is_empty($dispValue) && $dispIndex === "dateEnd") {
    
                    //print $dispIndex . " - " . $dispValue . "<br>"; 
                    // Seperate the date into values for the select menus 
                    $month = date('m', $dispValue);
                    $day   = date('d', $dispValue); 
                    $year  = date('Y', $dispValue); 
                
                    // Looking for solution using the date class in engineAPI 
                     $date    = new date; 
                     $ddMonth = $date->dropdownMonthSelect(1,$month,array());
                     $ddDay   = $date->dropdownDaySelect($day,array()); 
                     $ddYear  = $date->dropdownYearSelect(-1,5,$year,array());

                     print $ddMonth . $ddDay . $ddYear . "<br>"; 
                }
                if(!is_empty($dispValue && $dispIndex === "timeStart")) {
                    
                    $hour = date("H", $dispValue); 
                    $min  = date("i", $dispValue); 

                    $date = new date; 
                    $ddHour = $date->dropdownHourSelect(TRUE, $hour, array("name" => "timeStart[]"));
                    $ddMin =  $date->dropdownMinuteSelect(TRUE, $min, array("name" => "timeStart[]"));

                    print $ddHour . $ddMin . "<br/>"; 

                }
                if(!is_empty($dispValue && $dispIndex === "timeEnd")) {
                    
                    $hour = date("H", $dispValue); 
                    $min  = date("i", $dispValue); 

                    $date = new date; 
                    $ddHour = $date->dropdownHourSelect(TRUE, $hour, array("name" => "timeStart[]"));
                    $ddMin =  $date->dropdownMinuteSelect(TRUE, $min, array("name" => "timeStart[]"));

                    print $ddHour . $ddMin . "<br/>"; 
                }
                if(!is_empty($dispValue && $dispIndex === "weekdays")) {
                    // // Removing Duplicate Weekday Values 
                    // // Maybe this should be done somewhere else, like when the values are added
                    // $weekdayArrayTemp = explode(",", $dispValue);  // Remove Duplicates 

                    // for($I = 0; $I<count($weekdayArrayTemp); $I++){ 
                    //    $weekdayValue = $weekdayArrayTemp[$I];
                    //    if(!in_array($weekdayValue, $weekdayArray)){
                    //         array_push($weekdayArray, $weekdayValue);
                    //    }
                    // }

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

// Call backs 
// ============================================================================

function processImageInfo() {
    // Take the Input Data
     $updateFormData = $_POST['MYSQL'];
     print "<h2>Update</h2> <pre>";
     var_dump($updateFormData);
     print "</pre>";
     return false; 
}

// Form Creating and Updating Pulling information into the values fo the form
// ============================================================================

// Callback Logic for handling the image upload 
    if(!is_empty($_POST) || session::has('POST')) { 
        $processor = formBuilder::createProcessor(); // create processor
        $processor->setCallback('beforeInsert', 'processImageInfo'); // Callbacks 
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
                    'value'  => "<a href='javascript:void(0);' class='addDateRange'> Add Date </a> | <a href='javascript:void(0);' class='deleteDateRange'> Remove Last Date </a>",
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
                    'showIn'  => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE),
                    'value' => $weekdayArray
                )
            );
        }

?>


