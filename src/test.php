<?php

require_once "includes/engineHeader.php";
templates::display('header'); 

$foo = array();

$foo['this']      = "that";
$foo['foo']       = "bar";
$foo['something'] = "";
$foo['crud']      = 0;

print "foo <pre>";
var_dump($foo);
print "</pre>";

foreach ($foo as $I=>$V) {

	if (isnull($V)) {
		print $I. " -- I'm Null<br />";
	}

	if (is_empty($V)) {
		print $I. " == I'm Empty! (ENGINE!)<br />";
	}

	if (empty($V)) {
		print $I. " == I'm Empty!<br />";
	}

	if ($V == NULL) {
		print $V . "I begin with a t.<br />";
	}

}


$mininterval = 1; 

for($I=0;$I<60;$I++) {
	if($I % $mininterval == 0) {
		$minArray[$I] = (($I<10)?"0".$I:$I);
	}
}


?> 