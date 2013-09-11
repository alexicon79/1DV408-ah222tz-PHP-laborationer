<?php

namespace view;

class LoggedOutHTMLView {

		/**
	 * @param  String $visibleUserName 
	 * @param  String $errorMsg
	 * @return String HTML
	 */
	
	public function getPage($visibleUserName, $errorMsg) {

	return "
	<div id='formWrapper'>
	<h1>Ej inloggad</h1>
	<p id='errorMsg'>$errorMsg</p>
	<form method='post' action='?login'>
	    
	    <label>Användarnamn</label>
	    <input name='username' placeholder='Användarnamn' value='$visibleUserName'>
	            
	    <label>Lösenord</label>
	    <input name='password' type='password' placeholder='Lösenord'>

	    <input id='submit' name='submit' type='submit' value='Logga in'>
	        
	</form>
	</div>";
	}
}