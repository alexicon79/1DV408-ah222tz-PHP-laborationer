<?php

namespace controller;

require_once("controller/LoginController.php");
require_once("model/LoginModel.php");
require_once("view/LoginView.php");

class Application {

	private $loginModel;
	private $loginView;
	private $loginController;
	private $tempAccount;

	public function __construct() {
		$this->tempAccount = new \model\TempAccount();
		$this->loginModel = new  \model\LoginModel($this->tempAccount);
		$this->loginView = new \view\LoginView();
		$this->loginController = new \controller\LoginController($this->loginView, $this->loginModel);
	}

	public function invoke() {
		
		session_start();

		$html = $this->loginController->doLoginAttempt();

		return $html;

	}
}