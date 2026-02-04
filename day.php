<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Leagues</title>  
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

/*if ($_POST['orderByLeagueTime'] === 'ob_time' || !$_POST['orderByLeagueTime']) {

setcookie('LeagueTime', 'ob_time', time() + (86400 * 30), "/");
}
*/

if ($_POST['orderByLeagueTime'] === 'ob_league') {
  setcookie("LeagueTime", "ob_league", (time() + (86400 * 30)), "/");

}

elseif ($_POST['orderByLeagueTime'] === 'ob_time') {
  setcookie("LeagueTime", "ob_time", (time() + (86400 * 30)), "/");

}

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

*/
?>

<script>

// Direct pagina laden bij aanklikken tabblad in browser.. 

document.addEventListener("visibilitychange", function() {
    if (!document.hidden){
       window.location.reload();
    }
});
</script>

<?php 

require('./api.php');

include('./variables.php');

include('./translations.php');

$all_matches_leagues = [];
$matches_on_selected_day = [];

// Controleer of er al een wedstrijd is opgeslagen...

if ($_GET['id'] && file_exists($json_fixture)) {

  $response_json_fixture = file_get_contents($json_fixture, true);
  $response = json_decode($response_json_fixture, true);

  }

else {

if ($_GET['id']) {

     $cur_url = 'https://v3.football.api-sports.io/fixtures?&id=' . $_GET['id'];
  }
   else {

    $array_all_leagues = array_merge($array_leagues, $array_extra_leagues);

   for ($i=0; $i < sizeof($array_all_leagues); $i++) { 
   $cur_url = 'https://v3.football.api-sports.io/fixtures?&league='. $array_all_leagues[$i] . '&season='. $selected_season;
   
  

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
    //'x-rapidapi-key: ' . $api_key .'',
     'x-rapidapi-key: 863bcd048478f98225b64bced629b376',
    'x-rapidapi-host: v3.football.api-sports.io',
    
  ),
));

$response = curl_exec($curl);

$response = json_decode($response, true);

array_push($all_matches_leagues, $response);
}
}}


$array_values_all_leagues = array_values($all_matches_leagues);

$ind = 0; 

if ($_GET['datum']) {

  $_POST['sel_day'] = strtotime($_GET['datum']);
}

while ($ind < sizeof($array_values_all_leagues)) {

    for ($l=0; $l < sizeof($array_values_all_leagues[$ind]['response']); $l++) {

    $date = date('d-m-Y', $array_values_all_leagues[$ind]['response'][$l]['fixture']['timestamp']);
      
    if ($date === date('d-m-Y', $_POST['sel_day'])) {
      array_push($matches_on_selected_day, $array_values_all_leagues[$ind]['response'][$l]);
    } 
       
}

$ind++;

};

echo '<div id="top"></div>';

// Uitcommentariëren bij binnenhalen einddata afgelopen seizoenen (zie ook 289)

include('./league_header.php'); 

if (sizeof($matches_on_selected_day) == 0) {
  echo '<div id="no_found_matches"> Geen wedstrijden op '. date('d-m-Y', $_POST['sel_day']).' gevonden.</div>';
  return;
};

//Wedstrijden sorteren op tijdstip;

if ($_POST['orderByLeagueTime'] === 'ob_time' || $_COOKIE['LeagueTime'] === 'ob_time' 
|| (!$_COOKIE['LeagueTime'] && !$_POST['orderByLeagueTime'])) {

$matches_ind_ts = [];

for ($i=0; $i < sizeof($matches_on_selected_day); $i++) {

    array_push($matches_ind_ts, $matches_on_selected_day[$i]['fixture']['timestamp']); 
  }
 
asort($matches_ind_ts);

$matches_ind_ts_keys = array_keys($matches_ind_ts);

for ($i=0; $i < sizeof($matches_ind_ts_keys); $i++) {
  
  $matches_on_selected_day_sorted[] = $matches_on_selected_day[$matches_ind_ts_keys[$i]];
}

$matches_on_selected_day = $matches_on_selected_day_sorted;

};

// Live wedstrijd(en) bovenaan plaatsen..  

$matches_live = []; 

$matches_not_live = [];

for ($x=0; $x < sizeof($matches_on_selected_day); $x++) {
  if (array_key_exists(strval($matches_on_selected_day[$x]['fixture']['status']['short']), $status)) 
    {
    array_push($matches_live, $matches_on_selected_day[$x]);
     } 
     else {
          array_push($matches_not_live, $matches_on_selected_day[$x]);
     }
}

//print_r($matches_on_selected_day);

$matches_on_selected_day = array_merge($matches_live, $matches_not_live);

//$matches_on_selected_day = $all_matches_leagues_sorted;

//print_r($matches_rest);

$numGames = sizeof($matches_on_selected_day);


if ($_GET['id']) {
  $round_to_fixture = intval(explode(' ', $response[0]['league']['round'])[3]);
  $season_to_fixture = $response[0]['league']['season'];
  $league_to_fixture = $response[0]['league']['id'];
 }

$prevent_loop = false;

$games_per_round = [];

//echo 'Cookie: ' . $_COOKIE['LeagueTime'] . '<br>';
//echo 'POST: ' . $_POST['orderByLeagueTime'];


echo '</div>';

// Aantal competities tellen om sorteerkeuze te tonen (bij meer dan 1 competitie)..

$num_leagues = []; 

for ($i=0; $i < sizeof($matches_on_selected_day); $i++) {
 array_push($num_leagues, $matches_on_selected_day[$i]['league']['id']);
}

if (sizeof(array_unique($num_leagues)) > 1) {

echo ' 
<div class= "container_sortby_league_time">
<form method="post" action="./day'. ($_GET['datum'] ? '?datum='. $_GET['datum'] : null) . '">
<input name="orderByLeagueTime" id="ob_league" onchange="this.form.submit();" 
value="ob_league" type="radio" ' . 
(($_COOKIE['LeagueTime'] === 'ob_league' || $_POST['orderByLeagueTime'] === 'ob_league') ? 'checked' : null) . '> 
<label for="ob_league">Competitie</label>

<input name="orderByLeagueTime" id="ob_time" onchange="this.form.submit();" value="ob_time" type="radio" ' . 
(($_POST['orderByLeagueTime'] === 'ob_time' || $_COOKIE['LeagueTime'] === 'ob_time' 
|| (!$_COOKIE['LeagueTime'] && !$_POST['orderByLeagueTime'])) ? 'checked' : null) . '> 
<label for="ob_time">Tijdstip</label>
</form>
</div>';
};


$matchesInRound = [];

if ($numGames > 0 ) {

for ($i = 0; $i < $numGames; $i++) {

  if (!$prevent_loop) {

 $homeTeam = $matches_on_selected_day[$i]['teams']['home']['name'];
 $awayTeam = $matches_on_selected_day[$i]['teams']['away']['name'];
 $matchId = $matches_on_selected_day[$i]['fixture']['id'];
 $matchStatus = $matches_on_selected_day[$i]['fixture']['status']['short'];
 $elapsed = $matches_on_selected_day[$i]['fixture']['status']['elapsed'] + $matches_on_selected_day[$i]['fixture']['status']['extra'];
 
  $selectedround_int_leagues =  $matches_on_selected_day [$i]['league']['round']; 
  $selectedround = intval(explode(' ', $matches_on_selected_day [$i]['league']['round'])[3]);
  $league_name = $matches_on_selected_day [$i]['league']['name'];
  $league_id = $matches_on_selected_day [$i]['league']['id'];
  $round = 'ronde ';
  
if (!in_array($league_id, $array_reg_leagues)) {

  $selectedround = $selectedround_int_leagues;
  $round = '';
};

// Competitielogo met -naam 
echo 
'<div class="background_match">
<div class="container_league_logo_name">
<a id="space_cont_league_logo" href="#">
<img id="league_logo" src = ' . $matches_on_selected_day [$i]['league']['logo'] . ' id="img_logo_day"">' 
. $league_name . ' ('.$round . (array_key_exists($selectedround, $array_rounds) ? $array_rounds[$selectedround] : $selectedround) .')</a></div>

<div class="main_container">';

   echo '<a style="background-color: ' .(date('d-m-Y') === date('d-m-Y', $_POST['sel_day']) ? $backgr_today_match : null) .'" href="./league.php?id=' . $matchId . '">';

}


 echo '<div class="country_container">

 <div class="flag_container' . (date('d-m-Y') === date('d-m-Y', $_POST['sel_day']) ? ' black_color' : ' white_color') .'">
  <img src="'.$matches_on_selected_day[$i]['teams']['home']['logo'] . '"/>
  <p>
  ' . $matches_on_selected_day[$i]['teams']['home']['name'] . '
  </div> 
  </div>

   <div class="stscore_container' . (date('d-m-Y') === date('d-m-Y', $_POST['sel_day']) ? ' black_color' : ' white_color') .'">'; 
                  
         if ($_GET['id']) { echo $matches_on_selected_day[$i]['fixture']['venue']['name'] . '<br>'; }

         if (!$_GET['id'])  { echo $matches_on_selected_day[$i]['fixture']['venue']['city'] . '<br>'; }

         echo date('d-m-Y H:i', $matches_on_selected_day[$i]['fixture']['timestamp']);


         // Bij live wedstrijden elke minuut pagina herladen om status te checken..

         if (array_key_exists($matchStatus, $status)) {
          ?>
          <script>
      setTimeout(() => {
       window.location.href = window.location;
    }, 60 * 1000);
          </script>
            <?php
         }
          
         if (array_key_exists($matchStatus, $status_cancel)) {
          echo '<div class="font_status_match red">' . $status_cancel[$matchStatus] . '<br>';
         }
         else {
          echo
         '<div '. (array_key_exists($matchStatus, $status)? 'class="font_status_match red">' 
         . $elapsed . '"' .'('.$status[$matchStatus] .')' : 'class="black_color"') . 
         '<br>';
         }
         echo '
         <div class="score" ' . (!array_key_exists($matchStatus, $status)? 'style="padding-top: 10%"' :null) . '>' .
        '<div class="score_home ' . (!is_null($matches_on_selected_day[$i]['goals']['home']) ? 'pd_score' : null) . '">' . $matches_on_selected_day[$i]['goals']['home'] . '</div>' . 
        
        '<div class="vs '. (date('d-m-Y') === date('d-m-Y', $_POST['sel_day']) ? 'black_color' : 'white_color') . '"> - ' . '</div>' .   
        
         '<div class="score_away '. (!is_null($matches_on_selected_day[$i]['goals']['away']) ? 'pd_score' : null) . '">'. $matches_on_selected_day[$i]['goals']['away'] . '</div>
          
        </div>
        </div>';

       if ($_GET['id']) { 

            echo '<p><div class="stscore_ref">
            <img id="ref" src="../ref.png">' . '<br> ' . explode(',', $matches_on_selected_day[$i]['fixture']['referee'])[0] . 
           '<br>'; 
          
          
           // Wedstrijd opslaan nadat deze een dag in het verleden ligt..

          if ( (!file_exists($json_fixture)) &&
            (date('d-m-Y', $matches_on_selected_day[$i]['fixture']['timestamp'])) < 
              date('d-m-Y', strtotime('today')) &&
                ($matches_on_selected_day[$i]['fixture']['status']['short'] === 'FT')  )

          {
    
           $json_file_fixture = fopen($json_fixture, "w");
   
           fwrite($json_file_fixture, json_encode($response));
   
           fclose($json_file_fixture);
                
           }            

      }
        echo '</div>';

        if ($_GET['id']) {
          echo '</div>';
        }
         
   echo '<div class="country_container">
 <div class="flag_container' . (date('d-m-Y') === date('d-m-Y', $_POST['sel_day']) ? ' black_color' : ' white_color') .'">
  <img src="'. $matches_on_selected_day[$i]['teams']['away']['logo'] . '"/>
   <p>' . 
   $matches_on_selected_day[$i]['teams']['away']['name'] . '
   </div>'; 

 
   echo 
   '</div></a>
   </div>
   </div>';
      };

//   if (!$_GET['id']) {
   echo '</div>
   <div style="height: 10px"></div>';


   if ( (sizeof($matches_on_selected_day) > 3) || ($_GET['id']) ) {
  echo '
  <div id="arrow_up">↑</div>';
};

   if ($_GET['id']) {
   include ('./events.php');
   include ('./lineup.php');
   }

  }
  

  $miR_sorted = array_map(function ($mt) {
    return $mt['timestamp'];
  }, $matchesInRound);

array_multisort($miR_sorted, SORT_ASC, $matchesInRound);



?>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js">
</script>

<script defer>
 document.getElementById('arrow_up').addEventListener('click', () => {

  document.getElementById('top').scrollIntoView({behavior: 'smooth'});
  
}) 
</script>

</body>
</html>
