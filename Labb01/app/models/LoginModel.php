<?php

namespace model;

/**
 * Login Model Class 
 */
class LoginModel {

	/**
	 * Temporary authentication
	 * 
	 * @var constant String, valid username (read-only)
	 * @var constant String, valid password (read-only)
	 */
	const VALID_USER = "Admin";
	const VALID_PASSWORD = "Password";

	/**
	 * This function finds out if a logged in user has been authenicated by entering 
	 * valid form input / logging in manually
	 * 
	 * @return boolean
	 */
	public function isAuthenticatedByForm() {
		if (isset ($_POST['submit'])) {
			if ( ($_POST["username"] == $this->getValidUser() ) && ($_POST["password"] == $this->getValidPassword() ) ) {
				return true;			
			} else {
				return false;
			}
		}
	}

	/**
	 * This function finds out if a logged in user has been authenicated by a saved session
	 * 
	 * @return boolean
	 */
	public function isAuthenticatedBySession() {

		if ( (isset($_POST['submit']) == false) && isset($_SESSION['username']) && !empty($_SESSION['username']) ) {
			if($_SESSION['username'] == $this->getValidUser() ){
				return true;
			}
		} else {
			return false;
		}
	}

	/**
	 * This function validates login form user input
	 *
	 * @return String
	 */
	public function formValidation() {

		if (isset ($_POST['submit'])) {
			if ( (empty ($_POST["password"])) && (!empty ($_POST["username"])) && ( ($_POST["username"]) == $this->getValidUser() ) ){
				return "NoPassword";
				// $displayUserName = "Admin";
				// $errorMsg = "Lösenord saknas";
			}

			if ( (empty ($_POST["username"])) && (!empty ($_POST["password"])) && ( ($_POST["password"]) == $this->getValidPassword() ) ){
				return "NoUser";
				// $displayUserName = "";
				// $errorMsg = "Användarnamn saknas";
			}

			if ( (empty ($_POST["username"])) && (empty ($_POST["password"])) ){
				return "NoUser_NoPassword";
				// $displayUserName = "";
				// $errorMsg = "Användarnamn saknas";
			}
			
			if ( ($_POST["username"] == $this->getValidUser() ) && (!empty ($_POST["password"])) && $_POST["password"] != $this->getValidPassword() ){
				return "InvalidPassword";
				// $displayUserName = "Admin";
				// $errorMsg = "Felaktigt användarnamn och/eller lösenord";
			}

			if ( ($_POST["username"] != $this->getValidUser() ) && ($_POST["password"] != $this->getValidPassword() ) ){
				return "InvalidUser_InvalidPassword";
				// $displayUserName = $_POST["username"];
				// $errorMsg = "Felaktigt användarnamn och/eller lösenord";
			}
			
			if ( ($_POST["username"] != $this->getValidUser() ) && ($_POST["password"] == $this->getValidPassword() ) ){
				return "InvalidUser_ValidPassword";
				// $displayUserName = $_POST["username"];
				// $errorMsg = "Felaktigt användarnamn och/eller lösenord";
			}
		}
	}

	/**
	 * This function displays date and time in Swedish format
	 *
	 * @return string
	 */
	public function getLocalTime() {

		setlocale(LC_ALL, 'sv_SE');
		
		$localTimeString = strftime('%A, den %e %B år %G. <br />Klockan är [%H:%M:%S]' , time());
		
		return $localTimeString;
	}

	/**
	 * This function returns valid user name
	 *
	 * @return string
	 */
	public function getValidUser() {
		return self::VALID_USER;
	}

	/**
	 * This function returns valid password
	 *
	 * @return string
	 */
	public function getValidPassword() {
		return self::VALID_PASSWORD;
	}
}