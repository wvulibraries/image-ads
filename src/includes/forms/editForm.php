<?php
function creatingEditViews(){
    // Pull in current information from the DB
    // ====================================================
    $imageID   = $_GET['MYSQL']['imageID'];
    $localvars = localvars::getInstance();
    $db        = db::get($localvars->get('dbConnectionName'));
    $sql       = sprintf("SELECT imageAds.*, displayConditions.dateStart, displayConditions.dateEnd, displayConditions.weekdays, displayConditions.timeStart, displayConditions.timeEnd FROM imageAds LEFT JOIN displayConditions ON displayConditions.imageAdID = imageAds.ID WHERE imageAds.ID=%s",
                    $imageID
                 );
    $sqlResult = $db->query($sql);


    if ($sqlResult->error()) {
        print "ERROR GETTING ADS  -- the error -- " . $sqlResult->errorMsg();
        return FALSE;
    }
    if ($sqlResult->rowCount() < 1) {
        print "NO ADS FOUND";
        return FALSE;
    }

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
            'ID'        => $row['ID'],
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
    $dbDateRanges = NULL;
    $dbTimeRanges = NULL;

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
    $localvars->set("exsistingWeekdays", $weekdayArray);
    // Setup the current image for displaying it
    $imgURI = $displayAdRecords[$imageID]['imageInfo']['imageAd'];
    $localvars->set("editingImage", $imgURI);

    // Local Var setup for the Edit Button
    $editLink = $localvars->get("baseDirectory"). "/deleteImage/?imageID=" . $imageID;
    $localvars->set("deleteButtonLink", $editLink);
}
?>