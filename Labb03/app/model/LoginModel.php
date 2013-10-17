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

	public static $USERNAME = "LoginModel::UserName";
	public static $PASSWORD = "LoginModel::Password";
	public static $USER_AGENT = "LoginModel::UserAgent";


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
		$_SESSION[self::$USERNAME] = $this->validUser;
		$_SESSION[self::$PASSWORD] = $this->validPassword;
		$_SESSION[self::$USER_AGENT] = $_SERVER['HTTP_USER_AGENT'];
		return true;
	}

	/**
	 * Clears session
	 * @return boolean
	 */
	public function clearSession() {
		unset($_SESSION[self::$USERNAME]);
		unset($_SESSION[self::$PASSWORD]);
		unset($_SESSION[self::$USER_AGENT]);
		return true;
	}
}