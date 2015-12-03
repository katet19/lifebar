<?php
class Category
{
	public $_id;
	public $_name;
	public $_enabled;
	public $_type;
	public $_list;
	
	function __construct($id, $name, $enabled, $type, $list) {
		$this->_id = $id;
		$this->_name = $name;
		$this->_enabled = $enabled;	
		$this->_type = $type;
		$this->_list = $list;
	}
		
}

?>