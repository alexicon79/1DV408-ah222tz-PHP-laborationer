<?php

namespace view;

require_once("view/LoginObserver.php");

class LoginView extends LoginObserver {

	/**
	 * @var \model\TempAccount
	 */
	private $tempAccount;

	/**
	 * @var \view\LoginObserver
	 */
	private $loginObserver;

	public function __construct() {
		$this->tempAccount = new \model\TempAccount();
		$this->loginObserver = new \view\loginObserver();
	}

	/**
	 * Renders login form and returns HTML
	 * @param  string $message  , Error message
	 * @param  string $username , User name
	 * @return string HTML
	 */
	public function renderForm($message, $username) {
		return "
		<div id='formWrapper'>
			<h1>Ej inloggad</h1>
			<p id='errorMsg'>$message</p>
			<form method='post' action='?'>
				<label>Användarnamn</label>
				<input name='" . LoginObserver::$USERNAME . "' placeholder='Användarnamn' value='$username'>
				<label>Lösenord</label>
				<input name='" . LoginObserver::$PASSWORD . "' type='password' placeholder='Lösenord'>
				<span id='checkbox'><input name='" . LoginObserver::$COOKIE . "' type='checkbox'>
				Håll mig inloggad</span>
				<input id='submit' name='" . LoginObserver::$SUBMIT . "' type='submit' value='Logga in'>
			</form>
		</div>";
	}

	/**
	 * Renders protected page and returns HTML
	 * @param  string $userName
	 * @param  string $message , Confirmation message     
	 * @return string HTML
	 */
	public function renderProtectedPage($userName, $message) {
		return "
		<div id='formWrapper'>
			<h1>$userName är inloggad</h1>
			<p>$message</p>
			<p><a href='?". LoginObserver::$LOGOUT . "'>Logga ut</a></p></div>";
	}

	/**
	 * Gets relevant confirmation message depending on authentication mode
	 * @return string
	 */
	public function getConfirmationMessage() {

		switch ($this->loginObserver->AuthenticationMode()) {
			// form
			case LoginObserver::FORM_AUTHENTICATION:
				return "Inloggningen lyckades";
			// session			
			case LoginObserver::SESSION_AUTHENTICATION:
				return "";
			// cookie
			case LoginObserver::COOKIE_AUTHENTICATION:
				return "Inloggningen lyckades via cookies";

			default:
				return "";
		}
	}
	
	/**
	 * Returns log out message
	 * @return string
	 */
	public function getLogOutMessage() {
		return "Du har nu loggat ut";
	}

	/**
	 * Returns saved cookie message
	 * @return string 
	 */
	public function getCookieMessage() {
		return "Inloggningen lyckades och vi kommer ihåg dig nästa gång";
	}

	/**
	 * Returns invalid cookie message
	 * @return string
	 */
	public function getCookieIsInvalidMessage() {
		return "Felaktig information i cookie";
	}
}