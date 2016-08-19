<?php
class Game
{
	public $_id;
	public $_gbid;
	public $_title;
	public $_rated;
	public $_released;
	public $_genre;
	public $_platforms;
	public $_year;
	public $_image;
	public $_imagesmall;
	public $_highlight;
	public $_publisher;
	public $_developer;
	public $_alias;
	public $_theme;
	public $_franchise;
	public $_similar;
	public $_t1;
	public $_t2;
	public $_t3;
	public $_t4;
	public $_t5;
	
	function __construct($id, $gbid, $title, $rated, $released, $genre, $platforms, $year, $image, $imagesmall, $highlight, $publisher, $developer, $alias, $theme, $franchise, $similar, $t1=0, $t2=0, $t3=0, $t4=0, $t5=0) {
		$this->_id = $id;
		$this->_gbid = $gbid;
		$this->_title = $title;
		$this->_rated = $rated;
		$this->_released = $released;
		$this->_genre = $genre;
		$this->_platforms = $platforms;
		$this->_year = $year;
		$this->_image = $image;
		$this->_imagesmall = $imagesmall;
		$this->_highlight = $highlight;
		$this->_publisher = $publisher;
		$this->_developer = $developer;
		$this->_alias = $alias;
		$this->_theme = $theme;
		$this->_franchise = $franchise;
		$this->_similar = $similar;
		$this->_t1 = $t1;
		$this->_t2 = $t2;
		$this->_t3 = $t3;
		$this->_t4 = $t4;
		$this->_t5 = $t5;
	}
		
}

class GameVideo{
	public $_id;
	public $_gameid;
	public $_url;
	public $_source;
	public $_desc;
	public $_sourceid;
	public $_channel;
	public $_image;
	public $_views;
	public $_length;
	
	function __construct($id, $gameid, $source, $url, $desc, $sourceid, $channel, $image, $views, $length) {
		$this->_id = $id;
		$this->_gameid = $gameid;
		$this->_url = $url;
		$this->_source = $source;
		$this->_desc = $desc;
		$this->_sourceid = $sourceid;
		$this->_channel = $channel;
		$this->_image = $image;
		$this->_views = $views;
		$this->_length = $length;
	}
}


?>