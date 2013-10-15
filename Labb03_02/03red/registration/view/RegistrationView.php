<?php

namespace registration\view;

class RegistrationView {
		
	private static $REGISTER = "register";
	private static $NEW_USERNAME = "RegistrationView::UserName";
	private static $NEW_PASSWORD = "RegistrationView::Password";
	private static $NEW_PASSWORD_CONFIRM = "RegistrationView::PasswordConfirmation";

	// error handling
	private static $USERNAME_EXISTS_ERROR = 1;
	private static $USERNAME_HAS_TAGS_ERROR = 2;


	private $message = "";

	public function getRegistrationBox() {

		$new_user = $this->getUserName();
		$new_password = $this->getPassword();

		$html = "
			<form action='?" . self::$REGISTER . "' method='post' enctype='multipart/form-data'>
				<fieldset>
					$this->message
					<legend>Registrera ny användare - Skriv in användarnamn och lösenord</legend>
					<label for='UserNameID' >Namn :</label>
					<input type='text' size='20' name='" . self::$NEW_USERNAME . "' id='UserNameID' value='$new_user' /><br />
					<label for='PasswordID' >Lösenord  :</label>
					<input type='password' size='20' name='" . self::$NEW_PASSWORD . "' id='PasswordID' value='' /><br />
					<label for='PasswordID_02' >Repetera lösenord  :</label>
					<input type='password' size='20' name='" . self::$NEW_PASSWORD_CONFIRM . "' id='PasswordID' value='' /><br />
					<input type='submit' name='submit'  value='Registrera' />
				</fieldset>
			</form>";

		return $html;
	}

	public function getUserCredentials() {
		if (\Common\Filter::hasTags($_POST[self::$NEW_USERNAME])) {
			throw new \Exception("Has tags", self::$USERNAME_HAS_TAGS_ERROR);
		}
		elseif ($this->getPassword() != $this->getPasswordConfirmation()) {
			throw new \Exception("Passwords do not match");
		} else {
			return \login\model\UserCredentials::createFromClientData(	new \login\model\UserName($this->getUserName()), 
																		\login\model\Password::fromCleartext($this->getPassword()));
		}
	}

	public function getRegistrationLink() {
		return "<a href='?register'>Registrera ny användare</a><br /><br />";
	}

	public function getBackToLoginFormLink() {
		return "<a href='./'>Tillbaka</a><br /><br />";
	}
	
	public function userWantsToRegister() {
		return isset($_GET[self::$REGISTER]);
	}

	public function isRegistering() {
		return isset($_POST['submit']);
	}

	public function getUserName() {
		if (isset($_POST[self::$NEW_USERNAME])) {
			return \Common\Filter::sanitizeString($_POST[self::$NEW_USERNAME]);
		}
		return "";
	}

	public function getUnfilteredUserName() {
		if (isset($_POST[self::$NEW_USERNAME])) {
			return self::$NEW_USERNAME;
		}
	}
	
	public function getPassword() {
		if (isset($_POST[self::$NEW_PASSWORD])) {
			return \Common\Filter::sanitizeString($_POST[self::$NEW_PASSWORD]);
		}
		return "";
	}

	private function getPasswordConfirmation() {
		if (isset($_POST[self::$NEW_PASSWORD_CONFIRM])) {
			return \Common\Filter::sanitizeString($_POST[self::$NEW_PASSWORD_CONFIRM]);
		}
		return "";
	}

	public function registrationFailed($errorCode) {

		if (strlen($this->getUserName()) < \login\model\UserName::MINIMUM_USERNAME_LENGTH) {
			$this->message .= "Användarnamnet har för få tecken. Minst 3 tecken<br/>";
		}
		if (strlen($this->getPassword()) < \login\model\Password::MINIMUM_PASSWORD_CHARACTERS) {
			$this->message .= "Lösenorden har för få tecken. Minst 6 tecken";	
		}
		if ($this->getPassword() != $this->getPasswordConfirmation()) {
			$this->message .= "Lösenorden matchar inte";
		}
		if ($errorCode == self::$USERNAME_EXISTS_ERROR) {
			$this->message = "Användarnamnet är redan upptaget";
		}
		if ($errorCode == self::$USERNAME_HAS_TAGS_ERROR) {
			$this->message = "Användarnamnet innehåller ogiltiga tecken";
		}
	}

}