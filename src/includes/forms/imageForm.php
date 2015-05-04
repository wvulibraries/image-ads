 <?php 
 // Building the form 
    $localvars = localvars::getInstance();
    $form      = formBuilder::createForm('imageAdForm');

    $form->linkToDatabase(array(
        'table' => "imageAds"
    ));

    $form->insertTitle      = "New Roating Image";
    $form->editTitle        = "Edit Rotating Image";
    $form->updateTitle      = "Update Form";
    $form->submitTextUpdate = 'Update';
    $form->submitTextEdit   = 'Update';

// Callbacks 
// ========================================================================
    recurseInsert("includes/forms/callbacks.php", "php");
// Display Option Information 
// ========================================================================
    recurseInsert("includes/addDateTimeFunctions.php","php");  


// Set Date and Time Variables for the form, but need to set before the editForm information is loaded. 
$dateValue = "<a href='javascript:void(0);' class='addDateRange'> Add Date </a> | <a href='javascript:void(0);' class='deleteDateRange'> Remove Last Date </a>"; 
$timeValue = "<a href='javascript:void(0);' class='addTimeRange'> Add Time </a> | <a href='javascript:void(0);' class='deleteTimeRange'> Remove Last Time Range </a>"; 

// Check to see if this is the edit form
// ========================================================================
    if(!is_empty($_GET) && validate::getInstance()->integer($_GET['MYSQL']['imageID'])) { 
        $imageID   = $_GET['MYSQL']['imageID']; 
        $editForm = TRUE; 
        recurseInsert("includes/forms/editForm.php", "php"); 

        $dateValue .= $localvars->get('exsistingDateRanges'); 
        $timeValue .= $localvars->get('exsistingTimeRanges');

    } else { 
        $editForm = FALSE;
    }  

// Callback Logic for handling the image upload 
    if(!is_empty($_POST) || session::has('POST')) { 
        // Run the Processor 
        // ========================================
        $processor = formBuilder::createProcessor(); 
        // Set the Callback functions to fire from the callbacks.php file
        // =========================================
        // Parameter Types ($trigger, $callback) 
        $processor->setCallback('beforeInsert', 'processNewImage');
        $processor->setCallback('afterInsert', 'processDisplayInformation');
        $processor->setCallback('beforeUpdate', 'processUpdate');
        $processor->processPost(); 
    }      

    $form->addField(
        array(
            'name'            => "imageAd",
            'fieldID'         => "imageAd",
            'label'           => "File Upload",
            'showInEditStrip' => FALSE,
            'showIn'          => array(formBuilder::TYPE_INSERT),
            'required'        => ($editForm === TRUE ? FALSE : TRUE),
            'type'            => 'file'
        )
    );

    $form->addField(
        array(
            'name'            => "ID",
            'label'           => "Table ID",
            'primary'         => TRUE,
            'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE, formbuilder::TYPE_EDIT),
            'type'            => 'hidden',
            'value'           => ($editForm === TRUE ? $imageID : NULL),
        )
    );

    $form->addField(
        array(
            'name'            => "name",
            'label'           => "Image Name",
            'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE, formbuilder::TYPE_EDIT),
            'required'        => TRUE,
            'type'            => 'text',
            'duplicates'      => TRUE, 
            'fieldID'         => "imgName"
        )
    );

    $form->addField(
        array(
            'name'            => "enabled",
            'label'           => "Is this image being displayed now?",
            'showInEditStrip' => TRUE,
            'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE, formbuilder::TYPE_EDIT),
            //'required'        => TRUE,
            'type'            => 'boolean',
            'duplicates'      => TRUE,
            'options'         => array("YES","N0")
        )
    );

    $form->addField(
        array(
            'name'            => "priority",
            'label'           => "Is this iamge high priority?",
            'showInEditStrip' => TRUE,
            'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE, formbuilder::TYPE_EDIT),
            //'required'        => TRUE,
            'type'            => 'boolean',
            'duplicates'      => TRUE,
            'options'         => array("YES","NO")
        )
    );

    $form->addField(
        array(
            'name'            => "altText",
            'label'           => "Please provide meaningful alt text.",
            'showInEditStrip' => TRUE,
            'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE, formbuilder::TYPE_EDIT),
            'required'        => TRUE,
            'type'            => 'textarea',
        )
    );

    $form->addField(
        array(
            'name'            => "actionURL",
            'label'           => "Add a Link",
            'showInEditStrip' => TRUE,
            'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE, formbuilder::TYPE_EDIT),
            'required'        => TRUE,
            'type'            => 'URL',
        )
    );

    $form->addField(
            array(
                'name'   => "Date Ranges",
                'label'  => "Add Dates Image Will Display", 
                'type'   => "plaintext",
                'value'  => $dateValue,
                'showIn' => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE)
            )
        );

        $form->addField(
            array(
                'name'   => "Time Ranges",
                'label'  => "Add Times Image Will Display", 
                'type'   => "plaintext",
                'value'  => $timeValue,
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
                'showIn'  => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE),
                'value'   => ($editForm === TRUE ? $localvars->get('exsistingWeekdays') : NULL),
            )
        );
    
    
?> 