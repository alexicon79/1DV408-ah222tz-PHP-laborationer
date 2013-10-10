<?php

namespace model;

class TempAccount {

	/**
	 * @var string , Valid user name
	 */
	private static $validUser = "Admin";

	/**
	 * @var string , Valid password
	 */
	private static $validPassword = "Password";

	/**
	 * @return string , Valid user name
	 */
	public function getValidUser() {
		return self::$validUser;
	}

	/**
	 * @return string , Valid password
	 */
	public function getValidPassword() {
		return self::$validPassword;
	}
	
	/**
	 * @return string , Hashed password
	 */
	public function getPasswordHash() {
		return sha1($this->getValidPassword());
	}
}