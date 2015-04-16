 <?php 

  // callback functions 
  //=========================================
    function testfunc(){
       if(!isset($_FILES['imageAd'])) { 
            echo "No Files"; 
        } else { 
            $imageInfo = $_FILES['imageAd']; 

            print "<pre>"; 
            var_dump($imageInfo);
            print "</pre>"; 

            // Upload the File 
            imageUpload($imageInfo); 

        }
    }

    function imageUpload($filedata){ 
            // $filedata['name'];
            // $filedata['type']; 
            // $filedata['tmp_name']; 
            // $filedata['size']; 
            // $filedata['error'];

            // Test The Image Stuff 
            $maxFileSize = 1000000; // 1mb  

            if($filedata['size'] < $maxFileSize) { 
                echo "Image Works!"; 
            } else {
                echo "David screwed up";
            }
    }


// Callback Logic for handling the image upload 
    if(!is_empty($_POST) || session::has('POST')) { 
        // Run the Processor 
        // ========================================
        $processor = formBuilder::createProcessor(); 
        // Set the Callback functions to fire from the callbacks.php file
        // =========================================
        // Parameter Types ($trigger, $callback) 
        $processor->setCallback('beforeInsert', 'testFunc');
        $processor->processPost(); 
    }

    $localvars = localvars::getInstance();
    $form = formBuilder::createForm('imageAdForm');

    $form->linkToDatabase(array(
        'table' => "imageAds"
    ));

    $form->insertTitle = "New Roating Image";
    $form->editTitle   = "Edit Rotating Image";


    



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
            'duplicates'      => TRUE, 
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