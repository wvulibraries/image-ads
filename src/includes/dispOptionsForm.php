<?php 

$localvars         = localvars::getInstance();
$form              = formBuilder::createForm('displayOptions');
$imageId = $_GET['MYSQL']['imageID']; 


// DISPLAY PARAMETERS -- Date Range / Time Range / Weekday 
// ==========================================================================================
// Engine Setups for making dropdown menus 
// Throws huge amounts of erros, but functions fine?  
$date = new date;

//DropDowns 
$startMonth = $date->dropdownMonthSelect(1,TRUE,array("id"=>"start_month"));
$startDay   = $date->dropdownDaySelect(TRUE,array("id"=>"start_day"));
$startYear  = $date->dropdownYearSelect(0,5,TRUE,array("id"=>"start_year"));


$endMonth   = $date->dropdownMonthSelect(1,TRUE,array("id"=>"end_month"));
$endDay     = $date->dropdownDaySelect(TRUE,array("id"=>"end_day"));
$endYear    = $date->dropdownYearSelect(0,5,TRUE,array("id"=>"end_year"));

$startTime  = $date->timeDropDown(array("formname" => "start_Time")); 
$endTime    = $date->timeDropDown(array("formname" => "end_Time")); 




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
                'value'  => $startMonth . $startDay . $startYear,
                'showIn' => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE)
            )
        );
            
        $form->addField(
            array(
                'name'   => "dateEnd",
                'label'  => "Date Range End", 
                'type'   => "plaintext",
                'value'  => $endMonth . $endDay . $endYear,
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