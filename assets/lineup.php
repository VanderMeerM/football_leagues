
<style>
#pointer:hover {
   cursor: pointer;
}   
</style>

<?php

$idx_home = 1;
$idx_away = 5;

require('../assets/api.php');

if ($_GET['id'] && file_exists($json_lineup_path)) {

 $response_json_lineup = file_get_contents($json_lineup_path, true);

 $response_lineup = json_decode($response_json_lineup, true);

}

else { 
 
$response_lineup = $response['response'][0]['lineups'];


// Sla opstelling op als wedstrijd bestaat... 

    if ( file_exists($json_fixture) && (!file_exists($json_lineup_path)) )

  {

   $json_file_lineup = fopen($json_lineup_path, "w");
    
   fwrite($json_file_lineup, json_encode($response_lineup));
    
   fclose($json_file_lineup);
    
   }
}

  
$num_lineups = sizeof($response_lineup);

$home_team_lineup = array();
$away_team_lineup = array();

$home_startXI = array();
$away_startXI = array();

$home_sub = array();
$away_sub = array();

// Basiselftal en wisselspelers ophalen..

if (($response_lineup[0]['startXI'] > 0 ) || 
($response_lineup[0]['startXI'] > 0 )) {

for ($i=0; $i < sizeof($response_lineup[0]['startXI']); $i++) {

    array_push($home_startXI, [
    'id' => $response_lineup[0]['startXI'][$i]['player']['id'],
    'name' => $response_lineup[0]['startXI'][$i]['player']['name'],
    'number' => $response_lineup[0]['startXI'][$i]['player']['number'],
    'position' => $response_lineup[0]['startXI'][$i]['player']['pos'],
    'grid' => $response_lineup[0]['startXI'][$i]['player']['grid']
    ]);
}

for ($i=0; $i < sizeof($response_lineup[1]['startXI']); $i++) {

    array_push($away_startXI, [
    'id' => $response_lineup[1]['startXI'][$i]['player']['id'],
    'name' => $response_lineup[1]['startXI'][$i]['player']['name'],
    'number' => $response_lineup[1]['startXI'][$i]['player']['number'],
    'position' => $response_lineup[1]['startXI'][$i]['player']['pos'],
    'grid' => $response_lineup[1]['startXI'][$i]['player']['grid']
    ]);
}

for ($i=0; $i < sizeof($response_lineup[0]['substitutes']); $i++) {

    array_push($home_sub, [
    'id' => $response_lineup[0]['substitutes'][$i]['player']['id'],
    'name' => $response_lineup[0]['substitutes'][$i]['player']['name'],
    'number' => $response_lineup[0]['substitutes'][$i]['player']['number'],
    'position' => $response_lineup[0]['substitutes'][$i]['player']['pos']
    ]);
}

for ($i=0; $i < sizeof($response_lineup[1]['substitutes']); $i++) {

    array_push($away_sub, [
    'id' => $response_lineup[1]['substitutes'][$i]['player']['id'],
    'name' => $response_lineup[1]['substitutes'][$i]['player']['name'],
    'number' => $response_lineup[1]['substitutes'][$i]['player']['number'],
    'position' => $response_lineup[1]['substitutes'][$i]['player']['pos']
    ]);
}


for ($i = 0; $i < $num_lineups; $i++) {
      
    if ($response_lineup[$i]['team']['name'] === $homeTeam) 
       {
        array_push($home_team_lineup, [ 
        'team' => $homeTeam,
        'formation' => $response_lineup[$i]['formation'], 
        'coach' => $response_lineup[$i]['coach']['name'],
        'coach_img' => $response_lineup[$i]['coach']['photo'] 
       ]);
    }

    if ($response_lineup[$i]['team']['name'] === $awayTeam) 
    {
     array_push($away_team_lineup, [ 
     'team' => $awayTeam,
     'formation' => $response_lineup[$i]['formation'], 
     'coach' => $response_lineup[$i]['coach']['name'],
     'coach_img' => $response_lineup[$i]['coach']['photo'] 
        ]);
    }
  }

$lineup_lines_home = str_replace("-","", $home_team_lineup[0]['formation']);
$lineup_lines_away = str_replace("-","", $away_team_lineup[0]['formation']);

$max_num_formation_home = $lineup_lines_home[0]; 
$max_num_formation_away = $lineup_lines_away[0]; 

for ($i=1; $i<strlen($lineup_lines_home); $i++) {
   if ($lineup_lines_home[$i] > $max_num_formation_home) {
      $max_num_formation_home = $lineup_lines_home[$i];
   }  
}

for ($i=1; $i<strlen($lineup_lines_away); $i++) {
   if ($lineup_lines_away[$i] > $max_num_formation_away) {
      $max_num_formation_away = $lineup_lines_away[$i];
   }  
}


  echo '

  <div class="btn_lineup">
  <button id="show_hide_lineup"> </button>
  </div>
  
  <div id="show_hide"> 
  
  <table>
  <tr><td>';

  // Blok coaches.. 

 echo 
 $home_team_lineup[0]['formation'] . '<br>'; 
  
  echo '<u>Coach:</u> ' . $home_team_lineup[0]['coach'] . '<br>    
  <img class="img_coach" src=' . $home_team_lineup[0]['coach_img'] . '></td> 

  <td>' 
  . $away_team_lineup[0]['formation'] . '<br>
  
  <u>Coach:</u> ' . $away_team_lineup[0]['coach'] . '<br>
  <img class="img_coach" src=' . $away_team_lineup[0]['coach_img'] . '></td>
  </tr>'; 

  
  echo '<tr><td colspan="2"> <strong>Basiselftal</strong></td></tr>
  </table>';

  $prevent_loop = true;

      echo '<div class="main_container_lineup">
     <table class="home">';

// Keeper thuisteam.. 
      echo 
      '<div class="center_formation">
      <div>
      <form method="post" action="../assets/players.php?id='.$home_startXI[0]['id'].'" target="_blank">
      <img id="pic_player" src="https://media.api-sports.io/football/players/'.$home_startXI[0]['id'].'.png">
      <input type="hidden" name="openLineup" value ="yes">
      <button id="pointer" type="submit" style="color: black">' . $home_startXI[0]['number'] . '. ' . $home_startXI[0]['name'] . '</button>              
      </form>
      </div>
      </div>'; 


   // Verdere opstelling thuisteam..

   echo '<div>';

   while ($idx_home <= 5) {

     echo '<div class="center_formation">';
   
     for ($i=1; $i < sizeof($home_startXI); $i++) {
        if (explode(':', $home_startXI[$i]['grid'])[0] == $idx_home) {
       echo '
       <div style="padding: 1% 0;"> 
        <form method="post" action="../assets/players.php?id='.$home_startXI[$i]['id'].'" target="_blank">
        <img id="pic_player" src="https://media.api-sports.io/football/players/'.$home_startXI[$i]['id'].'.png">
        <input type="hidden" name="openLineup" value ="yes">
        <button id="pointer" type="submit" style="color: black">' . $home_startXI[$i]['number'] . '. ' . $home_startXI[$i]['name'] . '</button>              
        </form>
        </div>';
         }
      }
     echo '</div>'; 
      $idx_home++;
     }


 // Opstelling uitteam..

 echo '<div style="margin-top: 5%;">';

    while ($idx_away > 1) {
   
   echo '<div class="center_formation">';

     for ($i=1; $i < sizeof($away_startXI); $i++) {
        if (explode(':', $away_startXI[$i]['grid'])[0] == $idx_away) {
       echo '
       <div style="padding: 1% 0;">
        <form method="post" action="../assets/players.php?id='.$away_startXI[$i]['id'].'" target="_blank">
        <img id="pic_player" src="https://media.api-sports.io/football/players/'.$away_startXI[$i]['id'].'.png">
        <input type="hidden" name="openLineup" value ="yes">
        <button id="pointer" type="submit" style="color: black">' . $away_startXI[$i]['number'] . '. ' . $away_startXI[$i]['name'] . '</button>              
        </form>
      </div>';
         }
      }
      echo '</div>';
      $idx_away--;
     }


     // Keeper uitteam.. 
   echo 
      '<div class="center_formation">
      <div>
      <form method="post" action="../assets/players.php?id='.$away_startXI[0]['id'].'" target="_blank">
      <img id="pic_player" src="https://media.api-sports.io/football/players/'.$away_startXI[0]['id'].'.png">
      <input type="hidden" name="openLineup" value ="yes">
      <button id="pointer" type="submit" style="color: black">' . $away_startXI[0]['number'] . '. ' . $away_startXI[0]['name'] . '</button>              
      </form>
      </div>
      </div>'; 


  echo '
  <table>
  <tr><td colspan="2"> <strong>Wisselspelers</strong></td></tr>
  
  <div class="main_container_lineup">';

     if (sizeof($home_sub) > sizeof($away_sub)) {
        $num_sub = $home_sub;
     } else {
        $num_sub = $away_sub;
     }

     for ($i=0; $i < sizeof($num_sub); $i++ ) {

       echo  '
        <tr>

      <td>
        <form method="post" action="../assets/players.php?id='.$home_sub[$i]['id'].'" target="_blank">
        <input type="hidden" name="openLineup" value ="yes">
        <button id="pointer" type="submit" style="color: black">' . $home_sub[$i]['number'] . '. ' . $home_sub[$i]['name'] . '</button>              
        </form>
        </td> 

       <td>
        <form method="post" action="../assets/players.php?id='.$away_sub[$i]['id'].'" target="_blank">
        <input type="hidden" name="openLineup" value ="yes">
        <button id="pointer" type="submit" style="color: black">' . $away_sub[$i]['number'] . '. ' . $away_sub[$i]['name'] . '</button>              
        </form>
        </td>'; 
     }

     echo '
     </tr>
     </table>';

    }
    else {
        echo '<div class="nomatches"> Geen details beschikbaar </div>';

    }

?>

<script>
    let showLineup = true;

    const showHideLineup = document.getElementById('show_hide_lineup');

    const showHideId = document.querySelector('#show_hide');

    showHideLineup.innerText = 'Toon opstellingen';

    function hideLineUp() {
          showHideId.removeAttribute('class', 'hide');
    showHideLineup.innerText = 'Verberg opstellingen';
    }

     function showLineUp() {
       showHideId.setAttribute('class', 'hide');
    showHideLineup.innerText = 'Toon opstellingen';
      localStorage.removeItem("lineup");

    }

    if (localStorage.getItem("lineup")) {
      hideLineUp();
    showLineup = !showLineup;
    } 
    else {
   showLineUp();
    }

    showHideLineup.addEventListener('click', () => {

        showLineup = !showLineup;

        if (showLineup) {
           showLineUp();   
        } 
        else {
           hideLineUp();
        }       

        })
</script>