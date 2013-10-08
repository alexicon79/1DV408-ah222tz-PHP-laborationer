<?php

namespace model;

class LoginModel2 {

	private $cookieEndTime;
	
	/**
	 * Stores user name and encrypted password to cookie, and saves a valid 
	 * expiration time in separate file.
	 *
	 * @return boolean
	 */
	public function setCookie() {

		$this->cookieEndTime = time() + 60;

		// save endTime to file
		file_put_contents(self::$expirationFile, "$this->cookieEndTime");

		setcookie("username", $this->getValidUser(), $this->cookieEndTime);
		setcookie("password", $this->getPasswordHash(), $this->cookieEndTime);
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

}