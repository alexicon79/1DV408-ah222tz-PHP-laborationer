<?php

namespace view;

class LoginObserver {

	/**
	 * @var \model\TempAccount
	 */
	private $tempAccount;

	/**
	 * @var timestamp
	 */
	private $cookieEndTime;

	/**
	 * @var string , path to file that stores valid expiration time for cookies
	 */
	private static $expirationFile = "endtime.txt";

	public function __construct() {
		$this->tempAccount = new \model\TempAccount();
	}

	/**
	 * Checks if login form has been posted/submitted
	 * @return boolean
	 */
	public function formHasBeenSubmitted() {
		if (isset($_POST['submit'])) {
			return true;
		} return false;
	}

	/**
	 * Checks if user is authenticated
	 * @return boolean
	 */
	public function UserIsAuthenticated(){
		if ($this->AuthenticationMode() == "form" ||
			$this->AuthenticationMode() == "session" ||
			$this->AuthenticationMode() == "cookie") {
			return true;
		}
		return false;
	}

	/**
	 * Checks mode of authentication
	 * @return string , "form" if authenticated by form
	 * @return string , "session" if authenticated by session
	 * @return string , "cookie" if authenticated by cookie
	 * @return string , "default" if none above
	 * @throws Exception , if cookie has been manipulated
	 */
	public function AuthenticationMode() {
		if (isset ($_POST['submit'])) {

			if ($_POST["username"] == $this->tempAccount->getValidUser() && 
	 			$_POST["password"] == $this->tempAccount->getValidPassword()){

	 			return "form";
	 		}
	 	}

	 	if ((!isset($_POST['submit'])) && isset($_SESSION['username'])) {
			
			if	($_SESSION['username'] == $this->tempAccount->getValidUser() && 
				($_SESSION['agent'] == $_SERVER['HTTP_USER_AGENT'])) {				
				
				return "session";
			}
		}

		if ((!isset($_POST['submit'])) && 
			(!isset($_SESSION['username'])) && 
			$this->cookiesAreSet()) {

			if ($this->cookieIsManipulated()) {
				throw new \Exception("Felaktig information i cookie", 1);
			}
			if ($this->timeManipulation()) {
				throw new \Exception("Felaktig information i cookie", 1);
			}
			return "cookie";
		}

		else {
			return "default";
		}
	}
	
	/**
	 * Checks if user wants to manually log out
	 * @return boolean
	 */
	public function userWantsToLogOut() {
			if (isset($_GET['logout'])) {
			return true;
		}
		return false;
	}

	/**
	 * Checks if user wants to stay logged in
	 * @return boolean
	 */
	public function userWantsToStayLoggedIn() {
		if ((isset($_POST['submit'])) && (isset($_POST['cookie'])) ) {
			return true;
		} 
		return false;
	}

	/**
	 * Gets username provided by user/client
	 * @return string
	 */
	public function getClientUserName() {
		if (isset($_POST['submit'])) {
			$username = $_POST["username"];
			return $username;
		}
	}

	/**
	 * Gets password provided by user/client
	 * @return string
	 */
	public function getClientPassword() {
		if (isset($_POST['submit'])) {
			$password = $_POST["password"];
			return $password;
		}
	}

	/**
	 * Generates cookie with user name and hashed password
	 * @return null
	 */
	public function generateCookie() {

		$this->cookieEndTime = time() + 30;

		file_put_contents(self::$expirationFile, "$this->cookieEndTime");

		setcookie("username", $this->tempAccount->getValidUser(), $this->cookieEndTime);
		setcookie("password", $this->tempAccount->getPasswordHash(), $this->cookieEndTime);
	}
	
	/**
	 * Clears cookies
	 * @return boolean
	 */
	public function clearCookie() {
		// setcookie("username", "", time() -3600);
		// setcookie("password", "", time() -3600);
		 
		setcookie("username", FALSE, 1);
		setcookie("password", FALSE, 1);
		return true;
	}

	/**
	 * Checks if cookies are set
	 * @return boolean
	 */
	public function cookiesAreSet() {
		if (isset($_COOKIE["username"]) && isset($_COOKIE["password"])) {
			return true;
		}
		return false;
	}

	/**
	 * Checks if cookie password has been manipulated
	 * @return boolean
	 */
	public function cookieIsManipulated() {
		
		if ($_COOKIE["password"] != $this->tempAccount->getPasswordHash()) {
			return true;
		}
		return false;
	}
	
	/**
	 * Checks if cookie expiration time has been manipulated
	 * @return boolean
	 */
	public function timeManipulation() {

		$savedCookieEndTime = file_get_contents(self::$expirationFile);

		if (time() > $savedCookieEndTime){
			return true;
		}
		return false;
	}
}