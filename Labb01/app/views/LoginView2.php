<?php

namespace view;

class LoginView2 {

	/**
	 * Returns HTML for login form
	 * 
	 * @param  string $userName
	 * @param  string $message
	 * @return string HTML
	 */
	public function getLoginForm($userName, $message) {
		assert(strlen($userName) < 50);
		return 
		"<body>
		<div id='formWrapper'>
			<h1>Ej inloggad</h1>
			<p id='errorMsg'>$message</p>
			<form method='post' action='?'>
				<label>Användarnamn</label>
				<input name='username' placeholder='Användarnamn' value='$userName'>
				<label>Lösenord</label>
				<input name='password' type='password' placeholder='Lösenord'>
				<span id='checkbox'><input name='cookie' type='checkbox'>
				Håll mig inloggad</span>
				<input id='submit' name='submit' type='submit' value='Logga in'>
			</form>
		</div>";
	}

	/**
	 * Returns HTML for page body when logged in
	 * 
	 * @param  string $currentUserName 
	 * @param  string $confirmationMessage
	 * @return string HTML
	 */
	public function getProtectedPage($currentUserName, $confirmationMessage) {
		assert(!empty($currentUserName));
		return 
		"<body>
		<div id='formWrapper'>
			<h1>$currentUserName är inloggad</h1>
			<p>$confirmationMessage</p>
			<p><a href='?logout'>Logga ut</a></p></div>";
	}

	/**
	 * Returns HTML for displaying current date/time
	 * 
	 * @param  string $timeString Local date and time in swedish
	 * @return string HTML
	 */
	public function getTime($timeString) {
		assert(!empty($timeString));
		return "
		<span id='date'>" . $timeString . "</span>";
	}

	/**
	 *	Clears cookies, sets session variable "hasLoggedOut" to TRUE and 
	 *	unsets username from session if user logs out manually. 
	 * @return boolean
	 */
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

	public function userWantsToSaveCookie() {
		if ( (isset($_POST['submit'])) && (isset($_POST['cookie'])) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Unsets session variable "hasLoggedOut" if user recently logged out
	 * @return boolean
	 */
	public function userHasRecentlyLoggedOut() {
		if (isset($_SESSION["hasLoggedOut"])) {
			// unset($_SESSION["hasLoggedOut"]);
			return true;
		} else {
			return false;
		}
	}

}