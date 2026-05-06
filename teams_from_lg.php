
<?php

require('./assets/api.php');

include('./assets/variables.php');

include('./assets/translations.php');

// Kan weg bij include.. 

$season = 2025;

$cur_url_teams_in_league = 'https://v3.football.api-sports.io/teams?league=88&season=' .$season;

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

echo '
<div class="container_logos">';


for ($i=0; $i < $response_teams_in_league['results']; $i++) {

// inbouwen, gebaseerd op teams..
 /*<img '.($team['value'] == $selected_team_logo ? setcookie('teams_team_selection', $team['value'], time() + 3600, '/', '', true)
 : null) . ' src= "https://media.api-sports.io/football/teams/'. $team['value']. '.png"/> 
 */

echo '
<div id="logo_club">
<form action="./teams.php" method="post">
<input type="hidden" id="team_code" name="team_code" value='. $response_teams_in_league['response'][$i]['team']['id'] .'> 
<button type="submit" name="send_team" id="send_team"> 
<img class="league_icon" src='.$response_teams_in_league['response'][$i]['team']['logo'].'>
</button>
</form>
</div>';
}

echo '</div>';
    
?>
