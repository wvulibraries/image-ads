<?php

// glob vars  
//=========================================
$localvars = localvars::getInstance();
$form      = formBuilder::createForm('displayOptions');
$imageId   = $_GET['MYSQL']['imageID']; 
$db        = db::get($localvars->get('dbConnectionName')); // TELL WHAT DB TO CONNECT TO

//Insert Date Time Functions & Callback Functions  
//======================================================== 
recurseInsert("includes/addDateTimeFunctions.php","php");    
recurseInsert("includes/forms/callbacks.php", "php");

// FORM LOGIC FOR FORM BUILDER  
// ===========================================

// Callback Logic for handling the image upload 
if(!is_empty($_POST) || session::has('POST')) { 
    // Run the Processor 
    $processor = formBuilder::createProcessor(); 
    // Set the Callback functions to fire from the callbacks.php file
    $processor->setCallback('beforeInsert', 'adjustDisplayConditions');
    $processor->processPost(); 
}


//  Setup the form itself & Add form fields
//  Built using Engines Form Builder 
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
                'name'   => "Date Ranges",
                'label'  => "Add Dates Image Will Display", 
                'type'   => "plaintext",
                'value'  => "<a href='javascript:void(0);' class='addDateRange'> Add Date </a> | <a href='javascript:void(0);' class='deleteDateRange'> Remove Last Date </a>  ",
                'showIn' => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE)
            )
        );

        $form->addField(
            array(
                'name'   => "Time Ranges",
                'label'  => "Add Times Image Will Display", 
                'type'   => "plaintext",
                'value'  => "<a href='javascript:void(0);' class='addTimeRange'> Add Time </a> | <a href='javascript:void(0);' class='deleteTimeRange'> Remove Last Time Range </a>  ",
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