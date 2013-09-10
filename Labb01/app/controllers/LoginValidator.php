<?php

namespace controller;

class LoginValidator {

	public function validateUser() {
	
		if (isset ($_POST['submit'])) {
			if ( (!empty ($_POST["username"])) && ( ($_POST["username"]) == "Admin") ){
				$displayUserName = "Admin";
				$errorMsg = "Lösenord saknas";
			}

			if ( (!empty ($_POST["password"])) && ( ($_POST["password"]) == "Password") ){
				$displayUserName = "";
				$errorMsg = "Användarnamn saknas";
			}

			if ( (empty ($_POST["username"])) && (empty ($_POST["password"])) ){
				$displayUserName = "";
				$errorMsg = "Användarnamn saknas";
			}
			
			if ( $_POST["username"] == "Admin" && (!empty ($_POST["password"])) && $_POST["password"] != "Password" ){
				$displayUserName = "Admin";
				$errorMsg = "Felaktigt användarnamn och/eller lösenord";
			}

			if ( $_POST["username"] != "Admin" && $_POST["password"] != "Password" ){
				$displayUserName = $_POST["username"];
				$errorMsg = "Felaktigt användarnamn och/eller lösenord";
			}
		}



	}
}