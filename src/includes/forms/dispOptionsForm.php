<?php 
// callback functions 
//=========================================
function adjustStartDate(){
    // Take the Input Data
    $thisdata = $_POST['MYSQL']; 

    print "<pre>";
    var_dump($thisdata);
    print "</pre>"; 

    // Make Adjustments to the Time Stuff 
    $theStartTime = $thisdata["timeStart_hour"] . $thisdata["timeStart_min"] . $thisdata["timeStart_ampm"];
    print strtotime($theStartTime); 
    // Start to refactor the input Data
    // Put the Input data into a new array, array should match the mysql information
    // $databaseData array(); 
    //return $databaseData; 
}


// array(10) {
//   ["__formID"]=> string(32) "6998c8ad8733b48e2e63628c50875ec9"
//   ["__csrfID"]=> string(13) "552eb7bbe5263"
//   ["__csrfToken"]=> string(32) "16178b01170ee839b212e3c40208932d"
//   ["imageAdID"]=> string(1) "5"
//   ["start_Time_hour"]=> string(1) "1"
//   ["start_Time_min"]=> string(1) "0"
//   ["start_Time_ampm"]=> string(2) "am"
//   ["end_Time_hour"]=> string(1) "1"
//   ["end_Time_min"]=> string(1) "0"
//   ["end_Time_ampm"]=> string(2) "pm"
// }

// Callback Logic for handling the image upload 
if(!is_empty($_POST) || session::has('POST')) { 
    // Run the Processor 
    // ========================================
    $processor = formBuilder::createProcessor(); 
    // Set the Callback functions to fire from the callbacks.php file
    // =========================================
    // Parameter Types ($trigger, $callback) 
    $processor->setCallback('beforeInsert', 'adjustStartDate');
    $processor->processPost(); 
}


$localvars = localvars::getInstance();
$form      = formBuilder::createForm('displayOptions');
$imageId   = $_GET['MYSQL']['imageID']; 


// DISPLAY PARAMETERS -- Date Range / Time Range / Weekday 
// ==========================================================================================
// Engine Setups for making dropdown menus 
// Throws huge amounts of erros, but functions fine?  
$date = new date;

//DropDowns 
// $startMonth = $date->dropdownMonthSelect(1,TRUE,array("id"=>"start_month","name"=>"dateStart_1"));
// $startDay   = $date->dropdownDaySelect(TRUE,array("id"=>"start_day","name"=>"dateStart_2"));
// $startYear  = $date->dropdownYearSelect(0,5,TRUE,array("id"=>"start_year","name"=>"dateStart_3"));
$startDateRange = $date->dateDropDown(array("id"=>"start_date","formname"=>"dateStart","monthdformat"=>"mon","setdate"=>"Ymd"));
$endDateRange  = $date->dateDropDown(array("id"=>"end_date","formname"=>"dateEnd","monthdformat"=>"mon","setdate"=>"Ymd"));

// $endMonth   = $date->dropdownMonthSelect(1,TRUE,array("id"=>"end_month", "name"=>"date_end_1"));
// $endDay     = $date->dropdownDaySelect(TRUE,array("id"=>"end_day", "name"=>"date_end_1"));
// $endYear    = $date->dropdownYearSelect(0,5,TRUE,array("id"=>"end_year", "name"=>"date_end_1"));

$startTime  = $date->timeDropDown(array("formname" => "timeStart")); 
$endTime    = $date->timeDropDown(array("formname" => "timeEnd")); 




$form->linkToDatabase(array(
    'table'            => "displayConditions"
));

    $form->insertTitle = "Add Image Display Options";
    $form->editTitle   = "Edit Image Display Options";

        $form->addField(
            array(
                'name'     => "imageAdID", 
                'label'    => "Image ID",
                'type'     => "hidden",
                'value'    => $_GET['MYSQL']['imageID']
            )
        );

        $form->addField(
            array(
                'name'   => "dateStart",
                'label'  => "Date Range Start",
                'type'   => "plaintext", 
                'value'  => $startDateRange,
                'showIn' => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE)
            )
        );
            
        $form->addField(
            array(
                'name'   => "dateEnd",
                'label'  => "Date Range End", 
                'type'   => "plaintext",
                'value'  => $endDateRange,
                'showIn' => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE)
            )
        );
            
        $form->addField(
            array(
                'name'   => "timeStart",
                'label'  => "Time Range Start",
                'type'   => "plaintext",
                'value'  => $startTime,
                'showIn' => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE)
            )
        );
            
        $form->addField(
            array(
                'name'   => "timeEnd",
                'label'  => "Time Range End", 
                'type'   => "plaintext",
                'value'  => $endTime,
                'showIn' => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE)
            )
        );
            
        $form->addField(
            array(
                'name'    => "weekdays",
                'label'   => "Days of the Week",
                'type'    => "checkbox",
                'options' => array(
                                'Monday'    => "Monday",
                                'Tuesday'   => 'Tuesday',
                                'Wednesday' => "Wednesday",
                                'Thursday'  => "Thursday", 
                                'Friday'    => "Friday", 
                                'Saturday'  => "Saturday", 
                                'Sunday'    => "Sunday" 
                              ), 
                'showIn'  => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE)
            )
        );
            
            

?> 