<?php

namespace application\controller;

require_once("application/view/View.php");
require_once("login/controller/LoginController.php");
require_once("registration/controller/RegistrationController.php");
require_once("registration/view/RegistrationView.php");


class Application {
	private $view;

	private $loginController;
	private $registrationController;

	public function __construct() {
		$loginView = new \login\view\LoginView();
		$registrationView = new \registration\view\RegistrationView();
		$this->registrationController = new \registration\controller\RegistrationController($registrationView);
		$this->loginController = new \login\controller\LoginController($loginView);
		$this->view = new \application\view\View($loginView, $registrationView);
	}
	
	public function doFrontPage() {
		$this->loginController->doToggleLogin();

		if ($this->loginController->isLoggedIn()) {
			$loggedInUserCredentials = $this->loginController->getLoggedInUser();
			return $this->view->getLoggedInPage($loggedInUserCredentials);	
		} else {
			
			if ($this->registrationController->doRegistration()) {
				return $this->view->getRegistrationPage();
			} else {
				return $this->view->getLoggedOutPage();
			}
		}

	}
}
