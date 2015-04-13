<?php 

$localvars         = localvars::getInstance();
$form              = formBuilder::createForm('displayOptions');
$imageId = $_GET['MYSQL']['imageID']; 

$form->linkToDatabase(array(
    'table'            => "displayConditions"
));

    $form->insertTitle = "Add Image Display Options";
    $form->editTitle   = "Edit Image Display Options";

        $form->addField(
            array(
                'name'     => "imageAdID", 
                'label'    => "Image ID",
                //'type'     => "hidden",
                'value'    => $_GET['MYSQL']['imageID']
            )
        );

        $form->addField(
            array(
                'name'   => "dateStart",
                'label'  => "Date Range Start",
                'type'   => "text", 
                'showIn' => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE)
            )
        );
            
        $form->addField(
            array(
                'name'   => "dateEnd",
                'label'  => "Date Range End", 
                'type'   => "text",
                'showIn' => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE)
            )
        );
            
        $form->addField(
            array(
                'name'   => "timeStart",
                'label'  => "Time Range Start",
                'type'   => "text", 
                'showIn' => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE)
            )
        );
            
        $form->addField(
            array(
                'name'   => "timeEnd",
                'label'  => "Time Range End", 
                'type'   => "text",
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