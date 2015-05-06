<?php

// Process New Image Callback
// ========================================
function processNewImage() {
    $localvars = localvars::getInstance();
    $imgForm   = $_POST['MYSQL'];

    $imgInfo   = array(
        "__formID"  => $imgForm["__formID"],
        "name"      => $imgForm['name'],
        "enbaled"   => $imgForm['enabled'],
        "priority"  => $imgForm['priority'],
        "altText"   => $imgForm['altText'],
        "actionURL" => $imgForm['actionURL'],
        "altText"   => $imgForm['altText']
    );

    $imgDisplayConditions = array(
        "dateStart" => $imgForm["dateStart"],
        "dateEnd"   => $imgForm["dateEnd"],
        "timeStart" => $imgForm["timeStart"],
        "timeEnd"   => $imgForm["timeEnd"],
        "weekdays"  => $imgForm["weekdays"]
    );

    $localvars->set("displayConditionsTemp", $imgDisplayConditions);

    if(isset($_FILES['imageAd'])) {
        $imageInfo = $_FILES['imageAd'];
        $imageData = imageUpload($imageInfo);
    }
    else {
        $errorStatus = '<div class="error"> Something went wrong or you did not properly upload an image. </div>';
        return FALSE;
    }

    $localvars->set("feedbackStatus",$errorStatus);
    $imgInfo['imageAd'] = $imageData;
    return $imgInfo;
}


// Process the Display Information
// after the Insert has happened
// ========================================
function processDisplayInformation($processor) {
    $localvars = localvars::getInstance();
    $db        = db::get($localvars->get('dbConnectionName'));
    $imageID   = $processor->insertID;

    $imgDisplayConditions              = $localvars->get("displayConditionsTemp");
    $imgDisplayConditions['imageAdID'] = $imageID;
    addDisplayConditions($imgDisplayConditions);
}



// Processing the Image and Uploading It
// ========================================
function imageUpload($filedata){
    $maxFileSize      = 1000000; // 1mb
    $fileTypesAllowed = array("image/gif", "image/png", "image/jpeg", "image/jpg");

    $theImageData     = base64_encode(file_get_contents($filedata['tmp_name']));
    $theImageMimeType = $filedata['type'];
    $theImageDataURI  = "data:" . $theImageMimeType . ";" . 'base64,' . $theImageData;

    if($filedata['size'] < $maxFileSize && in_array($filedata['type'], $fileTypesAllowed)) {
        return $theImageDataURI;
    } else {
        print "No Image was uploaded!";
        return FALSE;
    }
}


// DISPLAY OPTIONS SUBMISSIONS
//=========================================
function addDisplayConditions($dispCondData){
    if(!isnull($dispCondData)) {
         insertingDates($dispCondData);
         insertingTimes($dispCondData);
         insertWeekdays($dispCondData);
    }
}

// DATE FUNCTIONS FOR CALLBACKS
// ===========================================
function insertingDates($formInfo){
    $startDates =  adjustDates($formInfo["dateStart"]);
    $endDates = adjustDates($formInfo["dateEnd"]);

    $localvars = localvars::getInstance();
    $db  = db::get($localvars->get('dbConnectionName'));

    if(!$startDates == NULL) {
        $sql = sprintf("INSERT INTO displayConditions (imageAdID,dateStart,dateEnd, weekdays,timeStart,timeEnd) VALUES (?,?,?,?,?,?)");
        for($dateIteration = 0; $dateIteration < count($startDates); $dateIteration++) {
            $insertSQL = array($formInfo['imageAdID'],$startDates[$dateIteration],$endDates[$dateIteration], NULL, NULL, NULL);
            $db->query($sql, $insertSQL);
        }
    }
}

function adjustDates($formData){
   $returnDates     = array();
   $numofIterations = count($formData);

   for($i=0; $i<$numofIterations; $i+=3) {
        $month    = array_shift($formData);
        $day      = array_shift($formData);
        $year     = array_shift($formData);
        $unixDate = mktime(0,0,0,$month,$day,$year);

        array_push($returnDates, $unixDate);
    }
    return $returnDates;
}

function adjustTimes($formInfo){
    $returnTimes = array(); // array to send info back to the insert function
    $numOfTimes  = count($formInfo);

    for($i=0; $i < $numOfTimes;  $i+=2){
        $hour     = array_shift($formInfo);
        $min      = array_shift($formInfo);
        $theTimes = mktime($hour,$min,0,0,0,0);

        array_push($returnTimes, $theTimes);
    }
    return $returnTimes;
}

// Insert Time into the DB
function insertingTimes($formData){
    $startTimes = adjustTimes($formData["timeStart"]);
    $endTimes   = adjustTimes($formData["timeEnd"]);
    $localvars  = localvars::getInstance();
    $db         = db::get($localvars->get('dbConnectionName'));

    if(!$startTimes == NULL) {
        $sql = sprintf("INSERT INTO displayConditions (imageAdID,dateStart,dateEnd, weekdays,timeStart,timeEnd) VALUES (?,?,?,?,?,?)");
        for($timeIt = 0; $timeIt < count($startTimes); $timeIt++) {
            $insertSQL = array($formData['imageAdID'], NULL, NULL, NULL, $startTimes[$timeIt], $endTimes[$timeIt]);
            $db->query($sql, $insertSQL);
        }
    }
}

// Inserting Weekday Values
// ===========================================
function insertWeekdays($formData) {
    $localvars = localvars::getInstance();
    $db  = db::get($localvars->get('dbConnectionName'));

    if(!$formData['weekdays'] == NULL) {
        $sql = sprintf("INSERT INTO displayConditions (imageAdID,dateStart,dateEnd, weekdays,timeStart,timeEnd) VALUES (?,?,?,?,?,?)");
        $weekArrays = implode(", ", $formData['weekdays']);
        $insertSQL = array($formData['imageAdID'], NULL, NULL, $weekArrays, NULL, NULL);
        $db->query($sql,$insertSQL);
    }
}

// Process Updating Certain Rows of Data
// ===========================================
function processUpdate() {
    $editedFormData = $_POST['MYSQL'];
    $imageID        = $_GET['MYSQL']['imageID'];


    $imgInfo = array(
        "__formID"    => $editedFormData['__formID'],
        "__csrfToken" => $editedFormData['__csrfToken'],
        "__csrfID"    => $editedFormData['__csrfID'],
        "name"        => $editedFormData['name'],
        "enabled"     => $editedFormData['enabled'],
        "priority"    => $editedFormData['priority'],
        "altText"     => $editedFormData['altText'],
        "actionURL"   => $editedFormData['actionURL']
    );

    $imgDisplayConditions = array(
        "dateStart" => $editedFormData["dateStart"],
        "dateEnd"   => $editedFormData["dateEnd"],
        "timeStart" => $editedFormData["timeStart"],
        "timeEnd"   => $editedFormData["timeEnd"],
        "weekdays"  => $editedFormData["weekdays"]
    );

    updateImageDispOptions($imgDisplayConditions, $imageID);
    updateImageAd($imgInfo,$imageID);
    return $imgInfo;
}

function updateImageAd($data,$id) {
    $localvars = localvars::getInstance();
    $db        = db::get($localvars->get('dbConnectionName'));

    if(!isnull($data)) {
        $sql       = sprintf("UPDATE `imageAds` SET `name` = ?, `enabled` = ?, `priority` = ? , `altText` = ?, `actionURL` = ? WHERE `ID` = ?");
        $sqlArray  = array($data['name'],$data['enabled'],$data['priority'],$data['altText'],$data['actionURL'], $id);
        $sqlResult = $db->query($sql,$sqlArray);
    }

    if($sqlResult->error()) {
        errorHandle::newError(__FUNCTION__."() - " . $sqlResult->errorMsg(), errorHandle::DEBUG);
        errorHandle::errorMsg('Error getting the image information from the database');
     } else {
        errorHandle::successMsg('<i class="fa fa-thumbs-up"></i> Nice Job.  You have updated your image properties.');
     }

    $localvars->set("feedbackStatus",errorHandle::prettyPrint());
}


function updateImageDispOptions($formInfo, $id){
    $localvars = localvars::getInstance();
    $db        = db::get($localvars->get('dbConnectionName'));
    $sql       = sprintf("DELETE FROM displayConditions WHERE `imageAdID` = ?");
    $sqlResult = $db->query($sql, array($id));

    if($sqlResult->error()) {
        errorHandle::newError(__FUNCTION__."() - " . $sqlResult->errorMsg(), errorHandle::DEBUG);
        errorHandle::errorMsg(getResultMessage("systemsPolicyError"));
        return false;
    } else {
        $formInfo['imageAdID'] = $id;
        addDisplayConditions($formInfo);
    }

    $localvars->set("feedbackStatus",errorHandle::prettyPrint());
}

?>