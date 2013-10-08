<?php

namespace controller;

require_once("view/LoginView.php");
require_once("model/TempAccount.php");


class LoginController {

	private $loginView;
	private $loginModel;
	private $tempAccount;
	private $message;
	private $clientUserName;
	private $clientPassword;

	public function __construct(\view\LoginView $loginView, 
								\model\LoginModel $loginModel) {
		$this->loginView = $loginView;
		$this->loginModel = $loginModel;
		$this->tempAccount = new\model\TempAccount();
	}

	public function doLoginAttempt() {

		if ($this->loginView->isUserLoggedIn()){

			$this->loginModel->saveSession();

			$this->message = $this->loginView->getConfirmationMessage();
			$html = $this->showProtectedPage($this->message);

			if ($this->loginView->userWantsToLogOut()) {
				$this->loginModel->clearSession();
				$this->message = $this->loginView->getConfirmationMessage();
				$html = $this->loginView->renderForm($this->message, $this->clientUserName);
			}
			return $html;
		} else {
			
			if ($this->loginView->formHasBeenSubmitted()){

				// validate
				try {
					$this->clientUserName = $this->loginView->getClientUserName();
					$this->clientPassword = $this->loginView->getClientPassword();
					$this->loginModel->validateForm($this->clientUserName, $this->clientPassword);
				} catch (\Exception $e) {
					$this->message = $e->getMessage();
				}
			}

			$loginFormHTML = $this->loginView->renderForm($this->message, $this->clientUserName);

			return $loginFormHTML;
		}
	}



	public function showProtectedPage($msg) {

		$userName = $this->tempAccount->getValidUser();

		$protectedPageHTML = $this->loginView->renderProtectedPage($userName, $msg);

		return $protectedPageHTML;
	}
}