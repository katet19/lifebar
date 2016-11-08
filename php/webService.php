<?php require_once 'includes.php';
	if(isset($_POST['action']) && (!$GLOBALS["DownForMaintenance"] || $_SESSION['logged-in']->_security == 'Admin')){
		LoginServices();
		SignupServices();
		DiscoverServices();
		UserServices();
		GameServices();
		XPServices();
		NotificationServices();
		ActivityServices();
		AdminServices();
		WeaveServices();
		MilestoneServices();
		GeneralServices();
		ImportServices();
		CollectionServices();
		OnboardingServices();
		FormServices();
	}else if(isset($_POST['action']) && $GLOBALS["DownForMaintenance"]){
		?>
		<div style='font-size: 3em;font-weight: 100;padding-top: 100px;'>Lifebar is temporarily down for maintenance</div>
		<?php
	}
	
	function FormServices(){
		if($_POST['action'] == 'DisplayDailyCreationForm'){
			DailyForm(GetGameByGBID($_POST['gameid']), $_SESSION['logged-in'], '');
		}
		if($_POST['action'] == 'EditDailyCreationForm'){
			DailyForm('',$_SESSION['logged-in'], $_POST['refptid']);
		}
		if($_POST['action'] == 'PreviewRefPt'){
			$item = GetReflectionPoint($_POST['refptid']);
			$data['DTYPE'] = 'DAILY';
			$data['QUESTION'] = $item['Header'];
			$data['SUBQUESTION'] = $item['SubHeader'];
			$data['ID'] = $item['ID'];
			$data['OBJECTID'] = $item['ObjectID'];
			$data['OBJECTTYPE'] = $item['OBJECTTYPE'];
			$data['ITEMS'] = $item["Items"];
			DisplayDailyHeader(0, $data);
		}
		if($_POST['action'] == 'SubmitDailyForm'){
			SubmitDailyForm($_SESSION['logged-in']->_id, $_POST['question'], $_POST['subquestion'], 'Daily', 'Yes', $_POST['gameid'], 'Game', $_POST['defaultResponse'], $_POST['responses'], $_POST['responseurls'], $_POST['type'], $_POST['finished']);
		}
		if($_POST['action'] == 'UpdateDailyForm'){
			UpdateDailyForm($_POST['formid'], $_POST['question'], $_POST['subquestion'], $_POST['defaultResponse'], $_POST['responses'], $_POST['responseurls'], $_POST['type'], $_POST['finished']);
		}
		if($_POST['action'] == 'DeleteDaily'){
			DeleteReflectionPoint($_POST['formid']);
		}
		if($_POST['action'] == 'SubmitDailyChoice'){
			SaveFormChoices($_SESSION['logged-in']->_id, $_POST['formid'], $_POST['formitemid'], $_POST['gameid'], $_POST['objectid'], $_POST['objectType']);
			$choicesMade =  GetFormChoices($_SESSION['logged-in']->_id, $_POST['formid']);
			if(isset($_POST['gamepage']))
				$gamepage = true;
			else
				$gampage = false;

			ShowFormResults($_POST['formid'], $choicesMade, $gamepage);
		}
	}
	
	function OnboardingServices(){
		if($_POST['action'] == 'ShowOnboardingAccount'){
			AccountDetails();
		}
		if($_POST['action'] == 'ShowOnboardingSocial'){
			SocialDetails();
		}
		if($_POST['action'] == 'ShowOnboardingGamingPref'){
			GamingPrefDetails();
		}
		if($_POST['action'] == 'OnboardingViewMore'){
			ViewMoreMembers($_POST['exclude']);
		}
		if($_POST['action'] == 'OnboardingUserSearch'){
			ViewOnboardingUserSearch($_POST['searchstring']);
		}
		if($_POST['action'] == 'SaveAccountInfo'){
			SaveOnboardingAccount($_POST['steam'], $_POST['xbox'], $_POST['psn'], $_POST['age']);
		}
		if($_POST['action'] == 'SaveSocialInfo'){
			SaveOnboardingFollowing($_POST['following'], $_POST['pubs']);
		}
		if($_POST['action'] == 'SaveGamingPrefInfo'){
			SaveOnboardingPrefs($_POST['prefs']);
		}
	}
	
	function CollectionServices(){
		if($_POST['action'] == 'DisplayCollectionDetails'){
			DisplayCollectionDetails($_POST['collectionid']);
		}
		if($_POST['action'] == "NextPageCollection"){
			DisplayCollectionDetailGamesPagination($_POST['collectionid'], $_POST['userid'], $_POST['offset'], $_POST['editMode']);
		}
		if($_POST['action'] == "DisplaySearchCollection"){
			DisplaySearchCollection($_POST['collectionid'], $_POST['searchstring'], $_POST['offset'], $_POST['userid'], $_POST['editMode']);
		}
		if($_POST['action'] == "DisplayCollectionManagement"){
			DisplayCollectionManagement($_POST['gameid'],$_SESSION['logged-in']->_id,$_POST['quickAdd'],$_POST['gbid']);
		}
		if($_POST['action'] == "ValidateCollectionName"){
			ValidateCollectionName($_POST['collectionName'],$_SESSION['logged-in']->_id);
		}
		if($_POST['action'] == "CreateCollection"){
			CreatePersonalCollection($_POST['collectionName'],$_POST['collectionDesc'],$_SESSION['logged-in']->_id, $_POST['gameid']);
		}
		if($_POST['action'] == "AddGameToCollection"){
			$gameid = AddToCollection($_POST['collectionID'],$_POST['gbid'],$_SESSION['logged-in']->_id);
			if($gameid > 0)
				DisplayAddedGameToCollection($gameid);
		}
		if($_POST['action'] == "AddGameToCollectionFromCollectionManger"){
			$game = GetGame($_POST['gameid']);
			AddToCollection($_POST['collectionID'],$game->_gbid,$_SESSION['logged-in']->_id);
			$collection = GetCollectionByID($_POST['collectionID']);
			echo "<i>".$game->_title."</i>&nbsp; added to &nbsp;<div class='toast-collection-link' data-id='".$_POST['collectionID']."' style='cursor:pointer;font-weight:500;'>".$collection->_name."</div>";
		}
		if($_POST['action'] == "RemoveGameFromCollection"){
			RemoveFromCollection($_POST['collectionID'],$_POST['gameid'],$_SESSION['logged-in']->_id);
		}
		if($_POST['action'] == "UpdateCollection"){
			UpdateCollection($_POST['collectionid'],$_POST['collectionName'],$_POST['collectionDesc']);
		}
		if($_POST['action'] == "SetCollectionCover"){
			SetCollectionCover($_POST['collectionid'],$_POST['gameid']);
		}
		if($_POST['action'] == "DeleteCollection"){
			RemoveCollection($_POST['collectionid']);
		}
		if($_POST['action'] == "FollowCollection"){
			FollowCollection($_SESSION['logged-in']->_id, $_POST['collectionid']);
		}
		if($_POST['action'] == "UnfollowCollection"){
			UnfollowCollection($_SESSION['logged-in']->_id, $_POST['collectionid']);
		}
		if($_POST['action'] == "DisplayCollectionPlayedEdit"){
			DisplayCollectionPlayedEdit($_POST['gameid'], $_SESSION['logged-in']->_id);
		}
		if($_POST['action'] == "DisplayCollectionWatchedEdit"){
			DisplayCollectionWatchedEdit($_POST['gameid'], $_SESSION['logged-in']->_id);
		}
		if($_POST['action'] =='SavePlayedCollection' && $_SESSION['logged-in']->_id > 0){
			if($_POST['gameid'] > 0 && $_POST['tier'] > 0){
				$new = SavePlayedXP($_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['quote'],$_POST['tier'],$_POST['completion'],$_POST['quarter'],$_POST['year'],'','',$_POST['platform'],'','','','','','');
				SaveXP($_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['quote'],$_POST['tier'],$_POST['quarter'],$_POST['year'],'');
				CalculateWeave($_SESSION['logged-in']->_id);
				CalculateMilestones($_SESSION['logged-in']->_id, $_POST['gameid'], '', 'Played XP', false);
				echo "|**|".$new;
			}
		}
		if($_POST['action'] =='SaveWatchedCollection' && $_SESSION['logged-in']->_id > 0){
			if($_POST['gameid'] > 0){
				$new = SaveWatchedXP($_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['quote'],$_POST['tier'], $_POST['url'], $_POST['viewsrc'], $_POST['viewing'],$_POST['quarter'],$_POST['year']);
				SaveXP($_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['quote'],$_POST['tier'],$_POST['quarter'],$_POST['year'],'');
				CalculateWeave($_SESSION['logged-in']->_id);
				CalculateMilestones($_SESSION['logged-in']->_id, $_POST['gameid'], '', 'Played XP', false);
				echo "|**|".$new;
			}
		}
		if($_POST['action'] == 'PostUpdateFromCollection'){
			if($_POST['gameid'] > 0 && $_POST['quote'] != '' && $_POST['tier'] > 0){
				UpdateXP($_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['quote'],$_POST['tier'],'',$_POST['completion']);
				echo "|**|false";
			}
		}
	}
	function ImportServices(){
		if($_POST['action'] == 'DisplayStartSteamImport' ){
			DisplayStartSteamImport($_POST['userid']);
		}
		if($_POST['action'] == "GetEstimatedTimeToImport"){
			GetEstimatedTimeToImport($_POST['steamname']);
		}
		if($_POST['action'] == "ImportSteamGames"){
			DisplaySteamGameImport($_POST['steamname'], $_POST['forceImport'], $_POST['fullreset']);
		}
		if($_POST['action'] == "NextUnmappedRow"){
			GetNextUnmappedGameRow($_POST['offset']);
		}
		if($_POST['action'] == "MapGame"){
			MapGameToLifebar($_POST['importID'], $_POST['gbid'], $_POST['auditid']);
		}
		if($_POST['action'] == "TrashGame"){
			if($_SESSION['logged-in']->_security == "Admin"){
				TrashGameToLifebar($_POST['importID'], $_POST['gbid'], $_POST['auditid']);
			}
		}
		if($_POST['action'] == "IgnoreGame"){
			IgnoreGameFromMyImport($_POST['importID'], $_POST['gbid'], $_POST['auditid']);
		}
		if($_POST['action'] == "ReportGame"){
			ReportGameFromMyImport($_POST['importID'], $_POST['gbid'], $_POST['auditid']);
		}
	}
	function MilestoneServices(){
		if($_POST['action'] == 'SaveBadge' ){
			if(TestScript($_POST["validation"])){
				SaveNewMilestone($_POST["name"],$_POST["desc"],$_POST["type"],$_POST["image"],$_POST["diff"],$_POST["validation"],$_POST["threshold"],$_POST["category"],$_POST["subcategory"]);
			}
		}
		if($_POST['action'] == 'UpdateBadge' ){
			if(TestScript($_POST["validation"])){
				UpdateMilestone($_POST["badgeid"],$_POST["name"],$_POST["desc"],$_POST["type"],$_POST["image"],$_POST["diff"],$_POST["validation"],$_POST["threshold"],$_POST["enabled"],$_POST["category"],$_POST["subcategory"]);
			}
		}
		if($_POST['action'] == 'RemoveBadge' ){
			RemoveMilestones($_POST["badgeid"]);
		}
		if($_POST['action'] == 'TestSpecificBadge' ){
			TestSpecificMilestone($_POST["badgeid"], $_SESSION['logged-in']->_id);
		}
		if($_POST['action'] == "DisplayBadgesForCategory"){
			if(isset($_POST["userid"]))
				DisplayMilestonesCategory($_POST["userid"], $_POST['category']);
			else
				DisplayMilestonesCategory($_SESSION['logged-in']->_id, $_POST['category']);
		}
		if($_POST['action'] == "GetAllBadges"){
			if(isset($_POST["userid"]))
				DisplayMilestonesTree($_POST["userid"]);
			else
				DisplayMilestonesTree($_SESSION['logged-in']->_id);
		}
		if($_POST['action'] == "DisplayMyLibrary"){
			DisplayMyLibrary($_POST["userid"], $_POST["filter"]);
		}
		if($_POST['action'] == "DisplayMyLibraryEndless"){
			DisplayMyLibraryEndless($_POST["userid"], $_POST["page"], $_POST["group"], $_POST["filter"]);
		}
		
	}
	function WeaveServices(){
		if($_POST['action'] == 'DisplayWeave' ){
			DisplayWeave($_POST["userid"]);
		}
		if($_POST['action'] == 'DisplayWeaveSecondaryContent'){
			DisplaySecondaryWeave($_POST["userid"]);
		}
		if($_POST['action'] == "UpdatePreferredXP"){
			UpdatePreferredXP($_POST['gameid'], $_SESSION['logged-in']->_id, $_POST['slot']);
		}
		if($_POST['action'] == 'RunWeaveCalculator'){
			if($_SESSION['logged-in']->_security == "Admin"){
				CalculateWeave($_POST['userid']);
				CalculateAllMilestones($_POST['userid']);
			}
		}
		if($_POST['action'] == 'DisplayEquipXP'){
			DisplayEquipXP($_POST['gameid'], false);
		}
		if($_POST['action'] == 'DisplaySpyAbility'){
			DisplaySpyAbility($_POST['userid'],'','','');
		}
		if($_POST['action'] == 'DisplayCharismaAbility'){
			DisplayCharismaAbility($_POST['userid'],'','','');
		}
		if($_POST['action'] == 'DisplayLeadershipAbility'){
			DisplayLeadershipAbility($_POST['userid'],'','','');
		}
		if($_POST['action'] == 'DisplayTrackingAbility'){
			DisplayTrackingAbility($_POST['userid'],'','','');
		}
		if($_POST['action'] == 'DisplayAbilitiesViewMore'){
			DisplayAbilitiesViewMore($_POST['userid']);
		}
		if($_POST['action'] == 'DisplayKnowledgeViewMore'){
			DisplayKnowledgeViewMore($_POST['userid']);
		}
		if($_POST['action'] == 'DisplayKnowledgeDetails'){
			DisplayKnowledgeDetails($_POST['userid'], $_POST['objectid'], $_POST['progressid']);
		}
		if($_POST['action'] == 'DisplayGearViewMore'){
			DisplayGearViewMore($_POST['userid']);
		}
		if($_POST['action'] == 'DisplayGearDetails'){
			DisplayGearDetails($_POST['userid'], $_POST['objectid'], $_POST['progressid']);
		}
		if($_POST['action'] == 'DisplayBestViewMore'){
			DisplayBest($_POST['userid']);
		}
		if($_POST['action'] == 'DisplayDeveloperDetails'){
			DisplayDeveloperDetails($_POST['userid'], $_POST['objectid'], $_POST['progressid']);
		}
		if($_POST['action'] == 'DisplayDeveloperViewMore'){
			DisplayDevelopervViewMore($_POST['userid']);
		}
		if($_POST['action'] == 'ShowUserProfileActivity'){
			DisplayProfileActivity($_POST['userid']);
		}
		if($_POST['action'] == 'ShowUserProfileActivityEndless' ){
			DisplayProfileActivityEndless($_POST['userid'], $_POST['page'], $_POST['date']);
		}
		if($_POST['action'] == 'DisplayUserCollection' ){
			DisplayUserCollection($_POST['userid']);
		}
	}
	function AdminServices(){
		if($_POST['action'] == 'AdminGameSearch' && isset($_POST['search'])){
			DisplayAdminGameSearchResults($_POST['search']);
		}
		if($_POST['action'] == 'AdminBadgeGameSearch' && isset($_POST['search'])){
			DisplayAdminBadgeGameSearchResults($_POST['search']);
		}
		if($_POST['action'] == "UnequipBadge"){
			UnequipBadge($_POST['userid'], $_POST['badgeid']);
		}
		if($_POST['action'] == "EquipBadge"){
			EquipBadge($_POST['userid'], $_POST['badgeid']);
		}
		if($_SESSION['logged-in']->_security == 'Admin'){
			if($_POST['action'] == 'DisplayRefPtSchedule'){
				DisplayRefPtSchedule();
			}
			if($_POST['action'] == 'SaveRefPtSchedule'){
				SaveRefPtSchedule($_POST['savestring']);
			}
			if($_POST['action'] == 'DisplayRefPtPicker'){
				DisplayRefPtPicker($_POST['isNew'], $_POST['searchstring']);
			}
			if($_POST['action'] == 'DisplayAdmin' ){
				DisplayAdmin($_SESSION['logged-in']->_id);
			}
			if($_POST['action'] == 'DisplayAdminSecondaryContent'){
				DisplayAdminSecondaryContent($_SESSION['logged-in']->_id);
			}
			if($_POST['action'] == 'DisplayPendingReviews' ){
				DisplayPendingReviews();
			}
			if($_POST['action'] == 'DismissPendingReview'){
				SaveRSSFeedAsReviewed($_POST['id']);
			}
			if($_POST['action'] == 'SavePendingReview'){
				if($_POST['first'] != "" && $_POST['last'] != ""){
					$criticid = RegisterJournalist($_POST['first'], $_POST['last']);
				}else{
					$criticid = $_POST['journalist'];
				}
				$game = GetGameByGBIDFull($_POST['gameid']);
				
				SubmitCriticExperience($criticid,$game->_id,$_POST['quote'],$_POST['tier'],$_POST['link']);
				SaveRSSFeedAsReviewed($_POST['id']);
				CalculateWeave($criticid);
				CalculateMilestones($criticid, $game->_id, '', 'Played XP', true);
			}
			if($_POST['action'] == 'RequestUpdateFromGiantBomb' ){
				FullUpdateViaGameID($_POST['gameid'], "Sorta");
			}
			if($_POST['action'] == 'DisplayUnmappedManager'){
				DisplayManualMapping();
			}
			if($_POST['action'] == 'DisplayManualMappingInReview'){
				DisplayManualMappingInReview();
			}
			if($_POST['action'] == 'SaveMappingForLater'){
				SaveIGNImportForLater($_POST['id']);
			}
			if($_POST['action'] == 'DismissMapping'){
				DismissMappingReview($_POST['id']);
			}
			if($_POST['action'] == 'MapReviewToGame'){
				MapReviewToGame($_POST['id'], $_POST['quote'], $_POST['tier'], $_POST['link'], $_POST['gameid'], $_POST['criticid']);
			}
			if($_POST['action'] == 'DisplayNewBadgeForm'){
				DisplayNewMilestoneForm();
			}
			if($_POST['action'] == 'DisplayBadgeManagement'){
				DisplayMilestoneManagement();
			}
			if($_POST['action'] == 'DisplayEmailExport'){
				DisplayEmailExport();
			}
			if($_POST['action'] == 'DisplayDBThreads'){
				DisplayDBThreads();
			}
			if($_POST['action'] == 'CheckVersion'){
				CheckVersion($_POST['version']);
			}
			if($_POST['action'] == "DisplayRoleManagement"){
				if($_SESSION['logged-in']->_security == 'Admin')
					DisplayRoleManagement($_POST['userid']);
			}
			if($_POST['action'] == "UpdateRoleManagement"){
				if($_SESSION['logged-in']->_security == 'Admin')
					UpdateRoleManagement($_POST['userid'], $_POST['role']);
			}
			if($_POST['action'] == "DisplayAdminControlsForUser"){
				if($_SESSION['logged-in']->_security == 'Admin')
					DisplayAdminControlsForUser($_POST['userid']);
			}
			if($_POST['action'] == "AdminGiveBadge"){
				if($_SESSION['logged-in']->_security == 'Admin')
					GiveBadgeAccess($_POST['userid'], $_POST['badgeid']);
			}
			if($_POST['action'] == "AdminRemoveBadge"){
				if($_SESSION['logged-in']->_security == 'Admin')
					RemoveBadgeAccess($_POST['userid'], $_POST['badgeid']);
			}
			if($_POST['action'] == "DisplayAdminManageReportedGames"){
				DisplayAdminManageReportedGames();
			}
			if($_POST['action'] == "ClearSearchCache"){
				ClearSearchCache($_POST['searchstring']);
			}
		}
	}
	function ActivityServices(){
		if($_POST['action'] == 'DisplayActivity' ){
			DisplayActivity($_SESSION['logged-in']->_id, $_POST['filter']);
		}
		if($_POST['action'] == 'RefreshMainActivity' ){
			DisplayMainActivity($_SESSION['logged-in']->_id, $_POST['filter']);
		}
		if($_POST['action'] == 'DisplayActivityEndless' ){
			DisplayActivityEndless($_SESSION['logged-in']->_id, $_POST['page'], $_POST['date'], $_POST['filter']);
		}
	}
	function NotificationServices(){
		if($_POST['action'] == 'DisplayNotificationHome' ){
			DisplayMyNotifications($_SESSION['logged-in']->_id);
		}
		if($_POST['action'] == 'DismissNotification' ){
			ActionOnNotification($_SESSION['logged-in']->_id, $_POST['notificationid'], 'remove');
		}
		if($_POST['action'] == 'CheckForNotifications'){
			CheckForAsyncNotifications();
		}
	}
	function XPServices(){
		if($_POST['action'] == 'ShowTierModal' && $_SESSION['logged-in']->_id > 0){
			ShowTierModal($_POST['gameid']);
		}
		if($_POST['action'] == 'ShowXPModal' && $_SESSION['logged-in']->_id > 0){
			ShowXPModal($_POST['gameid']);
		}
		if($_POST['action'] == 'ShowRankModal' && $_SESSION['logged-in']->_id > 0){
			ShowRankModal($_POST['gameid']);
		}
		if($_POST['action'] == 'SaveAgreed' && isset($_POST['gameid']) && isset($_POST['eventid']) && $_SESSION['logged-in']->_id > 0){
			SaveAgreed($_POST['gameid'], $_SESSION['logged-in']->_id, $_POST['agreedwith'], $_POST['eventid']);
		}
		if($_POST['action'] == 'RemoveAgreed' && isset($_POST['gameid']) && isset($_POST['eventid']) && $_SESSION['logged-in']->_id > 0){
			RemoveAgreed($_POST['gameid'], $_SESSION['logged-in']->_id, $_POST['agreedwith'], $_POST['eventid']);
		}
		if($_POST['action'] == 'DisplayAddWatched' && isset($_POST['gameid'])){
			ShowMyNewXP($_POST['gameid'], "Watched", -1);
		}
		if($_POST['action'] == 'DisplayAddPlayed' && isset($_POST['gameid'])){
			ShowMyNewXP($_POST['gameid'], "Played", -1);
		}
		if($_POST['action'] == 'DisplayUpdatePlayed' && isset($_POST['gameid'])){
			ShowMyNewXP($_POST['gameid'], "Played", 0);
		}
		if($_POST['action'] == 'DisplayUpdateWatched' && isset($_POST['gameid'])){
			ShowFastEditWatched($_POST['gameid'], $_POST['watchid']);
		}
		if($_POST['action'] == 'DisplayTierQuote' && isset($_POST['gameid'])){
			ShowTierQuote(null, $_POST['gameid'], true);
		}
		if($_POST['action'] =='SavePlayedFull' && $_SESSION['logged-in']->_id > 0){
			if($_POST['gameid'] > 0){
				SavePlayedXP($_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['quote'],$_POST['tier'],$_POST['completion'],$_POST['quarter'],$_POST['year'],$_POST['single'],$_POST['multi'],$_POST['platforms'],$_POST['dlc'],$_POST['alpha'],$_POST['beta'],$_POST['early'],$_POST['demo'],$_POST['stream']);
				SaveXP($_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['quote'],$_POST['tier'],$_POST['quarter'],$_POST['year'],$_POST['criticlink']);
				CalculateWeave($_SESSION['logged-in']->_id);
				CalculateMilestones($_SESSION['logged-in']->_id, $_POST['gameid'], '', 'Played XP', false);
				echo "|**|";
				DisplayMyXP($_POST['gameid']);
			}
		}
		if($_POST['action'] =='SaveWatchedFull' && $_SESSION['logged-in']->_id > 0){
			if($_POST['gameid'] > 0){
				SaveWatchedXP($_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['quote'],$_POST['tier'], $_POST['viewurl'], $_POST['viewsrc'], $_POST['viewing'],$_POST['quarter'],$_POST['year']);
				SaveXP($_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['quote'],$_POST['tier'],$_POST['quarter'],$_POST['year'],$_POST['criticlink']);
				CalculateWeave($_SESSION['logged-in']->_id);
				CalculateMilestones($_SESSION['logged-in']->_id, $_POST['gameid'], '', 'Watched XP', false);
				echo "|**|";
				DisplayMyXP($_POST['gameid']);
			}
		}
		if($_POST['action'] =='SavePlayed' && $_SESSION['logged-in']->_id > 0){
			if($_POST['gameid'] > 0){
				SavePlayedXP($_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['quote'],$_POST['tier'],$_POST['completion'],$_POST['quarter'],$_POST['year'],$_POST['single'],$_POST['multi'],$_POST['platforms'],$_POST['dlc'],$_POST['alpha'],$_POST['beta'],$_POST['early'],$_POST['demo'],$_POST['stream']);
				CalculateWeave($_SESSION['logged-in']->_id);
				CalculateMilestones($_SESSION['logged-in']->_id, $_POST['gameid'], '', 'Played XP', false);
				echo "|**|";
				DisplayMyXP($_POST['gameid']);
			}
		}
		if($_POST['action'] =='SaveWatched' && $_SESSION['logged-in']->_id > 0){
			if($_POST['gameid'] > 0){
				SaveWatchedXP($_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['quote'],$_POST['tier'], $_POST['viewurl'], $_POST['viewsrc'], $_POST['viewing'],$_POST['quarter'],$_POST['year']);
				CalculateWeave($_SESSION['logged-in']->_id);
				CalculateMilestones($_SESSION['logged-in']->_id, $_POST['gameid'], '', 'Watched XP', false);
				echo "|**|";
				DisplayMyXP($_POST['gameid']);
			}
		}
		if($_POST['action'] =='SaveWatchedVideo' && $_SESSION['logged-in']->_id > 0){
			if($_POST['gameid'] > 0){
				$new = SaveWatchedXP($_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['quote'],$_POST['tier'], $_POST['url'], $_POST['viewsrc'], $_POST['viewing'],$_POST['quarter'],$_POST['year']);
				SaveXP($_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['quote'],$_POST['tier'],$_POST['quarter'],$_POST['year'],'');
				CalculateWeave($_SESSION['logged-in']->_id);
				CalculateMilestones($_SESSION['logged-in']->_id, $_POST['gameid'], '', 'Played XP', false);
				echo "|**|";
				DisplayMyXP($_POST['gameid']);
				echo "|**|".$new;
			}
		}
		if($_POST['action'] =='UpdateWatched' && $_SESSION['logged-in']->_id > 0){
			if($_POST['gameid'] > 0){
				UpdateWatchedXP($_POST['subxpid'],$_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['viewurl'], $_POST['viewsrc'], $_POST['viewing'],$_POST['quarter'],$_POST['year']);
				CalculateWeave($_SESSION['logged-in']->_id);
				CalculateMilestones($_SESSION['logged-in']->_id, $_POST['gameid'], '', 'Watched XP', false);
				echo "|**|";
				DisplayMyXP($_POST['gameid']);
			}
		}
		if($_POST['action'] =='SaveTierQuote' && $_SESSION['logged-in']->_id > 0){
			if($_POST['gameid'] > 0 && $_POST['quote'] != '' && $_POST['tier'] > 0){
				UpdateXP($_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['quote'],$_POST['tier'],$_POST['criticlink'],'');
				CalculateWeave($_SESSION['logged-in']->_id);
				CalculateMilestones($_SESSION['logged-in']->_id, $_POST['gameid'], '', 'XP', false);
				echo "|**|";
				DisplayMyXP($_POST['gameid']);
			}
		}
		if($_POST['action'] == 'GetGameFAB' ){
			$myxp = GetExperienceForUserByGame($_SESSION['logged-in']->_id, $_POST['gameid']);
			ShowMyGameFAB($_POST['gameid'], $myxp);
		}
		if($_POST['action'] == 'GetFeedbackLoop' ){
			CalculateFeedbackForSave($_POST['gameid'], $_POST['type']);
		}
		if($_POST['action'] == 'CalculateWeave' && $_SESSION['logged-in']->_id > 0){
			CalculateWeave($_SESSION['logged-in']->_id);
		}
		if($_POST['action'] == 'GetTierBreakdownYearTier'){
			DisplayTierGameDetails($_POST['year'], $_POST['tier'], $_SESSION['logged-in']->_id);
		}
		if($_POST['action'] == 'RemoveEntireExperience' && $_SESSION['logged-in']->_id > 0){
			RemoveEntireExperience($_SESSION['logged-in']->_id,$_POST['gameid']);
			CalculateWeave($_SESSION['logged-in']->_id);
		}
		if($_POST['action'] == 'RemoveSubExperience'){
			RemoveSubExperience($_POST['subxpid'], $_POST['gameid']);
			CalculateWeave($_SESSION['logged-in']->_id);
			DisplayMyXP($_POST['gameid']);
		}
		if($_POST['action'] == 'RemoveEvent'){
			$gameid = RemoveEvent($_POST['eventid'], $_SESSION['logged-in']->_id);
			CalculateWeave($_SESSION['logged-in']->_id);
			DisplayMyXP($gameid);
		}
		if($_POST['action'] == "SaveJournalEntry"){
			SaveJournalEntry($_POST['subject'], $_POST['journal'], $_POST['gameid']);
		}
	}
	function GameServices(){
		if($_POST['action'] == 'DisplayGame' && isset($_POST['gbid'])){
			DisplayGame($_POST['gbid']);
		}
		if($_POST['action'] == 'DisplayGameViaID' && isset($_POST['gameid'])){
			DisplayGameViaID($_POST['gameid'], -1);
		}
		if($_POST['action'] == 'DisplayGameViaIDWithUser' && isset($_POST['gameid'])){
			DisplayGameViaID($_POST['gameid'], $_POST['otherid']);
		}
		if($_POST['action'] == 'DisplayUserDetails' && isset($_POST['gameid']) && isset($_POST['userid'])){
			$xp = GetExperienceForUserByGame($_POST['userid'], $_POST['gameid']);
			ShowUserXP($xp);
		}
		if($_POST['action'] == 'DisplayMyXP' && isset($_POST['gameid']) && $_SESSION['logged-in']->_id > 0){
			DisplayMyXP($_POST['gameid']);
		}
		if($_POST['action'] == 'DisplayVideoForGame' && isset($_POST['gameid'])){
			DisplayVideoForGame($_POST['url'], $_POST['gameid']);
		}
		if($_POST['action'] == 'DisplayMyAnalyze' && isset($_POST['gameid']) && $_SESSION['logged-in']->_id > 0){
			$myxp = GetExperienceForUserComplete($_SESSION['logged-in']->_id, $_POST['gameid']);
			$game = GetGame($_POST['gameid']);
			DisplayAnalyzeTab($_SESSION['logged-in'], $myxp, $game);
		}
		if($_POST['action'] == 'AddBookmark' && isset($_POST['gameid']) && $_SESSION['logged-in']->_id > 0){
			SubmitBookmark($_SESSION['logged-in']->_id,$_POST['gameid'],"Yes");
		}
		if($_POST['action'] == 'RemoveBookmark' && isset($_POST['gameid']) && $_SESSION['logged-in']->_id > 0){
			SubmitBookmark($_SESSION['logged-in']->_id,$_POST['gameid'],"No");
		}
	}
	function UserServices(){
		if($_POST['action'] == 'FollowUser' && isset($_POST['followid']) && $_SESSION['logged-in']->_id > 0){
			AddConnection($_SESSION['logged-in']->_id, $_POST['followid'], true);
		}
		if($_POST['action'] == 'UnfollowUser' && isset($_POST['followid']) && $_SESSION['logged-in']->_id > 0){
			RemoveConnection($_SESSION['logged-in']->_id, $_POST['followid']);
		}
		if($_POST['action'] == 'UserSettings'){
			DisplayUserSettings();
		}
		if($_POST['action'] == 'SaveUserSettings'){
			SaveAccountChanges($_POST['userid'], $_POST['username'], $_POST['password'], $_POST['first'], $_POST['last'], $_POST['email'], $_POST['birthyear'], $_POST['source'], $_POST['steam'], $_POST['psn'], $_POST['xbox'], $_POST['title'], $_POST['weburl'], $_POST['twitter'], $_POST['image']);
		}
		if($_POST['action'] == "SaveTitle"){
			SaveUserTitle($_POST['userid'], $_POST['title']);
		}
		if($_POST['action'] == "PromoCode"){
			ApplyPromoCode($_SESSION['logged-in']->_id, $_POST['promo']);
		}
		if($_POST['action'] == "AsyncMyBadges"){
			DisplayBadgeManagementForUser($_SESSION['logged-in']->_id);
		}
		if($_POST['action'] == "DismissUser"){
			IgnoreUser($_POST['dismiss']);
		}
	}
	function DiscoverServices(){
		if($_POST['action'] == 'Search' && isset($_POST['search'])){
			DisplaySearchResults($_POST['search']);
		}
		if($_POST['action'] == 'AdvancedSearch'){
			DisplayAdvancedSearchResults($_POST['search'], $_POST['platform'], $_POST['year'], $_POST['publisher'], $_POST['developer'], $_POST['genre'], $_POST['franchise']);
		}
		if($_POST['action'] == 'CustomCategory'){
			DisplayCustomQuery($_POST['categoryid']);
		}
		if($_POST['action'] == 'DisplayDiscoverHome'){
			DisplayDiscoverTab();
		}
		if($_POST['action'] == 'DisplayDiscoverSecondaryContent'){
			DisplayDiscoverSecondaryContent();
		}
		if($_POST['action'] == 'DisplayAdvancedSearch'){
			DisplayAdvancedSearch();
		}
		if($_POST['action'] == 'DisplayDiscoverCategory' && isset($_POST['category'])){
			DisplayDiscoverCategory($_POST['category'], $_POST['catid']);
		}
		if($_POST['action'] == 'DisplayWatchedXPEntry'){
			DisplayWatchedXPEntryAjax($_POST['url'], $_POST['gameid']);
		}
		if($_POST['action'] == 'DisplayInviteUsers'){
			InviteUsers($_SESSION['logged-in']->_id);
		}
		if($_POST['action'] == 'SubmitInviteUsers'){
			SubmitInviteUsers($_SESSION['logged-in']->_id, $_POST['emails'], $_POST['message']);
		}
	}
	function SignupServices(){
		if($_POST['action'] == 'VerifyNewUser' && isset($_POST['username']) && isset($_POST['email'])){
			VerifyUniqueUsername($_POST['username']);
			VerifyUniqueEmail($_POST['email']);
		}
		if($_POST['action'] == "Signup" && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])){
			RegisterUser($_POST['username'], $_POST['password'], $_POST['first'], $_POST['last'], $_POST['email'],"Public");
		}
	}
	function LoginServices(){
		if($_POST['action'] == 'Login' && isset($_POST['user']) && isset($_POST['pw']))
			Login($_POST['user'], $_POST['pw']);	
		
		if($_POST['action'] == 'Logout')
			Logout();
			
		if($_POST['action'] == 'ForgotPassword' && isset($_POST['email']))
			SendForgotPasswordEmail($_POST['email']);
			
		if($_POST['action'] == 'ResetForgottenPassword' && isset($_POST['password']) && isset($_POST['key']))
			SubmitPWReset($_POST['key'], $_POST['password']);
		
		if($_POST['action'] == 'ShowLanding'){
			ShowLanding();
		}
		if($_POST['action'] == 'ThirdPartyLogin'){
			RegisterThirdPartyUser($_POST['username'], $_POST['email'], $_POST['first'], $_POST['last'], $_POST['image'], $_POST['thirdpartyID'], $_POST['whoAmI']);
		}
		if($_POST['action'] == 'FinishRegister'){
			FinishRegisterUser($_SESSION['pending-user']->_id, $_POST['email'], $_POST['username']);
		}
	}
	function GeneralServices(){
		if($_POST['action'] == 'TestScript' ){
			if($_SESSION['logged-in']->_security == "Admin"){
				TestScript($_POST["script"]);
			}
		}
		if($_POST['action'] == 'DisplayShareModal'){
			if(isset($_POST['userid']))
				$id = $_POST['userid'];
			else
				$id = $_SESSION['logged-in']->_id;
				
			DisplayShareContent($id, $_POST['type'], $_POST['otherid']);
		}
	}
?>
