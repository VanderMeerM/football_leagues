<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eredivisieclubs</title>  
    <link rel="shortcut icon" href="https://www.api-football.com/public/img/favicon.ico">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/smoothness/jquery-ui.css"> 
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
   <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>
   <link rel="stylesheet" type="text/css" href="./teams.css" />   
  
</head>

<body>

<?php

require('./assets/api.php');

include('./assets/variables.php');

include('./assets/translations.php');

// Kan weg bij include.. 

$season = 2025;

$cur_url = 'https://v3.football.api-sports.io/teams?league=88&season=' .$season;

$curl = curl_init();

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
     'x-rapidapi-key: ' . $api_key .'',
     'x-rapidapi-host: v3.football.api-sports.io',
    
  ),
));


$response = curl_exec($curl);

$response = json_decode($response, true);

//print_r($response['results']);


for ($i=0; $i < $response['results']; $i++) {

//echo $response['response'][$i]['team']['id'];
//echo $response['response'][$i]['team']['name'];


// inbouwen, gebaseerd op teams..
 /*<img '.($team['value'] == $selected_team_logo ? setcookie('teams_team_selection', $team['value'], time() + 3600, '/', '', true)
 : null) . ' src= "https://media.api-sports.io/football/teams/'. $team['value']. '.png"/> 
 */

echo '
<form action="./teams.php" method="post">
<input type="hidden" id="team_code" name="team_code" value='. $response['response'][$i]['team']['id'] .'> 
<button type="submit" name="send_team" id="send_team"> 
<img class="league_icon" src='.$response['response'][$i]['team']['logo'].'>
</button>
</form>';
}
    
?>

</body>
</html>