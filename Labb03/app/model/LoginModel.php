<?php

namespace model;

class LoginModel {

	private $tempAccount;
	private $validUser;
	private $validPassword;

	public function __construct(\model\TempAccount $tempAccount) {
		$this->tempAccount = $tempAccount;	
		$this->validUser = $this->tempAccount->getValidUser();
		$this->validPassword = $this->tempAccount->getValidPassword();
	}

	public function validateForm($clientUserName, $clientPassword) {

		if (!empty($clientUserName) && empty($clientPassword)) {
			throw new \Exception("Lösenord saknas");
		}
		if ((empty($clientUserName) && empty($clientPassword)) ||
			(empty($clientUserName) && !empty($clientPassword))) {
			throw new \Exception("Användarnamn saknas");
		}
		if (($clientUserName == $this->validUser && $clientPassword != $this->validPassword) ||
			($clientUserName != $this->validUser && $clientPassword == $this->validPassword) ||
			($clientUserName != $this->validUser && $clientPassword != $this->validPassword)) {
			throw new \Exception("Felaktigt användarnamn och/eller lösenord");
		}
	}

	public function saveSession() {
		$_SESSION["username"] = $this->tempAccount->getValidUser();
		$_SESSION["password"] = $this->tempAccount->getValidPassword();
		$_SESSION["agent"] = $_SERVER['HTTP_USER_AGENT'];
		return true;
	}

	public function clearSession() {
		unset($_SESSION["username"]);
		unset($_SESSION["agent"]);
		unset($_SESSION["password"]);
	}
}