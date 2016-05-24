<?php
class Event
{
	public $_id;
	public $_userid;
	public $_name;
	public $_event;
	public $_gameid;
	public $_date;
	public $_quote;
	public $_tier;
	
	
	function __construct($id, $userid, $name, $event, $gameid, $date, $quote, $tier) {
		$this->_id = $id;
		$this->_userid = $userid;
		$this->_name = $name;
		$this->_event = $event;		
		$this->_gameid = $gameid;
		$this->_date = $date;
		$this->_quote = $quote;
		$this->_tier = $tier;
	}
		
}

?>
