<?php
    header('Content-Type: application/json');
    require_once "../../engineHeaderClean.php";

    $localvars        = localvars::getInstance();
    $db               = db::get($localvars->get('dbConnectionName'));
    $URLpath          = $localvars->get("URLpath");

    $sql = "SELECT imageAds.*, displayConditions.dateStart, displayConditions.dateEnd, displayConditions.monday, displayConditions.tuesday, displayConditions.wednesday, displayConditions.thursday, displayConditions.friday, displayConditions.saturday, displayConditions.sunday, displayConditions.timeStart, displayConditions.timeEnd
                FROM imageAds LEFT JOIN displayConditions
                ON displayConditions.imageAdID = imageAds.ID
                    WHERE enabled='1' ORDER BY priority DESC";

    $sqlResult = $db->query($sql);

    if($sqlResult->error()) {
        errorHandle::newError(__FUNCTION__."() - " . $sqlResult->errorMsg(), errorHandle::DEBUG);
        errorHandle::errorMsg('Error getting the image information from the database');
    }

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
    }

    $viewableAdRecords = array();

    foreach ($displayAdRecords as $images) {
        if(checkHasDisplayConditions($images['display'])) {
            $displayBools = checkDisplayConditions((refactorDisplayConditions($images['display'])));
            if(!in_array(false, $displayBools, TRUE)) {
               array_push($viewableAdRecords, getImageInfo($images['imageInfo']));
            }
        }
        else {
            array_push($viewableAdRecords, getImageInfo($images['imageInfo']));
        }
    }

    print json_encode($viewableAdRecords);


    function checkHasDisplayConditions($data) {
        foreach($data as $dispOptions) {
            if(!isnull($dispOptions['dateStart']) || (!isnull($dispOptions['timeStart'])) || (array_search('1', $dispOptions['weekdays'])) ) {
                return TRUE;
            }
            else {
                return FALSE;
            }
        }
    }


    // Pull info from the images.
    function getImageInfo($data) {
        $tempArray = array();
        foreach($data as $imgKey => $imageInfo) {
            if($imgKey == 'name' || $imgKey == 'imageAd' || $imgKey == 'altText' || $imgKey == 'actionURL' || $imgKey == 'priority'){
                $tempArray[$imgKey] = $imageInfo;
            }
        }
        return $tempArray;
    }

    function refactorDisplayConditions($data){
        $tempDataArray = array();

        foreach($data as $dispOptions) {

            if(!isnull($dispOptions['dateStart']) || !isnull($dispOptions['dateEnd'])) {
               $tempDataArray['dateStart'] = $dispOptions['dateStart'];
               $tempDataArray['dateEnd']   = $dispOptions['dateEnd'];
            }

            elseif(!isnull($dispOptions['timeStart']) || !isnull($dispOptions['timeEnd'])) {
               $tempDataArray['timeStart'] = $dispOptions['timeStart'];
               $tempDataArray['timeEnd']   = $dispOptions['timeEnd'];
            }

            elseif(array_search('1', $dispOptions['weekdays'])) {
                $tempWeekdayValuesArray = array();
                foreach($dispOptions['weekdays'] as $weekday => $weekbool) {
                    if($weekday == "monday" && $weekbool == 1) {
                        array_push($tempWeekdayValuesArray, "monday");
                    }
                    if($weekday == "tuesday" && $weekbool == 1) {
                         array_push($tempWeekdayValuesArray, "tuesday");
                    }
                    if($weekday == "wednesday" && $weekbool == 1) {
                         array_push($tempWeekdayValuesArray, "wednesday");
                    }
                    if($weekday == "thursday" && $weekbool == 1) {
                         array_push($tempWeekdayValuesArray, "thursday");
                    }
                    if($weekday == "friday" && $weekbool == 1) {
                         array_push($tempWeekdayValuesArray, "friday");
                    }
                    if($weekday == "saturday" && $weekbool == 1) {
                         array_push($tempWeekdayValuesArray,"saturday");
                    }
                    if($weekday == "sunday" && $weekbool == 1) {
                         array_push($tempWeekdayValuesArray,"sunday");
                    }

                    $tempDataArray['weekdays'] = $tempWeekdayValuesArray;
                }
            }
        }
        return $tempDataArray;
    }

    function checkDisplayConditions($data) {
        $currentDate      = time();
        $currentDayofWeek = strtolower( date('l', time()) );
        $currentTime      = mktime(date('h'),date('i'),0,0,0,0);

        $hasDate     = (array_key_exists('dateStart', $data) ? TRUE : FALSE);
        $hasTime     = (array_key_exists('timeStart', $data) ? TRUE : FALSE);
        $hasWeekdays = (array_key_exists('weekdays', $data) ? TRUE : FALSE);

        $dateShowBool     = NULL;
        $timeShowBool     = NULL;
        $weekdayShowBool  = NULL;

        if($hasDate) {
            if($data['dateStart'] <= $currentDate && $data['dateEnd'] >= $currentDate) {
                $dateShowBool = TRUE;
            } else {
                $dateShowBool = FALSE;
            }
        } else {
            $dateShowBool = TRUE;
        }


        if($hasTime) {
            if($data['timeStart'] <= $currentTime && $data['timeEnd'] >= $currentTime) {
                $timeShowBool = TRUE;
            } else {
                $timeShowBool = FALSE;
            }
        } else {
            $timeShowBool = TRUE;
        }

        if($hasWeekdays) {
            if(array_search($currentDayofWeek, $data['weekdays'])) {
                $weekdayShowBool = TRUE;
            }
            else {
               $weekdayShowBool = FALSE;
            }
        } else {
            $weekdayShowBool = TRUE;
        }

        return array($dateShowBool,$timeShowBool,$weekdayShowBool);

    }

?>

