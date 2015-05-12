<?php
    $localvars = localvars::getInstance();
    $db        = db::get($localvars->get('dbConnectionName'));
    $sql       = sprintf("SELECT imageAds.*, displayConditions.dateStart, displayConditions.dateEnd, displayConditions.monday, displayConditions.tuesday, displayConditions.wednesday, displayConditions.thursday, displayConditions.friday, displayConditions.saturday, displayConditions.sunday, displayConditions.timeStart, displayConditions.timeEnd FROM imageAds LEFT JOIN displayConditions ON displayConditions.imageAdID = imageAds.ID WHERE imageAds.ID=?");
    $sqlResult = $db->query($sql,array($_GET['MYSQL']['imageID']));
    $URLpath = "http://$_SERVER[HTTP_HOST]/admin/image_manager";

    if($sqlResult->error()) {
        errorHandle::newError(__FUNCTION__."() - " . $sqlResult->errorMsg(), errorHandle::DEBUG);
        errorHandle::errorMsg('Error getting the image information from the database');
     }

    $localvars->set("feedbackStatus",errorHandle::prettyPrint());

    $displayAdRecords = array();
    while($row = $sqlResult->fetch()) {
        $tempAdArray = array(
            'name'      => htmlSanitize($row['name']),
            'imageAd'   => NULL,
            'ID'        => $row['ID'],
            'enabled'   => $row['enabled'],
            'priority'  => $row['priority'],
            'altText'   => htmlSanitize($row['altText']),
            'actionURL' => htmlSanitize($row['actionURL']),
        );

        $tempDispArray = array(
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

        if(isnull($tempAdArray['imageAd'])){
            $imageURL = sprintf("%s/display.php?imageID=%s",
                    $URLpath,
                    $row['ID']
            );
            $tempAdArray['imageAd'] = $imageURL;
        }

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
                foreach($dispValue as $weekday => $weekbool) {
                    if($weekday == "monday" && $weekbool == 1) {
                        array_push($weekdayArray, "Monday");
                    }
                    if($weekday == "tuesday" && $weekbool == 1) {
                         array_push($weekdayArray, "Tuesday");
                    }
                    if($weekday == "wednesday" && $weekbool == 1) {
                         array_push($weekdayArray, "Wednesday");
                    }
                    if($weekday == "thursday" && $weekbool == 1) {
                         array_push($weekdayArray, "Thursday");
                    }
                    if($weekday == "friday" && $weekbool == 1) {
                         array_push($weekdayArray, "Friday");
                    }
                    if($weekday == "saturday" && $weekbool == 1) {
                         array_push($weekdayArray, "Saturday");
                    }
                    if($weekday == "sunday" && $weekbool == 1) {
                         array_push($weekdayArray, "Sunday");
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
    $localvars->set("editingImage", $imageInfoArray['imageAd']);
    $editLink = $localvars->get("baseDirectory"). "/deleteImage/?imageID=" . $imageID;
    $localvars->set("deleteButtonLink", $editLink);
?>