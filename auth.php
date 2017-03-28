<?php
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();


/**
 * privacyIDEA Authentication backend
 *
 * @author corny@cornelinux.de
 */
class auth_plugin_authprivacyidea extends auth_plugin_authplain  {

    public function __construct() {
        parent::__construct();
        
        $this->success = true;
    }


	public function checkPass($user, $pass) {
		assert(is_string($user));
		assert(is_string($pass));
		$userinfo = $this->getUserData($user);

		$status = False;
		$value = False;

		if($userinfo === false) return false;

		if (!function_exists('curl_init')){
	        die('Sorry cURL is not installed!');
	    }

        $escPassword = urlencode($pass);
        $escUsername = urlencode($user);

		dbglog("Starting privacyIDEA auth with " . $escUsername . " and " . $escPassword);

		try {	
        	$crl = curl_init();
	        $timeout = 5;
			$privacyidea_url = $this->getConf("privacyidea_url");
			$privacyidea_realm = $this->getConf("privacyidea_realm");
			$privacyidea_verify = $this->getConf("privacyidea_verify");
			$timeout = $this->getConf("privacyidea_timeout");


	        $url = $privacyidea_url . '?user=' . $escUsername . '&pass=' . $escPassword;
			if ($privacyidea_realm != "") {
				$url = $url . "&realm=" . $privacyidea_realm;
			}
			curl_setopt ($crl, CURLOPT_URL, $url);
	        curl_setopt ($crl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt ($crl, CURLOPT_HEADER, TRUE);
	        curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
	        curl_setopt ($crl, CURLOPT_SSL_VERIFYPEER, $privacyidea_verify);
	        curl_setopt ($crl, CURLOPT_SSL_VERIFYHOST, $privacyidea_verify);

			dbglog("About to execute curl for url ". $url);
	
	        $response = curl_exec($crl);

			dbglog("Got response " . $response);

	        $header_size = curl_getinfo($crl, CURLINFO_HEADER_SIZE);
	        $body = json_decode(substr( $response, $header_size ));
			
			$status = $body->result->status;
			$value = $body->result->value;			

	        curl_close($crl);
		} 
		catch (Exception $e)
		{
			die("Something went wrong: " + $e);
		}

		return $value;
	}


	
}
?>
