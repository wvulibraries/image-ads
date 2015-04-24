<?php

// Making Date ranges into a function for JS to create new ones on the fly. 
// Engine Setups for making dropdown menus 
function addDateRanges() {
    $date = new date;
    // Date and Time Dropdown built by engine 
    $startMonth = $date->dropdownMonthSelect(1, TRUE, array("id"=>"start_date","name"=>"dateStart[]"));
    $startDay   = $date->dropdownDaySelect(TRUE, array("id"=>"start_date","name"=>"dateStart[]")); 
    $startYear  = $date->dropdownYearSelect(0,5, TRUE, array("id"=>"start_date","name"=>"dateStart[]"));
    
    $endMonth = $date->dropdownMonthSelect(1, TRUE, array("id"=>"end_date","name"=>"dateEnd[]"));
    $endDay   = $date->dropdownDaySelect(TRUE, array("id"=>"end_date","name"=>"dateEnd[]")); 
    $endYear  = $date->dropdownYearSelect(0,5, TRUE, array("id"=>"end_date","name"=>"dateEnd[]"));
    
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