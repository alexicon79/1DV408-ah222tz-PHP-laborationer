<?php

namespace login\view;

require_once("./common/Filter.php");
require_once("./login/model/LoginObserver.php");

class LoginView implements \login\model\LoginObserver {

	private static $LOGOUT = "logout";
	private static $LOGIN = "login";
	private static $USERNAME = "LoginView::UserName";
	private static $PASSWORD = "LoginView::Password";
	private static $CHECKED = "LoginView::Checked";
	private static $NEW_USERNAME = "RegistrationModel::NewUser";
	private static $REGISTRATION_SUCCESS = "RegistrationModel::RegistrationSucceded";

	private $message = "";

	public function getLoginBox() {
		
		$user = $this->getUserName();

		if (isset($_SESSION[self::$REGISTRATION_SUCCESS])) {
			$this->message = "Registrering lyckades";
			unset($_SESSION[self::$REGISTRATION_SUCCESS]);
		}

		unset($_SESSION[self::$NEW_USERNAME]);

		$checked = $this->userWantsToBeRemembered() ? "checked=checked" : "";
		
		$html = "
			<form action='?" . self::$LOGIN . "' method='post' enctype='multipart/form-data'>
				<fieldset>
					$this->message
					<legend>Login - Skriv in användarnamn och lösenord</legend>
					<label for='UserNameID' >Användarnamn :</label>
					<input type='text' size='20' name='" . self::$USERNAME . "' id='UserNameID' value='$user' />
					<label for='PasswordID' >Lösenord  :</label>
					<input type='password' size='20' name='" . self::$PASSWORD . "' id='PasswordID' value='' />
					<label for='AutologinID' >Håll mig inloggad  :</label>
					<input type='checkbox' name='" . self::$CHECKED . "' id='AutologinID' $checked/>
					<input type='submit' name=''  value='Logga in' />
				</fieldset>
			</form>";
			
		return $html;

	}
	
	public function isLoggingOut() {
		return isset($_GET[self::$LOGOUT]);
	}
	
	public function isLoggingIn() {
		if (isset($_GET[self::$LOGIN])) {
			return true;
		} else if ($this->hasCookies()) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getLogoutButton() {
		return "$this->message <a href='?" . self::$LOGOUT . "'>Logga ut</a>";
	}
	
	public function getUserCredentials() {
		if ($this->hasCookies()) {
			return \login\model\UserCredentials::createWithTempPassword(new \login\model\UserName($this->getUserName()), 
																	  $this->getTemporaryPassword());
		} else {
			return \login\model\UserCredentials::createFromClientData(	new \login\model\UserName($this->getUserName()), 
																	\login\model\Password::fromCleartext($this->getPassword()));
		}
	}
	
	public function loginFailed() {
		if ($this->hasCookies()) {
			$this->message = "<p>Felaktig information i cookie</p>";
			$this->removeCookies();
		} else {
			
			if ($this->getUserName() == "") {
				$this->message .= "<p>Användarnamn saknas</p>";
			} else if ($this->getPassword() == "") {
				$this->message .= "<p>Lösenord saknas</p>";
			} else {
				$this->message = "<p>Felaktigt användarnamn och/eller lösenord</p>";
			}
		}
	}
	
	public function loginOK($tempCookie) {

		if ($this->userWantsToBeRemembered() || 
			$this->hasCookies()) {
			if ($this->hasCookies()) {
				$this->message  = "<p>Inloggning lyckades via cookies</p>";
			} else {
				$this->message  = "<p>Inloggning lyckades och vi kommer ihåg dig nästa gång</p>";
			}
			
			$expire = $tempCookie->getExpireDate();
			setcookie(self::$USERNAME, $this->getUserName(), $expire);
			setcookie(self::$PASSWORD, $tempCookie->getTemporaryPassword(), $expire);
		} else {
			$this->message  = "<p>Inloggning lyckades</p>";
		} 
		
	}
	
	public function doLogout() {
		$this->removeCookies();
		
		$this->message  = "<p>Du har nu loggat ut</p>";
	}
	
	private function getUserName() {
		if (isset($_POST[self::$USERNAME]))
			return \Common\Filter::sanitizeString($_POST[self::$USERNAME]);
		else if (isset($_COOKIE[self::$USERNAME]))
			return \Common\Filter::sanitizeString($_COOKIE[self::$USERNAME]);
		else if (isset($_SESSION[self::$NEW_USERNAME])) {
			return \Common\Filter::sanitizeString($_SESSION[self::$NEW_USERNAME]);
		}
		else
			return "";
	}
	
	private function getPassword() {
		if (isset($_POST[self::$PASSWORD]))
			return \Common\Filter::sanitizeString($_POST[self::$PASSWORD]);
		else
			return "";
	}
	
	private function userWantsToBeRemembered() {
		return isset($_POST[self::$CHECKED]);
	}

	private function getTemporaryPassword() {
		if (isset($_COOKIE[self::$PASSWORD])) {
			$fromCookieString = \Common\Filter::sanitizeString($_COOKIE[self::$PASSWORD]);
			return \login\model\TemporaryPasswordClient::fromString($fromCookieString);
		} else {
			return \login\model\TemporaryPasswordClient::emptyPassword();
		}
	}
	
	private function hasCookies() {
		return isset($_COOKIE[self::$PASSWORD]) && isset($_COOKIE[self::$USERNAME]);
	}

	private function removeCookies() {

		unset($_COOKIE[self::$USERNAME]);
		unset($_COOKIE[self::$PASSWORD]);
			
		$expireNow = time()-1;
		setcookie(self::$USERNAME, "", $expireNow);
		setcookie(self::$PASSWORD, "", $expireNow);
	}
	
}
