<?
/**
 * Login form
 */

namespace view;

class LoginForm {

	public function getLoginForm(){

		return "<form>
	        
	    <label>Name</label>
	    <input name='name' placeholder='User'>
	            
	    <label>Password</label>
	    <input name='password' placeholder='Password'>
	            
	    <input id='submit' name='submit' type='submit' value='Submit'>
	        
	</form>";
	}
}