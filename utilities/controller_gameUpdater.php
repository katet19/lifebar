<?php
require_once 'controller_database.php';
require_once 'controller_game.php';
require_once 'controller_giantbomb.php';

	echo "Check for new games/update existing games that are releasing soon<hr>";
	
	PrepareForNewReleases(date("Y-m-d"), date("Y-m-d", strtotime("+14 days")));
	
	echo "<BR>Finished<BR>";

	echo "Create Event for New Game Releases<HR>";
	
	CreateNewReleaseEvent(date("Y-m-d"));
	
	echo "<BR>Finished<BR>";
	
	echo "Clear Old Search Cache<HR>";
	
	ClearOldSearchCache();
	
	echo "<BR>Finished<BR>";
	
	/*echo "Finding Games to Update<HR>";
	
	FindGamesToBackfill();
	
	echo "<BR>Finished<BR>";*/
	

	

?>