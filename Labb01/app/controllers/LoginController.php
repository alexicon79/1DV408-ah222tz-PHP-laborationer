<?php

namespace controller;

require_once("models/LoginModel.php");
require_once("views/LoggedOutHTMLView.php");

class LoginController {
	
	public $loginModel;

	public function __construct() {
		$this->loginModel = new  \model\LoginModel();
	}

	public function invoke(){
		
		session_start();

		if (isset($_GET['logout'])){
			header('Location: ./');
			unset($_SESSION["username"]);
			// setcookie("username", "", time() - 60);
			exit;
		}

		// kontrollera om formulär är korrekt ifyllt eller om Session är aktiv
		if ($this->loginModel->isAuthenticatedByForm() || $this->loginModel->isAuthenticatedBySession()) {

			// setcookie("username", "Admin", time() + 3600);
			$_SESSION["username"] = $this->loginModel->getValidUser();
			$_SESSION["password"] = $this->loginModel->getValidPassword();

			include_once("views/header.php");
			include_once("views/LoggedInView.php");
			echo '<span id=\'date\'>' . $this->loginModel->getLocalTime() . '</span>';
		} else {

			// if ( (!empty ($_POST["username"])) && ( ($_POST["username"]) == $this->loginModel->getValidUser()) ){
			// 	$_POST["username"] == $this->loginModel->getValidUser();
			// }
			
			// validering
			// 
			$errorMsg = "";
			$displayName = "";

			include_once("views/header.php");
			$formValidation = $this->loginModel->formValidation();
			switch ($formValidation) {
				case 'NoPassword':
					$errorMsg = "Lösenord saknas";
					$displayName = $this->loginModel->getValidUser();
					break;
				case 'NoUser':
					$errorMsg = "Användarnamn saknas";
					$displayName = "";
					break;
				case 'NoUser_NoPassword':
					$errorMsg = "Användarnamn saknas";
					$displayName = "";
					break;
				case 'WrongPassword':
					$errorMsg = "Felaktigt användarnamn och/eller lösenord";
					$displayName = $this->loginModel->getValidUser();
					break;
				case 'WrongUser_WrongPassword':
					$errorMsg = "Felaktigt användarnamn och/eller lösenord";
					$displayName = $_POST["username"];
					break;
				
				default:
					$errorMsg = "";
					$displayName = "";
					break;
			}

			$HTMLform = new \view\LoggedOutHTMLView();
			// include_once("views/LoggedOutView.php");

			echo $HTMLform->getPage($displayName, $errorMsg);
			echo '<span id=\'date\'>' . $this->loginModel->getLocalTime() . '</span>';
		}
		


		// if ($this->loginModel->isSessionActive() == false) {
		// 	echo "<h1>Session hamnar h&auml;r</h1>";
		// 	echo $this->loginModel->getLocalTime();
		// }

		// echo "<p>Form: ";
		// var_dump($this->loginModel->isAuthenticatedByForm());
		// echo "</p><p>Session: ";
		// var_dump($this->loginModel->isAuthenticatedBySession());
		// echo "</p><p>Cookie: ";
		// var_dump($this->loginModel->isAuthenticatedByCookie());
		// echo "</p>";
	}
}