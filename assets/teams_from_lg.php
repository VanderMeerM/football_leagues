<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" type="text/css" href="../assets/teams.css" />   


</head>
<body>
  
<?php

require('../assets/api.php');

include('../assets/variables.php');

include('../assets/translations.php');


echo '<div class="container_logos">';

for ($i=0; $i < sizeof($array_reg_leagues); $i++) {
  echo '
  <form action="" method="post">
  <input type="hidden" name="sel_league" id="sel_league" value= '.$array_leagues[$i].'>
   <input type="hidden" id="no_scroll" name="no_scroll" value= "yes"> 
  <button type="submit" name="send_league" id="send_league"> 
  <img class="league_icon" src="https://media.api-sports.io/football/leagues/' . $array_leagues[$i] . '.png"/>
  </button>

  </form>';
}

echo '</div>';

$cur_url_teams_in_league = 'https://v3.football.api-sports.io/teams?league='.$_POST['sel_league'].'&season=' .$season;

$curl_teams_in_league = curl_init();

curl_setopt_array($curl_teams_in_league, array(
  CURLOPT_URL => $cur_url_teams_in_league,
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


$response_teams_in_league = curl_exec($curl_teams_in_league);

$response_teams_in_league = json_decode($response_teams_in_league, true);

$response_teams_in_league = $response_teams_in_league['response'];

$country_code = $response_teams_in_league[0]['team']['country'];

// Teams sorteren op naam...

$teams_on_name = [];

for ($i=0; $i < sizeof($response_teams_in_league); $i++) {

    array_push($teams_on_name, $response_teams_in_league[$i]['team']['name']); 
  }
 
asort($teams_on_name);

$teams_on_name_keys = array_keys($teams_on_name);

for ($i=0; $i < sizeof($teams_on_name_keys); $i++) {
  
  $teams_on_name_sorted[] = $response_teams_in_league[$teams_on_name_keys[$i]];
}

$response_teams_in_league = $teams_on_name_sorted;


if ($response_teams_in_league) {

echo '<div class="container_leagues">';

for ($i=0; $i < sizeof($response_teams_in_league); $i++) {

// inbouwen, gebaseerd op teams..
 /*<img '.($team['value'] == $selected_team_logo ? setcookie('teams_team_selection', $team['value'], time() + 3600, '/', '', true)
 : null) . ' src= "https://media.api-sports.io/football/teams/'. $team['value']. '.png"/> 
 */

echo '

<div id="logo_club">

<form action="./" method="post">

<input type="hidden" id="team_code" name="team_code" value='. $response_teams_in_league[$i]['team']['id'] .'> 
<input type="hidden" id="country_code" name="country_code" value= '.$country_code.'> 
<input type="hidden" id="no_scroll" name="no_scroll" value= ""> 
<button type="submit" title="'.$response_teams_in_league[$i]['team']['name'].'" name="send_team" id="send_team"> 
<img class="league_icon" src='.$response_teams_in_league[$i]['team']['logo'].'>
</button>
</form>

</div>';
}

echo '</div>';
};

?>
