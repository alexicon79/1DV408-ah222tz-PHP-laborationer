<?php

namespace view;

class HTMLPageTemplate {

	/**
	 * @param  String $title 
	 * @param  String $body
	 * @param  Boolean $signedIn
	 * @return String HTML
	 */
	public function getPage($title, $body) {
		
		return
'<!DOCTYPE HTML>
<html>
	<head>
		<title>' . $title . '</title>
		<meta http-equiv=\'content-type\' content=\'text/html; charset=utf8\'>
		<link rel="stylesheet" href="" />
	</head>
	<body>
		' . $body . '
	</body>
</html>';
	}

	public static function getClientUserName(){
		if (isset ($_POST['submit'])) {
			$clientUserName = $_POST['name'];

			return $clientUserName;

			// setcookie("username", "$clientUserName");
			// setcookie("password", "$clientPassword");
		}
	}



}