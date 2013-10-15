<?php

namespace registration\controller;

require_once("./registration/model/RegistrationModel.php");
require_once("./registration/view/RegistrationView.php");


class RegistrationController {

	private $model;

	private $view;

	public function __construct($view) {
		$this->model = new \registration\model\RegistrationModel();;
		$this->view = $view;
	}

	public function doRegistration() {
		if ($this->view->userWantsToRegister()) {

			if ($this->view->isRegistering()) {
				
				try {
					$this->view->getUserCredentials();
					$this->model->doRegister($this->view->getUserName(), $this->view->getPassword());
					$this->model->setRegistrationSuccess();
					\Debug::log("Registration succeded");
					return false; // stop showing registration page
				} catch (\Exception $e) {
					\Debug::log("Registration failed", false, $e->getMessage());
					$this->view->registrationFailed($e->getCode());
				}
				return true;
			}
			return true;
		}
	}
}