<?php
// Logger for Renault API queries

// https://console.cron-job.org/jobs


// v.  1.1.0 - 24/2/2025:
//     Fixed support of endpoints with parameters
//     Fixed support of days with no charges
//     Fixed support for manual paths with or without endpoint   

// v. 1.0.0 - 19/2/2025


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

$FULL_ADDRESS_TEST = 'gigya-login.php?gigyakey=3_js8th3jdmCWV86fKR3SXQWvXGKbHoWFv8NAgRbH7FnIBsi_XvCpN_rtLcI07uNuq&gigyasite=https://accounts.eu1.gigya.com&kamereon=YjkKtHmGfaceeuExUDKGxrLZGGvtVS0J&kamereonurl=https://api-wired-prod-1-euw1.wrd-aws.com&country=IT&username=EMAIL&password=PASSWORD';

if (empty($_GET)) {
    echo "<h1>Renault ZE API Interface</h1>";
    printHelp();
    echo "<p>Full url for testing: <a href='" . $_SERVER['HTTP_ORIGIN'] . "/" . basename(getcwd()) . "/" . $FULL_ADDRESS_TEST . "'>Link</a></p>";
    exit();
}



if (isset($_GET['gigyakey'])) {
    $gigya_api = $_GET['gigyakey']; // 3_js8th3jdmCWV86fKR3SXQWvXGKbHoWFv8NAgRbH7FnIBsi_XvCpN_rtLcI07uNuq
} else {
    $gigya_api = '3_js8th3jdmCWV86fKR3SXQWvXGKbHoWFv8NAgRbH7FnIBsi_XvCpN_rtLcI07uNuq';
	//echo "ERROR! You must specify Gigya API key for your country in parameter <b>gigyakey</b>.<br>Try with '3_js8th3jdmCWV86fKR3SXQWvXGKbHoWFv8NAgRbH7FnIBsi_XvCpN_rtLcI07uNuq'<br>";
    //printHelp();
	//return -1;
}

if (isset($_GET['gigyasite'])) { // Gigya server: 'https://accounts.eu1.gigya.com' as of feb/2025
    $gigyasite = $_GET['gigyasite'];
} else {
    $gigyasite = 'https://accounts.eu1.gigya.com';
	//echo "ERROR! You must specify Gigya server address for your country in parameter <b>gigyasite</b>.<br>Try with 'https://accounts.eu1.gigya.com'<br>";
    //printHelp();
	//return -2;
}



if (isset($_GET['username'])) {
    $username = $_GET['username'];
} else {
	echo "ERROR! You must specify your username in parameter <b>username</b>.<br>";
    printHelp();
	return -3;
}


if (isset($_GET['password']) ){
    $password = $_GET['password'];
} else {
    printHelp();
	return -4;
}


if (isset($_GET['kamereon']) ){ // Kamereon api key
    $kamereon = $_GET['kamereon']; // YjkKtHmGfaceeuExUDKGxrLZGGvtVS0J
} else {
    $kamereon = 'YjkKtHmGfaceeuExUDKGxrLZGGvtVS0J';
	//echo "ERROR! You must specify kamereon API  in parameter <b>kamereon</b>.<br>At february 2025 this worked: 'YjkKtHmGfaceeuExUDKGxrLZGGvtVS0J'<br>";
    //printHelp();
	//return -5;
}



if (isset($_GET['kamereonurl'])) { // Kamrereon server: https://api-wired-prod-1-euw1.wrd-aws.com as of feb/2025
    $kamereonurl = $_GET['kamereonurl'];
} else {
    $kamereonurl = 'https://api-wired-prod-1-euw1.wrd-aws.com';
    //printHelp();
	//return -6;
}


if (isset($_GET['country'])) { // Country
    $country = $_GET['country'];
} else {
    $country = "IT";
    //printHelp();
	//return -7;
}

// {PATH1}{id}{PATH2}v1{PATH3}{vin}/"
// 
if (isset($_GET['path1'])) { // First part of path
    $path1 = $_GET['path1'];
} else {
    $path1 = "/commerce/v1/accounts/";
    //printHelp();
	//return -7;
}


if (isset($_GET['path2'])) { // Second part of path
    $path2 = $_GET['path2'];
} else {
    $path2 = "/kamereon/kca/car-adapter/";
    //printHelp();
	//return -7;
}


if (isset($_GET['path3'])) { // Third part of path
    $path3 = $_GET['path3'];
} else {
    $path3 = "/cars/";
    //printHelp();
	//return -7;
}


// /commerce/v1/accounts/{id}/kamereon/kca/car-adapter/v1/cars/{vin}/cockpit
// {PATH1}{id}{PATH2}v1{PATH3}cockpit
                  // Others:
                     // /commerce/v1/accounts/{id}/kamereon/kca/car-adapter/v1/cars/{vin}/cockpit 
                     // /commerce/v1/accounts/{id}/kamereon/kcm/v1/vehicles/{vin}  /ev/soc-levels
                     // 
                     // {PATH1}{id}{PATH4}v1{PATH5}{vin}  /ev/soc-levels
                     //
                     // /commerce/v1/accounts/{accountId}/vehicles/{vin}  /details
                     // {PATH1}{id}{PATH5}{vin}  /details
                     //
                     // /commerce/v1/accounts/{id}
                     // {PATH1}{id}
                     //
                     // /commerce/v1/accounts/{id}/password
                     // {PATH1}{id}/password
                     //
                     // /commerce/v2/accounts/{accountId}/vehicles
                     // {PATH1b}{id}/vehicles
                     //
                     // /commerce/v1/accounts/{accountId}/vehicles/{vin}/virtual-keys
                     // /commerce/v1/accounts/{accountId2}/vehicles/{vin}/alerts
                     // {PATH1}{id}{PATH5}{vin} /virtual-keys
                     // /{PATH1}{id}{PATH5}{vin}/virtual-keys
                     //
                     // /commerce/v1/persons/{personId}/vehicles/{vin}/admin
                     // {PATH6}{personId}{PATH5}{vin} /admin

if (isset($_GET['path4'])) { // Third part of path
    $path4 = $_GET['path4'];
} else {
    $path4 = "/kamereon/kcm/";
}

if (isset($_GET['path5'])) { // Third part of path
    $path5 = $_GET['path5'];
} else {
    $path5 = "/vehicles/";
}

if (isset($_GET['path6'])) { // Third part of path
    $path6 = $_GET['path6'];
} else {
    $path6 = "/commerce/v1/persons/";
}

if (isset($_GET['path1b'])) { // Third part of path
    $path1b = $_GET['path1b'];
} else {
    $path1b = "/commerce/v2/accounts/";
}


if (isset($_GET['pathtype'])) { // How the path is made
    $pathType = $_GET['pathtype'];
echo "PATHTYPE: manually set to '" . $pathType . "'<br>";
} else {
    $pathType = "";//{PATH1}{id}{PATH2}v1{PATH3}{vin}/";
                    // Default: /commerce/v1/accounts/{id}/kamereon/kca/car-adapter/v1/cars/{vin}/  {endpoint}
                     //          {PATH1}{id}{PATH2}v1{PATH3}{vin}
                     // Others:
                     // /commerce/v1/accounts/{accountId}/kamereon/kcm/v1/vehicles/{vin}/   ev/soc-levels , settings, pause-resume, schedule, start
                     // {PATH1}{id}{PATH4}v1{PATH5}/{vin}    /ev/soc-levels
                     //
                     // /commerce/v1/accounts/{accountId}/vehicles/{vin}   /details
                     // {PATH1}{accountId}{PATH5}{vin}    /details
                     //
                     // /commerce/v1/accounts  /{id}
                     // {PATH1}{id}
                     //
                     // /commerce/v1/accounts/{id}    /password
                     // {PATH1}{id}/password
                     //
                     // /commerce/v2/accounts/{accountId}   /vehicles
                     // {PATH1b}{accountId}   /vehicles
                     //
                     // /commerce/v1/accounts/{accountId}/vehicles/{vin}/virtual-keys
                     // {PATH1}{accountId}{PATH5}{vin}/virtual-keys
                     //
                     // /commerce/v1/persons/{personId}/vehicles/{vin}/admin
                     // {PATH6}{personId}{PATH5}{vin}/admin
                     // {PATH6}{personId} /ze-passes
    //printHelp();
	//return -7;
}




if (isset($_GET['format'])) {
    $format = $_GET['format'];
} else {
	$format = "human";
}

if (isset($_GET['save'])) {
    $save = $_GET['save'];
} else {
	$save = 0;
}


if (isset($_GET['myfilename'])) {
    $myfilename = $_GET['myfilename'];
} else {
	$myfilename = "";
}


if (isset($_GET['endpoint'])) {
    $endpoint = $_GET['endpoint'];
} else {
	$endpoint = "";
}



if (isset($_GET['getpost'])) {
    $getpost = $_GET['getpost'];
} else {
	$getpost = "get";
}

echo "<br>GET OR POST?" . $getpost . "<br>";

if (isset($_GET['postpayload'])) {
    $postPayload = $_GET['postpayload'];
} else {
	$postPayload = "";
}

echo "<br>Post payload:<br>" . $postPayload . "<br>";


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
		echo '{"loginData" : {"data" : "Login error 001. TERMINATED (" . $checkResult . ")"}}';
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
		echo '{"loginData" : {"data" : "Login error 002. TERMINATED (" . $checkResult . ")"}}';
		return -1;
	}

  $responseData = json_decode($response, TRUE);
  $JWT =  $responseData['id_token'];

/*	echo
	'
	{
		"loginData" : {
			"cookie" : "' . $oauth_token  . '" ,
			"JWT" : "' . $JWT . '",
			"personId" : "' . $personId . '"
		}
	}';

*/

//echo "Cookie: " .  $oauth_token  . "<br>";
//echo "JWT: " .  $JWT  . "<br>";
//echo "personId: " .  $personId  . "<br>";

$KAMEREON_KEY = $kamereon;
//echo "KAMEREON_KEY: " . $KAMEREON_KEY . "<br>";

$accountHeaders = [
    'apikey' => $KAMEREON_KEY,
    'x-gigya-id_token' => $JWT
];

/*
echo "accountHeaders: <pre>";
print_r($accountHeaders);
echo "</pre><br><br>";
*/

$accountUrl = $kamereonurl . "/commerce/v1/persons/" . $personId . "?apikey=" . $KAMEREON_KEY . "&country=" . $country;
// echo "URL PER ACCOUNT: " . $accountUrl . "\n";


///////////

try {
    $accounts = callAPI($accountUrl, $accountHeaders);
} catch (Exception $e) {
    echo "Errore cercando l'account: " . $e->getMessage();
}


/*echo "Accounts raw: <pre>";
    print_r($accounts);
echo "</pre><br>";
*/

// Controllo se esistono gli indici necessari
if (isset($accounts['accounts']) && !empty($accounts['accounts'])) {
    // Primo account
    if (isset($accounts['accounts'][0])) {
        $accountId = $accounts['accounts'][0]['accountId'] ?? '';
        $accountIdType = $accounts['accounts'][0]['accountType'] ?? '';
//echo "Account1: " . $accountId . " (Type: " . $accountIdType . ")<br>";
    }

    // Secondo account
    if (isset($accounts['accounts'][1])) {
        $accountId2 = $accounts['accounts'][1]['accountId'] ?? '';
        $accountId2Type = $accounts['accounts'][1]['accountType'] ?? '';
//echo "Account2: " . $accountId2 . " (Type: " . $accountId2Type . ")<br>";
    }
} else {
    echo "Nessun account trovato per utente:<br>";
    echo $username . "<br>";
    echo $password . "<br>";
}

///////////



///////////
$vehiclesListQueryUrl = $kamereonurl . '/commerce/v1/accounts/' . $accountId2 . '/vehicles?apikey=' . $KAMEREON_KEY . '&country=' . $country;
$vehicles = callAPI($vehiclesListQueryUrl, $accountHeaders);
/*
echo "Vehicles raw: <pre>";
    print_r($vehicles);
echo "</pre><br>";
*/
///////////




//////////////
// Verifica presenza veicoli
if (!isset($vehicles['vehicleLinks']) || empty($vehicles['vehicleLinks'])) {
    echo "Nessun veicolo trovato per utente:<br>";
    echo $username . "<br>";
    echo $password . "<br>";
    error_log("ERR unknown - Cannot extract VIN from response, no vehicles array: " . print_r($vehicles, true));
    return [
        'status' => 'error in VIN extraction, no vehicles array',
        'data' => $vehicles
    ];
} else {//
// ok
}



// Genera HTML per la lista VIN
$MY_VIN = '';
foreach ($vehicles['vehicleLinks'] as $vehicle) {
    $MY_VIN .= sprintf($vehicle['vin']);
}

echo "VIN: '" . $MY_VIN . "'<br><br>";

///////////////////////




//////////////////////////////////////////////////
if (empty($endpoint)) {
echo "<br>*** no endpoint<br>Pathtype = " . $pathType;
    if (empty($pathType)) {
echo "<br>    *** no endpint and no pathtype: showing default data<br>";
      $endpoint = "cockpit";
      //echo $endpoint . ": <br>";
      $result = kamereonGet($JWT, $endpoint,  1 , null, "{PATH1}{id}{PATH2}v1{PATH3}{vin}/");
      /*echo('<pre>' . print_r(json_encode($result, JSON_PRETTY_PRINT), true) . '</pre>');
      echo('Array:<br><pre>' . print_r($result) . '</pre><br>');*/
      if (isset($result['data']['data']['attributes'])) {
          $carAttributes = $result['data']['data']['attributes'];
          $fuelAutonomy = $carAttributes['fuelAutonomy'];
          $fuelQuantity = $carAttributes['fuelQuantity'];
          $totalMileage = $carAttributes['totalMileage'];
      } else {
          $fuelAutonomy = "n/a";
          $fuelQuantity = "n/a";
          $totalMileage = "n/a";
          echo "Dati cockpit non disponibili nell'array<br>";
      }


      $endpoint = "battery-status";
      //echo $endpoint . ":<br>";
      $result = kamereonGet($JWT, $endpoint,  1 , null, "{PATH1}{id}{PATH2}v1{PATH3}{vin}/");
      /*echo('<pre>' . print_r(json_encode($result, JSON_PRETTY_PRINT), true) . '</pre>');
      echo('Array:<br><pre>' . print_r($result) . '</pre><br>');*/
      if (isset($result['data']['data']['attributes'])) {
          $batteryAttributes = $result['data']['data']['attributes'];
          $batteryLevel = $batteryAttributes['batteryLevel'];
          $batteryAutonomy = $batteryAttributes['batteryAutonomy'];
      } else {
          $batteryLevel = "n/a";
          $batteryAutonomy = "n/a";
          echo "Dati batteria non disponibili nell'array<br>";
      }


      $oggi = date('Ymd');
      $timestamp = date('Y-m-d H:i:s');
      $endpoint = 'charges?start='.$oggi.'&end='.$oggi.'&type=day';
//echo $endpoint . ": <br>";
      $result = kamereonGet($JWT, $endpoint,  1 , null, "{PATH1}{id}{PATH2}v1{PATH3}{vin}/");
//echo('<pre>' . print_r(json_encode($result, JSON_PRETTY_PRINT), true) . '</pre><br>');
//echo('Array:<br><pre>' . print_r($result) . '</pre><br>');


      $newData = elaboraCariche($result);

      $BATTERY_CAPACITY = 7.2;


      echo formatOutput($format, $oggi, $fuelAutonomy, $batteryAutonomy, $fuelQuantity, $batteryLevel,
                      $totalMileage, $newData, $BATTERY_CAPACITY, $save, $username, $timestamp,$myfilename);
     ///////// End of default behaviousr                      
    } else {
echo "<br>*** found pathtype: showing result of direct path:<br>";

    // direct path with endpoint included
        if ($getpost === "get") {
echo "<br>Performing GET...<br>";
            $result =  kamereonGet($JWT, "",  1 , null, $pathType);
        } else {
echo "<br>1 Performing POST for '" . $pathType . "'...<br>";
            $result =  kamereonPost($JWT, "", $postPayload, 1, "", "", "", $pathType);
        }
        echo('DIRECT PATH RESULT:<br><pre>' . print_r(json_encode($result, JSON_PRETTY_PRINT), true) . '</pre>');
    }
} else {
echo "<br>found endpoint<br>";
        if ($getpost === "get") {
echo "<br>Performing GET...<br>";
            $result =  kamereonGet($JWT, $endpoint,  1 , null, $pathType);
        } else {
echo "<br>2 Performing POST for '" . $endpoint . "'...<br>";
        //     function kamereonGet($id_token, $endp, $version, $optionalParameters, $pathType) {
            $result =  kamereonPost($JWT, $endpoint, $postPayload, 1, "", "", "", $pathType);
        }
  echo('<pre>' . print_r(json_encode($result, JSON_PRETTY_PRINT), true) . '</pre>');
}

////////////////////////



function formatOutput($format, $oggi, $fuelAutonomy, $batteryAutonomy, $fuelQuantity, $batteryLevel,
                    $totalMileage, $newData, $BATTERY_CAPACITY, $save, $username, $timestamp,$myfilename ) {

   $output = '';
   $KML = round($fuelAutonomy/$fuelQuantity,1);
   $L100 = round(100/$KML,1);
   $KMKWH = round($batteryAutonomy/($batteryLevel * $BATTERY_CAPACITY/100),0);
   $KW100 = round(100/$KMKWH,0);
   $totEnergy = (isset($newData['totalEnergy']) ? $newData['totalEnergy'] : 0);
   $totDuration = (isset($newData['totalDuration']) ? $newData['totalDuration'] : 0);
   switch($format) {
       case 'human':
            if ($save === '1') {
                echo "You specified 'save=1', but human-readable output is not saved: please specify TSV or CSV format to save data to file.<br>";
            }
           $output = "Data/ora query: " . $timestamp . "<br>" .
                  "Autonomia benzina: " . $fuelAutonomy . " km<br>" .
                  "Autonomia batteria: " . $batteryAutonomy . " km<br>" .
                  "Livello benzina: " . $fuelQuantity . " L<br>" .
                  "Livello batteria: " . $batteryLevel . "%<br>" .
                  "Km tot: " . $totalMileage . " km<br>" .
                  "Ricarica tot del " . $oggi . ": " . $totEnergy . " kWh<br>" .
                  "Durata ricarica: " . $totDuration . " min<br>" .
                  "Stima prossimi consumi benzina: " . $KML . " km/L, " . $L100  . " L/100km<br>" .
                  "Stima prossimi consumi elettrici: " . $KMKWH . " km/kWh, " . $KW100 . " kWh/100km<br>";
           break;

       case 'csv':
       case 'tsv':
           $separator = ($format === 'csv') ? ',' : "\t";
           $output = implode($separator, [
               $timestamp,
               $fuelAutonomy,
               $batteryAutonomy,
               $fuelQuantity,
               $batteryLevel,
               $totalMileage,
               $oggi,
               $totEnergy,
               $totDuration,
               round($fuelAutonomy/$fuelQuantity,1),
               round($batteryAutonomy/($batteryLevel * $BATTERY_CAPACITY/100),0)
           ]) . "\n";

           if ($save === '1')  {
                if (empty($myfilename)) {
                  echo "ERROR, NOT SAVED: please specify parameter 'myfilename' (without extension)<br>";
                } else {
                    $filename = $myfilename . "." . $format;
                   // Salva su file (modalità append)
                   file_put_contents($filename, $output, FILE_APPEND);
                   echo "Saved to <a href='" . $filename . "'>" . $filename . "</a><br>";
                }
           }
           break;

       case 'html':
            if ($save === '1') {
                echo "You specified 'save=1', but HTML output is not saved: please specify TSV or CSV format to save data to file.<br>";
            }
           $output = "<table border='1'>
                   <tr><td>Data query</td><td>$timestamp</td></tr>
                   <tr><td>Autonomia benzina</td><td>$fuelAutonomy km</td></tr>
                   <tr><td>Autonomia batteria</td><td>$batteryAutonomy km</td></tr>
                   <tr><td>Livello benzina</td><td>$fuelQuantity L</td></tr>
                   <tr><td>Livello batteria</td><td>$batteryLevel%</td></tr>
                   <tr><td>Km tot</td><td>$totalMileage km</td></tr>
                   <tr><td>Ricarica giorno $oggi</td><td>{$totEnergy} kWh</td></tr>
                   <tr><td>Durata ricarica</td><td>{$totDuration} min</td></tr>
                   <tr><td>Stima prossimi consumi benzina</td><td>" . $KML . " km/L, " . $L100  . " L/100km</td></tr>
                   <tr><td>Stima prossimi consumi elettrici</td><td>" . $KMKWH . " km/kWh, " . $KW100 . " kWh/100km</td></tr>
                  </table>";
           break;

       default:
           $output = "Formato '" . $format . "' non supportato, disponibili: csv, tsv, human, html";
   }

   return $output;
}



//////////////////////////////////////////////////






function callAPI($url, $headers) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array_map(
        function($key, $value) { return "$key: $value"; },
        array_keys($headers),
        $headers
    ));

    $response = curl_exec($ch);

    if(curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("Errore cURL: " . $error);
    }

    curl_close($ch);

    $data = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "<br>ERROR! Raw api response: <pre><br>" . $response . "<br><br></pre>Array:<br><pre>";
        print_r($response);
        echo "</pre>";
        throw new Exception("Errore nella decodifica JSON: " . json_last_error_msg());
    }

    return $data;
}


function kamereonGet($id_token, $endp, $version, $optionalParameters, $pathType) {
    global $kamereonurl, $country, $accountId,  $accountId2, $MY_VIN, $KAMEREON_KEY;
    global $path1,$path1b,$path2,$path3,$path4,$path5,$path6;

     // Determina il separatore di query
    $QUESTION_MARK = (strpos($path, "?") !== false) ? "&" : "?";

    // Percorso standard per GET:
    // /commerce/v1/accounts/{id}/kamereon/kca/car-adapter/v1/cars/{vin}/{query}
    // /commerce/v1/accounts/{id}/kamereon/kca/car-adapter/v1/cars/{vin}/actions/{action}
    // {PATH1}{id}{PATH2}v1{PATH3}{vin}/
    // path1 = /commerce/v1/accounts/
    // path2 = /kamereon/kca/car-adapter/
    // path3 = /cars/

//echo "DEBUG: pathtype1 = " .  $pathType . "<br>";

    $pathType = str_ireplace("{PATH1}", $path1, $pathType);
    $pathType = str_ireplace("{PATH1b}", $path1b, $pathType);
    $pathType = str_ireplace("{PATH2}", $path2, $pathType);
    $pathType = str_ireplace("{PATH3}", $path3, $pathType);
    $pathType = str_ireplace("{PATH4}", $path4, $pathType);
    $pathType = str_ireplace("{PATH5}", $path5, $pathType);
    $pathType = str_ireplace("{PATH6}", $path6, $pathType);
    $pathType = str_ireplace("{id}", $accountId, $pathType);
    $pathType = str_ireplace("{accountId}", $accountId, $pathType);
    $pathType = str_ireplace("{account_id}", $accountId, $pathType);
    $pathType = str_ireplace("{id2}", $accountId2, $pathType);
    $pathType = str_ireplace("{accountId2}", $accountId2, $pathType);
    $pathType = str_ireplace("{account_id2}", $accountId, $pathType);
    $pathType = str_ireplace("{vin}", $MY_VIN, $pathType);
    $pathType = str_ireplace("{personId}", $personId, $pathType);

        // Costruisci l'URL completo
    /*    $fullUrl = $kamereonurl .
                    $path1 . $accountId .  $path2 . 'v' . $version . $path3 . $MY_VIN .
                    "/" . $path .
                  $QUESTION_MARK . "country=" . $country . "&" . $optionalParameters;
    // https://api-wired-prod-1-euw1.wrd-aws.com/commerce/v1/accounts/47d74bba-7bcb-41c2-8bba-564931815b6d/kamereon/kca/car-adapter/v1/cars/VF1RJB00666097032/cockpit?country=IT&
    // https://api-wired-prod-1-euw1.wrd-aws.com/commerce/v1/accounts/47d74bba-7bcb-41c2-8bba-564931815b6d/kamereon/kca/car-adapter/v1/cars/VF1RJB00666097032/cockpit?country=IT&
    */
//echo "DEBUG: pathtype2 = " .  $pathType . "<br>";


    $fullUrl = $kamereonurl .
                $pathType .
                $endp .
                $QUESTION_MARK . "country=" . $country . "&" . $optionalParameters;

echo "   Full url: " . $fullUrl . "<br>";

    // Prepara gli headers
    $headers = [
        'x-gigya-id_token' => $id_token,
        'apikey' => $KAMEREON_KEY,
        'id' => $accountId
    ];

    $debugPath = "v" . $version . "/cars/" . $MY_VIN . "/" . $path;

    try {
        $response = callAPI($fullUrl, $headers);

        return [
                'status' => 'ok',
                'message' => "?",
                'debugPath' => $debugPath,
                'data' => $response
            ];

    } catch (Exception $e) {
        echo "***ERROR: " . $e->getMessage();

        if (isset($e->response)) {
            return [
                'status' => 'error1',
                'message' => "??",
                'debugPath' => $debugPath
            ];
        } else {
            return [
                'status' => 'error2',
                'message' => $e->getMessage(),
                'debugPath' => $debugPath
            ];
        }
    }
}







function kamereonPost($id_token, $endp,     $data,      $version, $sourceField, $destField, $debugMode, $pathType) {
    global $kamereonurl, $country, $accountId,  $accountId2, $MY_VIN, $KAMEREON_KEY;
    // Log di inizio
    echo "Sending POST request for '" . $path . "' using library version " . $version . "...<br>";

    // Determina se la URL contiene un "?" e imposta il delimitatore
    $QUESTION_MARK = (strpos($path, "?") > 0) ? "&" : "?";

    $pathType = str_ireplace("{id}", $accountId, $pathType);
    $pathType = str_ireplace("{accountId}", $accountId, $pathType);
    $pathType = str_ireplace("{id2}", $accountId2, $pathType);
    $pathType = str_ireplace("{accountId2}", $accountId2, $pathType);
    $pathType = str_ireplace("{vin}", $MY_VIN, $pathType);
    $pathType = str_ireplace("{personId}", $personId, $pathType);


    $fullUrl = $kamereonurl .
                $pathType .
                $endp .
                $QUESTION_MARK . "country=" . $country . "&" . $optionalParameters;


    // Log di debug
    echo "DEBUG:<br>";
    echo "id_token: " . $id_token . "<br>";
    echo "path: " . $path . "<br>";
    echo "data: " . $data . "<br>";
    echo "version: " . $version . "<br>";
    echo "sourceField: " . $sourceField . "<br>";
    echo "destField: " . $destField . "<br>";
    echo "==================== POST URL: " . $fullUrl . "<br>";

    // Inizializza cURL
    $ch = curl_init($fullUrl);

    // Imposta le opzioni di cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/vnd.api+json',
        'x-gigya-id_token: ' . $id_token,
        'apikey: ' . $KAMEREON_KEY
    ]);


    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    // Esegui la richiesta cURL e cattura la risposta
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "<br>Return code: '" . $httpCode . "'<br>";
echo "<br>Raw response: '" . $response . "'<br>";
    // Chiudi la sessione cURL
    curl_close($ch);

    // Gestione della risposta
    if ($httpCode >= 200 && $httpCode < 300) {
        echo "POST result successful.\n";
        $responseData = json_decode($response, true);

        // Log e gestione della notifica
        if (isset($responseData['id'])) {
            $notificationId = $responseData['id'];
        } elseif (isset($responseData['data']['id'])) {
            $notificationId = $responseData['data']['id'];
        } else {
            $notificationId = "MISSING NOTIFICATION ID";
            echo "NOTIFICATION ERROR! No ID for " . $path . "\n";
        }

        echo "kamereonPost: notification to manage: " . $notificationId . "\n";

        // Puoi chiamare qui la funzione manageNotification, se definita
// manageNotification("last", $fullUrl, $notificationId); // Da definire

        // Se sourceField e destField sono definiti, aggiorna il valore
        if (!empty($sourceField) && !empty($destField)) {
            // Qui dovresti implementare la logica per risolvere il path e
            // aggiornare il valore dell'elemento, ad esempio:
            // $_POST[$destField] = resolvePath($responseData, $sourceField);
        }

        return ['status' => 'ok', 'data' => $responseData['id']];
    } else {
        // Gestione dell'errore
        echo "Error occurred: HTTP Code " . $httpCode . "\n";
        $wholeError = json_decode($response, true);

        if (isset($wholeError['errors'][0])) {
            $err = $wholeError['errors'][0];
            $mess = $wholeError['messages'][0];
            $errorMessage = $err['errorMessage'] . "," . $mess['propertyPath'] . " " . $mess['message'];
            echo "Error message: " . $errorMessage . "\n";
        } else {
            echo "???? Unexpected error structure: " . $response . "\n";
        }
    }
}









function manageOutput($response, $debugPath) {
    error_log("===  RESULT for " . $debugPath);
    error_log("Response: " . print_r($response, true));
}


function manageErrors($response, $debugPath) {
    error_log("===  *******  ERROR for " . $debugPath);
    error_log("Error Response: " . print_r($response, true));
}


function elaboraCariche($responseArray) {
   $totalEnergy = 0;
   $totalDuration = 0;

   if (!isset($responseArray['data']['data']['attributes']['charges'])) {
       return [
           'error' => 'Struttura dati non valida',
           'energy' => 0,
           'duration' => 0
       ];
   }

   foreach ($responseArray['data']['data']['attributes']['charges'] as $charge) {
       $totalEnergy += $charge['chargeEnergyRecovered'];
       $totalDuration += $charge['chargeDuration'];
   }

   return [
       'totalEnergy' => round($totalEnergy, 2), // arrotonda a 2 decimali
       'totalDuration' => $totalDuration
   ];
}

function printHelp() {
   $help = [
       'gigyakey' => [
           'required' => true,
           'default' => '3_js8th3jdmCWV86fKR3SXQWvXGKbHoWFv8NAgRbH7FnIBsi_XvCpN_rtLcI07uNuq',
           'example' => '3_js8th3jdmCWV86fKR3SXQWvXGKbHoWFv8NAgRbH7FnIBsi_XvCpN_rtLcI07uNuq',
           'description' => 'Gigya API key per il tuo paese'
       ],
       'gigyasite' => [
           'required' => true,
           'default' => 'https://accounts.eu1.gigya.com',
           'example' => 'https://accounts.eu1.gigya.com',
           'description' => 'Server Gigya per il tuo paese'
       ],
       'username' => [
           'required' => true,
           'default' => null,
           'example' => 'your.email@example.com',
           'description' => 'Il tuo username'
       ],
       'password' => [
           'required' => true,
           'default' => null,
           'example' => 'yourpassword',
           'description' => 'La tua password'
       ],
       'kamereon' => [
           'required' => true,
           'default' => 'YjkKtHmGfaceeuExUDKGxrLZGGvtVS0J',
           'example' => 'YjkKtHmGfaceeuExUDKGxrLZGGvtVS0J',
           'description' => 'Kamereon API key'
       ],
       'kamereonurl' => [
           'required' => true,
           'default' => 'https://api-wired-prod-1-euw1.wrd-aws.com',
           'example' => 'https://api-wired-prod-1-euw1.wrd-aws.com',
           'description' => 'Server Kamereon per il tuo paese'
       ],
       'country' => [
           'required' => true,
           'default' => 'IT',
           'example' => 'IT',
           'description' => 'Codice paese'
       ],
       'format' => [
           'required' => false,
           'default' => 'human',
           'example' => 'human/csv/tsv/html',
           'description' => 'Formato output'
       ],
       'save' => [
           'required' => false,
           'default' => '0',
           'example' => '1/0',
           'description' => 'Salva output su file se = 1'
       ],
       'endpoint' => [
           'required' => false,
           'default' => 'cockpit',
           'example' => 'cockpit',
           'description' => 'Endpoint da interrogare'
       ]
   ];

   echo "<h2>Parametri richiesti:</h2>\n";
   echo "<table border='1'>\n";
   echo "<tr><th>Parametro</th><th>Descrizione</th><th>Esempio</th><th>Default</th></tr>\n";

   // Prima i parametri required
   foreach($help as $param => $details) {
       if($details['required']) {
           echo "<tr>\n";
           echo "<td><b>$param</b></td>\n";
           echo "<td>{$details['description']}</td>\n";
           echo "<td>{$details['example']}</td>\n";
           echo "<td>" . ($details['default'] ?? 'none') . "</td>\n";
           echo "</tr>\n";
       }
   }

   echo "</table>\n\n";

   echo "<h2>Parametri opzionali:</h2>\n";
   echo "<table border='1'>\n";
   echo "<tr><th>Parametro</th><th>Descrizione</th><th>Esempio</th><th>Default</th></tr>\n";

   // Poi i parametri optional
   foreach($help as $param => $details) {
       if(!$details['required']) {
           echo "<tr>\n";
           echo "<td><b>$param</b></td>\n";
           echo "<td>{$details['description']}</td>\n";
           echo "<td>{$details['example']}</td>\n";
           echo "<td>{$details['default']}</td>\n";
           echo "</tr>\n";
       }
   }

   echo "</table>\n<br>";

}


?>
