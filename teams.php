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

<script>

// Direct pagina laden bij aanklikken tabblad in browser.. 

document.addEventListener("visibilitychange", function() {
    if (!document.hidden){
       window.location.reload();
    }
});
</script>

<?php 

$all_matches_leagues = [];

$array_competition_leagues = [78,79, 88,89]; // 78, 79 (1e en 2e Bundesliga), 88 - Eredivisie, 89 - KVK 
$array_cup_leagues = [81, 90]; // 81 - DFB Pokal, 90 - KNVB beker
$array_international_leagues = [2, 3, 848]; // 2, 3, 848 (CL, EL & Conf. League)

$array_of_leagues = array_merge($array_competition_leagues, $array_cup_leagues, $array_international_leagues); 


if (date('m') >= 1 && date('m') < 8) {
$current_season = date('Y')-1;
}
else {
$current_season = date('Y');

}

$allseasons = [];

if ($_POST['team_selection']) { 
  $team_id = $_POST['team_selection']; 
  } else if (
    $_COOKIE['teams_team_selection'])  
    {
  $team_id = $_COOKIE['teams_team_selection'];
  } else {
  $team_id = 194;
}

if ($_POST['season_selection']) {
  $season = $_POST['season_selection'];
} else if (
 $_COOKIE['teams_season_selection'])
 {
  $season = $_COOKIE['teams_season_selection'];
 } else {
  $season = $current_season;
 };

require('./api.php');

include('./variables.php');

include('./translations.php');

echo '<div id="top"></div>';

include('./teams_header.php');

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/


for ($i=0; $i < sizeof($array_of_leagues); $i++) {

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://v3.football.api-sports.io/fixtures?team='. $team_id . '&league=' . $array_of_leagues[$i] . '&season='. $season,
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

$response= json_decode($response, true);

if ($response['results'] > 0) {

array_push($all_matches_leagues, $response);
}}


// Gevonden wedstrijden in de competities chronologisch sorteren...

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

for ($i=0; $i < sizeof($matches_leagues_ts_keys); $i++) {
  
  $all_matches_leagues_sorted[] = $matches_in_one_array[$matches_leagues_ts_keys[$i]];
}

// Bepaal eerstvolgende wedstrijd (incl. vandaag) en zet deze bovenaan 

/*
$match_today = []; 

for ($x=0; $x < sizeof($all_matches_leagues_sorted); $x++) {
  if ($all_matches_leagues_sorted[$x]['fixture']['timestamp'] >= strtotime('now')) {
    
  if (sizeof($match_today) == 0) {
    array_push($match_today, $all_matches_leagues_sorted[$x]);
     array_splice($all_matches_leagues_sorted,$x,1);
  } 
}}

*/
$all_matches_leagues = $all_matches_leagues_sorted; //array_merge($match_today,$all_matches_leagues_sorted);


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
  
  if ((!$_GET['id']) || ($_GET['id'] && $_GET['id'] == $matchId)) {

if (strtotime('today') <= strtotime($date)) {

    echo '<div id="focus"></div>';
}

  if (date('d-m-Y') === $date) {

   echo '<div class="main_container background_today_match extra_padding" ' . ($_GET['id'] ? 'style="display:flex"' : null) . '>';
   }
  
else { 

 echo '<div class="main_container extra_padding" '. ($_GET['id'] ? 'style="display:flex"' : null) . '>'; 
}

  if (!$_GET['id']) {
 
    echo '<a '. (date('d-m-Y') === $date ? ' style="background-color: ' . $backgr_today_match : null) . '" href="' . $_SERVER['PHP_SELF'] . '?id=' . $matchId . '">';
  }

echo'
  <div class="country_container' . (date('d-m-Y') === $date ? ' black_color' : ' white_color') . '">

 <div class="flag_container' . (date('d-m-Y') === $date ? ' black_color' : ' white_color') .'">
  <img src="'. $all_matches_leagues[$i]['teams']['home']['logo'] . '"/></div>
   <p>
  ' . $all_matches_leagues[$i]['teams']['home']['name'] . ''; 

   
  echo '</div>
          <div class="stscore_container ' . (date('d-m-Y') === $date ? ' black_color' : ' white_color') . '">

        <img class="white_background" id="league_logo_club" src="'. $all_matches_leagues[$i]['league']['logo'] . '" id="img_logo_day"><br>';
                     
         if ($_GET['id']) { echo $all_matches_leagues[$i]['fixture']['venue']['name'] . '<br>'; }

         if (!$_GET['id'])  { echo $all_matches_leagues[$i]['fixture']['venue']['city'] . '<br>'; }

         echo date('d-m-Y H:i', $all_matches_leagues[$i]['fixture']['timestamp'])  . '<br>
         
         <div class="font_status_match red">'. (array_key_exists($matchStatus, $status)? 
         $status[$matchStatus] : null) . 
          '</div>'; 

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
            <img id="ref" src="../ref.png">' . '<br> ' . explode(',', $all_matches_leagues[$i]['fixture']['referee'])[0] . 
           '<br>'; 

            }

            /*
          if (sizeof(explode(',', $all_matches_leagues[0]['response'][$i]['fixture']['referee'])) > 1) {

            echo

           (array_search(explode(', ', $all_matches_leagues[$n]['response'][$i]['fixture']['referee'])[1], $countries) ? 
           '(' . array_search(explode(', ', $all_matches_leagues[$n]['response'][$i]['fixture']['referee'])[1], $countries) : 
           '(' . explode(', ', $all_matches_leagues[$n]['response'][$i]['fixture']['referee'])[1]) . ')
           <br></div>'; 
                   
           }

           else {
            echo '</div>';
           }
         */

        echo '</div>';

          if ($_GET['id']) {
          echo '</div>';
        }
         
   echo '
  <div class="country_container' . (date('d-m-Y') === $date ? ' black_color' : ' white_color') . '">
 <div class="flag_container' . (date('d-m-Y') === $date ? ' black_color' : ' white_color') .'">
   <img src="'. $all_matches_leagues[$i]['teams']['away']['logo'] . '"/></div>
   <p>
  ' . $all_matches_leagues[$i]['teams']['away']['name'] . ''; 


   echo '</div>
   </div>';

   if (!$_GET['id']) {
    echo '</a>';
   }

   // Bij live westrijden elke minuut pagina herladen om status te checken..

  if (array_key_exists($matchStatus, $status_live)) {
          ?>
          <script>
      setTimeout(() => {
       window.location.href = window.location;
    }, 60 * 1000);
          </script>

  <?php 
  };
      


   // Scoreverloop en opstellingen ophalen (alleen op afzonderlijke wedstrijdpagina)...

   if ($_GET['id']) {
   include ('./events.php');
   include ('./lineup.php');
   }      

   
  }}
  }}

   if (!$_GET['id']) {
    echo '<div class="white_background" style="padding: 0" id="arrow_up">↑</div>';
    }

?>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

<script defer>
 document.getElementById('arrow_up').addEventListener('click', () => {

  document.getElementById('top').scrollIntoView({behavior: 'smooth'});
  
});

document.getElementById('focus').scrollIntoView({behavior: 'smooth'});

</script>


</body>
</html>
