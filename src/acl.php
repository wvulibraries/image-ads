<?php
	$engine = EngineAPI::singleton();
	accessControl::accessControl("ADgroup","libraryWeb_adManager",TRUE);
	accessControl::accessControl("denyAll");
	accessControl::build();
?>