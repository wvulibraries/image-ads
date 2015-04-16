<?php
 
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

?>