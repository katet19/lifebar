<?php
//Models
require_once 'php/model_game.php';
require_once 'php/model_milestones.php';
require_once 'php/model_user.php';
require_once 'php/model_experience.php';
require_once 'php/model_event.php';
require_once 'php/model_category.php';
require_once 'php/model_notifications.php';
require_once 'php/model_weave.php';

$GLOBALS["AllowNewUsers"] = false; 
$GLOBALS["DownForMaintenance"] = false; 
session_save_path('php/temp/');
//error_reporting(E_ERROR | E_PARSE);
ini_set('session.gc_maxlifetime', 60*60); // 1 hour
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);
ini_set('session.cookie_secure', FALSE);
ini_set('session.use_only_cookies', TRUE);
session_start();
require_once 'php/webService.php';
require_once 'php/controller_database.php';
require_once 'php/controller_gravatar.php';
require_once 'php/controller_weave.php';

require_once 'php/controller_login.php';
require_once 'php/library/Hybrid/Auth.php';
require_once 'php/library/Hybrid/Endpoint.php';
//GoogleLogin();
if(!isset($_SESSION['logged-in']) && isset($_COOKIE["RememberMe"])){
	LoginWithCookie($_COOKIE["RememberMe"]);
}

//Controllers
require_once 'php/controller_admin.php';
require_once 'php/controller_agree.php';
require_once 'php/controller_email.php';
require_once 'php/controller_event.php';
require_once 'php/controller_feedbackLoop.php';
require_once 'php/controller_experience.php';
require_once 'php/controller_game.php';
require_once 'php/controller_milestones.php';
require_once 'php/controller_giantbomb.php';
require_once 'php/controller_notifications.php';
require_once 'php/controller_profile.php';
require_once 'php/controller_user.php';
//Views
require_once 'php/view_admin.php';
require_once 'php/view_activity.php';
require_once 'php/view_milestones.php';
require_once 'php/view_discover.php';
require_once 'php/view_game.php';
require_once 'php/view_graphs.php';
require_once 'php/view_landing.php';
require_once 'php/view_login.php';
require_once 'php/view_navigation.php';
require_once 'php/view_notifications.php';
require_once 'php/view_thirdparty.php';
require_once 'php/view_settings.php';
require_once 'php/view_user.php';
require_once 'php/view_xp.php';
require_once 'php/view_profile.php';
?>
