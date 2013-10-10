<?php

namespace controller;

require_once("model/LoginModel.php");
require_once("view/LoginView.php");
require_once("controller/LoginController.php");
require_once("view/LoginObserver.php");
require_once("model/TempAccount.php");


class Application {

	/**
	 * @var \model\LoginModel
	 */
	private $loginModel;

	/**
	 * @var \view\LoginView
	 */
	private $loginView;

	/**
	 * @var \model\TempAccount
	 */
	private $tempAccount;

	/**
	 * @var \view\LoginObserver
	 */
	private $loginObserver;

	/**
	 * @var \controller\LoginController
	 */
	private $loginController;

	public function __construct() {
		$this->tempAccount = new \model\TempAccount();
		$this->loginModel = new  \model\LoginModel($this->tempAccount);
		$this->loginView = new \view\LoginView();
		$this->loginObserver = new \view\LoginObserver();
		$this->loginController = new \controller\LoginController($this->loginView, 
																 $this->loginModel, 
																 $this->tempAccount, 
																 $this->loginObserver);
	}

	/**
	 * Runs application, returns html
	 * @return string HTML
	 */
	public function invoke() {
		
		session_start();

		$html = $this->loginController->doLoginAttempt();

		return $html;

	}
}