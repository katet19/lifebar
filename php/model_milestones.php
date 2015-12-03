<?php
class Milestone
{
	public $_id;
	public $_name;
	public $_description;
	public $_type;
	public $_image;
	public $_rarity;
	public $_difficulty;
	public $_validation;
	public $_level1;
	public $_level2;
	public $_level3;
	public $_level4;
	public $_level5;
	public $_enabled;
	public $_progress;
	public $_parent;
	public $_category;
	public $_objectid;
	
	function __construct($id, $name, $description, $type, $image, $difficulty, $validation, $level1, $level2, $level3, $level4, $level5, $enabled, $parent, $category, $progress, $objectid) {
		$this->_id = $id;
		$this->_name = $name;
		$this->_description = $description;
		$this->_type = $type;
		$this->_image = $image;
		$this->_rarity = $rarity;
		$this->_difficulty = $difficulty;
		$this->_validation = $validation;
		$this->_level1 = $level1;
		$this->_level2 = $level2;
		$this->_level3 = $level3;
		$this->_level4 = $level4;
		$this->_level5 = $level5;
		$this->_enabled = $enabled;
		$this->_parent = $parent;
		$this->_category = $category;
		$this->_progress = $progress;
		$this->_objectid = $objectid;
	}
		
}

class MilestoneProgress
{
	public $_id;
	public $_milestoneid;
	public $_userid;
	public $_progresslevel1;
	public $_progresslevel2;
	public $_progresslevel3;
	public $_progresslevel4;
	public $_progresslevel5;
	public $_percentlevel1;
	public $_percentlevel2;
	public $_percentlevel3;
	public $_percentlevel4;
	public $_percentlevel5;
	public $_start;
	public $_finishlevel1;
	public $_finishlevel2;
	public $_finishlevel3;
	public $_finishlevel4;
	public $_finishlevel5;

	function __construct($id, $milestoneid, $userid, $progresslevel1, $progresslevel2, $progresslevel3, $progresslevel4, $progresslevel5, 
						$percentlevel1, $percentlevel2, $percentlevel3, $percentlevel4, $percentlevel5, $start,
						$finishlevel1, $finishlevel2, $finishlevel3, $finishlevel4, $finishlevel5) {
		$this->_id = $id;
		$this->_milestoneid = $milestoneid;
		$this->_userid = $userid;
		$this->_progresslevel1 = $progresslevel1;
		$this->_progresslevel2 = $progresslevel2;
		$this->_progresslevel3 = $progresslevel3;
		$this->_progresslevel4 = $progresslevel4;
		$this->_progresslevel5 = $progresslevel5;
		$this->_percentlevel1 = $percentlevel1;
		$this->_percentlevel2 = $percentlevel2;
		$this->_percentlevel3 = $percentlevel3;
		$this->_percentlevel4 = $percentlevel4;
		$this->_percentlevel5 = $percentlevel5;
		$this->_start = $start;
		$this->_finishlevel1 = $finishlevel1;
		$this->_finishlevel2 = $finishlevel2;
		$this->_finishlevel3 = $finishlevel3;
		$this->_finishlevel4 = $finishlevel4;
		$this->_finishlevel5 = $finishlevel5;
	}
		
}

class BattleProgressItem
{
	public $_milestoneid;
	public $_name;
	public $_desc;
	public $_category;
	public $_userid;
	public $_oldvalue;
	public $_newvalue;
	public $_threshold;
	public $_oldlevel;
	public $_newlevel;
	public $_image;
	
	function __construct($milestoneid,$name,$desc,$category,$userid,$oldvalue,$newvalue,$threshold,$oldlevel,$newlevel,$image){
		$this->_milestoneid = $milestoneid;
		$this->_name = $name;
		$this->_desc = $desc;
		$this->_category = $category;
		$this->_userid = $userid;
		$this->_oldvalue = $oldvalue;
		$this->_newvalue = $newvalue;
		$this->_threshold = $threshold;
		$this->_oldlevel = $oldlevel;
		$this->_newlevel = $newlevel;
		$this->_image = $image;
	}
}

?>