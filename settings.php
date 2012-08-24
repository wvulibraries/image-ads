<?php
require("engineIncludeStatement.php");
$eAPI = EngineAPI::singleton();

// Apply ACL
recurseInsert("acl.php","php");
$eAPI->accessControl("build");

// Connect to the database
$eAPI->dbConnect("database","adManager",TRUE);
localvars::dbImport("settings", "id", "value");

// Fire up the Template Enging
localvars::add("pageTitle", localvars::get("pageTitle")." - Settings");
$eAPI->eTemplate("include","header");
recurseInsert("headerNav.php","php");
 
$listObj = new listManagement('settings');
//$listObj->whereClause = (checkGroup('libraryDept_dlc_systems')) ? '' : " WHERE `systems`='0'";
$listObj->orderBy = "ORDER BY `ID` ASC";
$listObj->addField(array(
    'field'    => "ID",
    'label'    => "ID",
    'validate' => 'alphaNumericNoSpaces',
    'size' => "20"
));
$listObj->addField(array(
    'field' => "value",
    'label' => "Value",
    'dupes' => TRUE,
    'size' => "20"
));
$listObj->addField(array(
    'field' => "notes",
    'label' => "Notes",
    'dupes' => TRUE,
    'optional' => TRUE,
    'size' => "20"
));


if(isset($eAPI->cleanPost['RAW']['settings_submit'])){
    $errorMsg = $listObj->insert();
}elseif(isset($eAPI->cleanPost['RAW']['settings_update'])) {
    $errorMsg = $listObj->update();
    sessionSet('adManager_success', true);
    sessionSet('adManager_successMsg', "Settings saved!");
    gotoAdManagerHome();
}

?>
<div id="pageTitle">{local var="pageTitle"}</div>
<?php if(isset($eAPI->errorStack['error']) and sizeof($eAPI->errorStack['error'])){ ?>
<fieldset class="errorMessage"><legend>Error(s):</legend>
    <ul>
    <?php
    foreach($eAPI->errorStack['error'] as $error){
        echo sprintf('<li>%s</li>', $error);
    }
    ?>
    </ul>
</fieldset>
<?php } ?>
{listObject display="editTable"}
<hr>
{listObject display="insertForm"}
<?php $eAPI->eTemplate("include","footer"); ?>