<?php 


$curl = curl_init();

$cur_url = 'http://ip-api.com/php/?fields=timezone';

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

if ($response['timezone'] === '') {
 $curr_timezone === 'Europe/Amsterdam';
}
else {
  $curr_timezone = $response['timezone'];
}

date_default_timezone_set($curr_timezone);

?>
