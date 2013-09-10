<?php

$displayUserName = "";
$errorMsg = "";

/**
 * TODO: fixa snyggare lösning.. Validera detta i kontrollern istället, skapa en klass med inparametrar för UserName + errorMsg?
 */

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

echo "
	<h1>Ej inloggad</h1>
	<p>$errorMsg</p>
	<form method='post' action='?login'>
	    
	    <label>Name</label>
	    <input name='username' placeholder='User' value='$displayUserName'>
	            
	    <label>Password</label>
	    <input name='password' type='password' placeholder='Password'>

	    <input id='submit' name='submit' type='submit' value='Submit'>
	        
	</form>";