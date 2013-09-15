<?php

namespace view;

class LoggedInHTMLView {

	/**
	 * @param  string $currentUser 
	 * @param  string $confirmationMsg
	 * @return string HTML
	 */
	public function getPage($currentUser, $confirmationMsg) {
		return "
		<div id='formWrapper'>
		<h1>$currentUser är inloggad</h1>
		<p>$confirmationMsg</p>
		<p><a href='?logout'>Logga ut</a></p></div>";
	}
}