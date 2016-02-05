<?php
class User
{
	public $_id;
	public $_first;
	public $_last;
	public $_username;
	public $_email;
	public $_birthdate;
	public $_defaultwatched;
	public $_weaveview;
	public $_facebook;
	public $_twitter;
	public $_steam;
	public $_xbox;
	public $_psn;
	public $_google;
	public $_security;
	public $_cookieID;
	public $_privacy;
	public $_avatar;
	public $_thumbnail;
	public $_realnames;
	public $_weave;
	public $_title;
	public $_image;
	public $_website;
	
	
	function __construct($id, $first, $last, $username, $email, $birthdate, $facebook, $twitter, $steam, $xbox, $psn, $google, $security, $defaultwatched, $weaveview, $cookieID, $privacy, $realnames, $title, $image, $website) {
		$this->_id = $id;
		$this->_first = $first;
		$this->_last = $last;
		$this->_username = $username;
		$this->_email = $email;
		$birthdate = explode("-", $birthdate);
		$this->_birthdate = $birthdate[0];
		$this->_defaultwatched = $defaultwatched;
		$this->_weaveview = $weaveview;
		$this->_facebook = $facebook;
		$this->_twitter = $twitter;
		$this->_steam = $steam;
		$this->_xbox = $xbox;
		$this->_psn= $psn;
		$this->_google = $google;
		$this->_security = $security;
		$this->_cookieID = $cookieID;
		$this->_privacy = $privacy;
		$this->_realnames = $realnames;
		$this->_title = $title;
		$this->_image = $image;
		$this->_website = $website;
		
		
		if($security == "Journalist"){
			//Big Image
			$filepath = "http://lifebar.io/Images/CriticAvatars/".$id.".png";
			$filepathtest = "../Images/CriticAvatars/".$id.".png";
			if(!file_exists($filepathtest)){
				$filepath = "http://lifebar.io/Images/CriticAvatars/".$id.".jpg";
				$filepathtest = "../Images/CriticAvatars/".$id.".jpg";
			}
			
			if(file_exists($filepathtest))
				$this->_avatar = $filepath;
			else
				$this->_avatar = get_gravatar($this->_email);
			
			//Little Image
			$filepathT = "http://lifebar.io/Images/CriticAvatars/".$id."s.png";
			$filepathtestT = "../Images/CriticAvatars/".$id."s.png";
			if(!file_exists($filepathtestT)){
				$filepathT = "http://lifebar.io/Images/CriticAvatars/".$id."s.jpg";
				$filepathtestT = "../Images/CriticAvatars/".$id."s.jpg";
			}
			
			if(file_exists($filepathtestT))
				$this->_thumbnail = $filepathT;
			else
				$this->_thumbnail = get_gravatar($this->_email);
		}else{
			if($image == "Gravatar"){
				$this->_thumbnail = get_gravatar($this->_email);
				$this->_avatar = $this->_thumbnail;
			}else if($image == "Uploaded"){
				$filepath = "http://lifebar.io/Images/Avatars/".$id.".jpg";
				$filepathtest = "../Images/Avatars/".$id.".jpg";
				if(!file_exists($filepathtest)){
					$filepath = "http://lifebar.io/Images/Avatars/".$id.".png";
					$filepathtest = "../Images/Avatars/".$id.".png";
				}
				
				if(file_exists($filepathtest))
					$this->_avatar = $filepath;
				else
					$this->_avatar = get_gravatar($this->_email);
				
				$this->_thumbnail = $this->_avatar;
			}else{
				$this->_avatar = $image;
				$this->_thumbnail = $this->_avatar;
			}
		}
	}
		
}

/*function GetJournalistPublication($id){
	$mysqli = Connect();
	if ($result = $mysqli->query("SELECT * FROM  `Experiences` where `UserID` = '".$id."' and `Link` != '' ORDER BY `ExperienceDate` DESC LIMIT 0,1")) {
		while($row = mysqli_fetch_array($result)){
				$link = $row["Link"];
				$domain = parse_url($link);
				if ($result2 = $mysqli->query("SELECT * FROM  `Publications` where `Link` like '%".$domain["host"]."%' LIMIT 0,1")) {
					while($row2 = mysqli_fetch_array($result2)){
						$publication[] = $row2["Title"];
						$publication[] = $row2["Link"];
					}
				}

		}
	}
	return $publication;		
}*/


?>
