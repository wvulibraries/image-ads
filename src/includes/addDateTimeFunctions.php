<?php

function checkDateOptionMonth($value) {
    if(validate::getInstance()->integer($value) && ($value <= 12 && $value >= 1)) {
        return TRUE;
    }
   return FALSE;
}

function checkDateOptionDay($value) {
    if(validate::getInstance()->integer($value) && ($value <= 31 && $value >= 1)){
        return TRUE;
    }
    return FALSE;
}

function addDateRanges($options=NULL) {

    if(!isnull($options) && !is_array($options)) {
        return FALSE;
    }

    $date           = new date;
    $startMonth     = $date->dropdownMonthSelect(1, (isset($options['month']) && checkDateOptionMonth($options['month']))?$options['month']:TRUE, array("id"=>"start_date","name"=>"dateStart[]"));
    $startDay       = $date->dropdownDaySelect((isset($options['day']) && checkDateOptionDay($options['day']))?$options['day']:TRUE, array("id"=>"start_date","name"=>"dateStart[]"));
    $startYear      = $date->dropdownYearSelect(0,5, (isset($options['year']))?$options['year']:TRUE, array("id"=>"start_date","name"=>"dateStart[]"));
    $endMonth       = $date->dropdownMonthSelect(1, (isset($options['endMonth']) && checkDateOptionMonth($options['endMonth']))?$options['endMonth']:TRUE, array("id"=>"end_date","name"=>"dateEnd[]"));
    $endDay         = $date->dropdownDaySelect((isset($options['endDay']) && checkDateOptionDay($options['endDay']))?$options['endDay']:TRUE, array("id"=>"end_date","name"=>"dateEnd[]"));
    $endYear        = $date->dropdownYearSelect(0,5, (isset($options['endYear']))?$options['endYear']:TRUE, array("id"=>"end_date","name"=>"dateEnd[]"));
    $startDateRange = $startMonth . "/" . $startDay . "/" . $startYear;
    $endDateRange   = $endMonth . "/" . $endDay . "/" . $endYear;

    return sprintf('<div class="inputs"> <strong> Start Date : </strong> <br/> %s <br/> <strong> End Date : </strong> <br/> %s <br/><br/> </div>',
        $startDateRange,
        $endDateRange
    );
}

function addTimeRanges($options=NULL) {
    $date      = new date;
    $startHour = $date->dropdownHourSelect(TRUE, isset($options['startHour'])?$options['startHour']:TRUE, array("name" => "timeStart[]"));
    $startMin  = $date->dropdownMinuteSelect(15, isset($options['startMin'])?$options['startMin']:TRUE, array("name" => "timeStart[]"));
    $endHour   = $date->dropdownHourSelect(TRUE, isset($options['endHour'])?$options['endHour']:TRUE, array("name" => "timeEnd[]"));
    $endMin    = $date->dropdownMinuteSelect(15, isset($options['endMin'])?$options['endMin']:TRUE, array("name" => "timeEnd[]"));
    $startTime = $startHour . " : " . $startMin . "mins";
    $endTime   = $endHour . " : " . $endMin . "mins";

    return sprintf('<div class="times"><strong> Start Time : </strong> <br/>  %s <br/><strong> End Time : </strong> <br/>  %s <br/><br/> </div>',
        $startTime,
        $endTime
    );
}

?>