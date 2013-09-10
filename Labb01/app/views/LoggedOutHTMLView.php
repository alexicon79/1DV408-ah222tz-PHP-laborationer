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
	<h1>Ej inloggad</h1>
	<p>$errorMsg</p>
	<form method='post' action='?login'>
	    
	    <label>Name</label>
	    <input name='username' placeholder='User' value='$visibleUserName'>
	            
	    <label>Password</label>
	    <input name='password' type='password' placeholder='Password'>

	    <input id='submit' name='submit' type='submit' value='Submit'>
	        
	</form>";
	}
}