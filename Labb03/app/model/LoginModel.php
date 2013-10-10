<?php

namespace model;

class LoginModel {

	/**
	 * @var \model\TempAccount
	 */
	private $tempAccount;

	/**
	 * @var string , Valid user name
	 */
	private $validUser;

	/**
	 * @var string, Valid password
	 */
	private $validPassword;

	public function __construct(\model\TempAccount $tempAccount) {
		$this->tempAccount = $tempAccount;	
		$this->validUser = $this->tempAccount->getValidUser();
		$this->validPassword = $this->tempAccount->getValidPassword();
	}

	/**
	 * Validates user input provided in login form
	 * @throws Exception , if invalid input
	 */

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

	/**
	 * Saves session
	 * @return boolean
	 */
	public function saveSession() {
		$_SESSION["username"] = $this->validUser;
		$_SESSION["password"] = $this->validPassword;
		$_SESSION["agent"] = $_SERVER['HTTP_USER_AGENT'];
		return true;
	}

	/**
	 * Clears session
	 * @return boolean
	 */
	public function clearSession() {
		unset($_SESSION["username"]);
		unset($_SESSION["agent"]);
		unset($_SESSION["password"]);
		return true;
	}
}