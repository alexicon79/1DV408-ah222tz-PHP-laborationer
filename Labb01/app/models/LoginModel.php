<?php

namespace model;

/**
 * \model\LoginModel 
 *
 * @todo Add functionality to allow more than one valid user/password
 */
class LoginModel {

	/**
	 * Temporary authentication
	 * 
	 * @var string $validUser, Valid username (read-only)
	 * @var string $validPassword, Valid password (read-only)
	 * @var string $expirationFile, File for storing valid cookie expiration time
	 * @var int $cookieEndTime, Expiration time for cookies
	 */
	private static $validUser = "Admin";
	private static $validPassword = "Password";
	private static $expirationFile = "endtime.txt"; 
	private $cookieEndTime;

	/**
	 * Checks if a logged in user has been authenticated by 
	 * manually entering valid form input.
	 * 
	 * @return boolean
	 */
	public function userIsAuthenticatedByForm() {
		if (isset ($_POST['submit'])) {

			if ( 	($_POST["username"] == $this->getValidUser()) && 
				($_POST["password"] == $this->getValidPassword()) 	){
				
				return true;

			} else {
				return false;
			}
		}
	}

	/**
	 * Checks if a logged in user has been authenticated by a saved session.
	 * 
	 * @return boolean
	 */
	public function userIsAuthenticatedBySession() {

		if ( (!isset($_POST['submit'])) && isset($_SESSION['username']) ) {
			if( ($_SESSION['username'] == $this->getValidUser()) && 
				($_SESSION['agent'] == $_SERVER['HTTP_USER_AGENT']) ){
				
				return true;
			
			}
		} else {
			return false;
		}
		return false;
	}

	/**
	 * Checks if a logged in user has been authenticated by cookies.
	 * 
	 * @return boolean
	 */
	public function userIsAuthenticatedByCookie() {

		if((!isset($_POST['submit'])) && 
			(!isset($_SESSION['username'])) &&
			  isset($_COOKIE['username']) && 
			  isset($_COOKIE['password']) &&
			  $_COOKIE['password'] === $this->getPasswordHash()){

			return true;

		} else {
			return false;
		}
	}

	/**
	 * Checks if cookie has been manipulated by client, either by changing 
	 * the encrypted password value or by changing expiration time.
	 * 
	 * @var string $cookieExpiration , Valid cookie expiration time
	 * @return boolean
	 */

	public function isCookieInvalid() {
		if ( 	(isset($_COOKIE["password"])) || (isset($_COOKIE["admin"])) ) {

			$cookieExpiration = file_get_contents(self::$expirationFile);
			
			if ($_COOKIE["password"] != $this->getPasswordHash()) {
				$this->clearCookie();
				return true;
			}

			if ( time() > $cookieExpiration ) {
				$this->clearCookie();
				return true;
			} else {
				return false;
			}
		}
	}

	/**
	 * Checks if session belongs to current user agent / browser.
	 *
	 * @return boolean
	 */
	public function isSessionHijacked() {
		
		if ((isset($_SESSION["agent"])) && 
			($_SESSION["agent"] != $_SERVER['HTTP_USER_AGENT'])) {
			
			unset($_SESSION["username"]);
			unset($_SESSION["agent"]);
			
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Stores user name and encrypted password to cookie, and saves a valid 
	 * expiration time in separate file.
	 *
	 * @return boolean
	 */
	public function setCookie() {
		if ( (isset($_POST['submit'])) && (isset($_POST['cookie'])) ) {

			$this->cookieEndTime = time() + 60;

			// save endTime to file
			file_put_contents(self::$expirationFile, "$this->cookieEndTime");

			setcookie("username", $this->getValidUser(), $this->cookieEndTime);
			setcookie("password", $this->getPasswordHash(), $this->cookieEndTime);

			return true;

		} else {

			return false;

		}
	}

	public function clearCookie() {
		setcookie("username", "", time() - 600);
		setcookie("password", "", time() - 600);
		return true;
	}

	public function saveSession() {
		$_SESSION["username"] = $this->getValidUser();
		$_SESSION["password"] = $this->getValidPassword();
		$_SESSION["agent"] = $_SERVER['HTTP_USER_AGENT'];
		return true;
	}

	/**
	 * Validates login form user input
	 *
	 * @throws \Exception if username and/or password is incorrect
	 */
	public function formValidation() {

		if (isset($_POST['submit'])) {

			$password = $_POST["password"];
			$userName = $_POST["username"];
			$correctUserName = $this->getValidUser();
			$correctPassWord = $this->getValidPassword();

			if (empty($password) && $userName == $correctUserName) {
				throw	new \Exception("Lösenord saknas");
			}
			if (empty($userName) && $password == $correctPassWord) {
				throw	new \Exception("Användarnamn saknas");
			}
			if (empty($userName) && empty($password)){
				throw	new \Exception("Användarnamn saknas");
			}
			if ($userName == $correctUserName && $password != $correctPassWord){
				throw	new \Exception("Felaktigt användarnamn och/eller lösenord");
			}
			if ($userName != $correctUserName && $password != $correctPassWord){
				throw	new \Exception("Felaktigt användarnamn och/eller lösenord");
			}
			if ($userName != $correctUserName && $password == $correctPassWord){
				throw	new \Exception("Felaktigt användarnamn och/eller lösenord");
			}
		}
	}

	// /**
	//  * Displays date and time in Swedish format
	//  *
	//  * @return string
	//  */
	// public function getLocalTime() {

	// 	setlocale(LC_ALL, 'sv_SE');

	// 	$localTimeString = strftime('%A, den %e %B &aring;r %G. <br />
	// 		Klockan &auml;r [%H:%M:%S]' , time());
		
	// 	$utf8encodedTimeString = ucfirst(utf8_encode($localTimeString));

	// 	return $utf8encodedTimeString;
	// }

	/**
	 * Returns valid user name
	 *
	 * @return string
	 */
	public function getValidUser() {
		return self::$validUser;
	}

	/**
	 * Returns valid password
	 *
	 * @return string
	 */
	public function getValidPassword() {
		return self::$validPassword;
	}

	/**
	 * Returns encrypted password
	 *
	 * @return string
	 */
	public function getPasswordHash() {
		return sha1($this->getValidPassword());
	}
}