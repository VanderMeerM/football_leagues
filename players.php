
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

    ?>
    <script>
     document.title= 'Over <?php echo json_encode($response_player['response'][0]['player']['name']); ?>';
</script>

<?php

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
 
?>

<style>

table {
    margin: auto;
    width: 30%;
    border-collapse: collapse;
}

table tr td img {
    display: block;
    margin: 0 auto;
}

td {
    width: 50%;
    padding: 2%;
}


</style>

<?php 

echo '<br> 

<table>

<tr>
<td colspan="2">
<img src='. $response_player['response'][0]['player']['photo'] .'>
</td>
</tr>
<tr>
<td valign="top"> Naam: </td> 
<td> ' . $response_player['response'][0]['player']['firstname'] . ' ' . $response_player['response'][0]['player']['lastname'] .

'</td></tr>

<tr><td valign="top">
Geboren op: </td>
<td> ' .$response_player['response'][0]['player']['birth']['date'] . 
' (' . $response_player['response'][0]['player']['age'] .' jaar) 
<br>te ' .$response_player['response'][0]['player']['birth']['place'] . ' (' 
.   (array_key_exists($response_player['response'][0]['player']['birth']['country'], $array_countries) ?  
         $array_countries[$response_player['response'][0]['player']['birth']['country']] : 
         $response_player['response'][0]['player']['birth']['country']) .')</td></tr> 

<tr><td>
Nationaliteit: </td>
<td> ' . (array_key_exists($response_player['response'][0]['player']['birth']['nationality'], $array_countries) ?  
         $array_countries[$response_player['response'][0]['player']['birth']['nationality']] : 
         $response_player['response'][0]['player']['birth']['nationality']) . ')
         </td></tr> 
         </td></tr>

<tr><td>
Positie: </td><td> ' 
        . (array_key_exists($response_player['response'][0]['player']['position'], $array_position) ?  
         $array_position[$response_player['response'][0]['player']['position']] : 
         $response_player['response'][0]['player']['position']) . 
         '</td></tr>';  



$array_teams = array_reverse($response_player_teams['response']); 


echo '<td colspan="2"><strong>Clubs</strong></td>';

for ($i=0; $i < sizeof($array_teams); $i++) {

echo 
'<tr><td>' . $array_teams[$i]['team']['name'] . '</td>
<td>'; 

$counter = 0; 

foreach (array_reverse($array_teams[$i]['seasons']) as $seasons) {
    echo 
    $seasons . ($counter < (count($array_teams[$i]['seasons']) - 1) ? ', ' : null);
    $counter++;  

}}

echo 
'</td></tr>
</table>';


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

