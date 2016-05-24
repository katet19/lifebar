<?php
class Badge
{
	public $_id;
	public $_title;
	public $_url;
	public $_file;
	public $_description;
	
	function __construct($id, $title, $url, $file, $description) {
		$this->_id = $id;
		$this->_title = $title;
		$this->_url = $url;
		$this->_file = $file;
		$this->_description = $description;
	}
		
}
?>
