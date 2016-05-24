<?php
class Collection
{
	public $_id;
	public $_owner;
	public $_name;
	public $_desc;
	public $_numOfSubs;
	public $_created;
	public $_createdby;
	public $_lastUpdate;
	public $_games;
	public $_visibility;
	public $_cover;
	public $_coversmall;
	public $_rule;
	public $_ruledesc;
	
	function __construct($id, $owner, $name, $desc, $numOfSubs, $created, $createdby, $lastUpdate, $games, $visibility, $cover, $coversmall, $rule, $ruledesc){
		$this->_id = $id;
		$this->_owner = $owner;
		$this->_name = $name;
		$this->_desc = $desc;
		$this->_numOfSubs = $numOfSubs;
		$this->_created = $created;
		$this->_createdby = $createdby;
		$this->_lastUpdate = $lastUpdate;
		$this->_games = $games;
		$this->_visibility = $visibility;
		$this->_cover = $cover;
		$this->_coversmall = $coversmall;
		$this->_rule = $rule;
		$this->_ruledesc = $ruledesc;
	}
}
?>
