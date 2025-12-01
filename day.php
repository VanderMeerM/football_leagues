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

    for ($i=0; $i < 18; $i++) { //sizeof($array_all_leagues); $i++) { 
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
    'x-rapidapi-key: ' . $api_key . '',
    'x-rapidapi-host: v3.football.api-sports.io',
    
  ),
));

$response = curl_exec($curl);

$response = json_decode($response, true);

array_push($all_matches_leagues, $response);
}
}}

$array_values_all_leagues = array_values($all_matches_leagues);

//echo sizeof($array_values_all_leagues) . '<br>';
//echo sizeof($array_values_all_leagues[10]['response']);

//Voorbeeld met Franse competitie (timestamp van eerste wedstrijd)
//print_r( $array_values_all_leagues[10]['response'][0]['fixture']['timestamp']);

$ind = 0; 

while ($ind < sizeof($array_values_all_leagues)) {

    for ($l=0; $l < sizeof($array_values_all_leagues[$ind]['response']); $l++) {

    $date = date('Y-m-d', $array_values_all_leagues[$ind]['response'][$l]['fixture']['timestamp']);
      
    if ($date === date('Y-m-d', $_POST['sel_day'])) {
      array_push($matches_on_selected_day, $array_values_all_leagues[$ind]['response'][$l]);
    } 
       
}

$ind++;

};

//print_r($matches_on_selected_day); //['teams']['home']['name']);

$numGames = sizeof($matches_on_selected_day);

//include('./get_current_round.php');

// Deze 5 regels uitcommentariëren

/*
$array_season = './season_20242025.json';
$curr_season = fopen($array_season, 'r');
fclose($curr_season);
$response_json = file_get_contents($array_season, true);
$response= json_decode($response_json, true);
*/

if ($_GET['id']) {
  $round_to_fixture = intval(explode(' ', $response[0]['league']['round'])[3]);
  $season_to_fixture = $response[0]['league']['season'];
  $league_to_fixture = $response[0]['league']['id'];
 }

$prevent_loop = false;

// $enddate_selected_round = [];
$games_per_round = [];


// Uitcommentariëren bij binnenhalen einddata afgelopen seizoenen (zie ook 289)
include('./league_header.php');

echo '</div>';

$matchesInRound = [];

if ($numGames > 0 ) {

  for ($i = 0; $i < $numGames; $i++) {

  if (!$prevent_loop) {

 $homeTeam = $matches_on_selected_day[$i]['teams']['home']['name'];
 $awayTeam = $matches_on_selected_day[$i]['teams']['away']['name'];
 $matchId = $matches_on_selected_day[$i]['fixture']['id'];
 $matchStatus = $matches_on_selected_day[$i]['fixture']['status']['short'];

  $selectedround_int_leagues =  $matches_on_selected_day [$i]['league']['round']; 
  $selectedround = intval(explode(' ', $matches_on_selected_day [$i]['league']['round'])[3]);
  //$enddate_selected_round['Ronde '. $selectedround] = $response["response"][$i]["fixture"]["timestamp"];
  
  /*

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
 
   if ($round_to_select == $selectedround) {
    
 array_push($matchesInRound, $matches_on_selected_day[$i]);

 $date = date_create($matches_on_selected_day[$i]['fixture']['date']);

  if (date('d-m-Y') === date_format($date, 'd-m-Y')) {

    echo '<div class="main_container background_today_match">';
   }
*/  


 echo '<div class="main_container">'; 
}

  if (!$_GET['id']) {
   
      echo '<a '. (date('d-m-Y') === date('Y-m-d', $_POST['sel_day']) ? ' style="background-color: ' . $backgr_today_match : null) . '" href="' . $_SERVER['PHP_SELF'] . '?id=' . $matchId . '">';
  }  

  echo '
  <div class="country_container">'; 


  echo '
  <div class="flag_container' . (date('d-m-Y') === date('d-m-Y', $_POST['sel_day']) ? ' black_color' : ' white_color') .'">
  <img src="'.$matches_on_selected_day[$i]['teams']['home']['logo'] . '"/>
  <p>
  ' . $matches_on_selected_day[$i]['teams']['home']['name'] . '
  </div> 
  </div>

  <div class="stscore_container white_color">'; 

                  
         if ($_GET['id']) { echo $matches_on_selected_day[$i]['fixture']['venue']['name'] . '<br>'; }

         if (!$_GET['id'])  { echo $matches_on_selected_day[$i]['fixture']['venue']['city'] . '<br>'; }

         echo date('d-m-Y H:i', $matches_on_selected_day[$i]['fixture']['timestamp']);
        // echo date('H:i', $matches_on_selected_day[$i]['fixture'][)  . '<br>';

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
         <div class="score">' .
        '<div class="score_home ' . (!is_null($matches_on_selected_day[$i]['goals']['home']) ? 'w-12 pd_score' : null) . '">' . $matches_on_selected_day[$i]['goals']['home'] . '</div>' . 
        
        '<div class="vs '. (date('d-m-Y') === date('d-m-Y', $date) ? 'black_color' : 'white_color') . '"> - ' . '</div>' .   
         '<div class="score_away '. (!is_null($matches_on_selected_day[$i]['goals']['away']) ? 'w-12 pd_score' : null) . '">'. $matches_on_selected_day[$i]['goals']['away'] . '</div>
          
        </div>
        </div>';

       
        if ($_GET['id']) { 

            echo '<p><div class="stscore_ref">
            <img id="ref" src="../ref.png">' . '<br> ' . explode(',', $matches_on_selected_day[$i]['fixture']['referee'])[0] . 
           '<br>'; 
          
          
           // Wedstrijd opslaan nadat deze een dag in het verleden ligt..

          if ( (!file_exists($json_fixture)) &&
            (date('Y-m-d', $matches_on_selected_day[$i]['fixture']['timestamp'])) < 
              date('Y-m-d', strtotime('Today')) &&
                ($matches_on_selected_day[$i]['fixture']['status']['short'] === 'FT')  )

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
   <div class="flag_container'. (date('d-m-Y') === date('d-m-Y', $date) ? ' black_color' : ' white_color') .'">
   <img src="'. $matches_on_selected_day[$i]['teams']['away']['logo'] . '"/>
   <p>' . 
   $matches_on_selected_day[$i]['teams']['away']['name'] . '
   </div>'; 

 
   echo '</div>
   </div>';
      };

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

  }
  

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

?>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js">
</script>

</body>
</html>
