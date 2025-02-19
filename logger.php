<?php
// Logger for Renault API queries
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


if (isset($_GET['country'])) { // Kamrereon server: https://api-wired-prod-1-euw1.wrd-aws.com as of feb/2025
    $country = $_GET['country'];
} else {
    $country = "IT";
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

if (isset($_GET['endpoint'])) {
    $endpoint = $_GET['endpoint'];
} else {
	$endpoint = "cockpit";
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
    echo "Errore durante la chiamata API: " . $e->getMessage();
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
    echo "Nessun account trovato nell'array<br>";
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
    error_log("ERR unknown - Cannot extract VIN from response, no vehicles array: " . print_r($vehicles, true));
    return [
        'status' => 'error in VIN extraction, no vehicles array',
        'data' => $vehicles
    ];
}



// Genera HTML per la lista VIN
$MY_VIN = '';
foreach ($vehicles['vehicleLinks'] as $vehicle) {
    $MY_VIN .= sprintf($vehicle['vin']);
}

//echo "VIN: " . $MY_VIN . "<br><br>";

///////////////////////




//////////////////////////////////////////////////
$endpoint = "cockpit";
//echo $endpoint . ": <br>";
$result = kamereonGet($JWT, $endpoint,  1 , null);
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
$result = kamereonGet($JWT, $endpoint,  1 , null);
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


$ieri = date('Ymd', strtotime('-1 day'));
$timestamp = date('Y-m-d H:i:s');
$endpoint = 'charges?start='.$ieri.'&end='.$ieri.'&type=day';
//echo $endpoint . ": <br>";
$result =  kamereonGet($JWT, $endpoint,  1 , null);
/*echo('<pre>' . print_r(json_encode($result, JSON_PRETTY_PRINT), true) . '</pre><br>');
echo('Array:<br><pre>' . print_r($result) . '</pre><br>');*/


$newData = elaboraCariche($result);

$BATTERY_CAPACITY = 7.2;


echo formatOutput($format, $ieri, $fuelAutonomy, $batteryAutonomy, $fuelQuantity, $batteryLevel,
                $totalMileage, $newData, $BATTERY_CAPACITY, $save, $username, $timestamp);

////////////////////////



function formatOutput($format, $ieri, $fuelAutonomy, $batteryAutonomy, $fuelQuantity, $batteryLevel,
                    $totalMileage, $newData, $BATTERY_CAPACITY, $save, $username, $timestamp ) {

   $output = '';
   $KML = round($fuelAutonomy/$fuelQuantity,1);
   $L100 = round(100/$KML,1);
   $KMKWH = round($batteryAutonomy/($batteryLevel * $BATTERY_CAPACITY/100),0);
   $KW100 = round(100/$KMKWH,0);
   switch($format) {
       case 'human':
           $output = "Data/ora query: " . $timestamp . "<br>" .
                  "Autonomia benzina: " . $fuelAutonomy . " km<br>" .
                  "Autonomia batteria: " . $batteryAutonomy . " km<br>" .
                  "Livello benzina: " . $fuelQuantity . " L<br>" .
                  "Livello batteria: " . $batteryLevel . "%<br>" .
                  "Km tot: " . $totalMileage . " km<br>" .
                  "Ricarica tot del " . $ieri . ": " . $newData['totalEnergy'] . " kWh<br>" .
                  "Durata ricarica: " . $newData['totalDuration'] . " min<br>" .
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
               $ieri,
               $newData['totalEnergy'],
               $newData['totalDuration'],
               round($fuelAutonomy/$fuelQuantity,1),
               round($batteryAutonomy/($batteryLevel * $BATTERY_CAPACITY/100),0)
           ]) . "\n";

           if (($save === '1') && !empty($username)) {
               // Estrai la parte prima della @
               $filename = explode('@', $username)[0];
               // Aggiungi estensione appropriata
               $filename .= '.' . $format;
               // Salva su file (modalità append)
               file_put_contents($filename, $output, FILE_APPEND);
           }
           break;

       case 'html':
           $output = "<table border='1'>
                   <tr><td>Data query</td><td>$timestamp</td></tr>
                   <tr><td>Autonomia benzina</td><td>$fuelAutonomy km</td></tr>
                   <tr><td>Autonomia batteria</td><td>$batteryAutonomy km</td></tr>
                   <tr><td>Livello benzina</td><td>$fuelQuantity L</td></tr>
                   <tr><td>Livello batteria</td><td>$batteryLevel%</td></tr>
                   <tr><td>Km tot</td><td>$totalMileage km</td></tr>
                   <tr><td>Ricarica giorno $ieri</td><td>{$newData['totalEnergy']} kWh</td></tr>
                   <tr><td>Durata ricarica</td><td>{$newData['totalDuration']} min</td></tr>
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
        throw new Exception("Errore nella decodifica JSON: " . json_last_error_msg());
    }

    return $data;
}


function kamereonGet($id_token, $path, $version, $optionalParameters) {
    global $kamereonurl, $country, $accountId, $MY_VIN, $KAMEREON_KEY;

     // Determina il separatore di query
    $QUESTION_MARK = (strpos($path, "?") !== false) ? "&" : "?";

    // Costruisci l'URL completo
    $fullUrl = $kamereonurl . '/commerce/v1/accounts/' . $accountId .
              "/kamereon/kca/car-adapter/v" . $version . "/cars/" . $MY_VIN . "/" . $path .
              $QUESTION_MARK . "country=" . $country . "&" . $optionalParameters;

//echo "   Full url: " . $fullUrl . "<br>";

    // Prepara gli headers
    $headers = [
        'x-gigya-id_token' => $id_token,
        'apikey' => $KAMEREON_KEY
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
