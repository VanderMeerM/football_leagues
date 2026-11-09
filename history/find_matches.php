<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historische confrontaties</title>  
    <link rel="shortcut icon" href="https://www.api-football.com/public/img/favicon.ico">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/smoothness/jquery-ui.css"> 
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
   <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>
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

<?php 

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

// Alle wedstrijden van thuis-team ophalen over alle seizoenen (eigenlijk zelfde werking zoals in Teams)..

include('../assets/getTZ.php');
include('../assets/variables.php');


// Alleen indien teams uit dezelfde landen komen, dan ook nationale competities doorlopen..
// anders alleen internationale competities.. 

$array_leagues_sep = [];

if (explode('-', $_POST['Inp_teamA'])[1] === (explode('-', $_POST['Inp_teamB'])[1]) ) {

$array_leagues_to_selected_country = $array_leagues_countries[(explode('-', $_POST['Inp_teamA'])[1])];

foreach($array_leagues_to_selected_country as $key=>$value) {
  array_push($array_leagues_sep, $value);
};

};

$all_matches_leagues = [];
$found_matches = [];

$array_of_leagues = array_merge($array_leagues_sep, $array_intern_leagues); 

$allseasons = [];

$team_id = explode('-', $_POST['Inp_teamA'])[0];

$season = $current_season-1;

require('../assets/api.php');

include('../assets/variables.php');

include('../assets/translations.php');

echo '<div id="top"></div>';


// Wedstrijden over seizoenen ophalen...

$first_season = 2010;

while ($first_season <= ($season)) {

for ($i=0; $i < sizeof($array_of_leagues); $i++) {

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://v3.football.api-sports.io/fixtures?team='. $team_id . '&league=' . $array_of_leagues[$i] . '&season='.$first_season.'',
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

$response= json_decode($response, true);

if ($response['results'] > 0) {

array_push($all_matches_leagues, $response);
}}

$first_season++;
}

$selected_team_team = $all_matches_leagues[0]['parameters']['team'];
$selected_leage_team = $all_matches_leagues[0]['parameters']['league'];
$selected_season_team = $all_matches_leagues[0]['parameters']['season']; 

// Gevonden wedstrijden aflopend sorteren...

$matches_leagues_ts = [];
$matches_in_one_array = [];

for ($n=0; $n < sizeof($all_matches_leagues); $n++) {

 for ($i = 0; $i < $all_matches_leagues[$n]['results']; $i++) {

  array_push($matches_in_one_array, $all_matches_leagues[$n]['response'][$i]);
 } 
}


for ($i=0; $i < sizeof($matches_in_one_array); $i++) {

    array_push($matches_leagues_ts, $matches_in_one_array[$i]['fixture']['timestamp']); 
   
  }
 
asort($matches_leagues_ts);


$matches_leagues_ts_keys = array_keys($matches_leagues_ts); 

for ($i=sizeof($matches_leagues_ts_keys); $i>0; $i--) {
  
  $all_matches_leagues_sorted[] = $matches_in_one_array[$matches_leagues_ts_keys[$i]];
}


$all_matches_leagues = $all_matches_leagues_sorted; 

// Vind confrontaties met Team B...

for ($i=0; $i < sizeof($all_matches_leagues); $i++) {

if (
  ($all_matches_leagues[$i]['teams']['home']['id'] == (explode('-', $_POST['Inp_teamB'])[0])) ||
  ($all_matches_leagues[$i]['teams']['away']['id'] == (explode('-', $_POST['Inp_teamB'])[0]))
  ){
  array_push($found_matches, $all_matches_leagues[$i]);
}
}

$all_matches_leagues = $found_matches;

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

//include('../assets/teams_header.php');

// Toon wedstrijden...

$prevent_loop = false;

$numGames = sizeof($all_matches_leagues);

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
    <form action="./?id=' . $matchId . '" method="post">
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
         '<div '. (array_key_exists($matchStatus, $status)? 'class="font_status_match red">' 
         . $status_live[$matchStatus] : 'class="black_color"') . 
         '<br>
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
