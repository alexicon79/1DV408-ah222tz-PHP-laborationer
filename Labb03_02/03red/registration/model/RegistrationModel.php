<?php

namespace registration\model;

class RegistrationModel {

	private $adminFile;  

	// private $allUsers;
	private static $NEW_USERNAME = "RegistrationModel::NewUser";
	private static $REGISTRATION_SUCCESS = "RegistrationModel::RegistrationSucceded";


	public function __construct() {	
		$this->allUsers = new \login\model\UserList();
		$this->adminFile = new \common\model\PHPFileStorage("data/admin.php");
	}

	
	public function doRegister($clientUserName, $clientPassword) {

		if ($this->userNameExists($clientUserName)) {
			throw new \Exception("Username exists", 1);
		}
		if ($this->userNameHasTags($clientUserName)) {
			throw new Exception("Has tags", 2);
		}

		$_SESSION[self::$NEW_USERNAME] = $clientUserName;

		$this->registerNewUser($clientUserName, $clientPassword);
	}

	public function userNameExists($clientUserName) {
		
		$allUsers = $this->adminFile->readAll();
		
		foreach ($allUsers as $key => $value) {
			$adminUserString = $this->adminFile->readItem($key);
			$adminUserName = \login\model\UserCredentials::onlyUserNameFromString($adminUserString);
			
			if ($clientUserName == $adminUserName) {
				return true;
			}
		}
		return false;
	}

	public function userNameHasTags($clientUserName) {

		if (\Common\Filter::hasTags($clientUserName) == true) {
			return true;
		}
		return false;
	}

	public function registerNewUser($clientUserName, $clientPassword) {

		$newUserName = new \login\model\UserName($clientUserName);
		$newPassword = \login\model\Password::fromCleartext($clientPassword);
		$newAdmin = \login\model\UserCredentials::create($newUserName, $newPassword);
		$this->allUsers->update($newAdmin);
	}

	public function setRegistrationSuccess(){
		$_SESSION[self::$REGISTRATION_SUCCESS] = true;
	}
}