<?php

require_once "includes/engineHeader.php";

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


