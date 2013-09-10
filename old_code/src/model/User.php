<?php

namespace model;

class User 
{
	public function __construct() {
		
		if (isset($_POST['submit'])) {
			$this->username = $_POST['username'];
			$this->password = $_POST['password'];
		}
		$this->username = "Empty";
	}

	public function getUserName(){
		return $this->username;
	}

	public function getPassword(){
		return $this->password;
	}

	public function setUserName($username){
		$this->username = $username;
	}
	
	public function setPassword($password){
		$this->password = $password;
	}
}