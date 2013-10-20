<?php
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();


/**
 * LinOTP Authentication backend
 *
 * @author corny@cornelinux.de
 */
class auth_plugin_authlinotp extends auth_plugin_authplain  { 

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

		dbglog("Starting linotp auth with " . $escUsername . " and " . $escPassword);

		try {	
        	$crl = curl_init();
	        $timeout = 5;
			$linotp_url = $this->getConf("linotp_url");
			$linotp_realm = $this->getConf("linotp_realm");
			$linotp_verify = $this->getConf("linotp_verify");
			$timeout = $this->getConf("linotp_timeout");


	        $url = $linotp_url . '?user=' . $escUsername . '&pass=' . $escPassword;
			if ($linotp_realm != "") {
				$url = $url . "&realm=" . $linotp_realm;
			}
			curl_setopt ($crl, CURLOPT_URL, $url);
	        curl_setopt ($crl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt ($crl, CURLOPT_HEADER, TRUE);
	        curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
	        curl_setopt ($crl, CURLOPT_SSL_VERIFYPEER, $linotp_verify);
	        curl_setopt ($crl, CURLOPT_SSL_VERIFYHOST, $linotp_verify);

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
