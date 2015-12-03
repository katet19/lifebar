<?php
require_once "importfiles.php";
	
	echo "Finding Games to Update<BR><BR>";
	
	FindGamesToBeUpdated();
	
	echo "<BR>Finished";
	
	echo "Create Event for New Game Releases<BR><BR>";
	
	CreateNewReleaseEvent(date("Y-m-d"));
	
	echo "<BR>Finished";
?>