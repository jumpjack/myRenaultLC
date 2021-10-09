<?php
if (!isset($_GET['pass'])) {
    die("Not authorized");
} else {
	if ($_GET['pass'] != "miapasssegretissima") {
		die("Not authorized");
	}
}



if (!isset($_GET['username'])) {
    die("Please provide username for your MyRenault account.");
} else {
	$username = $_GET['username'];
}




if (!isset($_GET['password'])) {
    die("Please provide password for your MyRenault account.");
} else {
	$password = $_GET['password'];
}

if (!isset($_GET['vin'])) {
    die("Please provide VIN of your vehicle (it's in your MyRenault app)");
} else {
	$vin = $_GET['vin'];
}

///////////////////////////
echo "<center><b><big><big><big>Renault unofficial dashboard</big></big></big></b></center><br><br>";
echo "Syntax:<br>";
echo "https://jumpjack.altervista.org/myrenault-debug/php/history.php?pass=miapasssegretissima&username=MYRENAULT_EMAIL&password=MYRENAULT_PASSWORD&&vin=MYVIN<br><br>";
echo "You can add these parameters to the url:<br>";
echo "<b>backmonths</b>: negative number - how many months back to start data from. (Works only with '-1'?)<br>";
echo "<b>groupingType</b>: day or month<br>";
echo "<b>dateRange</b> (overrides 'backmonths'): For day grouping use 8 figures  per date(YYYYMMDD-YYYYMMDD); for month grouping use 6 figures per date (YYYYMM-YYYYMM)=20210901-20210929<br><br><br>";


if (!isset($_GET['backmonths'])) {
    $backmonths = '-1';
	echo "Back months defaults to '" . $backmonths . "'<br>\n";;
} else {
	$backmonths = $_GET['backmonths'];
	echo "Back months set to '" . $backmonths . "' by user<br>\n";;
}



if (!isset($_GET['groupingType'])) {
    $groupingType = 'day';
	echo "groupingType defaults to '" . $groupingType . "'<br>\n";;
	echo "Listing all days; you can specify to group by month by adding '&groupingType=month' in url.";
} else {
	$groupingType = $_GET['groupingType'];
	echo "groupingType  set to '" . $groupingType . "' by user<br>\n";;
}



if (!isset($_GET['dateRange'])) {
	echo "dateRange not set.<br>\n";;
} else {
    $dateRange = $_GET['dateRange'];
	$bothDates = explode("-", $dateRange);
	$startDate =  $bothDates[0];
	$endDate =  $bothDates[1];
	$rangeUrl = "&start=" . $startDate . "&end=" . $endDate;
	echo "dateRange  set to '" . $dateRange . "' by user<br>";
	echo "Result: " . $rangeUrl. '<br><br>';
}



session_cache_limiter('nocache');
require 'api-keys.php';
require 'config.php';
if (file_exists('lng/'.$country.'.php')) require 'lng/'.$country.'.php';
else require 'lng/EN.php';
header('Content-Type: text/html; charset=utf-8');
if (empty(${$country})) $gigya_api = $GB;
else $gigya_api = ${$country};

$date_today = date_create('now');
$date_today = date_format($date_today, 'md');
$update_ok = FALSE;

//Request cached login
$session = file_get_contents('session');
$session = explode('|', $session);

//Retrieve new Gigya token if the session file is outdated
if ($session[0] !== $date_today) {
  //Login Gigya
  $update_ok = TRUE;
  $postData = array(
    'ApiKey' => $gigya_api,
    'loginId' => $username,
    'password' => $password,
    'include' => 'data',
	'sessionExpiration' => 60
  );
  $ch = curl_init('https://accounts.eu1.gigya.com/accounts.login');
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
  $response = curl_exec($ch);
  if ($response === FALSE) die(curl_error($ch));  
  $responseData = json_decode($response, TRUE);
  $oauth_token = $responseData['sessionInfo']['cookieValue'];

  //Request Gigya JWT token
  $postData = array(
    'login_token' => $oauth_token,
    'ApiKey' => $gigya_api,
    'fields' => 'data.personId,data.gigyaDataCenter',
	'expiration' => 87000
  );
  $ch = curl_init('https://accounts.eu1.gigya.com/accounts.getJWT');
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
  $response = curl_exec($ch);
  if ($response === FALSE) die(curl_error($ch));
  $responseData = json_decode($response, TRUE);
  $session[1] = $responseData['id_token'];
  $session[0] = $date_today;
}


$postData = array(
    'apikey: '.$kamereon_api,
    'x-gigya-id_token: '.$session[1]
);
if ($groupingType == "day") {
	$ch = curl_init('https://api-wired-prod-1-euw1.wrd-aws.com/commerce/v1/accounts/'.$session[2].'/kamereon/kca/car-adapter/v1/cars/'.$vin.'/charge-history?country='.$country.'&type=' . $groupingType . '&start='.date("Ymd", strtotime($backmonths . " months")).'&end='.date("Ymd"));
}


if ($groupingType == "month") {
	$ch = curl_init('https://api-wired-prod-1-euw1.wrd-aws.com/commerce/v1/accounts/'.$session[2].'/kamereon/kca/car-adapter/v1/cars/'.$vin.'/charge-history?country='.$country.'&type=' . $groupingType . '&start='.date("Ym", strtotime($backmonths . " months")).'&end='.date("Ym"));
}

if (isset($_GET['dateRange'])) {
	$finalUrl = 'https://api-wired-prod-1-euw1.wrd-aws.com/commerce/v1/accounts/'.$session[2].'/kamereon/kca/car-adapter/v1/cars/'.$vin.'/charge-history?country=' . $country . '&type=' . $groupingType  .	$rangeUrl;
	echo "Request url:<br>\n";
	echo  str_replace ( $vin ,'xxx ',  str_replace  ( $session[2],'xxx',    $finalUrl  )) ;
	$ch = curl_init($finalUrl);
}


curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, $postData);
$response = curl_exec($ch);
if ($response === FALSE) die(curl_error($ch));

$response = str_replace($vin, "xxxxxxxxxxxxxx", $response); // obfuscate personal data

$responseData = json_decode($response, TRUE);
$data = array();
if (isset($responseData['data']['attributes']['chargeSummaries'])) {
	 $data = $responseData['data']['attributes']['chargeSummaries'];
} else {
	echo "<br>\n<br>\n<b>Sorry, could not retrieve data, please try again later.</b><br>\n";
	echo "<br>\nRaw response:<br>\n";
	echo $response;
	die("<br>\nTerminated.<br>\n");
}


//Output
echo '<HTML>'."\n".'<HEAD>'."\n".'<LINK REL="stylesheet" HREF="stylesheet.css">'."\n".'<META NAME="viewport" CONTENT="width=device-width, initial-scale=1.0">'."\n".'<TITLE>Charges history</TITLE>'."\n".'</HEAD>'."\n".'<BODY>'."\n".'<DIV ID="container">'."\n".'<MAIN>'."\n".'<ARTICLE>'."\n".'<TABLE border="0">'."\n".'<TR ALIGN="left"><TH>Charges history</TH></TR>'."\n".'<TR><TD COLSPAN="2"><HR></TD></TR>';
echo '<tr><td>'.$lng[140].'</td><td>'.$lng[141].'</td><td>'.$lng[142].'</td><td>'.$lng[44].'</td></tr>';

for ($i = 0; $i < count($data); $i++) {
  if (!empty($data[$i]['day']) ) {
	$sd = $data[$i]['day'];
    echo '<TR><TD>'.$sd.'</TD><TD>'.$data[$i]['totalChargesNumber'].'</TD><TD>'.$data[$i]['totalChargesEnergyRecovered'].'</TD><TD>'.$data[$i]['totalChargesDuration'].'</TD></TR>';
  }
	if (!empty($data[$i]['month']) ) {
	$sd = $data[$i]['month'];
    echo '<TR><TD>'.$sd.'</TD><TD>'.$data[$i]['totalChargesNumber'].'</TD><TD>'.$data[$i]['totalChargesEnergyRecovered'].'</TD><TD>'.$data[$i]['totalChargesDuration'].'</TD></TR>';
  }
}
echo '<TR><TD COLSPAN="2"><A HREF="./">'.$lng[48].'</A></TD></TR>'."\n".'</TABLE>'."\n".'</ARTICLE>'."\n";
echo '</MAIN>'."\n".'</DIV>'."\n".'</BODY>'."\n".'</HTML>';

echo "<br><br><br>Raw response for debugging:<br><br><pre>";
echo json_encode(json_decode($response), JSON_PRETTY_PRINT);;
echo "</pre><br><br>----------------------------------<br><br>";

$dayTemplate = '{
  "data": {
    "type": "Car",
    "id": "xxxxxx",
    "attributes": {
      "chargeSummaries": [
        {
          "day": "20210901",
          "totalChargesDuration": 223
        },
        {
          "day": "20210902",
          "totalChargesNumber": 1,
          "totalChargesEnergyRecovered": 2.8,
          "totalChargesDuration": 101
        },
        {
          "day": "20210903",
          "totalChargesNumber": 1,
          "totalChargesEnergyRecovered": 3.15,
          "totalChargesDuration": 109
        }
      ]
    }
  }
}';


$monthTemplate = '{"data":{"type":"Car","id":"xxxxxx","attributes":{"chargeSummaries":[{"month":"202101","totalChargesDuration":66}]}}}';;
$monthTemplate_Pretty = json_encode(json_decode($monthTemplate), JSON_PRETTY_PRINT);


echo "<br><br><b>Day-grouping response template:</b><br>";
echo "<pre>" . $dayTemplate . "</pre>";


echo "<br><b>Month-grouping response template:</b><br>";
echo "<pre>" . $monthTemplate_Pretty . "</pre>";
echo "<br><br><br><br>";
echo "Source: <a href='https://github.com/jumpjack/RenaultPHP_LC/tree/main/src'>Github</a><br><br><br>";

curl_close($ch);

//Cache new Gigya token
if ($update_ok === TRUE) {
  $session = implode('|', $session);
  file_put_contents('session', $session);
}
?>