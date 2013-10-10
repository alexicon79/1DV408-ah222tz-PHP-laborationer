<?php

namespace view;

class ApplicationView {

	/**
	 * Renders full page HTML
	 * @return string HTML
	 */

	public function renderFullPage($title, $body){
		$HtmlPage = self::getHeader($title) .
				$body .
				self::getLocalTime() .
				self::getFooter();

		return $HtmlPage;
	}
	
	/**
	 * Returns HTML for page header
	 * @param string $title
	 * @return string HTML
	 */
	private function getHeader($title) {
		return 
		"<!DOCTYPE HTML>
		<html>
			<head>
				<title>$title</title>
				<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
				<link rel='stylesheet' type='text/css' href='assets/css/style.css'>
			</head>
		<body>";
	}

	/**
	 * Returns HTML for page footer
	 * 
	 * @return string 
	 */
	private function getFooter() {
		return 
		"</body>
		</html>";
	}


	/**
	 * Displays date and time in Swedish format
	 *
	 * @return string
	 */
	private function getLocalTime() {

		setlocale(LC_ALL, 'sv_SE');

		$localTimeString = strftime('%A, den %e %B &aring;r %G. <br />
			Klockan &auml;r [%H:%M:%S]' , time());
		
		$utf8TimeString = ucfirst(utf8_encode($localTimeString));

		return "<span id='date'>" . $utf8TimeString . "</span>";
	}


}