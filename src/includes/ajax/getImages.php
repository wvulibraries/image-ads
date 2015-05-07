<?php
    require_once "../../engineHeader.php";

    $localvars = localvars::getInstance();
    $db        = db::get($localvars->get('dbConnectionName'));

    $sql       = sprintf("SELECT DISTINCT  displayConditions.imageAdID, imageAds.*, displayConditions.dateStart, displayConditions.dateEnd, displayConditions.weekdays, displayConditions.timeStart, displayConditions.timeEnd FROM imageAds LEFT JOIN displayConditions ON displayConditions.imageAdID = imageAds.ID");

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

    $displayImage = array();
    while($row = $sqlResult->fetch()){
        $displayImage[] = $row;
    }


    print "<pre>";
    var_dump($displayImage);
    print "</pre>";


    // $workingImageArray = array();
    // foreach ($displayImage as $image) {
    //     foreach ($image as $propertyKey => $propertyValue) {
    //         if($propertyKey === "ID") {
    //             collectImageID($propertyValue);
    //         }
    //     }
    // }

    // function collectImageID($ID) {
    //     print $ID;
    //     if(!in_array($ID, $workingImageArray)) {
    //         $workingImageArray['ID'] = $ID;
    //     }
    // }


    // print "<pre>";
    // var_dump($workingImageArray);
    // print "</pre>";



    $localvars->set("feedbackStatus",errorHandle::prettyPrint());

    $currentDate = date(Ymd);
    $currentTime = time();



?>

{local var="feedbackStatus"}