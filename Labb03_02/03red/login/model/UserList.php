<?php

namespace login\model;


require_once("UserCredentials.php");
require_once("common/model/PHPFileStorage.php");

class UserList {
	private $adminFile;

	private $users;

	public function  __construct() {
		$this->users = array();
		$this->loadAdmin();
	}

	public function findUser($fromClient) {
		foreach($this->users as $user) {
			if ($user->isSame($fromClient) ) {
				\Debug::log("found User");
				return  $user;
			}
		}
		throw new \Exception("could not login, no matching user");
	}

	public function update($changedUser) {
		$this->adminFile->writeItem($changedUser->getUserName(), $changedUser->toString());

		\Debug::log("wrote changed user to file", true, $changedUser);
		$this->users[$changedUser->getUserName()->__toString()] = $changedUser;
	}

	public function loadAdmin() {
		
		$this->adminFile = new \common\model\PHPFileStorage("data/admin.php");
		
		$adminArray = $this->adminFile->readAll();

		foreach ($adminArray as $key => $value) {
			$adminUserString = $this->adminFile->readItem($key);
			$admin = UserCredentials::fromString($adminUserString);
			$this->users[$admin->getUserName()->__toString()] = $admin;
		}
	}
}