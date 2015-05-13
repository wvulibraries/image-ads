<?php
    $localvars = localvars::getInstance();

    $localvars->set('dbConnectionName', 'appDB');
    $localvars->set('pageTitle', 'Image Ad Manager');
    $localvars->set("baseDirectory","/admin/image_manager");
    $localvars->set("URLpath","http://$_SERVER[HTTP_HOST]/admin/image_manager");
?>