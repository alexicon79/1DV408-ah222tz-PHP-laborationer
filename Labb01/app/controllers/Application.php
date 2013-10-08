<?php

namespace controller;

require_once("controllers/LoginController.php");
require_once("models/LoginModel.php");
require_once("views/LoginView.php");

class Application {

	/** 
	 * @var \model\LoginModel
	 */
	private $loginModel;

	/**
	 * @var \view\LoginView
	 */
	private $loginView;

		/**
	 * @var \controller\LoginController
	 */
	private $loginController;


	public function __construct() {
		$this->loginModel = new  \model\LoginModel2();
		$this->loginView = new \view\LoginView2();
		$this->loginController = new \controller\LoginController();
	}

	/**
	 * Runs application controller
	 */
	public function invoke(){
		
		session_start();
		
		if ($this->loginView->userIsLoggedOut()){ 
			
			$html = $this->loginController->letUserLogIn();

			return $html;

		} elseif ($this-loginView->{
			$html = $this->loginController->user
		}


	}
}