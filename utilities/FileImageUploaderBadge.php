<?php
require_once 'controller_game.php';

if ($_FILES["file"]["error"] > 0){
  echo "Error: " . $_FILES["file"]["error"] . "<br>";
} else if(isset($_POST["badgename"])) {
  $name = $_POST["badgename"].".jpg";
  $location = "../Images/Badges";
  if (file_exists("upload/" . $name)){
      echo $name . " already exists. ";
  }	else {
	  move_uploaded_file($_FILES["file"]["tmp_name"], $location."/". $name);
	  move_uploaded_file($_FILES["file"]["tmp_name"], $location."/". $name);
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
<form action="FileImageUploaderBadge.php" method="post" enctype="multipart/form-data" style="color:black;">
	<label for="file" style="inline-block; color:black;">Image:</label>
	<input type="file" name="file" id="file" style="inline-block; color:black;">
	Badge Name (include lvl if it varies):
	<input type="text" name="badgename" id="badgename" value='' style="inline-block; color:black;" >
	<input type="submit" name="submit" value="Submit">
</form>
</body>
</html>