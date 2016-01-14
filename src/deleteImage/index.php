<?php
    require_once "../engineHeader.php";
    templates::display('header'); 

    $localvars = localvars::getInstance();

    $tempID    =   $_GET['MYSQL']['imageID'];  
    if(validate::getInstance()->integer($tempID)) {
        $imageID = $tempID; 
    } else { 
        $imageID = NULL; 
    }

    recurseInsert("../includes/deleteFunction.php", "php");
    
    $deleteURL = sprintf("%s/deleteImage/?%s&imageID=%s",
        $localvars->get('baseDirectory'),
        "delete=TRUE",
        $imageID);  

    $cancelURL = sprintf("%s/deleteImage/?%s&imageID=%s",
        $localvars->get('baseDirectory'),
        "delete=FALSE",
        $imageID);  

    $localvars->set('deletesURL', $deleteURL); 
    $localvars->set('cancelURL', $cancelURL); 

    $delete = (isset($_GET['MYSQL']['delete']) ? $_GET['MYSQL']['delete'] : NULL); 

    if(isnull($delete)) { 
        $deleteHTML = ' <div class="deleteMessage"> 
                            <h2> Delete Image? </h2>
                            <p> Are you sure you would like to Delete this image? <br/> <br/>  
                                <a href="{local var="deletesURL"}" class="confirm-delete button"> Delete </a>   
                                <a href="{local var="cancelURL"}" class="cancel button"> Cancel </a> 
                            </p>
                        </div> 
                      '; 
    } 
    elseif ($delete === "TRUE") {
        deleteRecord($imageID); 
        $deleteHTML = ' <div class="deleteMessage"> 
                        <h2> Deleting Image </h2> 
                            <p> {local var="resultMessage"} </p> 
                        </div> 
                      '; 
    } 
    else {
         header("Location:" . $localvars->get('baseDirectory'));
    } // This should handle false and any bad intentions

    $localvars->set('deleteHTML', $deleteHTML);  
?>

{local var="deleteHTML"}