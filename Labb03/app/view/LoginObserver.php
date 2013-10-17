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
	 * 
	 * @var \model\LoginModel
	 */
	private $loginModel;

	/**
	 * @var string , path to file that stores valid expiration time for cookies
	 */
	private static $expirationFile = "endtime.txt";

	
	const FORM_AUTHENTICATION = 0;
	const SESSION_AUTHENTICATION = 1;
	const COOKIE_AUTHENTICATION = 2;
	const DEFAULT_AUTHENTICATION = 3;

	protected static $SUBMIT = "submit";
	protected static $USERNAME = "username";
	protected static $PASSWORD = "password";
	protected static $COOKIE = "cookie";
	protected static $LOGOUT = "logout";


	public function __construct() {
		$this->tempAccount = new \model\TempAccount();
		$this->loginModel = new \model\LoginModel($this->tempAccount);
	}

	/**
	 * Checks if login form has been posted/submitted
	 * @return boolean
	 */
	public function formHasBeenSubmitted() {
		if (isset($_POST[self::$SUBMIT])) {
			return true;
		} return false;
	}

	/**
	 * Checks if user is authenticated
	 * @return boolean
	 */
	public function UserIsAuthenticated(){
		if ($this->AuthenticationMode() == self::FORM_AUTHENTICATION ||
			$this->AuthenticationMode() == self::SESSION_AUTHENTICATION ||
			$this->AuthenticationMode() == self::COOKIE_AUTHENTICATION) {
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
		if (isset ($_POST[self::$SUBMIT])) {

			if ($_POST[self::$USERNAME] == $this->tempAccount->getValidUser() && 
	 			$_POST[self::$PASSWORD] == $this->tempAccount->getValidPassword()){

	 			return self::FORM_AUTHENTICATION;
	 		}	 	}
	 	if ((!isset($_POST[self::$SUBMIT])) && isset($_SESSION[\model\LoginModel::$USERNAME])) {
			
			if	($_SESSION[\model\LoginModel::$USERNAME] == $this->tempAccount->getValidUser() && 
				($_SESSION[\model\LoginModel::$USER_AGENT] == $_SERVER['HTTP_USER_AGENT'])) {				
				
				return self::SESSION_AUTHENTICATION;
			}
		}

		if ((!isset($_POST[self::$SUBMIT])) && 
			(!isset($_SESSION[\model\LoginModel::$USERNAME])) && 
			$this->cookiesAreSet()) {

			if ($this->cookieIsManipulated()) {
				throw new \Exception("Felaktig information i cookie", 1);
			}
			if ($this->timeManipulation()) {
				throw new \Exception("Felaktig information i cookie", 1);
			}
			return self::COOKIE_AUTHENTICATION;
		}

		else {
			return self::DEFAULT_AUTHENTICATION;
		}
	}
	
	/**
	 * Checks if user wants to manually log out
	 * @return boolean
	 */
	public function userWantsToLogOut() {
			if (isset($_GET[self::$LOGOUT])) {
			return true;
		}
		return false;
	}

	/**
	 * Checks if user wants to stay logged in
	 * @return boolean
	 */
	public function userWantsToStayLoggedIn() {
		if ((isset($_POST[self::$SUBMIT])) && (isset($_POST[self::$COOKIE])) ) {
			return true;
		} 
		return false;
	}

	/**
	 * Gets username provided by user/client
	 * @return string
	 */
	public function getClientUserName() {
		if (isset($_POST[self::$SUBMIT])) {
			$username = $_POST[self::$USERNAME];
			return $username;
		}
	}

	/**
	 * Gets password provided by user/client
	 * @return string
	 */
	public function getClientPassword() {
		if (isset($_POST[self::$SUBMIT])) {
			$password = $_POST[self::$PASSWORD];
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

		setcookie(self::$USERNAME, $this->tempAccount->getValidUser(), $this->cookieEndTime);
		setcookie(self::$PASSWORD, $this->tempAccount->getPasswordHash(), $this->cookieEndTime);
	}
	
	/**
	 * Clears cookies
	 * @return boolean
	 */
	public function clearCookie() {
		// setcookie("username", "", time() -3600);
		// setcookie("password", "", time() -3600);
		 
		setcookie(self::$USERNAME, FALSE, 1);
		setcookie(self::$PASSWORD, FALSE, 1);
		return true;
	}

	/**
	 * Checks if cookies are set
	 * @return boolean
	 */
	public function cookiesAreSet() {
		if (isset($_COOKIE[self::$USERNAME]) && isset($_COOKIE[self::$PASSWORD])) {
			return true;
		}
		return false;
	}

	/**
	 * Checks if cookie password has been manipulated
	 * @return boolean
	 */
	public function cookieIsManipulated() {
		
		if ($_COOKIE[self::$PASSWORD] != $this->tempAccount->getPasswordHash()) {
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