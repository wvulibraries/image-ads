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

// ========================================================================
if(isset($eAPI->cleanGet['MYSQL']['id'])){
    localvars::add("pageTitle", localvars::get("pageTitle")." - Edit Ip Group");
    $dbIpGroup = $eAPI->openDB->query(sprintf("SELECT ipGroups.*,ipRanges.ipRange FROM ipGroups LEFT JOIN ipRanges ON ipGroups.ID=ipRanges.groupID WHERE ipGroups.ID='%s'", $eAPI->cleanGet['MYSQL']['id']));
    if($dbIpGroup['result'] and $dbIpGroup['numrows']){
        $ipGroup = array();
        $ipRanges = array();
        while($row = mysql_fetch_assoc($dbIpGroup['result'])){
            $ipGroup['name'] = $row['name'];
            $ipGroup['groupID'] = $row['ID'];
            $ipRanges[] = $row['ipRange'];
        }
    }else{
        $errors[]="No IP Group defined for given ID";
        unset($eAPI->cleanGet['MYSQL']['id']);
        unset($eAPI->cleanGet['HTML']['id']);
    }
}else{
    localvars::add("pageTitle", localvars::get("pageTitle")." - Create Ip Group");
}
// ========================================================================

// Validate the input (for create and update)
if(isset($eAPI->cleanPost['MYSQL']['createIpGroup']) or isset($eAPI->cleanPost['MYSQL']['updateIpGroup'])){
    $errors = array();
    if(is_empty($eAPI->cleanPost['MYSQL']['name'])){
        errorHandle::errorMsg("You must supply a name for this IP Group!");
    }elseif(strip_tags($eAPI->cleanPost['MYSQL']['name']) != $eAPI->cleanPost['MYSQL']['name']){
        errorHandle::errorMsg("HTML is not allowed in the group name!");
    }
    foreach($eAPI->cleanPost['MYSQL']['ipRanges'] as $key => $ipRange){
        if(!validate::ipAddrRange($ipRange)){
            errorHandle::errorMsg("Invalid IP Range for range #".($key+1));
        }
    }

    if(!sizeof($eAPI->errorStack['error'])){
        // Create a new IP Group
        if(isset($eAPI->cleanPost['MYSQL']['createIpGroup'])){
            $dbIpGroups = $eAPI->openDB->query(sprintf("INSERT INTO ipGroups (name) VALUES('%s')", strip_tags($eAPI->cleanPost['MYSQL']['name'])));
            foreach($eAPI->cleanPost['MYSQL']['ipRanges'] as $ipRange){
                $dbIpRange = $eAPI->openDB->query(sprintf("INSERT INTO ipRanges (groupID,ipRange) VALUES('%s','%s')",
                    $eAPI->openDB->escape($dbIpGroups['id']),
                    $eAPI->openDB->escape(strip_tags($ipRange))));
            }

            sessionSet('adManager_success', true);
            sessionSet('adManager_successMsg', "IP group created successfuly");
            gotoListing();
        }

        // Update an existing IP Group
        if(isset($eAPI->cleanPost['MYSQL']['updateIpGroup'])){
            if($eAPI->cleanGet['MYSQL']['id'] == $eAPI->cleanPost['MYSQL']['groupID']){
                $eAPI->openDB->transBegin();
                // Did the group name change?
                if($ipGroup['name'] != $eAPI->cleanPost['MYSQL']['name']){
                    $dbIpGroups = $eAPI->openDB->query(sprintf("UPDATE ipGroups SET name='%s' WHERE ID='%s' LIMIT 1", $eAPI->cleanPost['MYSQL']['name'], $eAPI->cleanPost['MYSQL']['groupID']));
                    if($dbIpGroups['errorNumber']) $eAPI->openDB->transRollback();
                }
                // Refresh the group's IP Range(s)
                $dbIpRange = $eAPI->openDB->query(sprintf("DELETE FROM ipRanges WHERE groupID='%s'", $eAPI->cleanPost['MYSQL']['groupID']));
                foreach($eAPI->cleanPost['MYSQL']['ipRanges'] as $ipRange){
                    $dbIpRange = $eAPI->openDB->query(sprintf("INSERT INTO ipRanges (groupID,ipRange) VALUES('%s','%s')", $eAPI->cleanPost['MYSQL']['groupID'], $ipRange));
                    if($dbIpRange['errorNumber']) $eAPI->openDB->transRollback();
                }
                $eAPI->openDB->transCommit();
                $eAPI->openDB->transEnd();
                gotoListing();
            }else{
                die("Group ID conflict! (Possible Attack)");
            }
        }
    }
}


function fieldValue($name, $key=null){
    global $eAPI, $ipGroup;
    $result = '';
    if(isset($eAPI->cleanPost['MYSQL'][$name])){
        $result = $eAPI->cleanPost['MYSQL'][$name];
    }elseif(isset($ipGroup[$name])){
        $result = $ipGroup[$name];
    }
    return htmlentities($result);
}
function __ipRanges(){
    global $eAPI, $ipRanges;
    if(isset($eAPI->cleanPost['MYSQL']['ipRanges'])){
        return $eAPI->cleanPost['MYSQL']['ipRanges'];
    }elseif(isset($ipRanges)){
        return $ipRanges;
    }
    return array();
}
function gotoListing(){
    header("Location: ipGroups.php");
    exit();
}
?>
<script type="text/javascript">
    $(function(){
        $('#ipRanges').delegate('.deleteIpRange', 'click', function(){
            if(confirm("Are you sure you want to remove this IP Range?")){
                $(this).closest('li').remove();
                $('.ipRange:visible:last .ipRangeDeleteLink').show();
            }
        });
    });
    function addIpRange(){
        var ipRange = $('#ipRangeTmpl').clone();
        ipRange.removeClass('hidden').removeAttr('id');
        $('input', ipRange).removeAttr('disabled');
        $('.ipRangeNumber', ipRange).html( $('.ipRange:visible').length+1 );
        $('.ipRange:visible .ipRangeDeleteLink').hide();
        $('#ipRangeTmpl').before(ipRange);
    }
</script>
<div id="pageTitle">{local var="pageTitle"}</div>
<form action="" method="post">
    {engine name="insertCSRF"}
    <?php
    if(isset($eAPI->cleanGet['MYSQL']['id'])){
        echo sprintf('<input type="hidden" name="groupID" value="%s">', $eAPI->cleanGet['MYSQL']['id']);
    }
    echo errorHandle::prettyPrint('error');
    ?>
    <ul>
        <li>
            <label for="name" class="requiredField">Name:</label>
            <input name="name" id="name" class="requiredField" value="<?php echo fieldValue('name') ?>">
        </li>
        <li>
            <fieldset style="width: 250px;"><legend>IP Range(s)</legend>
                <ul id="ipRanges">
                    <?php
                    $ipRanges = __ipRanges();
                    if(sizeof($ipRanges)){
                        $i=0;
                        foreach($ipRanges as $ipRangeNumber => $ipRange){
                            $i++;
                            echo sprintf('
                            <li class="ipRange">
                                <span class="ipRangeNumber">%s</span>:
                                <input name="ipRanges[]" type="text" class="requiredField" value="%s">%s
                            </li>',
                                $ipRangeNumber+1,
                                $ipRange,
                                (($i > 1)
                                    ? ( ($i < sizeof($ipRanges))
                                        ? ' <span class="ipRangeDeleteLink hidden"> (<a href="javascript:;" class="deleteIpRange">Delete</a>)</span>'
                                        : ' <span class="ipRangeDeleteLink"> (<a href="javascript:;" class="deleteIpRange">Delete</a>)</span>' )
                                    : ''));
                        }
                    }else{
                        echo '<li class="ipRange"><span class="ipRangeNumber">1</span>: <input name="ipRanges[]" type="text"></li>';
                    }
                    ?>
                    <li id="ipRangeTmpl" class="hidden ipRange">
                        <span class="ipRangeNumber">1</span>:
                        <input name="ipRanges[]" type="text" class="requiredField" disabled="disabled">
                        <span class="ipRangeDeleteLink"> (<a href="javascript:;" class="deleteIpRange">Delete</a>)</span>
                    </li>
                    <li><a href="javascript:addIpRange();">Add another ip range</a></li>
                </ul>
            </fieldset>
        </li>
        <li>
            <?php
            if(isset($eAPI->cleanGet['MYSQL']['id'])){
                echo '<input type="submit" value="Update IP Group" name="updateIpGroup">';
                echo '<input type="button" value="Cancel" onclick="window.location=\'ipGroups.php\'">';
            }else{
                echo '<input type="submit" value="Create IP Group" name="createIpGroup">';
            }
            ?>
        </li>
    </ul>
</form>
<?php $eAPI->eTemplate("include","footer"); ?>