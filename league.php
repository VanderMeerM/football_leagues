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


// Controleer of er al een seizoen (uit het verleden) is opgeslagen..

if (!$_GET['id'] && file_exists($json_league_season_path)) {

  $response_json_season = file_get_contents($json_league_season_path, true);

  $response = json_decode($response_json_season, true);

}

// Controleer of er al een wedstrijd is opgeslagen...

else if ($_GET['id'] && file_exists($json_fixture)) {

  $response_json_fixture = file_get_contents($json_fixture, true);
  $response = json_decode($response_json_fixture, true);

  }

else {

if ($_GET['id']) {

     $cur_url = 'https://v3.football.api-sports.io/fixtures?&id=' . $_GET['id'];
  }
   else {
    $cur_url = 'https://v3.football.api-sports.io/fixtures?&league=' . $league_id . '&season='. $selected_season;
  }

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

}

$numGames = $response['results'];

include('./get_current_round.php');

// Deze 5 regels uitcommentariëren

/*
$array_season = './season_20242025.json';
$curr_season = fopen($array_season, 'r');
fclose($curr_season);
$response_json = file_get_contents($array_season, true);
$response= json_decode($response_json, true);
*/

if (!$_GET['season']) {
  header("Location: ./league.php?league=" . $league_id . "&season=" . $selected_season);
}

if ($_GET['id']) {
  $round_to_fixture = intval(explode(' ', $response['response'][0]['league']['round'])[3]);
  $season_to_fixture = $response['response'][0]['league']['season'];
  $league_to_fixture = $response['response'][0]['league']['id'];
 }

$prevent_loop = false;

// $enddate_selected_round = [];
$games_per_round = [];


// Uitcommentariëren bij binnenhalen einddata afgelopen seizoenen (zie ook 260)
include('./league_header.php');

echo '</div>
<div id="top"></div>';


$matchesInRound = [];

if ($numGames > 0 ) {

  for ($i = 0; $i < $numGames; $i++) {

  if (!$prevent_loop) {

  $homeTeam = $response['response'][$i]['teams']['home']['name'];
  $awayTeam = $response['response'][$i]['teams']['away']['name'];
  $matchId = $response['response'][$i]['fixture']['id'];
  $matchStatus = $response['response'][$i]['fixture']['status']['short'];

  $selectedround_int_leagues = $response['response'][$i]['league']['round']; 
  $selectedround = intval(explode(' ', $response['response'][$i]['league']['round'])[3]);
  //$enddate_selected_round['Ronde '. $selectedround] = $response["response"][$i]["fixture"]["timestamp"];
  
  if ((!$_GET['round_selection']) || is_null($_GET['round_selection'])) { 

   // header("/league.php?league=' . $league_id . '&season=' . $selected_season . ");

       if ($_GET['season'] != $current_season) {
        $round_to_select = 1;
        }
         else {
           $round_to_select = $round_of_first_upcoming_matches;
        } 
        
        if ($_GET['id']) {
       $round_to_select = $round_to_fixture;
      
   }
    //$selectedround = $round_of_first_upcoming_matches;
   }
  
   elseif ($_GET['round_selection']) {
    $round_to_select = $_GET['round_selection'];
   }

   if (($round_to_select == $selectedround) || ($round_to_select === $selectedround_int_leagues)) {
    
 array_push($matchesInRound, $response['response'][$i]);

  $date = date('d-m-Y', $response['response'][$i]['fixture']['timestamp']);

  if (date('d-m-Y') === $date) {

    echo '<div class="main_container background_today_match extra_padding" ' . ($_GET['id'] ? 'style="display:flex"' : null) . '>';
   }
  
else { 

 echo '<div class="main_container extra_padding" '. ($_GET['id'] ? 'style="display:flex"' : null) . '>'; 
}

  if (!$_GET['id']) {

        echo '<a '. (date('d-m-Y') === $date ? ' style="background-color: ' . $backgr_today_match : null) . '" href="' . $_SERVER['PHP_SELF'] . '?id=' . $matchId . '">';
  }  

  echo '
  <div class="country_container">'; 

//  <div class="flag_container' . (date('d-m-Y') === $date ? ' black_color' : ' white_color') .'">

  echo '
 <div class="flag_container' . (date('d-m-Y') === $date ? ' black_color' : ' white_color') .'">
  <img src="'.$response['response'][$i]['teams']['home']['logo'] . '"/>
  <p>
  ' . $response['response'][$i]['teams']['home']['name'] . '</div>'; 

  
  echo '</div>
    <div class="stscore_container' . (date('d-m-Y') === $date ? ' black_color' : ' white_color') .'">'; 
                  
         if ($_GET['id']) { echo $response['response'][$i]['fixture']['venue']['name'] . '<br>'; }

         if (!$_GET['id'])  { echo $response['response'][$i]['fixture']['venue']['city'] . '<br>'; }

         echo $date . ' ';
         echo date('H:i', $response['response'][$i]['fixture']['timestamp'])  . '<br>';

      
         if (array_key_exists($matchStatus, $status)) {
          ?>
          <script>
      setTimeout(() => {
       window.location.href = window.location;
    }, 60 * 1000);
          </script>
            <?php
         }

         echo 
         '<div style="font-size:15pt; font-weight:600" '. (array_key_exists($matchStatus, $status)? 'class="red">' 
         . $status[$matchStatus] : 'class="black_color"') . 
         '<br>
         <div class="score" ' . (!array_key_exists($matchStatus, $status)? 'style="padding-top: 10%"' :null) . '>' .
        '<div class="score_home ' 
        . (!is_null($response['response'][$i]['goals']['home']) ? 'pd_score' : null) . 
        ((!array_key_exists($matchStatus, $status) && $response['response'][$i]['goals']['home'] !=0) ? 
        ' background_score_small_screens padding_background_score_small_screens' : null) .  
        '">' . $response['response'][$i]['goals']['home'] . '</div>' . 
        
          '<div class="vs ' . (date('d-m-Y') === $date ? ' black_color' : ' white_color') .'"> - </div>
        
        <div class="score_away '
        . (!is_null($response['response'][$i]['goals']['away']) ? 'pd_score' : null) .
          ((!array_key_exists($matchStatus, $status) && $response['response'][$i]['goals']['away'] !=0)? 
          ' background_score_small_screens padding_background_score_small_screens' : null) .  
        '">'. $response['response'][$i]['goals']['away'] . '</div>
          
        </div>
        </div>';

       
        if ($_GET['id']) { 

            echo '<p><div class="stscore_ref">
            <img id="ref" src="../ref.png">' . '<br> ' . explode(',', $response['response'][$i]['fixture']['referee'])[0] . 
           '<br>'; 
          
          
           // Wedstrijd opslaan nadat deze een dag in het verleden ligt..

          if ( (!file_exists($json_fixture)) &&
            (date('d-m-Y', $response['response'][$i]['fixture']['timestamp'])) < 
              date('d-m-Y', strtotime('today')) &&
                ($response['response'][$i]['fixture']['status']['short'] === 'FT')  )

          {
    
           $json_file_fixture = fopen($json_fixture, "w");
   
           fwrite($json_file_fixture, json_encode($response));
   
           fclose($json_file_fixture);
                
           }
 
                    

        /*  if (sizeof(explode(',', $response['response'][$i]['fixture']['referee'])) > 1) {

            echo

           (array_search(explode(', ', $response['response'][$i]['fixture']['referee'])[1], $countries) ? 
           '(' . array_search(explode(', ', $response['response'][$i]['fixture']['referee'])[1], $countries) : 
           '(' . explode(', ', $response['response'][$i]['fixture']['referee'])[1]) . ')
           <br></div>'; 
                   
           }

           else {
            echo '</div>';
           }
         
           */
        }
        echo '
        </div>';

        if ($_GET['id']) {
          echo '</div>';
        }
         
   echo '<div class="country_container">
   <div class="flag_container' . (date('d-m-Y') === $date ? ' black_color' : ' white_color') .'">
   <img src="'.$response['response'][$i]['teams']['away']['logo'] . '"/>
   <p>' . 
   $response['response'][$i]['teams']['away']['name'] . '
   </div>'; 

 
   echo '</div>
   </div>';

   if (!$_GET['id']) {
   echo '</a>';

// Oudere seizoenen opslaan (vanaf juni als seizoen voorbij is)...

if ( (date('Y') >  ($selected_season + 1)) || 
(date('Y') ==  ($selected_season + 1)) && (date('m') >= 6) 
&& (!file_exists($json_league_season_path)) ) {

  $json_file_mt = fopen($json_league_season_path, "w");
  
  fwrite($json_file_mt, json_encode($response));
  
  fclose($json_file_mt);
      
 }
 }

   if ($_GET['id']) {
   include ('./events.php');
   include ('./lineup.php');
   }

  }}
  }}

  $miR_sorted = array_map(function ($mt) {
    return $mt['timestamp'];
  }, $matchesInRound);

array_multisort($miR_sorted, SORT_ASC, $matchesInRound);

//sort_by_end($matchesInRound); 

//print_r($matchesInRound[0]);


// Binnenhalen einddata seizoenen (seizoen invullen in url; include league_header uitcommentariëren (zie 139))

/*
$array_leagues = [88, 89, 78, 79, 135, 140, 39, 40, 179, 408]; // 357 = Ierse competitie

foreach($array_leagues as $al) {

$json_enddates = './JSON/enddates_'. $al . '_' . $selected_season . ($selected_season + 1) . '.json'; 

$json_file_enddate = fopen($json_enddates, "w");

fwrite($json_file_enddate, json_encode($enddate_selected_round));

fclose($json_file_enddate);
}
*/

if (sizeof($matchesInRound) > 3) {
  echo '
  <div id="arrow_up">↑</div>';
};

?>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

<script defer>
 document.getElementById('arrow_up').addEventListener('click', () => {

  document.getElementById('top').scrollIntoView({behavior: 'smooth'});
  
}) 
</script>

</body>
</html>
