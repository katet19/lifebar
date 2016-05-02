<?php
class Collection
{
	public $_id;
	public $_owner;
	public $_name;
	public $_desc;
	public $_numOfSubs;
	public $_created;
	public $_lastUpdate;
	public $_games;
	public $_visibility;
	public $_cover;
	public $_coversmall;
	
	function __construct($id, $owner, $name, $desc, $numOfSubs, $created, $lastUpdate, $games, $visibility, $cover, $coversmall){
		$this->_id = $id;
		$this->_owner = $owner;
		$this->_name = $name;
		$this->_desc = $desc;
		$this->_numOfSubs = $numOfSubs;
		$this->_created = $created;
		$this->_lastUpdate = $lastUpdate;
		$this->_games = $games;
		$this->_visibility = $visibility;
		$this->_cover = $cover;
		$this->_coversmall = $coversmall;
	}
}
?>