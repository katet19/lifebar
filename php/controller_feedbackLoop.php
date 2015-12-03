<?php
require_once "includes.php";

function CalculateFeedbackForSave($gameid, $type){
	AddSimilarGames($_SESSION['logged-in']->_id, $gameid);
	//CalculateMilestones($_SESSION['logged-in']->_id, $gameid, '', $type);
	//$toasts[] = CalculateThisOrThat($_SESSION['logged-in']->_id, $gameid);
}

?>