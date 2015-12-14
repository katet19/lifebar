<?php
//Models
require_once 'model_game.php';
require_once 'model_milestones.php';
require_once 'model_user.php';
require_once 'model_experience.php';
require_once 'model_event.php';
require_once 'model_category.php';
require_once 'model_notifications.php';
require_once 'model_weave.php';

$GLOBALS["AllowNewUsers"] = false; 
$GLOBALS["DownForMaintenance"] = false; 
session_save_path('temp/');
//error_reporting(E_ERROR | E_PARSE);
ini_set('session.gc_maxlifetime', 60*60); // 1 hour
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);
ini_set('session.cookie_secure', FALSE);
ini_set('session.use_only_cookies', TRUE);
session_start();
require_once 'webService.php';

require_once 'controller_database.php';
require_once 'controller_gravatar.php';
require_once 'controller_weave.php';

require_once 'controller_login.php';
require_once 'library/Hybrid/Auth.php';
require_once 'library/Hybrid/Endpoint.php';
//GoogleLogin();
if(!isset($_SESSION["logged-in"]) && isset($_COOKIE["RememberMe"])){
	LoginWithCookie($_COOKIE["RememberMe"]);
}

//Controllers
require_once 'controller_admin.php';
require_once 'controller_agree.php';
require_once 'controller_email.php';
require_once 'controller_event.php';
require_once 'controller_feedbackLoop.php';
require_once 'controller_experience.php';
require_once 'controller_game.php';
require_once 'controller_milestones.php';
require_once 'controller_giantbomb.php';
require_once 'controller_notifications.php';
require_once 'controller_profile.php';
require_once 'controller_user.php';
//Views
require_once 'view_admin.php';
require_once 'view_activity.php';
require_once 'view_milestones.php';
require_once 'view_discover.php';
require_once 'view_game.php';
require_once 'view_graphs.php';
require_once 'view_landing.php';
require_once 'view_login.php';
require_once 'view_navigation.php';
require_once 'view_notifications.php';
require_once 'view_thirdparty.php';
require_once 'view_settings.php';
require_once 'view_user.php';
require_once 'view_xp.php';
require_once 'view_profile.php';
?>
