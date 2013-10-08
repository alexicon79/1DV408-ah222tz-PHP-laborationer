<?php

namespace view;

/**
 * \view\PageHTML
 */
class ApplicationView {

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
			</head>";
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


	public function renderPage($title, $body) {
		$htmlPage = self::getHeader($title) .
					$body . 
					self::getTime();
					self::getFooter();

		return $htmlPage;
	}
}