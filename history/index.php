<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Historische confrontaties</title>  
    <link rel="shortcut icon" href="https://www.api-football.com/public/img/favicon.ico">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/smoothness/jquery-ui.css">
 
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
   <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>
   <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

   <link rel="stylesheet" type="text/css" href="../assets/teams.css" />   
  
</head>

<body>

<script>

// Direct pagina laden bij aanklikken tabblad in browser.. 

document.addEventListener("visibilitychange", function() {
    if (!document.hidden){
       window.location.reload();
    }
});
</script>

<style>
  
.main_container_teams {
  display: flex;
}

.container_teamA, 
.container_teamB {
    width: 50%;
    display: flex;
    flex-direction: column;
    margin: 5% auto;
    align-items: center;
}

.selection_teams {
    display: flex;
    justify-content: left;
    margin: 5%;
  }

.container_search {
    margin: auto;
    width: fit-content;
  }

  #btn_search {
 background-color: #002e61;
 padding: 2%;
 width: 100px;
 color: white;
  }
</style>

<?php 

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

$all_teams = [];
$all_teams_response = [];
$name_teams = [];
$array_leagues_found_matches = [];
$ind = 0; 

// Deze regels straks weer verwijderen/uitcommentarieren...
/*
$team_json_file = './all_teams.json';
$teams = fopen($team_json_file, 'r');
fclose($teams);
$response_json = file_get_contents($team_json_file, true);
$response = json_decode($response_json, true);
array_push($all_teams, $response);
*/


require('../assets/api.php');

include('../assets/variables.php');

include('../assets/translations.php');

include('../assets/getTZ.php');


// Deze regels eventueel uitcommentariëren (bij inzet json boven)..

for ($i=0; $i < sizeof($array_reg_leagues); $i++) { 
   $cur_url = 'https://v3.football.api-sports.io/teams?league='. $array_reg_leagues[$i] . '&season='. $current_season;
   
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

array_push($all_teams, $response);
}


// Teams van competities in array plaatsen... 

for ($i=0; $i < sizeof($all_teams); $i++) {

array_push($all_teams_response, $all_teams[$i]['response']);

}


 // Nederlandse clubs bovenaan in selectie.. 

 /*
$dutch_leagues = $all_teams_response[0]; 

$dt_names = array_column($dutch_leagues, 'name');
//$dt_leagues = array_column($dutch_leagues, 'main_league');

 array_multisort($dt_names, SORT_ASC, $dutch_leagues);


$rest_leagues = unset($all_teams_response[0], $all_teams_response[1]); 

 $names = array_column($rest_leagues, 'name');
 $leagues = array_column($rest_leagues, 'main_league');

 array_multisort($leagues, SORT_ASC, $names, SORT_ASC, $rest_leagues); 

$leagues_sorted = array_merge($dutch_leagues, $rest_leagues);
*/

// in array met id, land en naam team...

while ($ind < sizeof($all_teams_response)) {

for ($i=0; $i < sizeof($all_teams_response[$ind]); $i++) {


 // Nederlandse clubs bovenaan in selectie (vervolg).. 

/*
if ($all_teams_response[$ind][$i]['team']['country'] === 'Netherlands') {

  array_push($dutch_leagues, [
  'id'=> $all_teams_response[$ind][$i]['team']['id'], 
  'name' => $all_teams_response[$ind][$i]['team']['name'], 
  'country' => $all_teams_response[$ind][$i]['team']['country'], 
  'main_league' => $all_teams[$ind]['parameters']['league']
  ]);

  $dutch_names = array_column($dutch_leagues, 'name');
  $dutch_main_leagues = array_column($dutch_leagues, 'main_league');

  array_multisort($dutch_main_leagues, SORT_ASC, $dutch_names, SORT_ASC, $dutch_leagues);
 } 
 
 else {
  */
  array_push($name_teams, [
  'id'=> $all_teams_response[$ind][$i]['team']['id'], 
  'name' => $all_teams_response[$ind][$i]['team']['name'], 
  'country' => $all_teams_response[$ind][$i]['team']['country'], 
  'main_league' => $all_teams_response[$ind]['parameters']['league']
  ]);

  
  $names = array_column($name_teams, 'name');
  $countries = array_column($name_teams, 'country');

  array_multisort($countries, SORT_ASC, $names, SORT_ASC, $name_teams);
  }

  $ind++;
}


include('./menu_history.php');

if (!$_GET['id']) {

echo '<div id="no_found_matches" style="font-weight: bold; font-style:normal">Confrontaties uit het verleden <br>
(seizoen '. ($current_season - 11) .'-' . ($current_season - 10) .' t/m '.($current_season-1).'-'.$current_season.')</div>'; 


if ($_POST['TeamA'] != 'Selecteer een team:') {   
setcookie("TeamA", $_POST['TeamA'], (time() + 3600), "/", '');
}

if ( ($_POST['TeamB'] != 'Selecteer een team:') && ($_POST['TeamA']) ) {   
setcookie("TeamB", $_POST['TeamB'], (time() + 3600), "/", '');
setcookie("TeamA", $_POST['TeamA'], (time() + 3600), "/", '');
}

echo '
<div class="main_container_teams">

<div class="container_teamA">';

if ($_POST['TeamA']) {

  $image_id = explode('-', $_POST['TeamA'])[0];

} else {
  $image_id = explode('-', $_COOKIE['TeamA'])[0]; 
}

if ($image_id != '') {
  
echo '
<div>
  <img src="https://media.api-sports.io/football/teams/'.$image_id.'.png">
</div>';  
}

echo '
<div class="selection_teams">

<form method="post">

<select name="TeamA" onchange="this.form.submit()">

<option selected disabled> Selecteer een team </option>';

 if ($_POST['TeamA'] ) {
           $selta = explode('-', $_POST['TeamA'])[0]; 
            }
            elseif ( ($_COOKIE['TeamA']) && ($_COOKIE['TeamA'] != '') ) {
               $selta = explode('-', $_COOKIE['TeamA'])[0];  
            }

for ($i=0; $i < sizeof($name_teams); $i++) {
  echo '<option ' . ($name_teams[$i]['id'] == $selta ? 'selected' : null) .' 
  value='.$name_teams[$i]['id'].'-' .$name_teams[$i]['country'].'> ' .$name_teams[$i]['name']. '</option>';
}

echo '
</select>
</form>
</div>
</div>


<div class="container_teamB">';

  if ($_POST['TeamB']) {

  $image_id_b = explode('-', $_POST['TeamB'])[0];

} else {
  $image_id_b = explode('-', $_COOKIE['TeamB'])[0]; 
}

if ($image_id_b != '') {
  
echo '
<div>
  <img src="https://media.api-sports.io/football/teams/'.$image_id_b.'.png">
</div>';  
}

echo '
<div class="selection_teams">

<form method="post">

<select name="TeamB" onchange="this.form.submit()">

<option selected disabled> Selecteer een team </option>';

 if ($_POST['TeamB'] ) {
           $seltb = explode('-', $_POST['TeamB'])[0]; 
            }
            elseif ( ($_COOKIE['TeamB']) && ($_COOKIE['TeamB'] != '') ) {
               $seltb = explode('-', $_COOKIE['TeamB'])[0];  
            }

for ($i=0; $i < sizeof($name_teams); $i++) {

  if ($name_teams[$i]['id'] != explode('-', $_POST['TeamA'])[0]) {

  echo '<option ' . ($name_teams[$i]['id'] == $seltb ? 'selected' : null) .' 
  value='.$name_teams[$i]['id'].'-'.$name_teams[$i]['country'].'> ' .$name_teams[$i]['name']. '</option>';
}
}

echo '
</select>
</form>
</div> 
</div>
</div>

<div class="container_search">

<form method="post"> 

<input name="Inp_teamA" type="hidden" value='. explode('-', $_COOKIE['TeamA'])[0] . '-' . explode('-', $_COOKIE['TeamA'])[1] .'>
<input name="Inp_teamB" type="hidden" value='. explode('-', $_POST['TeamB'])[0] . '-' . explode('-', $_POST['TeamB'])[1] .'>

<input id="btn_search" type="submit" value="Zoeken">


</form>
</div>';
}

if ($_POST['Inp_teamA'] && $_POST['Inp_teamB']) {

include ('./find_matches.php');
 
}

if (sizeof($all_matches_leagues) == 0) {
  echo '<div id="no_found_matches"> Geen confrontaties gevonden. </div>';
}

else {

// Bepalen van competitie bij 1 wedstrijd (belangrijk voor niet tonen menu Stand bij bekerwedstrijden)

if ($_GET['id']) {
  for ($i=0; $i < sizeof($all_matches_leagues); $i++) {
    if ($all_matches_leagues[$i]['fixture']['id'] == $_GET['id']) {
     $league_to_fixture = $all_matches_leagues[$i]['league']['id'];
     $country_to_match = $all_matches_leagues[$i]['league']['country'];
    };
  }
}


// Toon wedstrijden...

$prevent_loop = false;

$numGames = sizeof($all_matches_leagues);

// Indien meer dan 1 verschillende competities, deze in checkboxes weergeven.. 

$array_leagues_found_matches = array_unique($array_leagues_found_matches, SORT_REGULAR);

if (sizeof($array_leagues_found_matches) > 1) {

echo '<div class="container_checkbox_leagues">';

foreach($array_leagues_found_matches as $key => $value) { 
  $league_id = array_keys(($value))[0]; 
  
  $league_name = array_values(($value))[0];

  echo '
  <input checked type="checkbox" value="'. $league_id .'"> ' .$league_name . ''; 

}

echo '</div>';
}


if ($numGames > 0 ) {

  for ($i = 0; $i < $numGames; $i++) {

  if (!$prevent_loop) {

  $homeTeam = $all_matches_leagues[$i]['teams']['home']['name'];
  $awayTeam = $all_matches_leagues[$i]['teams']['away']['name'];
  $matchId = $all_matches_leagues[$i]['fixture']['id'];
  $matchStatus = $all_matches_leagues[$i]['fixture']['status']['short'];
  $date = date('d-m-Y', $all_matches_leagues[$i]['fixture']['timestamp']);
  $elapsed = $all_matches_leagues[$i]['fixture']['status']['elapsed'] + $all_matches_leagues[$i]['fixture']['status']['extra'];

    
 if ((!$_GET['id']) || ($_GET['id'] && $_GET['id'] == $matchId)) {

  
    if (strtotime('today') <= strtotime($date)) {

    echo '<div id="focus"></div>';
}

 echo '<div class="main_container extra_padding" '. ($_GET['id'] ? 'style="display:flex"' : null) . '>'; 


  if (!$_GET['id']) {
 
    echo '
    <form action="../league/index.php?id=' . $matchId . '" method="post">
    <input type="hidden" id="country_code" name="country_code" value= "'. $all_matches_leagues[$i]['league']['country'] .'"> 
    <button type="submit" name="send_team"> 
    <a '. (date('d-m-Y') === $date ? ' style="background-color: ' . $backgr_today_match : null) . '">
    </form>';
  }

echo'
  <div class="country_container' . (date('d-m-Y') === $date ? ' black_color' : ' white_color') . '">

 <div class="flag_container' . (date('d-m-Y') === $date ? ' black_color' : ' white_color') . '">
  <img src="'. $all_matches_leagues[$i]['teams']['home']['logo'] . '"/></div>
   <p>
       ' . (array_key_exists($homeTeam, $array_countries) ? $array_countries[$homeTeam] : $homeTeam). ' 

     </div>
          <div class="stscore_container ' . (date('d-m-Y') === $date ? ' black_color' : ' white_color') . '">';

        if (!$_GET['id'])  {

        echo '<img class="white_background" id="league_logo_club" src="'. $all_matches_leagues[$i]['league']['logo'] . '" id="img_logo_day"><br>';

        }
         
      if (!array_key_exists($matchStatus, $status)) {

         if ($_GET['id']) { echo $all_matches_leagues[$i]['fixture']['venue']['name'] . '<br>'; }

         if (!$_GET['id'])  { echo $all_matches_leagues[$i]['fixture']['venue']['city'] . '<br>'; } 

         echo date('d-m-Y H:i', $all_matches_leagues[$i]['fixture']['timestamp'])  . '<br>'; 
      
      }

           if (array_key_exists($matchStatus, $status_cancel)) {
          echo '<div class="font_status_match red">' . $status_cancel[$matchStatus] . '</div>';
         }
         else {

         echo '         
         <div class="font_status_match red">'. (array_key_exists($matchStatus, $status)? 
          $elapsed .'"' .  '<br>'.$status[$matchStatus] : null) . 

          '</div>'; 

         }

          if ($matchStatus === 'PEN') {
            if ($all_matches_leagues[$i]['teams']['home']['winner']) { 
                echo '<div>'. $homeTeam . ' w.n.s. </div>'; 
                }
                else {
                   echo '<div>'. $awayTeam . ' w.n.s.</div>'; 
                }
                 echo '(' . $all_matches_leagues[$i]['score']['penalty']['home']. ' - 
                ' .$all_matches_leagues[$i]['score']['penalty']['away'] . ')';
          }
                
                    
     echo
         '<div class="black_color">
         <br>
         <div class="score" ' . (!array_key_exists($matchStatus, $status)? 'style="padding-top: 10%"' :null) . '>'
         . '<div class="score_home ' . (!is_null($all_matches_leagues[$i]['goals']['home']) ? 'pd_score' : null) . '
          '. (array_key_exists($matchStatus, $status)? 'height_live_score_small_screens' :null) .'">' 

        . $all_matches_leagues[$i]['goals']['home'] . '</div>' . 
        
        '<div class="vs '. (date('d-m-Y') === $date ? 'black_color' : 'white_color') . '"> - ' . '</div>' .   
        
         '<div class="score_away '. (!is_null($all_matches_leagues[$i]['goals']['away']) ? 'pd_score' : null) . '
         '. (array_key_exists($matchStatus, $status)? 'height_live_score_small_screens' :null) .'">' 
         . $all_matches_leagues[$i]['goals']['away'] . '</div>
          
        </div>
        </div>';


        if ($_GET['id']) { 

          echo '<p><div class="stscore_ref">
            <img id="ref" src="../img/ref.png">' . '<br> ' . explode(',', $all_matches_leagues[$i]['fixture']['referee'])[0] . 
           '<br>'; 

            }

        echo '</div>';

          if ($_GET['id']) {
          echo '</div>';
        }
         
   echo '
  <div class="country_container' . (date('d-m-Y') === $date ? ' black_color' : ' white_color') . '">
 <div class="flag_container' . (date('d-m-Y') === $date ? ' black_color' : ' white_color') .'">
   <img src="'. $all_matches_leagues[$i]['teams']['away']['logo'] . '"/></div>
   <p>
      ' . (array_key_exists($awayTeam, $array_countries) ? $array_countries[$awayTeam] : $awayTeam). ' 

   </div>
   </div>';

   if (!$_GET['id']) {
    echo '</a>';
   }

     

   // Scoreverloop en opstellingen ophalen (alleen op afzonderlijke wedstrijdpagina)...

   if ($_GET['id']) {
   include ('../assets/events.php');
   include ('../assets/lineup.php');
   }      
   
  }}
  }}
  
    echo '<div class="white_background" style="padding: 0" id="arrow_up">↑</div>';
}

?>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

<script defer>
 document.getElementById('arrow_up').addEventListener('click', () => {

 document.getElementById('top').scrollIntoView({behavior: 'smooth'});
  
});

</script>

<?php

if ($_POST['no_scroll'] != 'yes') {

?> 
<script>
  if (document.getElementById('focus') !=null) {
document.getElementById('focus').scrollIntoView({behavior: 'smooth'});
  }
</script>

<?php
}

?>

</body>
</html>
