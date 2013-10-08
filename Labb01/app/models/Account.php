<?php

namespace model;

class Account {

	private static $validUser = "Admin";
	private static $validPassword = "Password";

	/**
	 * Returns valid user name
	 *
	 * @return string
	 */
	public function getValidUser() {
		return self::$validUser;
	}

	/**
	 * Returns valid password
	 *
	 * @return string
	 */
	public function getValidPassword() {
		return self::$validPassword;
	}

	/**
	 * Returns encrypted password
	 *
	 * @return string
	 */
	public function getPasswordHash() {
		return sha1($this->getValidPassword());
	}
}