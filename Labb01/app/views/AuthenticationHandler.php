<?php 

namespace view;

class AuthenticationHandler {
	
	
	private static $expirationFile = "endtime.txt"; 


	/**
	 * Checks if a logged in user has been authenticated by 
	 * manually entering valid form input.
	 * 
	 * @return boolean
	 */
	public function userIsAuthenticatedByForm() {
		if (isset ($_POST['submit'])) {

			if (($_POST["username"] == $this->getValidUser()) && 
				($_POST["password"] == $this->getValidPassword())){
				
				return true;

			} else {
				return false;
			}
		}
	}

	/**
	 * Checks if a logged in user has been authenticated by a saved session.
	 * 
	 * @return boolean
	 */
	public function userIsAuthenticatedBySession() {

		if ((!isset($_POST['submit'])) && isset($_SESSION['username'])) {
			if( ($_SESSION['username'] == $this->getValidUser()) && 
				($_SESSION['agent'] == $_SERVER['HTTP_USER_AGENT']) ){
				
				return true;
			
			}
		} else {
			return false;
		}
		return false;
	}

	/**
	 * Checks if a logged in user has been authenticated by cookies.
	 * 
	 * @return boolean
	 */
	public function userIsAuthenticatedByCookie() {

		if((!isset($_POST['submit'])) && 
			(!isset($_SESSION['username'])) &&
			  isset($_COOKIE['username']) && 
			  isset($_COOKIE['password']) &&
			  $_COOKIE['password'] === $this->getPasswordHash()){

			return true;

		} else {
			return false;
		}
	}

	/**
	 * Checks if cookie has been manipulated by client, either by changing 
	 * the encrypted password value or by changing expiration time.
	 * 
	 * @var string $cookieExpiration , Valid cookie expiration time
	 * @return boolean
	 */

	public function isCookieInvalid() {
		if ( 	(isset($_COOKIE["password"])) || (isset($_COOKIE["admin"])) ) {

			$cookieExpiration = file_get_contents(self::$expirationFile);
			
			if ($_COOKIE["password"] != $this->getPasswordHash()) {
				$this->clearCookie();
				return true;
			}

			if ( time() > $cookieExpiration ) {
				$this->clearCookie();
				return true;
			} else {
				return false;
			}
		}
	}

	/**
	 * Checks if session belongs to current user agent / browser.
	 *
	 * @return boolean
	 */
	public function isSessionHijacked() {
		
		if ((isset($_SESSION["agent"])) && 
			($_SESSION["agent"] != $_SERVER['HTTP_USER_AGENT'])) {
			
			unset($_SESSION["username"]);
			unset($_SESSION["agent"]);
			
			return true;
		} else {
			return false;
		}
	}
}