<?php
if(!class_exists('EngineAPI')){
    require("engineIncludeStatement.php");
}
$eAPI = EngineAPI::singleton();

// Connect to the database
$eAPI->dbConnect("database","adManager",TRUE);
localvars::dbImport("settings", "id", "value");

// Remove the pageTitle (so we don't interfer with any template)
localvars::del('pageTitle');

// Prep CSS and JS files
localvars::add("adManager_cssFile", localvars::get('baseURL')."css/adFlipper.css");
localvars::add("adManager_cssTag", sprintf('<link rel="stylesheet" href="%s">', localvars::get('adManager_cssFile')));
localvars::add("adManager_jsFile", localvars::get('baseURL')."js/adFlipper.min.js");
localvars::add("adManager_jsTag", sprintf('<script type="text/javascript" src="%s"></script>', localvars::get('adManager_jsFile')));

// Proccess the Ads
$ads = array();
$dbAds = $eAPI->openDB->query("SELECT * FROM `ads` WHERE `enabled`='1' ORDER BY `priority` DESC, `added` ASC");
if($dbAds['result']){
    while($ad = mysql_fetch_assoc($dbAds['result'])){
        $dbAdConditions = $eAPI->openDB->query("SELECT * FROM `releaseConditions` WHERE `ad_id`='%s'", $eAPI->openDB->escape($ad['ID']));
        if($dbAdConditions['result'] and mysql_num_rows($dbAdConditions['result'])){
            while($adCondition = mysql_fetch_assoc($dbAdConditions['result'])){
                $adConditions[ $adCondition['type'] ][] = $adCondition;
            }

            if(isset($adConditions['weekDay'])){
                $weekday = date('l');
                $found   = false;
                foreach($adConditions['weekDay'] as $weekDayCondition){
                    if($weekDayCondition['value'] == $weekday){
                        $found = true;
                        break;
                    }
                }
                if(!$found) continue;
            }

            if(isset($adConditions['dateRange'])){
                $now   = time();
                $found = false;
                foreach($adConditions['weekDay'] as $dateRangeCondition){
                    list($from,$to) = explode('-', $dateRangeCondition['value']);
                    if($now >= $from and $now <= $to){
                        $found = true;
                        break;
                    }
                }
                if(!$found) continue;
            }

            if(isset($adConditions['timeRange'])){
                // All times are in Standard Time (Not DST)
                $now   = ((int)(date('G')*60)+date('i'))-(date('I')*60);
                $found = false;
                foreach($adConditions['timeRange'] as $timeRangeCondition){
                    list($from,$to) = explode('-', $timeRangeCondition['value']);
                    if($now >= $from and $now <= $to){
                        $found = true;
                        break;
                    }
                }
                if(!$found) continue;
            }

            if(isset($adConditions['ipGroup'])){
                $found = false;
                foreach($adConditions['ipGroup'] as $ipGroupCondition){
                    if(userInfoIPRangeCheck($ipGroupCondition['value'])){
                        $found = true;
                        break;
                    }
                }
                if(!$found) continue;
            }
        }

        // -------------------------------------------------------------------------------------

        // Add this ad!
        $ads[] = sprintf(
            '<a href="%s"><img src="%simg.php?id=%s" alt="%s"></a>',
            $ad['imgActionType']=='email' ? 'mailto:'.$ad['imgActionValue'] : $ad['imgActionValue'],
            localvars::get('baseURL'),
            $ad['ID'],
            $ad['imgAltText']);

        // If we now have the displayNum of ad images, stop
        if(sizeof($ads) >= localvars::get('displayNum')) break;
    }
}

// Build the HTML
localvars::add("adManager_html", implode('', $ads));

// Build the JavaScript
$adManager_js = sprintf("
    $('#rotatingImgs').adFlipper({
        autoPlay: %s,
        debug: %s,
        pagination: %s,
        pauseOnHover: %s,
        pauseTime: %s,
        width: %s,
        height: %s,
        debug: true
    });",
    // localvars::get('adManager_sliderID'), // Edit Mike
    localvars::get('adFlipper_autoPlay', '1') ? 'true' : 'false',
    localvars::get('adFlipper_debug', '0') ? 'true' : 'false',
    localvars::get('adFlipper_pagination', '1') ? 'true' : 'false',
    localvars::get('adFlipper_pauseOnHover', '1') ? 'true' : 'false',
    localvars::get('adFlipper_pauseTime', '3000'),
    localvars::get('maxImgWidth', '200'),
    localvars::get('maxImgHeight', '200'));

localvars::add("adManager_js", $adManager_js);
?>
