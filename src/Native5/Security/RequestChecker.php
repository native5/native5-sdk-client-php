<?php
class RequestChecker {
	public static function isAuthorized() {
		$headers = apache_request_headers();
		if(isset($_SESSION['uid']))
			return TRUE;
		if(!isset($headers['N5-Api-Sig']))
			return FALSE;
		$salt = "N@t1v3";
		$request_sig = $headers['N5-Api-Sig'];
		$string = $headers['N5-Api-Key'].$salt;
		if(hash('sha256', $string) == $request_sig)
			return TRUE;
		return FALSE;	
	}
}
?>
