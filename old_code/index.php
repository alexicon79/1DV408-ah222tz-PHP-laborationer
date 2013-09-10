<?php
/**
 * Laboration 1 - Uppgift 3
 * TODO: Implementera egen inloggningsmodul
 */

require_once("src/model/User.php");
require_once("src/model/LocalTime.php");

require_once("src/view/HTMLPageTemplate.php");
require_once("src/view/LoginForm.php");

session_start();


// hämtar lokal tid
$time = new \model\LocalTime();
$localTime = $time::getLocalTime();


// //testar cookies
// if (isset($_COOKIE["user"])){
// 	$cookieValue = $_COOKIE["user"];
// } else {
// 	setcookie("user", "yyy", time() + 60);
// 	$cookieValue = "test";
// }


$pageTemplate = new \view\HTMLPageTemplate();
$loginForm = new \view\LoginForm();

$loginFormHTML = $loginForm->getHTML();

$myUser = new \model\User();


if (isset ($_POST['submit'])) {

	if ( (!empty ($_POST["username"])) && (!empty ($_POST["password"])) ) {

		if ( ($_POST["username"] == "Admin") && ($_POST["password"] == "Password") ) {
			echo $pageTemplate->getPage("Login", "<h1>Du är inloggad</h1><p>Inloggning lyckades</p><a href='#'>Logga ut");
		}
		else {
			echo $pageTemplate->getPage("Login", "<h1>Inte inloggad</h1> $loginFormHTML <h3>$localTime</h3> $myUser->username");
		}

	}
} else {
	echo $pageTemplate->getPage("Login", "<h1>Logga in då</h1> $loginFormHTML <h3>$localTime</h3> $myUser->username");
}

// <?php
// session_start();  
// if(isset($_SESSION['views']))
//     $_SESSION['views'] = $_SESSION['views']+ 1;
// else
//     $_SESSION['views'] = 1;

// echo "views = ". $_SESSION['views']; 
// 

?>