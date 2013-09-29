<?php

namespace controller;

require_once("models/LoginModel.php");
require_once("views/LoginView.php");

/**
 * \controller\LoginController
 */
class LoginController {
	
	/** 
	 * @var \model\LoginModel $loginModel - Private instance of LoginModel
	 */
	private $loginModel;

	public function __construct() {
		$this->loginModel = new  \model\LoginModel();
	}

	/**
	 * Runs application
	 */
	public function invoke(){
		
		session_start();
		
		/**
		 * @var \view\LoginView $loginView
		 */
		$loginView = new \view\LoginView();

		/**
		 * @var string $currentTime - Current date and time in Swedish
		 */
		$currentTime = $this->loginModel->getLocalTime();

		/**
		 * If user manually logs out, set "hasLoggedOut" as true, 
		 * redirect to default login view and clear username from session.
		 */
		if (isset($_GET['logout'])) {
			$_SESSION["hasLoggedOut"] = true;
			header('Location: ./');
			unset($_SESSION["username"]);
			exit;
		}

		/**
		 * If user recently logged out, display logout confirmation message.
		 */
		if (isset($_SESSION["hasLoggedOut"])) {
			$displayName = "";
			$message = "Du har nu loggat ut";

			echo $loginView->userIsLoggedOut($displayName, $message, $currentTime);

			unset($_SESSION["hasLoggedOut"]);
			exit;
		}

		/**
		 * If username/password is valid OR a valid session is active,
		 * display "Logged In Page" with relevant confirmation message.
		 * Else -> validate user input and show relevant error message.
		 */
		if ($this->loginModel->isAuthenticatedByForm() || 
			 $this->loginModel->isAuthenticatedBySession()) {
			
			$_SESSION["username"] = $this->loginModel->getValidUser();
			$_SESSION["password"] = $this->loginModel->getValidPassword();
			
			$userName = $this->loginModel->getValidUser();

			if ($this->loginModel->isAuthenticatedByForm()) {
				$message = "Inloggning lyckades";
			} elseif ($this->loginModel->isAuthenticatedBySession()) {
				$message = "";
			}

			echo $loginView->userIsLoggedIn($userName, $message, $currentTime);

			unset($_SESSION["hasLoggedOut"]);	

		} else {
			$message = "";
			$displayName = "";
			
			try {
				$formValidation = $this->loginModel->formValidation();
			} catch (\Exception $e) {
				$message = $e->getMessage();
				$displayName = $_POST["username"];
			}

			echo $loginView->userIsLoggedOut($displayName, $message, $currentTime);
		
		}
	}
}