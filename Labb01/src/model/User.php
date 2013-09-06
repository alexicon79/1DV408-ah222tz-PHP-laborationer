<?php

namespace model;

class User 
{
	public function __construct($name, $pass) {
		$this->name = $name;
		$this->pass = $pass;
	}

	public function getName(){
		return $this->name;
	}
}