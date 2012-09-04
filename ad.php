<?php
require("engineIncludeStatement.php");
$eAPI = EngineAPI::singleton();

// Apply ACL
recurseInsert("acl.php","php");
$eAPI->accessControl("build");

// Connect to the database & load-in the settings
$eAPI->dbConnect("database","adManager",TRUE);
localvars::dbImport("settings", "id", "value");

//// Instantiate the fileHandler class
$fManager = new fileHandler($eAPI);

// Fire up the Template
$eAPI->eTemplate("include","header");
$date = new date;
$dateConfig = array(
    'makearray' => TRUE,
    'disabled' => TRUE,
    'prompts' => TRUE,
    'monthdformat' => 'month',
    'startyear' => date('Y'),
    'endyear' => date('Y')+5);
$timeConfig = array(
    'makearray' => TRUE,
    'disabled' => TRUE,
    'prompts' => TRUE,
    'hourformat'  => '24',
    'mininterval'  => '15');

// ========================================================================
if(isset($eAPI->cleanGet['MYSQL']['id'])){
    localvars::add("pageTitle", localvars::get("pageTitle")." - Edit Ad");
    $dbAd = $eAPI->openDB->query(sprintf("SELECT ads.*, releaseConditions.type AS `conditionType`, releaseConditions.value AS `conditionValue` FROM `ads` LEFT JOIN releaseConditions ON releaseConditions.ad_id=ads.ID WHERE ads.ID=%s", $eAPI->cleanGet['MYSQL']['id']));
    if($dbAd['result'] and $dbAd['numrows']){
        $adData = array();
        $adConditions = array();
        while($row = mysql_fetch_assoc($dbAd['result'])){
            $adData = $row;
            $adConditions[ $row['conditionType'] ][] = $row['conditionValue'];
        }
    }else{
        $errors[]="No ad defined for given ID";
        unset($eAPI->cleanGet['MYSQL']['id']);
        unset($eAPI->cleanGet['HTML']['id']);
    }
}else{
    localvars::add("pageTitle", localvars::get("pageTitle")." - Create Ad");
}
//===================================================================================

// Validate the input (for create and update)

if(isset($eAPI->cleanPost['MYSQL']['createAd']) or isset($eAPI->cleanPost['MYSQL']['updateAd'])){
    if(is_empty($eAPI->cleanPost['MYSQL']['name'])){
        errorHandle::errorMsg("No value supplied for ad name");
    }elseif(strip_tags($eAPI->cleanPost['MYSQL']['name']) != $eAPI->cleanPost['MYSQL']['name']){
        errorHandle::errorMsg("HTML is not allowed in the ad name!");
    }
    if(is_empty($eAPI->cleanPost['MYSQL']['enabled'])){
        errorHandle::errorMsg("No value supplied for Ad Enabled");
    }
    if(is_empty($eAPI->cleanPost['MYSQL']['priority'])){
        errorHandle::errorMsg("No value supplied for Ad Priority");
    }
    if(is_empty($eAPI->cleanPost['MYSQL']['imgAltText'])){
        errorHandle::errorMsg("No value supplied for image alt text");
    }elseif(strip_tags($eAPI->cleanPost['MYSQL']['imgAltText']) != $eAPI->cleanPost['MYSQL']['imgAltText']){
        errorHandle::errorMsg("HTML is not allowed in the image alt text!");
    }
    if(!isset($eAPI->cleanPost['MYSQL']['imgActionType']) or is_empty($eAPI->cleanPost['MYSQL']['imgActionType'])){
        errorHandle::errorMsg("No value supplied for image action");
    }else
    if(isset($eAPI->cleanPost['MYSQL']['imgActionValue']) && strip_tags($eAPI->cleanPost['MYSQL']['imgActionValue']) != $eAPI->cleanPost['MYSQL']['imgActionValue']){
        errorHandle::errorMsg("HTML is not allowed in the image action!");
    }
    switch($eAPI->cleanPost['MYSQL']['imgActionType']){
        case 'link':
            if(!validate::url($eAPI->cleanPost['MYSQL']['imgActionValue'])){
                errorHandle::errorMsg("Invalid URL supplied for Image Action");
            }
            // Dupe check
            $dbDupeCheckLink = (isset($eAPI->cleanPost['MYSQL']['adID']))
                ? $eAPI->openDB->query(sprintf("SELECT `ID` FROM `ads` WHERE `ID`<>'%s' `imgActionType`='link' AND `imgActionValue`='%s'", $eAPI->cleanPost['MYSQL']['adID'], $eAPI->cleanPost['MYSQL']['imgActionValue']))
                : $eAPI->openDB->query(sprintf("SELECT `ID` FROM `ads` WHERE `imgActionType`='link' AND `imgActionValue`='%s'", $eAPI->cleanPost['MYSQL']['imgActionValue']));
            if($dbDupeCheckLink['result'] and $dbDupeCheckLink['numrows']){
                errorHandle::errorMsg("Duplicate ad action found! Please use another.");
            }
            break;
        case 'email':
            if(!validate::emailAddr($eAPI->cleanPost['MYSQL']['imgActionValue'])){
                errorHandle::errorMsg("Invalid Email supplied for Image Action");
            }
            break;
        case 'javascript':
            // No special validation (yet)
            break;
    }
    switch($_FILES['imgFile']['error']){
        case UPLOAD_ERR_OK:
            // No upload error - Validate the file's MIME Type
            $mimeType = $fManager->getMimeType($_FILES['imgFile']['tmp_name'], $_FILES['imgFile']['name']);
            if(!preg_match('#^image/#i', $mimeType)){
                errorHandle::errorMsg("Invalid file type! (it's not an image)");
            }
            break;
        case UPLOAD_ERR_INI_SIZE:
            errorHandle::errorMsg("Max filesize exceeded! [PHP]");
            break;
        case UPLOAD_ERR_FORM_SIZE:
            errorHandle::errorMsg("Max filesize exceeded! [HTML]");
            break;
        case UPLOAD_ERR_PARTIAL:
            errorHandle::errorMsg("Partial file sent!");
            break;
        case UPLOAD_ERR_NO_FILE:
            // If this is an update, we allow no file (because they are keeping the existing one)
            // Hack - If there's an img in the session, we allow no file
            if(!isset($eAPI->cleanPost['MYSQL']['adID']) and !sessionGet('adImage')){
                errorHandle::errorMsg("No image file was uploaded!");
            }
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            errorHandle::errorMsg("File I/O error! [type: 1]");
            break;
        case UPLOAD_ERR_CANT_WRITE:
            errorHandle::errorMsg("File I/O error! [type: 2]");
            break;
        case UPLOAD_ERR_EXTENSION:
            errorHandle::errorMsg("File I/O error! [type: 3]");
            break;
        default:
            errorHandle::errorMsg("Unknown error condition!");
            break;
    }

    // Dupe check - name
    $dbDupeCheckName = (isset($eAPI->cleanPost['MYSQL']['adID']))
        ? $eAPI->openDB->query(sprintf("SELECT `ID` FROM `ads` WHERE `ID`<>'%s' AND `name` = '%s'", $eAPI->cleanPost['MYSQL']['adID'], $eAPI->cleanPost['MYSQL']['name']))
        : $eAPI->openDB->query(sprintf("SELECT `ID` FROM `ads` WHERE `name` = '%s'", $eAPI->cleanPost['MYSQL']['name']));
    if($dbDupeCheckName['result'] and $dbDupeCheckName['numrows']){
        errorHandle::errorMsg("Duplicate ad name found! Please use another name.");
    }

    // Validate, and format the Release Conditions
    $releaseConditions = array();
    if(isset($eAPI->cleanPost['MYSQL']['condition_dateRangeStart_month'])){
        // DateRange
        $releaseConditions['dateRange'] = array();
        foreach($eAPI->cleanPost['MYSQL']['condition_dateRangeStart_month'] as $key => $value){
            $startMonth = $eAPI->cleanPost['MYSQL']['condition_dateRangeStart_month'][$key];
            $startDay   = $eAPI->cleanPost['MYSQL']['condition_dateRangeStart_day'][$key];
            $startYear  = $eAPI->cleanPost['MYSQL']['condition_dateRangeStart_year'][$key];
            $endMonth   = $eAPI->cleanPost['MYSQL']['condition_dateRangeEnd_month'][$key];
            $endDay     = $eAPI->cleanPost['MYSQL']['condition_dateRangeEnd_day'][$key];
            $endYear    = $eAPI->cleanPost['MYSQL']['condition_dateRangeEnd_year'][$key];

            if(!is_numeric($startMonth) or !is_numeric($startDay) or !is_numeric($startMonth)){
                errorHandle::errorMsg("Invalid data supplied for dateRange ".htmlentities($key+1));
            }elseif(!checkdate($startMonth, $startDay, $startDay)){
                errorHandle::errorMsg("Invalid start date supplied for dateRange ".htmlentities($key+1));
            }else{
                if(!is_numeric($endMonth) or !is_numeric($endDay) or !is_numeric($endYear)){
                    errorHandle::errorMsg("Invalid data supplied for dateRange ".htmlentities($key+1));
                }elseif(!checkdate($endMonth, $endDay, $endYear)){
                    errorHandle::errorMsg("Invalid end date supplied for dateRange ".htmlentities($key+1));
                }else{
                    $startTime = mktime(0,0,0,$startMonth,$startDay,$startYear);
                    $endTime   = mktime(0,0,0,$endMonth,$endDay,$endYear);
                    if($endTime <= $startTime){
                        errorHandle::errorMsg("End date cannot be equal to or before start date for dateRange ".htmlentities($key+1));
                    }else{
                        $releaseConditions['dateRange'][] = array('start' => $startTime, 'end' => $endTime);
                    }
                }
            }
        }
    }
    if(isset($eAPI->cleanPost['MYSQL']['condition_timeRangeStart_hour'])){
        // TimeRange
        $releaseConditions['timeRange'] = array();
        foreach($eAPI->cleanPost['MYSQL']['condition_timeRangeStart_hour'] as $key => $value){
            $startHr  = $eAPI->cleanPost['MYSQL']['condition_timeRangeStart_hour'][$key];
            $startMin = $eAPI->cleanPost['MYSQL']['condition_timeRangeStart_min'][$key];
            $endHr    = $eAPI->cleanPost['MYSQL']['condition_timeRangeEnd_hour'][$key];
            $endMin   = $eAPI->cleanPost['MYSQL']['condition_timeRangeEnd_min'][$key];

            if(!is_numeric($startHr) or !is_numeric($startMin)){
                errorHandle::errorMsg("Invalid data supplied for timeRange ".htmlentities($key+1));
            }elseif($startHr < 0 or $startHr > 23){
                errorHandle::errorMsg("Hour out of bounds for start time on timeRange ".htmlentities($key+1));
            }elseif($startMin < 0 or $startMin > 59){
                errorHandle::errorMsg("Minute out of bounds for start time on timeRange ".htmlentities($key+1));
            }else{
                if(!is_numeric($endHr) or !is_numeric($endMin)){
                    errorHandle::errorMsg("Invalid data supplied for timeRange ".htmlentities($key+1));
                }elseif($endHr < 0 or $endHr > 23){
                    errorHandle::errorMsg("Hour out of bounds for start time on timeRange ".htmlentities($key+1));
                }elseif($endMin < 0 or $endMin > 59){
                    errorHandle::errorMsg("Minute out of bounds for start time on timeRange ".htmlentities($key+1));
                }else{
                    // Data is clean!
                    $releaseConditions['timeRange'][] = array(
                        // All times are in Standard Time (Not DST)
                        'start' => (($startHr*60)+$startMin)-(date('I')*60),
                        'end'   => (($endHr*60)+$endMin)-(date('I')*60),
                    );
                }
            }
        }
    }
    if(isset($eAPI->cleanPost['MYSQL']['condition_weekDays'])){
        // Weekday
        $releaseConditions['weekDay'] = $eAPI->cleanPost['MYSQL']['condition_weekDays'];
    }
    if(isset($eAPI->cleanPost['MYSQL']['condition_ipGroups'])){
        // IP Group
        $releaseConditions['ipGroup'] = $eAPI->cleanPost['MYSQL']['condition_ipGroups'];
    }

    if(!isset($eAPI->errorStack['error']) || !sizeof($eAPI->errorStack['error'])){
        // Hack - Bring in the img saved in the session
        $sessionImgData = sessionGet('adImage');
        $img = ($_FILES['imgFile']['error'] == UPLOAD_ERR_OK)
            ? new image($_FILES['imgFile']['tmp_name'], sys_get_temp_dir().'/'.$_FILES['imgFile']['name'])
            : new image($sessionImgData['data']);

        // Resize the image (if it's too big)
        if(localvars::get('maxImgWidth', FALSE)  and $img->getWidth()  > localvars::get('maxImgWidth'))  $img->resizeToWidth(localvars::get('maxImgWidth'));
        if(localvars::get('maxImgHeight', FALSE) and $img->getHeight() > localvars::get('maxImgHeight')) $img->resizeToHeight(localvars::get('maxImgHeight'));

        // Create a new ad
        if(isset($eAPI->cleanPost['MYSQL']['createAd'])){
            // Time to save it all to the database!
            $eAPI->openDB->transBegin();
            $dbCreateAd = $eAPI->openDB->query(sprintf(
                "INSERT INTO ads (modified,enabled,priority,name, img, imgType,imgHeight,imgWidth,imgAltText,imgActionType,imgActionValue) VALUES(NOW(),'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
                strip_tags($eAPI->cleanPost['MYSQL']['enabled']),
                strip_tags($eAPI->cleanPost['MYSQL']['priority']),
                strip_tags($eAPI->cleanPost['MYSQL']['name']),
                $eAPI->openDB->escape($img->rawImage()),
                $eAPI->openDB->escape($img->getImageInfo('mimeType')),
                $eAPI->openDB->escape($img->getImageInfo('height')),
                $eAPI->openDB->escape($img->getImageInfo('width')),
                strip_tags($eAPI->cleanPost['MYSQL']['imgAltText']),
                strip_tags($eAPI->cleanPost['MYSQL']['imgActionType']),
                (isset($eAPI->cleanPost['MYSQL']['imgActionValue']))?strip_tags($eAPI->cleanPost['MYSQL']['imgActionValue']):""
                )
            );
            if($dbCreateAd['errorNumber']){
                sessionSet('adManager_success', false);
                sessionSet('adManager_successMsg', "Advertisement failed to save. (Please submit a helpdesk ticket so we can fix this)");
                errorHandle::newError("Failed to insert ad into database! (SQL Error: ".$dbCreateAd['error'].")", errorHandle::HIGH);
                $eAPI->openDB->transRollback();
                $eAPI->openDB->transEnd();
                gotoAdManagerHome();
            }

            foreach($releaseConditions as $conditionType => $condtions){
                foreach($condtions as $condtion){
                    $value = is_array($condtion) ? implode('-', $condtion): $condtion;
                    $dbCreateAdCondition = $eAPI->openDB->query(sprintf("INSERT INTO `releaseConditions` (`ad_id`,`type`,`value`) VALUES('%s','%s','%s')",
                        $eAPI->openDB->escape($dbIpGroups['id']),
                        strip_tags($eAPI->openDB->escape($conditionType)),
                        strip_tags($eAPI->openDB->escape($value))));
                    if($dbCreateAdCondition['errorNumber']) $eAPI->openDB->transRollback();
                }
            }

            sessionSet('adManager_success', true);
            sessionSet('adManager_successMsg', "Advertisement created successfuly");
            $eAPI->openDB->transCommit();
            $eAPI->openDB->transEnd();
            gotoAdManagerHome();
        }

        // Update an existing ad
        if(isset($eAPI->cleanPost['MYSQL']['updateAd'])){
            if($eAPI->cleanGet['MYSQL']['id'] == $eAPI->cleanPost['MYSQL']['adID']){
                $eAPI->openDB->transBegin();
                // Did the group name change?
                $dbAdUpdate = $eAPI->openDB->query(sprintf(
                    "UPDATE ads SET modified=NOW(),enabled='%s',priority='%s',`name`='%s',imgAltText='%s',imgActionType='%s',imgActionValue='%s' WHERE ID='%s' LIMIT 1",
                    strip_tags($eAPI->cleanPost['MYSQL']['enabled']),
                    strip_tags($eAPI->cleanPost['MYSQL']['priority']),
                    strip_tags($eAPI->cleanPost['MYSQL']['name']),
                    strip_tags($eAPI->cleanPost['MYSQL']['imgAltText']),
                    strip_tags($eAPI->cleanPost['MYSQL']['imgActionType']),
                    strip_tags($eAPI->cleanPost['MYSQL']['imgActionValue']),
                    $eAPI->cleanPost['MYSQL']['adID']));
                if($dbAdUpdate['errorNumber']) $eAPI->openDB->transRollback();

                // Update the ad image (if it changed)
                if($_FILES['imgFile']['error'] === UPLOAD_ERR_OK){
                    $dbAdImgUpdate = $eAPI->openDB->query(sprintf(
                        "UPDATE ads SET modified=NOW(),img='%s',`imgType`='%s',`imgHeight`='%s',`imgWidth`='%s' WHERE ID='%s' LIMIT 1",
                        $eAPI->openDB->escape($img->rawImage()),
                        $eAPI->openDB->escape($img->getImageInfo('mimeType')),
                        $eAPI->openDB->escape($img->getImageInfo('height')),
                        $eAPI->openDB->escape($img->getImageInfo('width')),
                        $eAPI->cleanPost['MYSQL']['adID']));
                    if($dbAdImgUpdate['errorNumber']) $eAPI->openDB->transRollback();
                }

                // Refresh the ad's release conditions
                $dbIpRange = $eAPI->openDB->query(sprintf("DELETE FROM releaseConditions WHERE ad_id='%s'", $eAPI->cleanPost['MYSQL']['adID']));
                foreach($releaseConditions as $conditionType => $condtions){
                    foreach($condtions as $condtion){
                        $value = is_array($condtion) ? implode('-', $condtion): $condtion;
                        $dbAdCondition = $eAPI->openDB->query(sprintf("INSERT INTO `releaseConditions` (`ad_id`,`type`,`value`) VALUES('%s','%s','%s')",
                            $eAPI->cleanPost['MYSQL']['adID'],
                            $eAPI->openDB->escape(strip_tags($conditionType)),
                            $eAPI->openDB->escape(strip_tags($value))));
                        if($dbAdCondition['errorNumber']) $eAPI->openDB->transRollback();
                    }
                }

                sessionSet('adManager_success', true);
                sessionSet('adManager_successMsg', "Advertisement updated successfuly");
                $eAPI->openDB->transCommit();
                $eAPI->openDB->transEnd();
                gotoAdManagerHome();
            }else{
                die("Ad ID conflict! (Possible Attack)");
            }
        }
    }else{
        if($_FILES['imgFile']['error'] == UPLOAD_ERR_OK){
            // Hack - Save the image in the session (this is so we can display it back out to the user)
            // Resize the image (if it's too big)
//            echo "<pre><tt>".print_r($_FILES['imgFile'], TRUE)."</tt></pre>";
            $img = new image($_FILES['imgFile']['tmp_name'], sys_get_temp_dir().'/'.$_FILES['imgFile']['name']);
            if($img->getWidth()  > localvars::get('maxImgWidth'))  $img->resizeToWidth(localvars::get('maxImgWidth'));
            if($img->getHeight() > localvars::get('maxImgHeight')) $img->resizeToHeight(localvars::get('maxImgHeight'));

            if($_FILES['imgFile']['error'] == UPLOAD_ERR_OK){
                sessionSet('adImage', array(
                    'data' => $img->rawImage(),
                    'type' => $img->getImageInfo('mimeType')
                ));
            }
        }
    }

    // Reformat the POST data, this will standardize the data (to make our live SO MUCH easier later)
    $adData['conditions'] = $releaseConditions;
}else{
    // Hack - wipe out any old session image
    sessionDelete('adImage');
}

function fieldValue($name){
    global $eAPI,$adData;
    $result = '';
    if(isset($eAPI->cleanPost['MYSQL'][$name])){
        $result = $eAPI->cleanPost['MYSQL'][$name];
    }elseif(isset($adData[$name])){
        $result = $adData[$name];
    }
    return htmlentities($result);
}
function adConditions($type){
    global $eAPI,$adConditions;
    $result = array();
    switch($type){
        case 'dateRange':
            if(isset($eAPI->cleanPost['MYSQL']['condition_dateRangeStart_month'])){
                foreach($eAPI->cleanPost['MYSQL']['condition_dateRangeStart_month'] as $key => $value){
                    $result[$key] = array(
                        'startDay'   => $eAPI->cleanPost['MYSQL']['condition_dateRangeStart_day'][$key],
                        'startMonth' => $eAPI->cleanPost['MYSQL']['condition_dateRangeStart_month'][$key],
                        'startYear'  => $eAPI->cleanPost['MYSQL']['condition_dateRangeStart_year'][$key],
                        'endDay'     => $eAPI->cleanPost['MYSQL']['condition_dateRangeEnd_day'][$key],
                        'endMonth'   => $eAPI->cleanPost['MYSQL']['condition_dateRangeEnd_month'][$key],
                        'endYear'    => $eAPI->cleanPost['MYSQL']['condition_dateRangeEnd_year'][$key]
                    );
                }
            }elseif(isset($adConditions['dateRange'])){
                foreach($adConditions['dateRange'] as $key => $dateRange){
                    list($from,$to) = explode('-', $dateRange);
                    $result[$key] = array(
                        'startDay'   => date('j', $from),
                        'startMonth' => date('n', $from),
                        'startYear'  => date('Y', $from),
                        'endDay'     => date('j', $to),
                        'endMonth'   => date('n', $to),
                        'endYear'    => date('Y', $to)
                    );
                }
            }
            break;

        case 'timeRange':
            if(isset($eAPI->cleanPost['MYSQL']['condition_timeRangeStart_hour'])){
                foreach($eAPI->cleanPost['MYSQL']['condition_timeRangeStart_hour'] as $key => $value){
                    $result[$key] = array(
                        'startHr'  => $eAPI->cleanPost['MYSQL']['condition_timeRangeStart_hour'][$key],
                        'startMin' => $eAPI->cleanPost['MYSQL']['condition_timeRangeStart_min'][$key],
                        'endHr'    => $eAPI->cleanPost['MYSQL']['condition_timeRangeEnd_hour'][$key],
                        'endMin'   => $eAPI->cleanPost['MYSQL']['condition_timeRangeEnd_min'][$key]
                    );
                }
            }elseif(isset($adConditions['timeRange'])){
                foreach($adConditions['timeRange'] as $key => $timeRange){
                    list($from,$to) = explode('-', $timeRange);
                    $from += ((int)date('I')*60);
                    $to += ((int)date('I')*60);

                    $result[$key] = array(
                        'startHr'  => floor($from/60),
                        'startMin' => $from%60,
                        'endHr'    => floor($to/60),
                        'endMin'   => $to%60
                    );
                }
            }
            break;

        case 'weekDay':
            if(isset($eAPI->cleanPost['MYSQL']['condition_weekDays'])){
                $result = $eAPI->cleanPost['MYSQL']['condition_weekDays'];
            }elseif(isset($adConditions['weekDay'])){
                $result = $adConditions['weekDay'];
            }
            break;

        case 'ipGroup':
            if(isset($eAPI->cleanPost['MYSQL']['condition_ipGroups'])){
                $result = $eAPI->cleanPost['MYSQL']['condition_ipGroups'];
            }elseif(isset($adConditions['ipGroup'])){
                $result = $adConditions['ipGroup'];
            }
            break;
    }
    return $result;
}
?>
<style type="text/css">
    label.formField{
        width: 125px;
    }
    .dateRange label{
        width: 135px;
    }
    .weekDays label{
        display: block;
    }
</style>
<script type="text/javascript">
    $(function(){
        $('#adAction').change(function(){
            var selectMenu = $(this);
            $('#actionURL,#actionEmail,#actionJs').hide();
            $('#actionURL input,#actionEmail input,#actionJs textarea').attr('disabled', 'disabled');
            switch(selectMenu.val()){
                case 'link':
                    $('#actionURL').show();
                    $('#actionURL input').removeAttr('disabled').focus();
                    break;
                case 'email':
                    $('#actionEmail').show();
                    $('#actionEmail input').removeAttr('disabled').focus();
                    break;
                case 'javascript':
                    $('#actionJs').show();
                    $('#actionJs textarea').removeAttr('disabled').focus();
                    break;
            }
        });

        // Initialize the UI
        $('#adAction').change();
        console.log("dateRange Length:"+$('#dateRangeConditions li.dateRange:visible').length);
        if($('#dateRangeConditions li.dateRange:visible').length > 0){
            $('#dateRangeConditions').removeClass('closed');
            $('#removeDateRangeLink').show();
        }
        console.log("timeRange Length:"+$('#timeRangeConditions li.timeRange:visible').length);
        if($('#timeRangeConditions li.timeRange:visible').length > 0){
            $('#timeRangeConditions').removeClass('closed');
            $('#removeTimeRangeLink').show();
        }
    });

    function addCondition(type){
        switch(type){
            case 'dateRange':
                var dateRangeTpml = $('#dateRangeTmpl');
                var dateRange = dateRangeTpml.clone().show();
                var newID = $('#dateRangeConditions li.dateRange:visible').length+1;
                dateRange.attr('id','dateRange-'+newID).removeClass('hidden');
                $('.dateRangeNumber', dateRange).html(newID+':');
                $(':input,select',dateRange).removeAttr('disabled');
                dateRange.addClass('conditionSeperator');
                dateRangeTpml.before(dateRange);
                $('#dateRangeConditions').removeClass('closed');
                $('#removeDateRangeLink').show();
                break;

            case 'timeRange':
                var timeRangeTpml = $('#timeRangeTmpl');
                var timeRange = timeRangeTpml.clone().show();
                var newID = $('#timeRangeConditions li.timeRange:visible').length+1;
                timeRange.attr('id','timeRange-'+newID).removeClass('hidden');
                $('.timeRangeNumber', timeRange).html(newID+':');
                $(':input,select',timeRange).removeAttr('disabled');
                timeRange.addClass('conditionSeperator');
                timeRangeTpml.before(timeRange);
                $('#timeRangeConditions').removeClass('closed');
                $('#removeTimeRangeLink').show();
                break;

            case 'weekday':
                $('#weekdayConditions').removeClass('closed');
                var weekDayBlock = $('#weekdayConditions .weekDays');
                $(':input,select',weekDayBlock).removeAttr('disabled');
                weekDayBlock.show();
                $('#addWeekdayConditionLink').hide();
                $('#removeWeekdayConditionLink').show();
                break;

            case 'ipGroup':
                $('#ipGroupConditions').removeClass('closed');
                var ipGroupBlock = $('#ipGroupConditions .ipGroups');
                $(':input,select',ipGroupBlock).removeAttr('disabled');
                ipGroupBlock.show();
                $('#addIpGroupConditionLink').hide();
                $('#removeIpGroupConditionLink').show();
                break;

            default:
                alert("Unknown condition type!");
                break;
        }
    }
    function removeCondition(type){
        switch(type){
            case 'dateRange':
                $('#dateRangeConditions li:visible:last').remove();
                if($('#dateRangeConditions li:visible').length == 0){
                    $('#dateRangeConditions').addClass('closed');
                    $('#removeDateRangeLink').hide();
                }
                break;

            case 'timeRange':
                $('#timeRangeConditions li:visible:last').remove();
                if($('#timeRangeConditions li:visible').length == 0){
                    $('#timeRangeConditions').addClass('closed');
                    $('#removeTimeRangeLink').hide();
                }
                break;

            case 'weekday':
                var weekDayBlock = $('#weekdayConditions .weekDays');
                weekDayBlock.hide();
                $(':input,select',weekDayBlock).attr('disabled','disabled').removeAttr('checked');
                $('#weekdayConditions').addClass('closed');
                $('#removeWeekdayConditionLink').hide();
                $('#addWeekdayConditionLink').show();
                break;

            case 'ipGroup':
                var ipGroupBlock = $('#ipGroupConditions .ipGroups');
                ipGroupBlock.hide();
                $(':input,select',ipGroupBlock).attr('disabled','disabled').removeAttr('checked');
                $('#ipGroupConditions').addClass('closed');
                $('#removeIpGroupConditionLink').hide();
                $('#addIpGroupConditionLink').show();
                break;

            default:
                alert("Unknown condition type!");
                break;
        }
    }
</script>
<div id="pageTitle">{local var="pageTitle"}</div>
<form action="" method="post" enctype="multipart/form-data">
    {engine name="insertCSRF"}
    <?php
    if(isset($eAPI->cleanGet['MYSQL']['id'])){
        echo sprintf('<input type="hidden" name="adID" value="%s">', $eAPI->cleanGet['MYSQL']['id']);
    }
    echo errorHandle::prettyPrint('error');
    ?>
    <div>
        <ul>
            <li>
                <label for="adName" class="formField requiredField">Name:</label>
                <input name="name" id="adName" value="<?php echo fieldValue('name'); ?>">
            </li>
            <li>
                <label for="adEnabled" class="formField requiredField">Enabled:</label>
                <select name="enabled" id="adEnabled">
                    <option value=""></option>
                    <option value="1"<?php if(fieldValue('enabled') == "1") echo ' selected="selected"' ?>>Yes</option>
                    <option value="0"<?php if(fieldValue('enabled') == "0") echo ' selected="selected"' ?>>No</option>
                </select>
            </li>
            <li>
                <label for="adPriority" class="formField requiredField">Priority:</label>
                <select name="priority" id="adPriority">
                    <option value=""></option>
                    <option value="1"<?php if(fieldValue('priority') == "1") echo ' selected="selected"' ?>>High</option>
                    <option value="0"<?php if(fieldValue('priority') == "0") echo ' selected="selected"' ?>>Low</option>
                </select>
            </li>
            <?php if(isset($eAPI->cleanGet['MYSQL']['id']) or sessionGet('adImage')){ ?>
            <li>
                <label class="formField requiredField">Ad image:</label>
                <?php echo sprintf('<img id="adImage" class="adImage" src="{local var="baseURL"}img.php?id=%s"><br>', (sessionGet('adImage') ? sessionID() : $eAPI->cleanGet['MYSQL']['id'])); ?>
            </li>
            <?php } ?>
            <li>
                <label for="adImage" class="formField requiredField"><?php if(isset($eAPI->cleanGet['MYSQL']['id']) or sessionGet('adImage')){ echo 'New image'; }else{ echo 'Ad image'; } ?>:</label>
                <input name="imgFile" id="adImage" type="file">
            </li>
            <li>
                <label for="adAltText" class="formField requiredField">Image alt text:</label>
                <input name="imgAltText" id="adAltText" value="<?php echo fieldValue('imgAltText'); ?>">
            </li>
            <li>
                <label for="adAction" class="formField requiredField">Click action:</label>
                <select name="imgActionType" id="adAction">
                    <option value=""></option>
                    <option value="none"<?php if(fieldValue('imgActionType') == "none") echo ' selected="selected"' ?>>No action</option>
                    <option value="link"<?php if(fieldValue('imgActionType') == "link") echo ' selected="selected"' ?>>Go to a URL</option>
                    <option value="email"<?php if(fieldValue('imgActionType') == "email") echo ' selected="selected"' ?>>Send an Email</option>
                </select>
            </li>
            <li id="actionURL" class="hidden">
                <label for="actionUrlField" class="formField requiredField">URL:</label>
                <input name="imgActionValue" id="actionUrlField" value="<?php if(fieldValue('imgActionType') == "link") echo fieldValue('imgActionValue'); ?>" disabled="disabled">
            </li>
            <li id="actionEmail" class="hidden">
                <label for="actionEmailField" class="formField requiredField">Email address:</label>
                <input name="imgActionValue" id="actionEmailField" value="<?php if(fieldValue('imgActionType') == "email") echo fieldValue('imgActionValue'); ?>" disabled="disabled">
            </li>
            <li class="displayConditions">
                Display conditions:
                <fieldset class="conditionFieldset closed" id="dateRangeConditions"><legend>Date Range</legend>
                    <ul>
                        <?php foreach(adConditions('dateRange') as $key => $values){ ?>
                        <li class="dateRange conditionSeperator" id="dateRange-<?php echo $key+1 ?>">
                            <div style="float: left; width: 20px;"><?php echo $key+1 ?>:</div>
                            <div style="float: left;">
                                <label>Start of availability:</label>
                                <?php echo $date->dateDropDown(array_merge($dateConfig,array('formname' => 'condition_dateRangeStart','disabled'=>FALSE,'setdate'=>mktime(1,0,0,$values['startMonth'],$values['startDay'],$values['startYear'])))) ?>
                                <br>
                                <label>End of availability:</label>
                                <?php echo $date->dateDropDown(array_merge($dateConfig,array('formname' => 'condition_dateRangeEnd','disabled'=>FALSE,'setdate'=>mktime(1,0,0,$values['endMonth'],$values['endDay'],$values['endYear'])))) ?>
                            </div>
                            <div style="clear: both;"></div>
                        </li>
                        <?php } ?>
                        <li class="hidden dateRange" id="dateRangeTmpl">
                            <div style="float: left; width: 20px;" class="dateRangeNumber"></div>
                            <div style="float: left;">
                                <label>Start of availability:</label>
                                <?php echo $date->dateDropDown(array_merge($dateConfig,array('formname' => 'condition_dateRangeStart'))) ?>
                                <br>
                                <label>End of availability:</label>
                                <?php echo $date->dateDropDown(array_merge($dateConfig,array('formname' => 'condition_dateRangeEnd'))) ?>
                            </div>
                            <div style="clear: both;"></div>
                        </li>
                    </ul>
                    <a href="javascript:addCondition('dateRange')" class="conditionCmds">Add a date range condition</a><span id="removeDateRangeLink" class="hidden"> | <a href="javascript:removeCondition('dateRange')" class="conditionCmds">Remove date range condition</a></span>
                </fieldset>
                <fieldset class="conditionFieldset closed" id="timeRangeConditions"><legend>Time Range</legend>
                    <ul>
                        <?php foreach(adConditions('timeRange') as $key => $values){ ?>
                        <li class="timeRange conditionSeperator" id="timeRange-<?php echo $key+1 ?>">
                            <div style="float: left; width: 20px;" class="timeRangeNumber"><?php echo $key+1 ?>:</div>
                            <div style="float: left;">
                                <?php echo $date->timeDropDown(array_merge($timeConfig,array('formname'=>'condition_timeRangeStart','disabled'=>FALSE,'settime'=>mktime($values['startHr'], $values['startMin'])))) ?>
                                to
                                <?php echo $date->timeDropDown(array_merge($timeConfig,array('formname'=>'condition_timeRangeEnd','disabled'=>FALSE,'settime'=>mktime($values['endHr'], $values['endMin'])))) ?>
                            </div>
                            <div style="clear: both;"></div>
                        </li>
                        <?php } ?>
                        <li class="hidden timeRange" id="timeRangeTmpl">
                            <div style="float: left; width: 20px;" class="timeRangeNumber"></div>
                            <div style="float: left;">
                                <?php echo $date->timeDropDown(array_merge($timeConfig,array('formname' => 'condition_timeRangeStart'))) ?>
                                to
                                <?php echo $date->timeDropDown(array_merge($timeConfig,array('formname' => 'condition_timeRangeEnd'))) ?>
                            </div>
                            <div style="clear: both;"></div>
                        </li>
                    </ul>
                    <a href="javascript:addCondition('timeRange')" class="conditionCmds">Add a time range condition</a><span id="removeTimeRangeLink" class="hidden"> | <a href="javascript:removeCondition('timeRange')" class="conditionCmds">Remove time range condition</a></span>
                </fieldset>
                <fieldset class="conditionFieldset<?php $showWeekdays = sizeof(adConditions('weekDay')); if(!$showWeekdays) echo ' closed'; ?>" id="weekdayConditions"><legend>Week Day</legend>
                    <div class="weekDays conditionSeperator<?php if(!$showWeekdays) echo ' hidden'; ?>">
                        <label><input name="condition_weekDays[]" value="Monday" type="checkbox"<?php if(!$showWeekdays) echo ' disabled="disabled"'; if($showWeekdays and in_array('Monday', adConditions('weekDay'))) echo ' checked="checked"'; ?>> Monday</label>
                        <label><input name="condition_weekDays[]" value="Tuesday" type="checkbox"<?php if(!$showWeekdays) echo ' disabled="disabled"'; if($showWeekdays and in_array('Tuesday', adConditions('weekDay'))) echo ' checked="checked"'; ?>> Tuesday</label>
                        <label><input name="condition_weekDays[]" value="Wednesday" type="checkbox"<?php if(!$showWeekdays) echo ' disabled="disabled"'; if($showWeekdays and in_array('Wednesday', adConditions('weekDay'))) echo ' checked="checked"'; ?>> Wednesday</label>
                        <label><input name="condition_weekDays[]" value="Thursday" type="checkbox"<?php if(!$showWeekdays) echo ' disabled="disabled"'; if($showWeekdays and in_array('Thursday', adConditions('weekDay'))) echo ' checked="checked"'; ?>> Thursday</label>
                        <label><input name="condition_weekDays[]" value="Friday" type="checkbox"<?php if(!$showWeekdays) echo ' disabled="disabled"'; if($showWeekdays and in_array('Friday', adConditions('weekDay'))) echo ' checked="checked"'; ?>> Friday</label>
                        <label><input name="condition_weekDays[]" value="Saturday" type="checkbox"<?php if(!$showWeekdays) echo ' disabled="disabled"'; if($showWeekdays and in_array('Saturday', adConditions('weekDay'))) echo ' checked="checked"'; ?>> Saturday</label>
                        <label><input name="condition_weekDays[]" value="Sunday" type="checkbox"<?php if(!$showWeekdays) echo ' disabled="disabled"'; if($showWeekdays and in_array('Sunday', adConditions('weekDay'))) echo ' checked="checked"'; ?>> Sunday</label>
                    </div>
                    <a id="addWeekdayConditionLink" href="javascript:addCondition('weekday');" class="conditionCmds<?php if($showWeekdays) echo ' hidden'; ?>">Enable weekday condition</a>
                    <a id="removeWeekdayConditionLink" href="javascript:removeCondition('weekday');" class="conditionCmds<?php if(!$showWeekdays) echo ' hidden'; ?>">Disable weekday condition</a>
                </fieldset>
                <fieldset class="conditionFieldset<?php $showIpGroups = (bool)sizeof(adConditions('ipGroup')); if(!$showIpGroups) echo ' closed'; ?>" id="ipGroupConditions"><legend>IP Group</legend>
                    <div class="ipGroups conditionSeperator<?php if(!$showIpGroups) echo ' hidden'; ?>">
                        <?php
                        $dbIpGroups = $eAPI->openDB->query("SELECT * FROM ipGroups ORDER BY `name` ASC");
	                    if($dbIpGroups['result']){
                            if($dbIpGroups['numrows']){
                                while($ipGroup = mysql_fetch_assoc($dbIpGroups['result'])){
                                    echo sprintf('<label><input name="condition_ipGroups[]" value="%s" type="checkbox"%s%s> %s</label>',
                                        $ipGroup['ID'],
                                        ((!$showIpGroups) ? ' disabled="disabled"' : ''),
                                        (($showIpGroups and in_array($ipGroup['ID'], adConditions('ipGroup'))) ? ' checked="checked"' : ''),
                                        $ipGroup['name']);
                                }
                            }else{
                                echo '<i>No IP Groups defined</i> (<a href="ipGroups.php">Edit Groups</a>)';
                            }
                        }else{
                            echo 'SQL Error! - '.$dbIpGroups['error'];
                        }
                        ?>
                    </div>
                    <a id="addIpGroupConditionLink" href="javascript:addCondition('ipGroup');" class="conditionCmds<?php if($showIpGroups) echo ' hidden'; ?>">Enable IP group condition</a>
                    <a id="removeIpGroupConditionLink" href="javascript:removeCondition('ipGroup');" class="conditionCmds<?php if(!$showIpGroups) echo ' hidden'; ?>">Disable IP group condition</a>
                </fieldset>
            </li>
        </ul>
    </div>
    <br><hr>
    <?php
    if(isset($eAPI->cleanGet['MYSQL']['id'])){
        echo '<input type="submit" value="Update Advertisement" name="updateAd">';
        echo '<input type="button" value="Cancel" onclick="window.location=\'index.php\'">';
    }else{
        echo '<input name="createAd" type="submit" value="Create Advertisement">';
    }
    ?>
</form>
<?php $eAPI->eTemplate("include","footer"); ?>