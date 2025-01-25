<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Standings</title>  
    <link rel="shortcut icon" href="https://www.api-football.com/public/img/favicon.ico">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/smoothness/jquery-ui.css"> 
    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
   <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>
   <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

   <link rel="stylesheet" type="text/css" href="./teams.css" />   
  
</head>
<body>

<?php 

include('./variables.php');

include('./translations.php');

include('./league_header.php');

$json_standings_path = './JSON/standings/standing_'. $league_id . '_season_'. $selected_season . ($selected_season + 1) . '.json'; 


if (file_exists($json_standings_path)) {

  $response_json_standing = file_get_contents($json_standings_path, true);

  $response = json_decode($response_json_standing, true);

}

else {

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

  $response= json_decode($response, true);

}
/* 

Onderstaande twee regels uitcommentariëren 

$json_standings = './JSON/standings_88.json';

$response = file_get_contents($json_standings, true);
*/

// Oudere standen opslaan (indien seizoen is afgelopen)...

if ( (date('Y') >  ($_GET['season'] + 1)) || 
(date('Y') ==  ($_GET['season'] + 1)) && (date('m') >= 6) 
&& (!file_exists($json_standings_path)) ) {

  $json_file_standing = fopen($json_standings_path, "w");
  
  fwrite($json_file_standing, json_encode($response));
  
  fclose($json_file_standing);
      
 }

$numTeams = sizeof($response['response'][0]['league']['standings'][0]);

$last_update = $response['response'][0]['league']['standings'][0][0]['update']; 

$show_qualifications = floor( 0.8 * (($numTeams * 2) -2));

echo 
'
<div class="top_standings">
<div id="standing_update"><i>Laatste update: ' . date('d-m-Y H:i', strtotime($last_update)) . '</i></div>

<table>
<tr>';

for ($i = 0; $i < $numTeams; $i++) {

  $won_matches = $response['response'][0]['league']['standings'][0][$i]['all']['win'];
  $draw_matches = $response['response'][0]['league']['standings'][0][$i]['all']['draw'];
  $lost_matches = $response['response'][0]['league']['standings'][0][$i]['all']['lose'];
  $description_qualifications = $response['response'][0]['league']['standings'][0][$i]['description'];
  $played_matches = $won_matches + $draw_matches + $lost_matches;
  $played_to_show_qualifications = intval($response['response'][0]['league']['standings'][0][0]['all']['played']);
  $points = (3 * $won_matches) + $draw_matches; 

  if ($points != $response['response'][0]['league']['standings'][0][$i]['points']) {
    $points = $response['response'][0]['league']['standings'][0][$i]['points'];
  }

 
  $goals_for = $response['response'][0]['league']['standings'][0][$i]['all']['goals']['for'];
  $goals_against = $response['response'][0]['league']['standings'][0][$i]['all']['goals']['against'];
  $goals_diff = $goals_for - $goals_against;  

echo '<td>' . $response['response'][0]['league']['standings'][0][$i]['rank'] .  '. </td>' .

'<td id="hidden_cell"><img class="logo_standings" src=' . $response['response'][0]['league']['standings'][0][$i]['team']['logo'] . '></td>' .  

'<td>' . $response['response'][0]['league']['standings'][0][$i]['team']['name'] . '</td>' .  

'<td>' . $played_matches . '</td>' . 

'<td id="hidden_cell">' . $won_matches . '</td>' . 

'<td id="hidden_cell">' . $draw_matches . '</td>' . 

'<td id="hidden_cell">' . $lost_matches . '</td>' . 

'<td><strong>' . $points . '</strong></td>' . 

'<td id="hidden_cell">' . $goals_for . '</td>' . 

'<td id="hidden_cell">' . $goals_against . '</td>' . 

'<td> ('.  ($goals_diff > 0 ? '+' : null) . $goals_diff . ') 

</td> 
</tr>';

if ($played_to_show_qualifications >= $show_qualifications) {

  if (array_key_exists($description_qualifications, $array_standings)) {
    $description_qualifications = $array_standings[$description_qualifications];
  }

echo '
<tr>
<td colspan="6"><div class="text-sm italic">' . $description_qualifications . '</div>
</td>
</tr>';
}

 
}

  echo '</table>
  </div>';

?>

</body>

</html>
