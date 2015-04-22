 <?php 

  // callback functions 
  //=========================================
    function processImg(){
       // Check if there is a legit file in there  
       if(isset($_FILES['imageAd'])) { 
           // Return the Image Info; 
           $imageInfo = $_FILES['imageAd']; 
           $imageData = imageUpload($imageInfo); 
        } else { 
            echo "Fail!"; 
        }

        $imgInfo = $_POST['MYSQL'];
        $imgInfo['imageAd'] = $imageData; // set the image to go back with the post 

        return $imgInfo;
    }

    function imageUpload($filedata){ 
       // Test The Image Stuff 
        $maxFileSize = 1000000; // 1mb  
        $fileTypesAllowed = array("image/gif", "image/png", "image/jpeg", "image/jpg");  
        
        $theImageData = base64_encode(file_get_contents($filedata['tmp_name']));
        $theImageMimeType = $filedata['type']; 
        $theImageDataURI = "data:" . $theImageMimeType . ";" . 'base64,' . $theImageData; 


        // Test to see if the image isn't too big & is an image 
        if($filedata['size'] < $maxFileSize && in_array($filedata['type'], $fileTypesAllowed)) { 
            return $theImageDataURI;
        } else {
            echo "Error!"; 
            return false; 
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
        $processor->setCallback('beforeInsert', 'processImg');
        $processor->processPost(); 
    }

    $localvars = localvars::getInstance();
    $form = formBuilder::createForm('imageAdForm');

    $form->linkToDatabase(array(
        'table' => "imageAds"
    ));

    $form->insertTitle = "New Roating Image";
    $form->editTitle   = "Edit Rotating Image";
    $form->updateTitle = "Update Form";
    $form->submitTextUpdate = 'Update';
    $form->submitTextEdit   = 'Update';

    $form->addField(
        array(
            'name'            => "imageAd",
            'fieldID'         => "imageAd",
            'label'           => "File Upload",
            'showInEditStrip' => TRUE,
            'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE, formbuilder::TYPE_EDIT),
            //'required'        => TRUE,
            'type'            => 'file'
        )
    );

    $form->addField(
        array(
            'name'            => "ID",
            'label'           => "Table ID",
            'primary'         => TRUE,
            'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE, formbuilder::TYPE_EDIT),
            'type'            => 'hidden'
        )
    );

    $form->addField(
        array(
            'name'            => "name",
            'label'           => "Image Name",
            'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE, formbuilder::TYPE_EDIT),
            //'required'        => TRUE,
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
            //'required'        => TRUE,
            'type'            => 'textarea',
        )
    );

    $form->addField(
        array(
            'name'            => "actionURL",
            'label'           => "Add a Link",
            'showInEditStrip' => TRUE,
            'showIn'          => array(formBuilder::TYPE_INSERT, formBuilder::TYPE_UPDATE, formbuilder::TYPE_EDIT),
            //'required'        => TRUE,
            'type'            => 'URL',
        )
    );
    
    
?> 