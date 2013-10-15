<?php

namespace login\model;

require_once("UserCredentials.php");
require_once("UserList.php");
require_once("LoginInfo.php");


class LoginModel {
	private static $loggedInUser = "LoginModel::loggedInUser";
	
	private $allUsers;
	
	
	public function __construct() {
		assert(isset($_SESSION));
		
		$this->allUsers = new UserList();
	}
	
	public function doLogin($fromClient, $observer) {

		try {
			$validUser = $this->allUsers->findUser($fromClient);

			$validUser->newTemporaryPassword();

			$this->allUsers->update($validUser);

			$this->setLoggedIn($validUser, $observer);

			$observer->loginOK($validUser->getTemporaryPassword());
		} catch (\Exception $e) {
			\Debug::log("Login Failed", false, $e->getMessage());
			$observer->LoginFailed();
			throw $e;
		}
	}

	public function isLoggedIn() {
		if (isset($_SESSION[self::$loggedInUser])) {

			if ($_SESSION[self::$loggedInUser]->isSameSession()) {
				return true;
			}
		}
		
		return false;
	}
	
	public function getLoggedInUser() {
		return $_SESSION[self::$loggedInUser]->user;
	}
	

	public function doLogout() {
		unset($_SESSION[self::$loggedInUser]);
	}
	
	private function setLoggedIn($info) {
		$_SESSION[self::$loggedInUser] = new LoginInfo($info);
	}
}
