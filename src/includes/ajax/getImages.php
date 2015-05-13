<?php
    require_once "../../engineHeader.php";

    $localvars = localvars::getInstance();
    $db        = db::get($localvars->get('dbConnectionName'));

    $currentDate = time();
    $currentDayofWeek = strtolower( date('l', time()) );
    $currentTime = mktime(date('h'),date('i'),0,0,0,0);
    $URLpath = $localvars->get('URLpath');

    $sql = "SELECT imageAds.*, displayConditions.dateStart, displayConditions.dateEnd, displayConditions.monday, displayConditions.tuesday, displayConditions.wednesday, displayConditions.thursday, displayConditions.friday, displayConditions.saturday, displayConditions.sunday, displayConditions.timeStart, displayConditions.timeEnd
                FROM imageAds LEFT JOIN displayConditions
                ON displayConditions.imageAdID = imageAds.ID
                    WHERE enabled='1'
                        AND (displayConditions.dateStart <= ".$currentDate. " AND displayConditions.dateEnd >= " .$currentDate. " OR (displayConditions.dateStart is NULL AND displayConditions.dateEnd is NULL))
                        AND (displayConditions.timeStart <= " .$currentTime. " AND displayConditions.timeEnd >= " .$currentTime. " OR (displayConditions.timeStart is NULL AND displayConditions.timeEnd is NULL))
                        ORDER BY imageAds.priority DESC";

    $sqlResult = $db->query($sql);

    if ($sqlResult->rowCount() < 1) {
        errorHandle::errorMsg('No Images found are you sure you have added them?');
        return FALSE;
    }

    if($sqlResult->error()) {
        errorHandle::newError(__FUNCTION__."() - " . $sqlResult->errorMsg(), errorHandle::DEBUG);
        errorHandle::errorMsg('Error getting the image information from the database');
    }
    else {
        errorHandle::successMsg('Success We got stuff!');
    }

    $localvars->set("feedbackStatus",errorHandle::prettyPrint());

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


    print "<pre>";
    var_dump($displayAdRecords);
    print "</pre>";


?>

{local var="feedbackStatus"}