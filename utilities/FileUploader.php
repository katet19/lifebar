<?php
require_once 'controller_game.php';

if ($_FILES["file"]["error"] > 0){
  echo "Error: " . $_FILES["file"]["error"] . "<br>";
} else if(isset($_POST["mygameid"])) {
  $name = $_POST["mygameid"].".jpg";
  $smallname = $_POST["mygameid"]."s.jpg";
  $location = "../Images/".$_POST["mygameyear"];
  if (file_exists("upload/" . $name)){
      echo $name . " already exists. ";
  }	else {
	  move_uploaded_file($_FILES["file"]["tmp_name"], $location."/". $name);

	$percent = 0.35;
	list($width, $height) = getimagesize($location."/". $name);
	$new_width = $width * $percent;
	$new_height = $height * $percent;
	$image_p = imagecreatetruecolor($new_width, $new_height);
	$image = imagecreatefromjpeg($location."/".$name);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	imagejpeg($image_p, $location."/".$smallname, 70);
	MarkGameReviewed($_POST["mygameid"], $_POST["mygameyear"]);
  }
}

function CustomImageLoad($start, $end){
	for($i = $start; $i <= $end; $i++){
		$name = $i.".jpg";
		$smallname = $i."s.jpg";
		$location = "../Images/Generic";
		$percent = 0.35;
		list($width, $height) = getimagesize($location."/". $name);
		$new_width = $width * $percent;
		$new_height = $height * $percent;
		$image_p = imagecreatetruecolor($new_width, $new_height);
		$image = imagecreatefromjpeg($location."/".$name);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		imagejpeg($image_p, $location."/".$smallname, 70);
	}
}


?>

<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>
		Upload File
	</title>
</head>
<body>
<form action="FileUploader.php" method="post" enctype="multipart/form-data" style="color:black;">
	<label for="file" style="inline-block; color:black;">Image:</label>
	<input type="file" name="file" id="file" style="inline-block; color:black;">
	GameID:<input type="text" name="mygameid" id="mygameid" style="inline-block; color:black;" >
	GameYear:<input type="text" name="mygameyear" id="mygameyear" style="inline-block; color:black;">
	<input type="submit" name="submit" value="Submit">
</form>
</body>
</html>