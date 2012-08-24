<?php
require_once("/home/library/phpincludes/engine/engineAPI/3.1/engine.php");
$engine = EngineAPI::singleton();


// Define the template
//$engine->eTemplate("load","library2012.1col");

//$engine->eTemplate("include","header");

function gotoAdManagerHome(){
    header("Location: index.php");
    exit();
}
