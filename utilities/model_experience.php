<?php
class Experience
{
	public $_id;
	public $_first;
	public $_last;
	public $_username;
	public $_userid;
	public $_gameid;
	public $_game;
	public $_tier;
	public $_quote;
	public $_date;
	public $_link;
	public $_owned;
	public $_bucketlist;
	public $_playedxp;
	public $_watchedxp;
	
	
	function __construct($id, $first, $last, $username, $userid, $gameid, $game, $tier, $quote, $date, $link, $owned, $bucketlist) {
		$this->_id = $id;
		$this->_first = $first;
		$this->_last = $last;
		$this->_username = $username;
		$this->_userid= $userid;
		$this->_gameid = $gameid;
		$this->_game = $game;
		$this->_tier= $tier;
		$this->_quote= $quote;
		$this->_date = $date;
		$this->_link= $link;
		$this->_owned = $owned;
		$this->_bucketlist = $bucketlist;
		$this->_playedxp = array();
		$this->_watchedxp = array();
	}
		
}


class SubExperience
{
	public $_id;
	public $_expid;
	public $_userid;
	public $_gameid;
	public $_type;
	public $_source;
	public $_date;
	public $_url;
	public $_length;
	public $_thoughts;
	public $_archivequote;
	public $_archivetier;
	public $_entereddate;
	public $_completed;
	public $_mode;
	public $_platform;
	public $_alpha;
	public $_dlc;
	public $_beta;
	public $_earlyaccess;
	public $_demo;
	public $_streamed;
	public $_archived;
	
	
	
	
	function __construct($id, $expid, $userid, $gameid, $type, $source, $date, $url, $length, $thoughts, $archivequote, $archivetier, $entereddate, $completed, $mode, $platform, $dlc, $alpha, $beta, $earlyaccess, $demo, $streamed, $archived) {
		$this->_id = $id;
		$this->_expid = $expid;
		$this->_userid= $userid;
		$this->_gameid = $gameid;
		$this->_type = $type;
		$this->_source = $source;
		$this->_date = $date;
		$this->_url = $url;
		$this->_length = $length;
		$this->_thoughts= $thoughts;
		$this->_archivequote = $archivequote;
		$this->_archivetier = $archivetier;
		$this->_entereddate = $entereddate;
		$this->_completed = $completed;
		$this->_mode = $mode;
		$this->_platform = $platform;
		$this->_dlc = $dlc;
		$this->_alpha = $alpha;
		$this->_beta = $beta;
		$this->_earlyaccess = $earlyaccess;
		$this->_demo = $demo;
		$this->_streamed = $streamed;
		$this->_archived = $archived;
	}
		
}


?>