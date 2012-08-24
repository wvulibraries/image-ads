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
localvars::add("pageTitle", localvars::get("pageTitle")." - Ip Groups");
$eAPI->eTemplate("include","header");

if(isset($eAPI->cleanPost['RAW']['deleteGroupID'])){
    $eAPI->openDB->transBegin();
    $dbIpGroupDelete = $eAPI->openDB->query(sprintf("DELETE FROM ipGroups WHERE ID='%s' LIMIT 1", $eAPI->cleanPost['MYSQL']['deleteGroupID']));
    $dbIpRangeDelete = $eAPI->openDB->query(sprintf("DELETE FROM ipRanges WHERE groupID='%s'", $eAPI->cleanPost['MYSQL']['deleteGroupID']));
    if($dbIpGroupDelete['errorNumber'] or $dbIpRangeDelete['errorNumber']){
        if($dbIpGroupDelete['errorNumber']) echo "Error: ".$dbIpGroupDelete['error']."<br>";
        if($dbIpRangeDelete['errorNumber']) echo "Error: ".$dbIpRangeDelete['error']."<br>";
        $eAPI->openDB->transRollback();
        $eAPI->openDB->transEnd();
    }else{
        $eAPI->openDB->transCommit();
        $eAPI->openDB->transEnd();
        sessionSet('adManager_success', true);
        sessionSet('adManager_successMsg', "IP Group deleted successfuly");
    }
}
?>
<script type="text/javascript">
    function deleteIpGroup(id){
        if(confirm("Are you sure you want to delete this IP Group?\n\n(This cannot be undone)")){
            var formGroupID = $('#deleteGroupID');
            formGroupID.val(id);
            formGroupID.closest('form').submit();
        }
    }
</script>
<div id="pageTitle">{local var="pageTitle"}</div>
<?php
if(sessionGet('adManager_success')){
    echo sprintf('<div class="successMessage" style="margin-bottom: 10px;">%s</div>', sessionGet('adManager_successMsg'));
    sessionDelete('adManager_success');
    sessionDelete('adManager_successMsg');
}
?>
<form action="" method="post">
    {engine name="insertCSRF"}
    <input type="hidden" name="deleteGroupID" id="deleteGroupID">
</form>
<a href="ipGroup.php">Create new IP Group</a>
<hr>
<ul id="ipGroups">
    <?php
    $dbIpGroups = $eAPI->openDB->query("SELECT * FROM ipGroups ORDER BY name ASC");
    if($dbIpGroups['result']){
        if(mysql_num_rows($dbIpGroups['result'])){
            while($ipGroup = mysql_fetch_assoc($dbIpGroups['result'])){
                $dbIpRanges = $eAPI->openDB->query(sprintf("SELECT * FROM ipRanges WHERE `groupID` = '%s' ORDER BY ipRange ASC", mysql_escape_string($ipGroup['ID'])));
                echo sprintf('<li>');
                echo sprintf('<strong>%s</strong> - <a href="ipGroup.php?id=%s">Edit</a> | <a href="javascript:deleteIpGroup(\'%s\');">Delete</a>', htmlentities($ipGroup['name']), htmlentities($ipGroup['ID']), htmlentities($ipGroup['ID']));
                echo sprintf('<ul class="ipRanges">');
                while($ipRange = mysql_fetch_assoc($dbIpRanges['result'])){
                    echo sprintf('<li>%s</li>', htmlentities($ipRange['ipRange']));
                }
                echo sprintf('</ul>');
                echo sprintf('</li>');
            }
        }else{
            echo '<i>No ip groups have been defined yet</i>';
        }
    }else{
        echo 'SQL Error!<br>'.$dbIpGroups['error'];
    }
    ?>
</ul>
<?php $eAPI->eTemplate("include","footer"); ?>