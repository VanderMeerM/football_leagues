<?php 

// IP-adres client achterhalen..

$data_ip = file_get_contents('https://api.ipify.org?format=json');  
$data_ip = json_decode($data_ip, true); 

$curr_ip = $data_ip['ip'];


// Tijdzone bij IP-adres achterhalen..

$curl = curl_init();

$cur_url = 'http://ip-api.com/php/'.$curr_ip.'?fields=timezone';

curl_setopt_array($curl, array(
  CURLOPT_URL => $cur_url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    ),
));

$response = curl_exec($curl);

$response = unserialize($response); 

$curr_timezone = $response['timezone'];

/*
if ($curr_timezone = '') {
 $curr_timezone = 'Europe/Amsterdam';
}
else {
  $curr_timezone = $response['timezone'];
}
*/

// Tijdzone instellen...

date_default_timezone_set($curr_timezone);

?>
