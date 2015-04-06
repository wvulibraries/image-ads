 <?php 
    $form = formBuilder::createForm('imageAdForm');

    $form->linkToDatabase(array(
        'table' => "imageAds"
    ));

    $form->insertTitle = "New Roating Image";
    $form->editTitle   = "Edit Rotating Image";


    // Callback Logic for handling the image upload 
    if(!is_empty($_POST) || session::has('POST')) { 
        
        // Code for the Processor 
        // =======================================
        
        function insertImage($processor,$data,$fieldName) {
            $fieldName = "imageAd";
            
             // Testing the Files Logic 
              if($_FILES[$filename]["name"]) { 
                print "FILES FOUND";   
              } else {
                print "No Files Found";   
              }

        }
        
        // Run the Processor 
        // ========================================
        $processor = formBuilder::createProcessor(); 
        $processor->setCallback('processor', 'picInsertBefore');
        $processor->processPost(); 
    }
    


    $form->addField(
        array(
            'name'            => "imageAd",
            'fieldID'         => "imgFile",
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
            'name'            => "nameOfImage",
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
    

?> 