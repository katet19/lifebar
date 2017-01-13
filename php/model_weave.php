<?php
class Weave
{
	public $_id;
	public $_userID;
	public $_totalXP;
	public $_recentPW;
	public $_recentXP;
	public $_overallTierTotal;
	public $_percentagePlayed;
	public $_percentageWatched;
	public $_percentageBoth;
	public $_totalAgrees;
	public $_totalFollowers;
	public $_preferredXP;
	public $_subpreferredXP1;
	public $_subpreferredXP2;
	public $_lifebarXP;
	
	
	function __construct($id, $userID, $totalXP, $recentPW, $recentXP, $overallTierTotal, $percentagePlayed, $percentageWatched, $totalAgrees, $totalFollowers, $percentageBoth, $preferredXP, $subpreferredXP1, $subpreferredXP2, $lifebarXP) {
		$this->_id = $id;
		$this->_userID = $userID;
		$this->_totalXP = $totalXP;
		$this->_recentPW = $recentPW;
		$this->_recentXP= $recentXP;
		$this->_overallTierTotal = $overallTierTotal;
		$this->_percentagePlayed = $percentagePlayed;
		$this->_percentageWatched = $percentageWatched;
		$this->_percentageBoth = $percentageBoth;
		$this->_totalAgrees = $totalAgrees;
		$this->_totalFollowers = $totalFollowers;
		$this->_preferredXP = $preferredXP;
		$this->_subpreferredXP1 = $subpreferredXP1;
		$this->_subpreferredXP2 = $subpreferredXP2;
		$this->_lifebarXP = $lifebarXP;
	}
	
}
?>