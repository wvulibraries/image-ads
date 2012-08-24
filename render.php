<?php require "load.php"; ?>
<script src='{engine var="jquery"}' type="text/javascript"></script>
{local var="adManager_cssTag"}
{local var="adManager_jsTag"}
<script>
    $(function(){ {local var="adManager_js"} });
</script>

<section id="{local var="adManager_sliderID"}" class="adFlipper">
{local var="adManager_html"}
</section>

<hr>
CSS File: {local var="adManager_cssFile"}<br>
JS File: {local var="adManager_jsFile"}<br>
