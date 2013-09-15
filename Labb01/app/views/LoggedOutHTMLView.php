<?php

namespace view;

class LoggedOutHTMLView {

	/**
	 * @param  string $visibleUserName 
	 * @param  string $errorMsg
	 * @return string HTML
	 */
	
	public function getPage($visibleUserName, $errorMsg) {
		return "
		<div id='formWrapper'>
		<h1>Ej inloggad</h1>
		<p id='errorMsg'>$errorMsg</p>
		<form method='post' action=''>

		<label>Användarnamn</label>
		<input name='username' placeholder='Användarnamn' value='$visibleUserName'>

		<label>Lösenord</label>
		<input name='password' type='password' placeholder='Lösenord'>

		<input id='submit' name='submit' type='submit' value='Logga in'>

		</form>
		</div>";
	}
}