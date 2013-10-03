<?php

namespace view;

/**
 * \view\LoginView
 */
class LoginView {

	/**
	 * Returns HTML for page header
	 * 
	 * @return string An HTML-string
	 */
	private function getHeader() {
		return 
		"<!DOCTYPE HTML>
		<html>
			<head>
				<title>1DV408 - Labb 01</title>
				<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
				<link rel='stylesheet' type='text/css' href='assets/css/style.css'>
			</head>";
	}

	/**
	 * Returns HTML for page footer
	 * 
	 * @return string 
	 */
	private function getFooter() {
		return 
		"</body>
		</html>";
	}

	/**
	 * Returns HTML for login form
	 * 
	 * @param  string $userName
	 * @param  string $message
	 * @return string
	 */
	private function getLoginForm($userName, $message) {
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
	 * @return string
	 */
	private function getPage($currentUserName, $confirmationMessage) {
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
	 * @return string
	 */
	private function getTime($timeString) {
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
			$_SESSION["hasLoggedOut"] = true;
			
			unset($_SESSION["username"]);

			setcookie("username", "", time() - 600);
			setcookie("password", "", time() - 600);

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
		if ( isset($_SESSION["hasLoggedOut"]) ) {
			unset($_SESSION["hasLoggedOut"]);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Returns HTML for entire page when user is logged in
	 * 
	 * @param  string $userName
	 * @param  string $message
	 * @param  string $time
	 * @return string
	 */
	public function loggedInHTML($userName, $message, $time) {
		$loggedInHTML = 	self::getHeader() .
								self::getPage($userName, $message) . 
								self::getTime($time) . 
								self::getFooter();
		return $loggedInHTML;
	}

	/**
	 * Returns HTML for entire page when user is logged out
	 * 
	 * @param  string $name
	 * @param  string $message
	 * @param  string $time
	 * @return string
	 */
	public function loggedOutHTML($name, $message, $time) {

		$loggedOutHTML = 	self::getHeader() . 
								self::getLoginForm($name, $message) . 
								self::getTime($time) . 
								self::getFooter();
		return $loggedOutHTML;
	}

}