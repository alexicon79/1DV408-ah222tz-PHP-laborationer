<?php

namespace controller;

require_once("models/LoginModel.php");
require_once("views/LoggedOutHTMLView.php");
require_once("views/LoggedInHTMLView.php");

class LoginController {
	
	public $loginModel;

	public function __construct() {
		$this->loginModel = new  \model\LoginModel();
	}

	public function invoke(){
		
		session_start();

		/**
		 * If user manually logs out -> set "wasRecentlyLoggedIn" as true, 
		 * redirect to default login view, and clear username from session.
		 */
		if (isset($_GET['logout'])){
			$_SESSION["wasRecentlyLoggedIn"] = true;
			header('Location: ./');
			unset($_SESSION["username"]);
			// setcookie("username", "", time() - 60);
			exit;
		}

		/**
		 * If user was recently logged in -> display logout confirmation message.
		 */
		if ( (!empty($_SESSION["wasRecentlyLoggedIn"]) ) && ($_SESSION["wasRecentlyLoggedIn"] == true) ) {
			$HTMLform = new \view\LoggedOutHTMLView();
			include_once("views/header.php");
			echo $HTMLform->getPage("", "Du har nu loggat ut");
			echo '<span id=\'date\'>' . $this->loginModel->getLocalTime() . '</span>';
			unset($_SESSION["wasRecentlyLoggedIn"]);
			exit;
		}

		/**
		 * If username/password is valid OR a valid session is active -> display "Logged In view" 
		 * with relevant confirmation message.
		 * Else -> validate user input and show relevant error message.
		 */
		if ($this->loginModel->isAuthenticatedByForm() || $this->loginModel->isAuthenticatedBySession()) {
			
			// setcookie("username", "Admin", time() + 3600);
			$_SESSION["username"] = $this->loginModel->getValidUser();
			$_SESSION["password"] = $this->loginModel->getValidPassword();

			$HTMLloggedIn = new \view\LoggedInHTMLView();
			
			$user = $this->loginModel->getValidUser();

			if ($this->loginModel->isAuthenticatedByForm()) {
				$confirmation = "Inloggning lyckades";
			} elseif ($this->loginModel->isAuthenticatedBySession()) {
				$confirmation = "";
			}
			include_once("views/header.php");
			echo $HTMLloggedIn->getPage($user, $confirmation);
			echo '<span id=\'date\'>' . $this->loginModel->getLocalTime() . '</span>';

			$firstLogin = $_SESSION["firstLogin"] = false;
		} 
		else {

			$errorMsg = "";
			$displayName = "";

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
				
				case 'InvalidPassword':
				$errorMsg = "Felaktigt användarnamn och/eller lösenord";
				$displayName = $this->loginModel->getValidUser();
				break;
				
				case 'InvalidUser_InvalidPassword':
				$errorMsg = "Felaktigt användarnamn och/eller lösenord";
				$displayName = $_POST["username"];
				break;

				case 'InvalidUser_ValidPassword':
				$errorMsg = "Felaktigt användarnamn och/eller lösenord";
				$displayName = $_POST["username"];
				break;
				
				default:
				$errorMsg = "";
				$displayName = "";
				break;
			}

			$HTMLform = new \view\LoggedOutHTMLView();

			include_once("views/header.php");
			echo $HTMLform->getPage($displayName, $errorMsg);
			echo '<span id=\'date\'>' . $this->loginModel->getLocalTime() . '</span>';
		}
		
	}
}