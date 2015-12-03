<?php
require_once "includes.php";
require_once 'simple_html_dom.php';

//GetIGNPage($_GET['page'], $_GET['year']);


/*
//Fix IGN errors

	UPDATE  `ImportReview` SET  `AuthorName` =  'Doug Perry',
	`AuthorID` =  '7931' WHERE AuthorName =  'Array';
	
	Delete from Users where `Username` = 'DouglassC.[CRITIC]';
	
	DELETE from `Users` where `Username` = '[CRITIC]';
*/



if($_GET['run'] == 'Y')
	AutoPageScrapeIGN();

function AutoPageScrapeIGN(){
	$info = GetPageNumber();
	$info = explode("," , $info);
	$year = $info[0];
	$page = $info[1];
	GetIGNPage($page, $year);
}

function GetIGNPage($page, $year){	
	$html = file_get_html("http://www.ign.com/games/reviews?startIndex=".$page."&time=range&startTime=".$year."&endTime=".$year);
	$count = 1;
	echo "IGN Import started searching by (".$year.", ".$page.") <BR><hr>";
	foreach($html->find(".itemList-item") as $review){
		//reset vars
		$title = "";
		$unscore = 0;
		$link = "";
		$author = "";
		$quote = "";
		$score = 0;
		
		$title = $review->find(".item-title a", 0)->plaintext;
		$unscore = $review->find(".scoreBox-score", 0)->plaintext;
		$link = $review->find(".item-quickLinks li a", 0)->href;
		if(!AlreadyImported($link)){
			$reviewhtml = file_get_html($link);
			$author = str_replace("by ", "", $reviewhtml->find("#creator-name", 0)->plaintext);
			
			if($author == ""){
				$author = str_replace("by ", "", $reviewhtml->find(".article_author", 0)->plaintext);
			}
			$verdict = $reviewhtml->find(".articleVerdictText", 0)->plaintext;
			$quote = $reviewhtml->find(".article_subtitle", 0)->plaintext;
			if($quote == ""){
				$quote = $reviewhtml->find(".inner-blurb", 0)->plaintext;
			}
			$fulldate = $reviewhtml->find(".article_pub_date", 0)->plaintext;
			$fulldate = explode(",", $fulldate);
			$year = trim($fulldate[1]);
			
			if($unscore > 9.0){
				$score = 1;
			}else if($unscore >= 8.0){
				$score = 2;
			}else if($unscore >= 6.0){
				$score = 3;
			}else if($unscore >= 3.0){
				$score = 4;
			}else{
				$score = 5;
			}
			
			$author = trim($author);
			if($author != "IGN Staff"){
					$originalauthor = $author;
					$alljournalists = GetJournalists(); 
					$foundit = false;
					 foreach ($alljournalists as $journalist) {
					 	  $smallauthor = explode(" ", $author);
					 	  if(sizeof($smallauthor) == 3){
   							  if(($journalist->_first." ".$journalist->_last) == $smallauthor[0]." ".$smallauthor[2]){ 
							  	$foundit = true; 
							  	$jid=$journalist->_id;
							  }
					 	  }else{
							  if(($journalist->_first." ".$journalist->_last) == $author){ 
							  	$foundit = true; 
							  	$jid=$journalist->_id;
							  }
					 	  }
					  } 
					  if($foundit == false){
						$author = explode(" ", $author);
						if(sizeof($author) == 3)
							$jid = RegisterJournalist($author[0], $author[2]); 
						else
							$jid = RegisterJournalist($author[0], $author[1]); 
						echo "New Critic (".$jid.") <br>";
					  } 
			
				//Submit Exp
				ImportReviewData($link, trim($title), $quote, $author, $jid, $unscore, $score, "IGN", $year);
				echo "<b>[".$count."]</b> ".$title."(".$originalauthor.") - $quote ($year)<BR>";
				
			}else{
				ImportReviewData($link, trim($title), $quote, $author, -1, $unscore, $score, "IGN", $year);
				echo "<b>[".$count."]</b> ".$title."(".$author.") - $quote ($year)<BR>";
			}
			
		}else{
			echo "<b>[".$count."]</b> ".$title." was already imported <BR>";
		}
		$count++;
	}
	
	if($count < 20)
		IncrementPageNumber(true);
	else
		IncrementPageNumber(false);
	
	echo "<hr><br> Finished Import Process";
}

function GetGameSpotPage($page){	
	$html = file_get_html("http://www.gamespot.com/reviews/?page=".$page);
	foreach($html->find("#js-sort-filter-results .media") as $article){
		$link = "http://gamespot.com".$article->children(0)->href;
		$title = str_replace(" Review", "", $article->find(".media-title", 0)->plaintext);
		$quote = $article->find(".media-deck", 0)->plaintext;
		$author = trim(str_replace("&nbsp;", " ", $article->find(".media-author", 0)->plaintext)," ");
		$unscore = $article->find(".well--review-gs", 0)->children(1)->plaintext;
		$jid = -1;
		$unscore = number_format($unscore, 1);
		
		if(!AlreadyImported($link)){
		
			//Convert score to Tier
			if($unscore > 9.0){ 
				$score = 1;
			}else if($unscore > 7.9){
				$score = 2;
			}else if($unscore > 5.9){
				$score = 3;
			}else if($unscore > 3.9){
				$score = 4;
			}else{
				$score = 5;	
			}
			if($author != "Gamespot Staff"){
				?>
				<div style='display:inline-block; width:40%; margin:1em; background-color:rgba(71,71,71,0.6); color:white; text-align:left;padding:0 0 0 0.1em'>
					<div class='ReviewBox' style='display:block;width:100%;'>Title: <input type='text' style='width:90%' class='ReviewInputs' id='ReviewTitle' value='<?php echo $title; ?>' ></div>
					<div class='ReviewBox' style='display:block;width:100%;'>Link: <input type='text' style='width:90%' class='ReviewInputs' id='ReviewLink' value='<?php echo $link; ?>' ></div>
					<div class='ReviewBox' style='display:block;width:100%;vertical-align:top;'>Quote: <textarea cols=45 rows=5 id='ReviewQuote'><?php echo $quote; ?></textarea></div>
					<?php $alljournalists = GetJournalists(); $foundit = false;
					  foreach ($alljournalists as $journalist) { ?>
					  <?php if(($journalist->_first." ".$journalist->_last) == $author){ $foundit = true; $jid=$journalist->_id;?>
						<div class='ReviewBox' style='display:block;width:100%;'>Author: <input type='text' style='width:80%' class='ReviewInputs' data-id='<?php echo $journalist->_id; ?>' id='ReviewAuthor' value='<?php echo $journalist->_first." ".$journalist->_last; ?>' ></div>
					  <?php }
					  }
					  if($foundit == false){
						$author = explode(" ", $author);
						$jid = RegisterJournalist($author[0], $author[1]); 
						?>
						<div class='ReviewBox' style='display:block;width:100%;'>Author: <input type='text' style='width:80%' class='ReviewInputs' data-id='<?php echo $jid; ?>' id='ReviewAuthor' value='<?php echo $author[0]." ".$author[1]; ?> *NEW*' ></div>
					  <?php } ?>
					<div class='ReviewBox' style='display:block;width:100%;'>Score: <input type='text' style='width:80%' class='ReviewInputs' id='ReviewScore' value='<?php echo $score; ?>' > (<?php echo $unscore; ?>)</div>
					<div class='ReviewBox' style='display:block;width:100%;'>GameID: <input type='text' style='width:80%' class='ReviewInputs' id='ReviewGameID'></div>
					<div class='ReviewBox SaveReview' style='display:block;width:100%;background-color:#474747;color:white;cursor:pointer;'>Save Critic Feedback</div>
				</div>
			<?php
			
				//Submit Exp
				ImportReviewData($link, $title, $quote, $author, $jid, $unscore, $score);
			
			}else{
				?>
				<div style='display:inline-block; width:40%; margin:1em; background-color:rgba(71,71,71,0.6); color:white; text-align:left;padding:0 0 0 0.1em'>
					<p>Gamespot Staff reviewed <?php echo $title; ?></p>
				</div>
				<?php
			}
		}else{
			?>
			<div style='display:inline-block; width:40%; margin:1em; background-color:rgba(71,71,71,0.6); color:white; text-align:left;padding:0 0 0 0.1em'>
				<p><?php echo $title; ?> already imported into system</p>
			</div>
			<?php
		}
	}
}
function Get1upPage($page){	
	$html = file_get_html("http://www.1up.com/reviews/?page=".$page);
	foreach($html->find("#featuresIndex .articleBlogView") as $article){
		$link = $quote = "";
		$link = "http://1up.com".$article->children(0)->href;
		$title = $article->find("h1", 0)->plaintext;
		
		//Much longer blurb 
		$quote = $article->find(".blogcontent", 0)->find("p", 1)->plaintext;
		if($quote == ""){
			//Short blurb
			$quote = $article->find("h4", 0)->plaintext;
		}
		$author = $article->find("p a", 0)->plaintext;
		$unscore = $article->find("img", 1)->src;
		$jid = -1;
		
		if(!AlreadyImported($link)){
		
			//Convert score to Tier
			if(strpos($unscore, '_A') !== FALSE || strpos($unscore, '_A+') !== FALSE){ 
				$score = 1;
			}else if(strpos($unscore, '_A-') !== FALSE || strpos($unscore, '_B+') !== FALSE || strpos($unscore, '_B') !== FALSE || strpos($unscore, '_B-') !== FALSE){
				$score = 2;
			}else if(strpos($unscore, '_C+') !== FALSE || strpos($unscore, '_C') !== FALSE || strpos($unscore, '_C-') !== FALSE){
				$score = 3;
			}else if(strpos($unscore, '_D+') !== FALSE || strpos($unscore, '_D') !== FALSE || strpos($unscore, '_D-') !== FALSE){
				$score = 4;
			}else if(strpos($unscore, '_NA') !== FALSE){
				$score = 0;
			}else{
				$score = 5;	
			}
			if($author != "1UP Staff"){
					$alljournalists = GetJournalists(); 
					$foundit = false;
					 foreach ($alljournalists as $journalist) {
						  if(($journalist->_first." ".$journalist->_last) == $author){ 
						  	$foundit = true; 
						  	$jid=$journalist->_id;
						  }
					  } 
					  if($foundit == false){
						$author = explode(" ", $author);
						$jid = RegisterJournalist($author[0], $author[1]); 
						echo "New Critic: ".$jid;
					  } 
			
				//Submit Exp
				ImportReviewData($link, $title, $quote, $author, $jid, $unscore, $score, "1up");
				
			}else{

			}
		}else{

		}
	}
	
	IncrementPageNumber();
}
function ShowPendingGameSpotImported(){
	$mysqli = Connect();
	$myuser = $_SESSION['logged-in'];
	$result = $mysqli->query("select * from `ImportReview` where `GameID` = '' and `Source` = 'Gamespot' and (`Owner` = '0' || `Owner` = '".$myuser->_id."') ORDER BY `Owner` DESC LIMIT 0,50");
	while($row = mysqli_fetch_array($result)){
		if($row['Owner'] == 0){
			TakeOwnership($row['ID'], $myuser->_id);
			$row['Owner'] = $myuser->_id;
		}
		?>
				<div class="ReviewContainer" style='display:inline-block; width:100%; margin:1em; background-color:rgba(71,71,71,0.6); color:white; text-align:left;padding:0 0 0 0.1em'>
					<div class='ReviewBox' style='display:block;width:100%; font-size:2em'><?php echo $row["Title"]; ?><input type='text' style='width:90%;display:none' class='ReviewInputs' id='ReviewTitle' value='<?php echo $row["Title"]; ?>' ></div>
					<div class='ReviewBox' style='display:block;width:100%;'>Link: <input type='text' style='width:70%' class='ReviewInputs' id='ReviewLink' value='<?php echo $row["Link"]; ?>' > <a href="<?php echo str_replace("http://", "http://www.", $row["Link"]); ?>" target="_blank" style='color:white;'>Link</a></div>
					<div class='ReviewBox' style='display:block;width:100%;vertical-align:top;'>Quote: <textarea cols=45 rows=5 id='ReviewQuote'><?php echo $row["Quote"]; ?></textarea></div>
					<div class='ReviewBox' style='display:inline-block;width:auto;'>Author: <input type='text' style='width:auto' class='ReviewInputs' data-id='<?php echo $row["AuthorID"]; ?>' id='ReviewAuthor' value='<?php echo $row["AuthorName"]; ?>' ></div>
					<div class='ReviewBox' style='display:inline-block;width:auto; margin-left:0.2em;'>Tier: <input type='text' style='width:25' class='ReviewInputs' id='ReviewScore' value='<?php echo $row["Tier"]; ?>' ></div>
					<div class='ReviewBox' style='display:inline-block;width:auto; margin-left:0.2em;'>GameID: <input type='text' style='width:30' class='ReviewInputs' id='ReviewGameID'></div>
					<div class='SaveReview'></div>
					<br><br>
					<div>Locked by <?php if($row['Owner'] == 7){ echo "Jonathan"; }else if($row['Owner'] == 7588){ echo "Ryan"; }else{ echo "No one"; }?> </div>
					<br>
					<div class='ReviewBox DismissReview' style='display:block;width:100%;background-color:yellow;color:black;cursor:pointer;'>Dismiss</div>
				</div>
		<?php
	}
	Close($mysqli, $result);
}
function ShowPending1upImported(){
	$mysqli = Connect();
	$myuser = $_SESSION['logged-in'];
	$result = $mysqli->query("select * from `ImportReview` where `GameID` = '' and `Source` = '1up' and (`Owner` = '0' || `Owner` = '".$myuser->_id."') ORDER BY `Owner` DESC LIMIT 0,50");
	while($row = mysqli_fetch_array($result)){
		if($row['Owner'] == 0){
			TakeOwnership($row['ID'], $myuser->_id);
			$row['Owner'] = $myuser->_id;
		}
		?>
				<div class="ReviewContainer" style='display:inline-block; width:100%; margin:1em; background-color:rgba(71,71,71,0.6); color:white; text-align:left;padding:0 0 0 0.1em'>
					<div class='ReviewBox' style='display:block;width:100%; font-size:2em'><?php echo $row["Title"]; ?><input type='text' style='width:90%;display:none' class='ReviewInputs' id='ReviewTitle' value='<?php echo $row["Title"]; ?>' ></div>
					<div class='ReviewBox' style='display:block;width:100%;'>Link: <input type='text' style='width:70%' class='ReviewInputs' id='ReviewLink' value='<?php echo $row["Link"]; ?>' > <a href="<?php echo $row["Link"]; ?>" target="_blank" style='color:white;'>Link</a></div>
					<div class='ReviewBox' style='display:block;width:100%;vertical-align:top;'>Quote: <textarea cols=45 rows=5 id='ReviewQuote'><?php echo $row["Quote"]; ?></textarea></div>
					<div class='ReviewBox' style='display:inline-block;width:auto;'>Author: <input type='text' style='width:auto' class='ReviewInputs' data-id='<?php echo $row["AuthorID"]; ?>' id='ReviewAuthor' value='<?php echo $row["AuthorName"]; ?>' ></div>
					<div class='ReviewBox' style='display:inline-block;width:auto; margin-left:0.2em;'>Tier: <input type='text' style='width:25' class='ReviewInputs' id='ReviewScore' value='<?php echo $row["Tier"]; ?>' ></div>
					<div class='ReviewBox' style='display:inline-block;width:auto; margin-left:0.2em;'>GameID: <input type='text' style='width:30' class='ReviewInputs' id='ReviewGameID'></div>
					<div class='SaveReview'></div>
					<br><br>
					<div>Locked by : <?php if($row['Owner'] == 7){ echo "Jonathan"; }else if($row['Owner'] == 7588){ echo "Ryan"; }else{ echo "No one"; }?> </div>
					<br>
					<div class='ReviewBox DismissReview' style='display:block;width:100%;background-color:yellow;color:black;cursor:pointer;'>Dismiss</div>
				</div>
		<?php
	}
	Close($mysqli, $result);
}
function TakeOwnership($id, $myid){
	$mysqli = Connect();
	$mysqli->query("update `ImportReview` set `Owner` = '".$myid."' where `ID` = '".$id."'");
	Close($mysqli, $result);
}
function GetPendingGameSpotImports(){
	$mysqli = Connect();
	$result = $mysqli->query("select count(*) from `ImportReview` where `GameID` = '' and `Source` = 'Gamespot'");
	while($row = mysqli_fetch_array($result)){
		$pending = $row["count(*)"]; 
	}
	Close($mysqli, $result);
	return $pending;
}
function GetPending1upImports(){
	$mysqli = Connect();
	$result = $mysqli->query("select count(*) from `ImportReview` where `GameID` = '' and `Source` = '1up'");
	while($row = mysqli_fetch_array($result)){
		$pending = $row["count(*)"]; 
	}
	Close($mysqli, $result);
	return $pending;
}
function ImportReviewData($links, $title, $quote, $aname, $aid, $score, $tier, $source, $year){
	$mysqli = Connect();

	if (!AlreadyImported($links)){
		$result = $mysqli->query("insert into `ImportReview` (`Link`,`Title`,`Quote`,`AuthorName`,`AuthorID`,`Score`,`Tier`,`Source`,`Year`) values ('".$links."','".mysqli_real_escape_string($mysqli, $title)."','".mysqli_real_escape_string($mysqli, $quote)."','".$aname."','".$aid."','".$score."','".$tier."', '".$source."', '".$year."')");
	}
	Close($mysqli, $result);
}
function CompleteImport($link, $gameid){
	$mysqli = Connect();
	$result = $mysqli->query("Update `ImportReview` set `GameID` = '".$gameid."' where `Link` = '".$link."'");
	Close($mysqli, $result);
}
function AlreadyImported($link){
	$foundit = false;
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `ImportReview` where `Link` = '".$link."'")) {
		while($row = mysqli_fetch_array($result)){
			$foundit = true;
		}
	}
	Close($mysqli, $result);
	return $foundit;
}
function AlreadyReviewed($link){
	$foundit = false;
	$mysqli = Connect();
	if ($result = $mysqli->query("select * from `Experiences` where `Link` = '".$link."'")) {
		while($row = mysqli_fetch_array($result)){
			$foundit = true;
		}
	}
	Close($mysqli, $result);
	return $foundit;
}
function GetPageNumber(){ 	
	$mysqli = Connect();
	$result = $mysqli->query("select * from `PublisherFeeds` where `Title` = 'IGN'");
	while($row = mysqli_fetch_array($result)){
		$pagenum = $row["PageScraper"];
	}
	Close($mysqli, $result);
	return $pagenum;
}
function IncrementPageNumber($leapyear){ 	
	$mysqli = Connect();
	$result = $mysqli->query("select * from `PublisherFeeds` where `Title` = 'IGN'");
	while($row = mysqli_fetch_array($result)){
		$pagenum = $row["PageScraper"];
		$pagenum = explode("," , $pagenum);
		if($leapyear){
			$year = $pagenum[0] + 1;
			$page = 0;
		}else{
			$year = $pagenum[0];
			$page = $pagenum[1] + 25;
		}
		$pagenum = $year.",".$page;
		$mysqli->query("update `PublisherFeeds` set `PageScraper` = '".$pagenum."' where `Title` = 'IGN'");
	}
	Close($mysqli, $result);
	return $pagenum;
}
function DismissReview($link){
	$mysqli = Connect();
	$result = $mysqli->query("Delete from `ImportReview` where `Link` = '".$link."'");
	Close($mysqli, $result);
}
?>