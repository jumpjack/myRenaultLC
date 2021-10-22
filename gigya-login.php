<?php
// v. 1.0.0
function checkResponse($resp, $errnum) {
  if ($resp === FALSE) {
		echo "Error in Renault login, missing response.<br>";
		return -1;
		//die(curl_error($ch));
	} else {
		if (strpos($resp,'"errorCode"') !== false )  { // if errorCode is present in response....
			if (strpos($resp,'"errorCode": 0') === false )  { // errorCode != 0 --> failed login
					echo "Error " . $errnum . ", errorCode != 0 in response: <br> <pre> " . $resp . "</pre><br>";
					return -2;
					// die(curl_error($ch));
			} else { // errorCode == 0 --> successful login
		 		//  echo "<pre>" . $resp . "</pre><br>";
					return 0;
			}
		} else { //errorCode not present in response:
		 	echo "Missing errorCode in response:<br><pre>" . $resp . "</pre><br>";
			return -3;
		}
	}
}


if (isset($_GET['gigyakey'])) {
    $gigya_api = $_GET['gigyakey']; // 3_js8th3jdmCWV86fKR3SXQWvXGKbHoWFv8NAgRbH7FnIBsi_XvCpN_rtLcI07uNuq
} else {
	echo "ERROR! You must specify Gigya API key for your country in parameter <b>gigyakey<b>.<br>";
	return -1;
}

if (isset($_GET['gigyasite'])) {
    $gigyasite = $_GET['gigyasite']; // 'https://accounts.eu1.gigya.com';
} else {
	echo "ERROR! You must specify gigya site address for your country in parameter <b>gigyasite<b>.<br>";
	return -2;
}

if (isset($_GET['username'])) {
    $username = $_GET['username'];
} else {
	echo "ERROR! You must specify your username in parameter <b>username<b>.<br>";
	return -3;
}

if (isset($_GET['password']) ){
    $password = $_GET['password'];
} else {
	echo "ERROR! You must specify your password in parameter <b>password<b>.<br>";
	return -4;
}

if (isset($_GET['kamereon']) ){
    $kamereon = $_GET['kamereon']; // Ae9FDWugRxZQAGm3Sxgk7uJn6Q4CGEA2
} else {
	echo "ERROR! You must specify kamereon API  in parameter <b>kamereon<b>.<br>";
	return -4;
}



  //Login Gigya
  $update_ok = TRUE;
  $postData = array(
    'ApiKey' => $gigya_api,
    'loginId' => $username,
    'password' => $password,
    'include' => 'data',
	'sessionExpiration' => 60
  );
  $ch = curl_init($gigyasite . '/accounts.login');
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
  $response = curl_exec($ch);
  $checkResult = checkResponse($response,"001");
  //echo "Result 001: " . $checkResult . "<br>";
	if ( $checkResult !==0 ) {
		echo("Login error 001. TERMINATED.");
		return -1;
	}

	$responseData = json_decode($response, TRUE);
    $oauth_token = $responseData['sessionInfo']['cookieValue'];
	$personId = $responseData['data']['personId'];


  //Request Gigya JWT token
  $postData = array(
    'login_token' => $oauth_token,
    'ApiKey' => $gigya_api,
    'fields' => 'data.personId,data.gigyaDataCenter',
	'expiration' => 87000
  );
  $ch = curl_init($gigyasite . '/accounts.getJWT');
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
  $response = curl_exec($ch);
  $checkResult = checkResponse($response,"002");
  //echo "Result 002: " . $checkResult . "<br>";
	if ( $checkResult !==0 ) {
		echo ("Login error 002. TERMINATED.");
		return -1;
	}

  $responseData = json_decode($response, TRUE);
  $JWT =  $responseData['id_token'];

	echo
	'
	{
		"loginData" : {
			"cookie" : "' . $oauth_token  . '" ,
			"JWT" : "' . $JWT . '",
			"personId" : "' . $personId . '"
		}
	}';


?>