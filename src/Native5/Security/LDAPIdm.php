<?php
require_once('IdentityManager.php');
class LDAPIdentityManager implements IdentityManager {
	
	public function isAuthenticated() {
		return false;
	}
	
	public function isAuthorized() {
		return true;
	}

	/**
	 * Authenticate using request &/or credentials in request.
	 * Authentication results in authentication token being generated. 
	 *
	 */
	public function authenticate($user) {
		$token = "#544719020012012";
		return $token;
	}

	/**
	 * Authorization of user based on userToken. 
	 * Generates token which will provide user access levels to functions.
	 *
	 */ 
	public function authorize($userToken) {
		$authToken = ''; // Token which can be used to define user access.
		return $authToken;
	}
}
?>
