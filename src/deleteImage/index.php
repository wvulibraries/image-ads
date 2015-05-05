<?php
    require_once "../engineHeader.php";
    templates::display('header'); 

    $localvars = localvars::getInstance();

    recurseInsert("../includes/deleteFunction.php", "php");
    deleteRecord(); 
?>

{local var="resultMessage"}

