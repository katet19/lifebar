<?php

if ($_FILES["file"]["error"] > 0){
  echo "Error: " . $_FILES["file"]["error"] . "<br>";
} else if(isset($_POST["saving"])) {
  $name = $_POST["saving"].".jpg";
  $location = "../Images/Avatars";
  //echo $location."/". $name;
  if (file_exists("upload/" . $name)){
      echo $name . " already exists. ";
  }	else {
  	  echo "Finished Uploading";
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
		Upload Avatar
	</title>
</head>
<body>
<form method="post" enctype="multipart/form-data" style="color:black;">
	<input type="file" name="file" id="file" style="inline-block; color:black;">
	<input type="hidden" name="saving" id="saving" value='<?php echo $_GET['id']; ?>' >
	<input type="submit" name="submit" value="Submit">
</form>
</body>
</html>