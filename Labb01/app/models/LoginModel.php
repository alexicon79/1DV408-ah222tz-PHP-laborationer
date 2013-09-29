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
	 * @var constant string, valid username (read-only)
	 * @var constant string, valid password (read-only)
	 */
	const VALID_USER = "Admin";
	const VALID_PASSWORD = "Password";

	/**
	 * Finds out if a logged in user has been authenicated by 
	 * manually entering valid form input.
	 * 
	 * @return boolean
	 */
	public function isAuthenticatedByForm() {
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
	 * Finds out if a logged in user has been authenicated by a saved session.
	 * 
	 * @return boolean
	 */
	public function isAuthenticatedBySession() {

		if ( (!isset($_POST['submit'])) && isset($_SESSION['username']) ) {
			if($_SESSION['username'] == $this->getValidUser() ){
				return true;
			}
		} else {
			return false;
		}
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
			$validUserName = $this->getValidUser();
			$validPassWord = $this->getValidPassword();

			if (empty($password) && $userName == $validUserName) {
				throw	new \Exception("Lösenord saknas");
			}
			if (empty($userName) && $password == $validPassWord) {
				throw	new \Exception("Användarnamn saknas");
			}
			if (empty($userName) && empty($password)){
				throw	new \Exception("Användarnamn saknas");
			}
			if ($userName == $validUserName && $password != $validPassWord){
				throw	new \Exception("Felaktigt användarnamn och/eller lösenord");
			}
			if ($userName != $validUserName && $password != $validPassWord){
				throw	new \Exception("Felaktigt användarnamn och/eller lösenord");
			}
			if ($userName != $validUserName && $password == $validPassWord){
				throw	new \Exception("Felaktigt användarnamn och/eller lösenord");
			}
		}
	}

	/**
	 * Displays date and time in Swedish format
	 *
	 * @return string
	 */
	public function getLocalTime() {

		setlocale(LC_ALL, 'sv_SE');

		$localTimeString = strftime('%A, den %e %B &aring;r %G. <br />
												Klockan &auml;r [%H:%M:%S]' , time());
		
		$utf8encodedTimeString = ucfirst(utf8_encode($localTimeString));

		return $utf8encodedTimeString;
	}

	/**
	 * Returns valid user name
	 *
	 * @return string
	 */
	public function getValidUser() {
		return self::VALID_USER;
	}

	/**
	 * Returns valid password
	 *
	 * @return string
	 */
	public function getValidPassword() {
		return self::VALID_PASSWORD;
	}
}