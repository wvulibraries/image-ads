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
$eAPI->eTemplate("include","header");

?>
<div id="pageTitle">{local var="pageTitle"}</div>

<?php
if(sessionGet('adManager_success')){
    echo sprintf('<div class="successMessage" style="margin-bottom: 10px;">%s</div>', sessionGet('adManager_successMsg'));
    sessionDelete('adManager_success');
    sessionDelete('adManager_successMsg');
}

if(isset($eAPI->cleanPost['RAW']['deleteAdID'])){
    $eAPI->openDB->transBegin();
    $dbAdDelete1 = $eAPI->openDB->query(sprintf("DELETE FROM ads WHERE ID='%s' LIMIT 1", $eAPI->cleanPost['MYSQL']['deleteAdID']));
    $dbAdDelete2 = $eAPI->openDB->query(sprintf("DELETE FROM releaseConditions WHERE ad_id='%s'", $eAPI->cleanPost['MYSQL']['deleteAdID']));
    if($dbAdDelete1['errorNumber'] or $dbAdDelete2['errorNumber']){
        if($dbAdDelete1['errorNumber']) echo "Error: ".$dbAdDelete1['error']."<br>";
        if($dbAdDelete2['errorNumber']) echo "Error: ".$dbAdDelete2['error']."<br>";
        $eAPI->openDB->transRollback();
        $eAPI->openDB->transEnd();
    }else{
        $eAPI->openDB->transCommit();
        $eAPI->openDB->transEnd();
        sessionSet('adManager_success', true);
        sessionSet('adManager_successMsg', "Advertisement deleted successfuly");
    }
}

// Get all the ads
$dbAds = $eAPI->openDB->query("SELECT * FROM `ads` ORDER BY `name` ASC");
if($dbAds['result']) while($ad = mysql_fetch_assoc($dbAds['result'])){
    $conditions = array();
    $adConditions = $eAPI->openDB->query(sprintf("SELECT * FROM `releaseConditions` WHERE `ad_id`='%s' ORDER BY `type` ASC", $ad['ID']));
    while($condition = mysql_fetch_assoc($adConditions['result'])){
        $conditions[ $condition['type'] ][] = $condition;
    }
?>
<script type="text/javascript">
    function deleteAd(id){
        if(confirm("Are you sure you want to delete this Advertisement?\n\n(This cannot be undone)")){
            var formAdID = $('#deleteAdID');
            formAdID.val(id);
            formAdID.closest('form').submit();
        }
    }
</script>
<form action="" method="post">
    {engine name="insertCSRF"}
    <input type="hidden" name="deleteAdID" id="deleteAdID">
</form>
<?php
if(sessionGet('adManager_success')){
    echo sprintf('<div class="successMessage" style="margin-bottom: 10px;">%s</div>', sessionGet('adManager_successMsg'));
    sessionDelete('adManager_success');
    sessionDelete('adManager_successMsg');
}
?>
<div class="imgBlock">
    <img class="image" src="{local var="baseURL"}img.php?id=<?php echo $ad['ID'] ?>" alt="<?php echo htmlentities($ad['imgAltText']) ?>">
    <div class="imgDetails">
        <div class="imgName"><?php echo htmlentities($ad['name']) ?></div>
        <ul>
            <li><span class="imgDetail">Available:</span> <?php if($ad['enabled']){ echo '<span class="availableYes">Yes</span>'; }else{ echo '<span class="availableNo">No</span>'; } ?></li>
            <li>
                <span class="imgDetail">Conditions:</span> <?php if(sizeof($conditions)){ echo sprintf('%s [<a href="javascript:showHideConditions(\''.$ad['ID'].'\');">Show/Hide</a>]', sizeof($conditions)); }else{ echo 'None'; } ?>
                <ul id="adConditions-<?php echo htmlentities($ad['ID']) ?>" class="conditions" style="display: none;">
                    <?php
                    foreach($conditions as $conditionType => $conditionGroup){
                        echo '<li class="condition">';
                        switch($conditionType){
                            case 'dateRange':
                                echo '<b>Date Range:</b><br>';
                                foreach($conditionGroup as $condition){
                                    list($from, $to) = explode('-', $condition['value']);
                                    echo date('M j Y',$from).'-'.date('M j Y',$to)."<br>";
                                }
                                break;

                            case 'timeRange':
                                echo '<b>Time Range:</b><br>';
                                foreach($conditionGroup as $condition){
                                    list($from, $to) = explode('-', $condition['value']);
                                    $from += ((int)date('I')*60);
                                    $to += ((int)date('I')*60);
                                    echo (floor($from/60).':'.str_pad($from%60, 2, '0', STR_PAD_LEFT)).'-'.(floor($to/60).':'.str_pad($to%60, 2, '0', STR_PAD_LEFT))."<br>";
                                }
                                break;

                            case 'weekDay':
                                echo '<b>Week Days:</b><br>';
                                foreach($conditionGroup as $condition){
                                    echo $condition['value']."<br>";
                                }
                                break;

                            case 'ipGroup':
                                echo '<b>IP Profile:</b><br>';
                                $ids = array();
                                foreach($conditionGroup as $condition) $ids[] = $condition['value'];
                                $adIpGroups = $eAPI->openDB->query(sprintf("SELECT `name` FROM `ipGroups` WHERE `ID` IN (%s) ORDER BY `name` ASC", implode(',', $ids)));
                                while($adIpGroup = mysql_fetch_assoc($adIpGroups['result'])){
                                    echo $adIpGroup['name']."<br>";
                                }
                                break;
                        }
                        echo '</li>';
                    }
                    ?>
                </ul>
            </li>
            <li><span class="imgDetail">Alt Text:</span> <?php echo htmlentities($ad['imgAltText']) ?></li>
            <li>
                <span class="imgDetail">Action:</span>
                <?php
                switch($ad['imgActionType']){
                    case 'link':
                        echo sprintf('Link to \'<a href="%s">%s</a>\'', htmlentities($ad['imgActionValue']), htmlentities($ad['imgActionValue']));
                        break;

                    case 'email':
                        echo sprintf('Send email to \'<a href="mailto:%s">%s</a>\'', htmlentities($ad['imgActionValue']), htmlentities($ad['imgActionValue']));
                        break;

                    case 'javascript':
                        echo sprintf('Javascript callback: [<a href="javascript:alert(\'%s\');">View source</a>]', htmlentities($ad['imgActionValue']));
                        break;
                }
                ?>
            </li>
            <li><a href="{local var="baseURL"}ad.php?id=<?php echo htmlentities($ad['ID']) ?>">Edit this ad</a> | <a href="javascript:deleteAd('<?php echo htmlentities($ad['ID']) ?>');">Delete this ad</a></li>
        </ul>
    </div>
</div>
<?php
}
$eAPI->eTemplate("include","footer");
?>