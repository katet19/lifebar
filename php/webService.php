<?php require_once 'includes.php';
	if(isset($_POST['action'])){
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
	}
	function AdminServices(){
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
		if($_POST['action'] == 'AdminGameSearch' && isset($_POST['search'])){
			DisplayAdminGameSearchResults($_POST['search']);
		}
		if($_POST['action'] == 'AdminBadgeGameSearch' && isset($_POST['search'])){
			DisplayAdminBadgeGameSearchResults($_POST['search']);
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
			UpdateGameFromGiantBombByID($_POST['gameid'], "Sorta");
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
		if($_POST['action'] == 'SaveAgreed' && isset($_POST['gameid']) && isset($_POST['expid']) && $_SESSION['logged-in']->_id > 0){
			SaveAgreed($_POST['gameid'], $_SESSION['logged-in']->_id, $_POST['agreedwith'], $_POST['expid']);
		}
		if($_POST['action'] == 'RemoveAgreed' && isset($_POST['gameid']) && isset($_POST['expid']) && $_SESSION['logged-in']->_id > 0){
			RemoveAgreed($_POST['gameid'], $_SESSION['logged-in']->_id, $_POST['agreedwith'], $_POST['expid']);
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
			SavePlayedXP($_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['quote'],$_POST['tier'],$_POST['completion'],$_POST['quarter'],$_POST['year'],$_POST['single'],$_POST['multi'],$_POST['platforms'],$_POST['dlc'],$_POST['alpha'],$_POST['beta'],$_POST['early'],$_POST['demo'],$_POST['stream']);
			SaveXP($_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['quote'],$_POST['tier'],$_POST['quarter'],$_POST['year'],$_POST['criticlink']);
			CalculateWeave($_SESSION['logged-in']->_id);
			CalculateMilestones($_SESSION['logged-in']->_id, $_POST['gameid'], '', 'Played XP', false);
			echo "|**|";
			DisplayMyXP($_POST['gameid']);
		}
		if($_POST['action'] =='SaveWatchedFull' && $_SESSION['logged-in']->_id > 0){
			SaveWatchedXP($_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['quote'],$_POST['tier'], $_POST['viewurl'], $_POST['viewsrc'], $_POST['viewing'],$_POST['quarter'],$_POST['year']);
			SaveXP($_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['quote'],$_POST['tier'],$_POST['quarter'],$_POST['year'],$_POST['criticlink']);
			CalculateWeave($_SESSION['logged-in']->_id);
			CalculateMilestones($_SESSION['logged-in']->_id, $_POST['gameid'], '', 'Watched XP', false);
			echo "|**|";
			DisplayMyXP($_POST['gameid']);
		}
		if($_POST['action'] =='SavePlayed' && $_SESSION['logged-in']->_id > 0){
			SavePlayedXP($_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['quote'],$_POST['tier'],$_POST['completion'],$_POST['quarter'],$_POST['year'],$_POST['single'],$_POST['multi'],$_POST['platforms'],$_POST['dlc'],$_POST['alpha'],$_POST['beta'],$_POST['early'],$_POST['demo'],$_POST['stream']);
			CalculateWeave($_SESSION['logged-in']->_id);
			CalculateMilestones($_SESSION['logged-in']->_id, $_POST['gameid'], '', 'Played XP', false);
			echo "|**|";
			DisplayMyXP($_POST['gameid']);
		}
		if($_POST['action'] =='SaveWatched' && $_SESSION['logged-in']->_id > 0){
			SaveWatchedXP($_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['quote'],$_POST['tier'], $_POST['viewurl'], $_POST['viewsrc'], $_POST['viewing'],$_POST['quarter'],$_POST['year']);
			CalculateWeave($_SESSION['logged-in']->_id);
			CalculateMilestones($_SESSION['logged-in']->_id, $_POST['gameid'], '', 'Watched XP', false);
			echo "|**|";
			DisplayMyXP($_POST['gameid']);
		}
		if($_POST['action'] =='UpdateWatched' && $_SESSION['logged-in']->_id > 0){
			UpdateWatchedXP($_POST['subxpid'],$_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['viewurl'], $_POST['viewsrc'], $_POST['viewing'],$_POST['quarter'],$_POST['year']);
			CalculateWeave($_SESSION['logged-in']->_id);
			CalculateMilestones($_SESSION['logged-in']->_id, $_POST['gameid'], '', 'Watched XP', false);
			echo "|**|";
			DisplayMyXP($_POST['gameid']);
		}
		if($_POST['action'] =='SaveTierQuote' && $_SESSION['logged-in']->_id > 0){
			UpdateXP($_SESSION['logged-in']->_id,$_POST['gameid'],$_POST['quote'],$_POST['tier'],$_POST['criticlink']);
			CalculateWeave($_SESSION['logged-in']->_id);
			CalculateMilestones($_SESSION['logged-in']->_id, $_POST['gameid'], '', 'XP', false);
			echo "|**|";
			DisplayMyXP($_POST['gameid']);
		}
		if($_POST['action'] == 'GetGameFAB' ){
			ShowMyGameFAB($_POST['gameid']);
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
	}
	function GameServices(){
		if($_POST['action'] == 'DisplayGame' && isset($_POST['gbid'])){
			DisplayGame($_POST['gbid']);
		}
		if($_POST['action'] == 'DisplayGameViaID' && isset($_POST['gameid'])){
			DisplayGameViaID($_POST['gameid']);
		}
		if($_POST['action'] == 'DisplayMyXP' && isset($_POST['gameid']) && $_SESSION['logged-in']->_id > 0){
			DisplayMyXP($_POST['gameid']);
		}
		if($_POST['action'] == 'AddBookmark' && isset($_POST['gameid']) && $_SESSION['logged-in']->_id > 0){
			SubmitBookmark($_SESSION['logged-in']->_id,$_POST['gameid'],"Yes");
		}
		if($_POST['action'] == 'RemoveBookmark' && isset($_POST['gameid']) && $_SESSION['logged-in']->_id > 0){
			SubmitBookmark($_SESSION['logged-in']->_id,$_POST['gameid'],"No");
		}
		if($_POST['action'] == 'AddOwned' && isset($_POST['gameid']) && $_SESSION['logged-in']->_id > 0){
			SubmitOwned($_SESSION['logged-in']->_id,$_POST['gameid'],"Yes");
		}
		if($_POST['action'] == 'RemoveOwned' && isset($_POST['gameid']) && $_SESSION['logged-in']->_id > 0){
			SubmitOwned($_SESSION['logged-in']->_id,$_POST['gameid'],"No");
		}
	}
	function UserServices(){
		if($_POST['action'] == 'FollowUser' && isset($_POST['followid']) && $_SESSION['logged-in']->_id > 0){
			AddConnection($_SESSION['logged-in']->_id, $_POST['followid']);
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
	}
	function SignupServices(){
		if($_POST['action'] == 'VerifyNewUser' && isset($_POST['username']) && isset($_POST['email'])){
			VerifyUniqueUsername($_POST['username']);
			VerifyUniqueEmail($_POST['email']);
		}
		if($_POST['action'] == "Signup" && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])){
			RegisterUser($_POST['username'], $_POST['password'], $_POST['first'], $_POST['last'], $_POST['email'], $_POST['birthyear']."-01-01","Public");
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
	}
?>
