<?
/**
 * Login form
 */

namespace view;

class LoginForm {

	public function getHTML(){

		return "<form method='post' action='?login'>
	    
	    <label>Name</label>
	    <input name='username' placeholder='User'>
	            
	    <label>Password</label>
	    <input name='password' placeholder='Password'>
	            
	    <input id='submit' name='submit' type='submit' value='Submit'>
	        
	</form>";
	}

	public function getClientUserName(){
		if (isset ($_POST['submit'])) {
			$clientUserName = $_POST['name'];

			return $clientUserName;

			// setcookie("username", "$clientUserName");
			// setcookie("password", "$clientPassword");
		}
	}
}