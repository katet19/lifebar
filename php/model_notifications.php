<?php
class Notification
{
	public $_id;
	public $_userid;
	public $_coreid;
	public $_category;
	public $_type;
	public $_title;
	public $_desc;
	public $_date;
	public $_valone;
	public $_valtwo;
	public $_valthree;
	public $_actionone;
	public $_actiontwo;
	public $_actionthree;
	public $_icon;
	public $_color;
	public $_link;
	
	
	function __construct($id, $userid, $coreid, $category, $type, $title, $desc, $date, $valone, $actionone, $valtwo, $actiontwo, $valthree, $actionthree, $icon, $color, $link) {
		$this->_id = $id;
		$this->_userid = $userid;
		$this->_coreid = $coreid;
		$this->_category = $category;
		$this->_type = $type;
		$this->_title = $title;		
		$this->_desc = $desc;
		$this->_date = $date;
		$this->_valone = $valone;
		$this->_actionone = $actionone;
		$this->_valtwo = $valtwo;
		$this->_actiontwo = $actiontwo;
		$this->_valthree = $valthree;
		$this->_actionthree = $actionthree;
		$this->_icon = $icon;
		$this->_color = $color;
		$this->_link = $link;
	}
		
}

?>