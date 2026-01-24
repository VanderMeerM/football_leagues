
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    


<?php

//require('./api.php');


// Profiel speler ophalen... 

    $curl_player = curl_init();
    
    curl_setopt_array($curl_player, array(
      CURLOPT_URL => 'https://v3.football.api-sports.io/players/profiles?player=' . $_GET['id']. '', 
     
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
         //'x-rapidapi-key: ' . $api_key .'',
     'x-rapidapi-key: 863bcd048478f98225b64bced629b376',
        'x-rapidapi-host: v3.football.api-sports.io',
        
      ),
    ));
    
    $response_player = curl_exec($curl_player);
    
    curl_close($curl_player);

    $response_player = json_decode($response_player, true);


// Teams van speler ophalen...

 $curl_player_teams = curl_init();
    
    curl_setopt_array($curl_player_teams, array(
      CURLOPT_URL => 'https://v3.football.api-sports.io/players/teams?player=' . $_GET['id']. '', 
     
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
         //'x-rapidapi-key: ' . $api_key .'',
     'x-rapidapi-key: 863bcd048478f98225b64bced629b376',
        'x-rapidapi-host: v3.football.api-sports.io',
        
      ),
    ));
    
  $response_player_teams = curl_exec($curl_player_teams);
    
    curl_close($curl_player_teams);

$response_player_teams = json_decode($response_player_teams, true);


    // Sla speler op ... 

   /* if ( file_exists($json_players) && (!file_exists($json_players_path)) )

  {

   $json_file_lineup = fopen($json_lineup_path, "w");
    
   fwrite($json_file_lineup, json_encode($response_lineup));
    
   fclose($json_file_lineup);
    
   }
   */


echo '<br> 

<div style="display:flex; justify-content: center">

<table>

<tr><td>
<img src='. $response_player['response'][0]['player']['photo'] .'>
</td></tr>
 
<tr>
<td> Naam: </td> 
<td> ' . $response_player['response'][0]['player']['firstname'] . ' ' . $response_player['response'][0]['player']['lastname'] .

'</td></tr>

<tr><td>
Geboren: </td>
<td> ' .$response_player['response'][0]['player']['birth']['date'] . 
' (' . $response_player['response'][0]['player']['age'] .' jaar) 
</td></tr> 

<tr><td>
Nationaliteit: </td>
<td> ' . $response_player['response'][0]['player']['nationality'] .
'</td></tr>';  



$array_teams = array_reverse($response_player_teams['response']); 

echo '<tr><td><strong>Clubs</strong></td></tr>';

for ($i=0; $i < sizeof($array_teams); $i++) {

echo 
'<tr><td>' . $array_teams[$i]['team']['name'] . '</td>
<td>'; 

foreach (array_reverse($array_teams[$i]['seasons']) as $seasons) {
    echo 
    $seasons .',';  

}}

echo 
'</td></tr>
</table>
</div>';


  /*
 $num_lineups = $response_lineup['results'];

$home_team_lineup = array();
$away_team_lineup = array();

$home_startXI = array();
$away_startXI = array();

$home_sub = array();
$away_sub = array();

if (($response_lineup['response'][0]['startXI'] > 0 ) || 
($response_lineup['response'][0]['startXI'] >0 )) {

for ($i=0; $i < sizeof($response_lineup['response'][0]['startXI']); $i++) {

    array_push($home_startXI, [
    'name' => $response_lineup['response'][0]['startXI'][$i]['player']['name'],
    'number' => $response_lineup['response'][0]['startXI'][$i]['player']['number'],
    'position' => $response_lineup['response'][0]['startXI'][$i]['player']['pos']
    ]);
}

for ($i=0; $i < sizeof($response_lineup['response'][1]['startXI']); $i++) {

    array_push($away_startXI, [
    'name' => $response_lineup['response'][1]['startXI'][$i]['player']['name'],
    'number' => $response_lineup['response'][1]['startXI'][$i]['player']['number'],
    'position' => $response_lineup['response'][1]['startXI'][$i]['player']['pos']
    ]);
}

for ($i=0; $i < sizeof($response_lineup['response'][0]['substitutes']); $i++) {

    array_push($home_sub, [
    'name' => $response_lineup['response'][0]['substitutes'][$i]['player']['name'],
    'number' => $response_lineup['response'][0]['substitutes'][$i]['player']['number'],
    'position' => $response_lineup['response'][0]['substitutes'][$i]['player']['pos']
    ]);
}

for ($i=0; $i < sizeof($response_lineup['response'][1]['substitutes']); $i++) {

    array_push($away_sub, [
    'name' => $response_lineup['response'][1]['substitutes'][$i]['player']['name'],
    'number' => $response_lineup['response'][1]['substitutes'][$i]['player']['number'],
    'position' => $response_lineup['response'][1]['substitutes'][$i]['player']['pos']
    ]);
}


for ($i = 0; $i < $num_lineups; $i++) {
      
    if ($response_lineup['response'][$i]['team']['name'] === $homeTeam) 
       {
        array_push($home_team_lineup, [ 
        'team' => $homeTeam,
        'formation' => $response_lineup['response'][$i]['formation'], 
        'coach' => $response_lineup['response'][$i]['coach']['name'],
        'coach_img' => $response_lineup['response'][$i]['coach']['photo'] 
       ]);
    }

    if ($response_lineup['response'][$i]['team']['name'] === $awayTeam) 
    {
     array_push($away_team_lineup, [ 
     'team' => $awayTeam,
     'formation' => $response_lineup['response'][$i]['formation'], 
     'coach' => $response_lineup['response'][$i]['coach']['name'],
     'coach_img' => $response_lineup['response'][$i]['coach']['photo'] 
        ]);
    }
  }


  echo '

  <div class="btn_lineup">
  <button id="show_hide_lineup"> </button>
  </div>
  
  <div id="show_hide"> 
  <div class="main_container_lineup_coach">
  <div class="lineup_container">
  <div>' 
  . $home_team_lineup[0]['formation'] .
  '</div>' .
 
  '<u>Coach:</u> ' . $home_team_lineup[0]['coach'] . 
  '<div>
  <img class="img_coach" src=' . $home_team_lineup[0]['coach_img'] . '>
  </div>
  </div> 

  <div class="lineup_container">
  <div>' 
  . $away_team_lineup[0]['formation'] .  
  '</div>' .
 
  '<u>Coach:</u> ' . $away_team_lineup[0]['coach'] . 
  '<div>
  <img class="img_coach" src=' . $away_team_lineup[0]['coach_img'] . '>
  </div>
  </div>
  </div>'; 

  echo '<div id="start_sub_team">Basiselftal</div>';

     $prevent_loop = true;

     echo '<div class="main_container_lineup">
     <table>';

     //<div class="main_container_lineup_home">';

     for ($i=0; $i < sizeof($home_startXI); $i++ ) {

     // for ($a=0; $a < sizeof($away_startXI); $a++ ) {

        echo  '
        <tr>
        <td> ' . $home_startXI[$i]['number'] . '. ' . $home_startXI[$i]['name'] . '</td> 
        <td>' .  $away_startXI[$i]['number'] . '. ' . $away_startXI[$i]['name'] . '</div>';
        echo '</tr>'; 
     }

     echo '</table>';

     echo '<div id="start_sub_team">Wisselspelers</div>';

     echo '<div class="main_container_lineup">
     <table>';

     //<div class="main_container_lineup_home">';

     if (sizeof($home_sub) > sizeof($away_sub)) {
        $num_sub = $home_sub;
     } else {
        $num_sub = $away_sub;
     }

     for ($i=0; $i < sizeof($num_sub); $i++ ) {

       echo  '
        <tr>
        <td> ' . $home_sub[$i]['number'] . '. ' . $home_sub[$i]['name'] . '</td> 
        <td>' .  $away_sub[$i]['number'] . '. ' . $away_sub[$i]['name'] . '</div>';
        echo '</tr>'; 
     }

     echo '</table>';

     /*
        echo  '    
        <div class="lineup_container"><span class="align-left">' .
        $home_sub[$i]['number'] . '. ' . $home_sub[$i]['name'] . 
        '</span></div>';
     

     echo '</div>
    <div class="main_container_lineup_away">';


     for ($i=0; $i < sizeof($away_sub); $i++ ) {

        echo '
        <div class="lineup_container"><span class="align-left">' .
        $away_sub[$i]['number'] . '. ' . $away_sub[$i]['name'] . 
        '</div>';
     }

     echo '</div>
     </div>
     </div>';

    }
    else {
        echo '<div class="nomatches"> Geen details beschikbaar </div>';

    }
*/
?>

</body>
</html>

