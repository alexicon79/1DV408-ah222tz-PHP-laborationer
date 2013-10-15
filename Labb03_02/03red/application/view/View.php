<?php

namespace application\view;

require_once("common/view/Page.php");
require_once("SwedishDateTimeView.php");



class View {
	private $loginView;
	private $registrationView;

	private $timeView;
	
	public function __construct($loginView, $registrationView) {
		$this->loginView = $loginView;
		$this->registrationView = $registrationView;
		$this->timeView = new SwedishDateTimeView();
	}
	
	public function getLoggedOutPage() {
		$html = $this->getHeader(false);
		$registrationLink = $this->registrationView->getRegistrationLink();
		$loginBox = $this->loginView->getLoginBox();

		$html .= "<h2>Ej Inloggad</h2>
					$registrationLink
				  	$loginBox
				 ";
		$html .= $this->getFooter();

		return new \common\view\Page("Laboration. Inte inloggad", $html);
	}
	
	public function getLoggedInPage($user) {
		$html = $this->getHeader(true);
		$logoutButton = $this->loginView->getLogoutButton(); 
		$userName = $user->getUserName();

		$html .= "
				<h2>$userName är inloggad</h2>
				 	$logoutButton
				 ";
		$html .= $this->getFooter();

		return new \common\view\Page("Laboration. Inloggad", $html);
	}

	public function getRegistrationPage() {
		$html = $this->getHeader(false);
		$backToLoginLink = $this->registrationView->getBackToLoginFormLink();
		$registrationBox = $this->registrationView->getRegistrationBox();

		$html .= "<h2>Ej inloggad, Registrerar användare</h2>
					$backToLoginLink
					$registrationBox
					";

		$html .= $this->getFooter();

		return new \common\view\Page("Registrering av ny användare", $html);
	}
	
	private function getHeader($isLoggedIn) {
		$ret =  "<h1>Laborationskod xx222aa</h1>";
		return $ret;
		
	}

	private function getFooter() {
		$timeString = $this->timeView->getTimeString(time());
		return "<p>$timeString<p>";
	}
	
	
	
}
