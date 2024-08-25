<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Standings</title>  
    <link rel="shortcut icon" href="https://www.api-football.com/public/img/favicon.ico">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/smoothness/jquery-ui.css"> 
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
   <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>
   <link rel="stylesheet" type="text/css" href="./teams.css" />   
  
</head>
<body>

<?php 

include('./translations.php');

//include('./league_header.php');


$curl = curl_init();

$league_id = $_GET['league'];
$current_season = $_GET['season'];

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://v3.football.api-sports.io/standings?&league=' . $league_id . '&season='. $current_season,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'x-rapidapi-key: f7e1aa54fd70dd93a3c920f503282930',
    'x-rapidapi-host: v3.football.api-sports.io'
  )));
 

  $response = curl_exec($curl);

  curl_close($curl);
  

//$json_standings = './JSON/standings_88.json';

//$response = file_get_contents($json_standings, true);

$response= json_decode($response, true);

//print_r ($response['response'][0]['league']['standings'][0]);
 
for ($i = 0; $i < 18; $i++) {

echo $response['response'][0]['league']['standings'][0][$i]['rank'] .  '. ' .

'<img class="logo_standings" src=' . $response['response'][0]['league']['standings'][0][$i]['team']['logo'] . '>' .  

$response['response'][0]['league']['standings'][0][$i]['team']['name'] . '   ' . 

$response['response'][0]['league']['standings'][0][$i]['all']['played'] . ' - ' .

$response['response'][0]['league']['standings'][0][$i]['all']['win']  . ' - ' . 

$response['response'][0]['league']['standings'][0][$i]['all']['draw']  . ' - ' . 

$response['response'][0]['league']['standings'][0][$i]['all']['lose']  . ' - ' . 

$response['response'][0]['league']['standings'][0][$i]['points']  . ' - ' . 

$response['response'][0]['league']['standings'][0][$i]['all']['goals']['for']  . ' - ' . 

$response['response'][0]['league']['standings'][0][$i]['all']['goals']['against']  .  

' ('.  ($response['response'][0]['league']['standings'][0][$i]['goalsDiff'] > 0 ? '+' : null) . $response['response'][0]['league']['standings'][0][$i]['goalsDiff']  . ')' . 

 '<p>';

  }

?>

</body>

</html>
