<?php

namespace controller;

require_once("models/LoginModel.php");
require_once("views/LoginView.php");

/**
 * \controller\LoginController
 */
class LoginController {
	
	/** 
	 * @var \model\LoginModel $loginModel - Instance of LoginModel
	 */
	private $loginModel;

	/**
	 * @var \view\LoginView $loginView - Instance of LoginView
	 */
	private $loginView;

	public function __construct() {
		$this->loginModel = new  \model\LoginModel();
		$this->loginView = new \view\LoginView();
	}

	/**
	 * Runs application controller
	 */
	public function invoke(){
		
		session_start();
		
		/**
		 * @var string $localTime , Current date and time in Swedish
		 */
		$localTime = $this->loginModel->getLocalTime();

		/**
		 * @var boolean , Indicates whether cookies have been set or not
		 */
		$cookiesAreSet = false;

		/**
		 * If user manually logs out, redirect to default login view.
		 */
		if ($this->loginView->userWantsToLogOut()) {
			header('Location: ./');
			exit;
		}

		/**
		 * If user has recently logged out, show login form 
		 * with relevant confirmation message.
		 */
		if ($this->loginView->userHasRecentlyLoggedOut()) {
			$name = "";
			$message = "Du har nu loggat ut";
			echo $this->loginView->loggedOutHTML($name, $message, $localTime);
			exit;
		}

		/**
		 * If username/password is valid, a valid session or valid cookies are 
		 * active, display "logged in-page" with relevant confirmation message.
		 */
		if ($this->loginModel->userIsAuthenticatedByForm() || 
			 $this->loginModel->userIsAuthenticatedBySession() ||
			 $this->loginModel->userIsAuthenticatedByCookie()) {

			/**
			 * @var string $userName , Valid username
			 */
			$userName = $this->loginModel->getValidUser();
			
			if ($this->loginModel->setCookie()) {
				$cookiesAreSet = true;
			}
			
			if ($this->loginModel->isSessionHijacked()) {
				exit;
			};

			if ($this->loginModel->isCookieInvalid()) {
				exit;
			}

			/**
			 * If login is succesful, show relevant confirmation message depending
			 * on method of authentication.
			 */
			if ($this->loginModel->userIsAuthenticatedByForm()) {
				if ($cookiesAreSet) {
					$message = "Inloggning lyckades och vi kommer ihåg 
									dig nästa gång";
				} else {
					$message = "Inloggning lyckades";
				}
			} 
			if ($this->loginModel->userIsAuthenticatedBySession()) {
				$message = "";
			} 
			if ($this->loginModel->userIsAuthenticatedByCookie()) {
				$message = "Inloggning lyckades via cookies";
			}
			
			/**
			 * Save valid username and password to Session
			 */
			$this->loginModel->saveSession();

			/**
			 * Display "logged in-page" with relevant confirmation message and
			 * current date and time.
			 */
			echo $this->loginView->loggedInHTML($userName, $message, $localTime);

		} else {
			$message = "";
			$name = "";
			
			/**
			 * Validate user input and show relevant error message
			 */
			try {
				$this->loginModel->formValidation();
			} catch (\Exception $e) {
				$message = $e->getMessage();
				$name = $_POST["username"];
			}

			/**
			 * If cookie has been manipulated, show error message
			 */
			if ($this->loginModel->isCookieInvalid()) {
				$message = "Felaktig information i cookie";
			}

			/**
			 * Display "logged out-page" with sign up form and relevant message
			 */
			echo $this->loginView->loggedOutHTML($name, $message, $localTime);
		
		}
	}
}