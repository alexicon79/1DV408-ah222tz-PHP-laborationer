<?php

namespace model;

class TempAccount {

	private static $validUser = "Admin";
	private static $validPassword = "Password";

	public function getValidUser() {
		return self::$validUser;
	}

	public function getValidPassword() {
		return self::$validPassword;
	}
	
	public function getPasswordHash() {
		return sha1($this->getValidPassword());
	}

}