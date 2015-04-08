 <?php 



    $localvars = localvars::getInstance();

    // DISPLAY PARAMETERS -- Date Range / Time Range / Weekday 
    // ==========================================================================================
    // Engine Setups for making dropdown menus 
    // Throws huge amounts of erros, but functions fine?  
    $date = new date;
    // Start Range 
    $localvars->set("monthSelect",$date->dropdownMonthSelect(1,TRUE,array("id"=>"start_month")));
    $localvars->set("daySelect",$date->dropdownDaySelect(TRUE,array("id"=>"start_day")));
    $localvars->set("yearSelect",$date->dropdownYearSelect(0,5,TRUE,array("id"=>"start_year")));

    $localvars->set("monthSelectEnd",$date->dropdownMonthSelect(1,TRUE,array("id" => "end_month")));
    $localvars->set("daySelectEnd",$date->dropdownDaySelect(TRUE,array("id"=>"end_day")));
    $localvars->set("yearSelectEnd",$date->dropdownYearSelect(0,5,TRUE,array("id"=>"end_year")));

    $localvars->set("startTime",$date->timeDropDown(array("formname" => "start_Time")));
    $localvars->set("endTime",$date->timeDropDown(array("formname" => "end_Time")));

    // Functions required to make the image and display options happen 
    function displayOptionsFields() {
        $htmlInputs = 
            " <div class='displayOptions'>  
                <strong> Add New: </strong> 
                <a href='javascript:void(0)' class='date-range'> Date Range </a>  |
                <a href='javascript:void(0)' class='weekday'> Week Day </a>     | 
                <a href='javascript:void(0)' class='time-range'> Time Range </a>   
              </div> 
              <div class='displayOptionInputs'> 

              </div> 
            "; 

        return $htmlInputs;   
    }


    $form = formBuilder::createForm('imageAdForm');

    $form->linkToDatabase(array(
        'table' => "imageAds"
    ));

    $form->insertTitle = "New Roating Image";
    $form->editTitle   = "Edit Rotating Image";


    // Callback Logic for handling the image upload 
    if(!is_empty($_POST) || session::has('POST')) { 
        // callback file 
        //=========================================
        recurseInsert('includes/callbacks.php'); 
        
        // Run the Processor 
        // ========================================
        $processor = formBuilder::createProcessor(); 
        $processor->processPost(); 
    }



    $form->addField(
        array(
            'name'            => "imageAd",
            'fieldID'         => "imageAd",
            'label'           => "File Upload",
            'showInEditStrip' => TRUE,
            'showIn'          => array(formBuilder::TYPE_INSERT),
            //'required'        => TRUE,
            'type'            => 'file'
        )
    );

    $form->addField(
        array(
            'name'            => "ID",
            'label'           => "Table ID",
            'primary'         => TRUE,
            'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE),
            'type'            => 'hidden'
        )
    );

    $form->addField(
        array(
            'name'            => "name",
            'label'           => "Image Name",
            'showInEditStrip' => TRUE,
            'required'        => TRUE,
            'type'            => 'text',
            'duplicates'      => FALSE, 
            'fieldID'         => "imgName"
        )
    );

    $form->addField(
        array(
            'name'            => "enabled",
            'label'           => "Is this image being displayed now?",
            'showInEditStrip' => TRUE,
            'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE),
            'required'        => TRUE,
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
            'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE),
            'required'        => TRUE,
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
            'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE),
            'required'        => TRUE,
            'type'            => 'textarea',
        )
    );

    $form->addField(
        array(
            'name'            => "actionURL",
            'label'           => "Add a Link",
            'showInEditStrip' => TRUE,
            'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE),
            'required'        => TRUE,
            'type'            => 'URL',
        )
    );
    
    // Adding Other Fields that will show up conditionally 
    $form->addField( 
        array(
            'name'  => "displayOptions",
            'label' => "Edit Display Options", 
            'type'  => "plaintext",
            'value' => displayOptionsFields()
        )
    );
    
?> 