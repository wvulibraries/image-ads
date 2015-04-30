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

// Making Date ranges into a function for JS to create new ones on the fly. 
// Engine Setups for making dropdown menus 
function addDateRanges($options=NULL) {
    // if $options and $options is not an array, return false
    if(!isnull($options) && !is_array($options)) { 
        return FALSE; 
    }
   
    $date = new date; 

    // Date and Time Dropdown built by engine 
    $startMonth = $date->dropdownMonthSelect(1, (isset($options['month']) && checkDateOptionMonth($options['month']))?$options['month']:TRUE, array("id"=>"start_date","name"=>"dateStart[]"));
    $startDay   = $date->dropdownDaySelect((isset($options['day']) && checkDateOptionDay($options['day']))?$options['day']:TRUE, array("id"=>"start_date","name"=>"dateStart[]")); 
    $startYear  = $date->dropdownYearSelect(0,5, (isset($options['year']))?$options['year']:TRUE, array("id"=>"start_date","name"=>"dateStart[]"));
    
    $endMonth = $date->dropdownMonthSelect(1, (isset($options['endMonth']) && checkDateOptionMonth($options['endMonth']))?$options['endMonth']:TRUE, array("id"=>"end_date","name"=>"dateEnd[]"));
    $endDay   = $date->dropdownDaySelect((isset($options['endDay']) && checkDateOptionDay($options['endDay']))?$options['endDay']:TRUE, array("id"=>"end_date","name"=>"dateEnd[]")); 
    $endYear  = $date->dropdownYearSelect(0,5, (isset($options['endYear']))?$options['endYear']:TRUE, array("id"=>"end_date","name"=>"dateEnd[]"));
    
    $startDateRange = $startMonth . "/" . $startDay . "/" . $startYear; 
    $endDateRange   = $endMonth . "/" . $endDay . "/" . $endYear;

    // Return JS
    return sprintf('<div class="inputs"> <strong> Start Date : </strong> <br/> %s <br/> <strong> End Date : </strong> <br/> %s <br/><br/> </div>', 
        $startDateRange,
        $endDateRange
    );
}


// TIME FUNCTIONS FOR CALLBACKS 
// ===========================================

function addTimeRanges() {
    $date = new date;

    // Define Dropdwons 
    $startHour = $date->dropdownHourSelect(TRUE, TRUE, array("name" => "timeStart[]")); 
    $startMin  = $date->dropdownMinuteSelect(TRUE, TRUE, array("name" => "timeStart[]"));  

    $endHour   = $date->dropdownHourSelect(TRUE, TRUE, array("name" => "timeEnd[]")); 
    $endMin    = $date->dropdownMinuteSelect(TRUE, TRUE, array("name" => "timeEnd[]")); 

    // Engine Time Dropdowns & Return them for JS
    $startTime = $startHour . " : " . $startMin . "mins"; 
    $endTime   = $endHour . " : " . $endMin . "mins"; 

    // Return for JS
    return sprintf('<div class="times"><strong> Start Time : </strong> <br/>  %s <br/><strong> End Time : </strong> <br/>  %s <br/><br/> </div>', 
        $startTime,
        $endTime
    );
}

?>