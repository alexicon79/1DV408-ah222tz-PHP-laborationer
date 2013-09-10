<?php

namespace model;

class LoginModel {

	/**
	 * temporary authentication
	 * @var constant valid username
	 * @var string valid password
	 */
	const VALID_USER = "Admin";
	const VALID_PASSWORD = "Password";

	/**
	 * @return boolean
	 */
	public function isAuthenticatedByForm() {
		if (isset ($_POST['submit'])) {
			// if ( (!empty ($_POST["username"])) && (!empty ($_POST["password"])) ){
				if ( ($_POST["username"] == $this->getValidUser() ) && ($_POST["password"] == $this->getValidPassword() ) ) {
				return true;
			
			} else {
				return false;
			}
		}
	}

	public function isAuthenticatedBySession() {

		if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
			if($_SESSION['username'] == $this->getValidUser() ){
				return true;
			}
		} else {
			return false;
		}
	}

	/**
	 * @return boolean
	 */
	public function isAuthenticatedByCookie() {
		return false;
	}

	public function formValidation() {

		if (isset ($_POST['submit'])) {
			if ( (empty ($_POST["password"])) && (!empty ($_POST["username"])) && ( ($_POST["username"]) == $this->getValidUser() ) ){
				return "NoPassword";
				// $displayUserName = "Admin";
				// $errorMsg = "Lösenord saknas";
			}

			if ( (!empty ($_POST["password"])) && ( ($_POST["password"]) == $this->getValidPassword() ) ){
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
				return "WrongPassword";
				// $displayUserName = "Admin";
				// $errorMsg = "Felaktigt användarnamn och/eller lösenord";
			}

			if ( ($_POST["username"] != $this->getValidUser() ) && ($_POST["password"] != $this->getValidPassword() ) ){
				return "WrongUser_WrongPassword";
				// $displayUserName = $_POST["username"];
				// $errorMsg = "Felaktigt användarnamn och/eller lösenord";
			}
		}
	}

	public function getLocalTime() {

		setlocale(LC_ALL, 'sv_SE');
	
		$localTimeString = strftime('%A, den %e %B år %G. Klockan är [%H:%M:%S]' , time());
	
		return $localTimeString;
	}

	public function getValidUser() {
		return self::VALID_USER;
	}

	public function getValidPassword() {
		return self::VALID_PASSWORD;
	}
}