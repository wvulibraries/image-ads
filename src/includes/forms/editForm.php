<?php
    $imageID   = $_GET['MYSQL']['imageID'];
    $localvars = localvars::getInstance();
    $db        = db::get($localvars->get('dbConnectionName'));
    $sql       = sprintf("SELECT imageAds.*, displayConditions.dateStart, displayConditions.dateEnd, displayConditions.weekdays, displayConditions.timeStart, displayConditions.timeEnd FROM imageAds LEFT JOIN displayConditions ON displayConditions.imageAdID = imageAds.ID WHERE imageAds.ID=%s",
                    $imageID
                 );
    $sqlResult = $db->query($sql);


    if ($sqlResult->rowCount() < 1) {
        errorHandle::errorMsg('No Images found are you sure you have added them?');
        return FALSE;
    }

    if($sqlResult->error()) {
        errorHandle::newError(__FUNCTION__."() - " . $sqlResult->errorMsg(), errorHandle::DEBUG);
        errorHandle::errorMsg('Error getting the image information from the database');
     }

    $localvars->set("feedbackStatus",errorHandle::prettyPrint());

    $displayAdRecords = array();
    while($row = $sqlResult->fetch()) {

        $tempAdArray = array(
            'ID'        => $row['ID'],
            'name'      => $row['name'],
            'enabled'   => $row['enabled'],
            'priority'  => $row['priority'],
            'altText'   => $row ['altText'],
            'actionURL' => $row['actionURL'],
            'imageAd'   => $row['imageAd']
        );

        $tempDispArray = array(
            'ID'        => $row['ID'],
            'dateStart' => $row['dateStart'],
            'dateEnd'   => $row['dateEnd'],
            'timeStart' => $row['timeStart'],
            'timeEnd'   => $row['timeEnd'],
        );

        $tempWeekdayArray = array(
            'monday'    => $row['monday'],
            'tuesday'   => $row['tuesday'],
            'wednesday' => $row['wednesday'],
            'thursday'  => $row['thursday'],
            'friday'    => $row['friday'],
            'saturday'  => $row['saturday'],
            'sunday'    => $row['sunday']
        );

        $tempDispArray['weekdays'] = $tempWeekdayArray;

        $displayAdRecords[$row['ID']]["imageInfo"] = $tempAdArray;
        $displayAdRecords[$row['ID']]['display'][] = $tempDispArray;

        $imageInfoArray    = $displayAdRecords[$row['ID']]['imageInfo'];
        $imageDisplayArray = $displayAdRecords[$row['ID']]['display'];
    }

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
                if ( $dispIndex === "dateStart") {
                     array_push($startDates, $dispValue);
                } else {
                     array_push($endDates, $dispValue);
                }
            }
            if(!is_empty($dispValue) && ($dispIndex === "timeStart" || $dispIndex ==="timeEnd")) {
                if ( $dispIndex === "timeStart") {
                    array_push($startTime, $dispValue);
                }
                if ($dispIndex === "timeEnd") {
                    array_push($endTime, $dispValue);
                }
            }
            if(!is_empty($dispValue && $dispIndex === "weekdays")) {
                $weekdaysTemp = explode(", ", $dispValue);
                for($I = 0; $I<count($weekdaysTemp); $I++){
                    $tempValue = $weekdaysTemp[$I];
                    if(!in_array( $tempValue ,$weekdayArray)) {
                        array_push($weekdayArray, $tempValue); // Push to array
                    }
                }
            }
        }
    }





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

    $localvars->set("exsistingDateRanges", $dbDateRanges);

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

    $localvars->set("exsistingTimeRanges", $dbTimeRanges);
    $localvars->set("exsistingWeekdays", $weekdayArray);

    $imgURI = $displayAdRecords[$imageID]['imageInfo']['imageAd'];
    $localvars->set("editingImage", $imgURI);


    $editLink = $localvars->get("baseDirectory"). "/deleteImage/?imageID=" . $imageID;
    $localvars->set("deleteButtonLink", $editLink);
?>