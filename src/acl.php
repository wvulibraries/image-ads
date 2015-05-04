<?php
	$engine = EngineAPI::singleton();
	
	// What does the AccessControl info do? 	
	accessControl::accessControl("ADgroup","libraryWeb_roomReservation",TRUE,FALSE);
	accessControl::accessControl("ADgroup","libraryWeb_roomReservation_rooms",TRUE,FALSE);
	accessControl::accessControl("ADgroup","libraryWeb_roomReservation_admin",TRUE,FALSE);
	accessControl::accessControl("denyAll",null,null); // Should this be NULL instead of null
	
	accessControl::build();
?>