<?php

namespace view;

class LoginView {

	private $tempAccount;
	
	public function __construct() {
		$this->tempAccount = new\model\TempAccount();
	}

	public function renderForm($message, $username) {
		return "
		<div id='formWrapper'>
			<h1>Ej inloggad</h1>
			<p id='errorMsg'>$message</p>
			<form method='post' action='?welcome'>
				<label>Användarnamn</label>
				<input name='username' placeholder='Användarnamn' value='$username'>
				<label>Lösenord</label>
				<input name='password' type='password' placeholder='Lösenord'>
				<span id='checkbox'><input name='cookie' type='checkbox'>
				Håll mig inloggad</span>
				<input id='submit' name='submit' type='submit' value='Logga in'>
			</form>
		</div>";
	}

	public function renderProtectedPage($userName, $msg) {
		return "
		<div id='formWrapper'>
			<h1>$userName är inloggad</h1>
			<p>$msg</p>
			<p><a href='?logout'>Logga ut</a></p></div>";
	}


	public function isUserLoggedIn(){
		if ($this->isAuthenticatedByForm() || 
			$this->isAuthenticatedBySession() ||
			$this->isAuthenticatedByCookie()) {

			return true;
		}
		return false;
	}

	public function userWantsToLogOut() {
		if (isset($_GET['logout'])) {
			// $_SESSION["hasLoggedOut"] = true;
			
			// unset($_SESSION["username"]);

			// setcookie("username", "", time() - 600);
			// setcookie("password", "", time() - 600);

			return true;
		} else {
			return false;
		}
	}


	public function getConfirmationMessage() {

		if ($this->isAuthenticatedByForm()) {
			return "Inloggningen lyckades";
		}

		if ($this->isAuthenticatedBySession()) {
			return "Session";
		}

		if ($this->isAuthenticatedByCookie()) {
			return "Cookies";
		}

		if ($this->userWantsToLogOut()) {
			return "Du har nu loggat ut";
		}

	}

	public function isAuthenticatedByForm() {
		if (isset ($_POST['submit'])) {

			if ($_POST["username"] == $this->tempAccount->getValidUser() && 
	 			$_POST["password"] == $this->tempAccount->getValidPassword()){

	 			return true;
	 		} 
	 		return false;
		}
	}

	public function getClientUserName() {
		if (isset($_POST['submit'])) {
			$username = $_POST["username"];
			return $username;
		}
	}

	public function getClientPassword() {
		if (isset($_POST['submit'])) {
			$password = $_POST["password"];
			return $password;
		}
	}

	public function formHasBeenSubmitted() {
		if (isset($_POST['submit'])) {
			return true;
		} return false;
	}

	public function isAuthenticatedBySession() {
		if ((!isset($_POST['submit'])) && isset($_SESSION['username'])) {
			if( ($_SESSION['username'] == $this->tempAccount->getValidUser()) && 
				($_SESSION['agent'] == $_SERVER['HTTP_USER_AGENT']) ){				
				return true;
			}
		} return false;
	}

	public function isAuthenticatedByCookie() {
		return false;
	}

}