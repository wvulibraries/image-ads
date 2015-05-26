 <?php

    $localvars = localvars::getInstance();
    $form      = formBuilder::createForm('imageAdForm');

    $form->linkToDatabase(array(
        'table' => "imageAds"
    ));

    $form->insertTitle      = "New Roating Image";
    $form->editTitle        = "Edit Rotating Image";
    $form->updateTitle      = "Update Form";
    $form->submitTextUpdate = 'Update Image';
    $form->deleteTextUpdate = 'Delete Image';
    $form->submitTextEdit   = 'Update';

    recurseInsert("includes/forms/callbacks.php", "php");
    recurseInsert("includes/addDateTimeFunctions.php","php");

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


    $dateValue = "<a href='javascript:void(0);' class='addDateRange'> Add Date </a> | <a href='javascript:void(0);' class='deleteDateRange'> Remove Last Date </a>";
    $timeValue = "<a href='javascript:void(0);' class='addTimeRange'> Add Time </a> | <a href='javascript:void(0);' class='deleteTimeRange'> Remove Last Time Range </a>";

    if(!is_empty($_GET) && validate::getInstance()->integer($_GET['MYSQL']['imageID'])) {
        $imageID   = $_GET['MYSQL']['imageID'];
        $editForm = TRUE;
        recurseInsert("includes/forms/editForm.php", "php");
        $dateValue .= $localvars->get('exsistingDateRanges');
        $timeValue .= $localvars->get('exsistingTimeRanges');
        $editingImage = sprintf("<img src='%s'/>",
            $localvars->get('editingImage')
        );
    }
    else {
        $editForm = FALSE;
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

    if($editForm === TRUE){
        $form->addField(
            array(
                'name'            => "imagePlaceholder",
                'fieldID'         => "imagePlaceholder",
                'label'           => "Image Your Editing",
                'showIn'          => array(formBuilder::TYPE_UPDATE),
                'type'            => 'plaintext',
                'value'           => $editingImage
            )
        );
    }

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
            'name'            => "imageType",
            'label'           => "Image Type",
            'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE, formbuilder::TYPE_EDIT),
            'type'            => 'hidden'
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
            'label'           => "Is this image high priority?",
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
                'value'   => ($editForm === TRUE ? $localvars->get('exsistingWeekdays') : array()),
            )
        );

        $form->addField(
            array(
                'name'            => ($editForm === TRUE ? 'update' : 'insert'),
                'value'           => ($editForm === TRUE ? 'Update Image' : 'Insert Image'),
                'fieldID'         => 'UpdateButton',
                'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE, formbuilder::TYPE_EDIT),
                'type'            => 'submit',
            )
        );

        $form->addField(
            array(
                'name'            => 'delete',
                'value'           => 'Delete Image',
                'fieldID'         => 'DeleteButton',
                'showIn'          => array(formBuilder::TYPE_UPDATE, formbuilder::TYPE_EDIT),
                'type'            => 'delete',
            )
        );


?>