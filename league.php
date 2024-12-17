<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

$array_leagues = [88, 89, 78, 79, 135, 140, 39, 40, 179, 357, 408];

$current_season = 2024;

$_GET['season'] ? $selected_season = $_GET['season'] : $selected_season = $current_season; 

$_GET['league'] ? $league_id = $_GET['league'] : $league_id = 88; 

$backgr_today_match = '#e4cd84';

include('./translations.php');


$json_league_season_path = './JSON/seasons/'. $league_id . '_season_'. $current_season . ($current_season+1) . '.json'; 

$json_fixture = './JSON/fixtures/' . $_GET['id'] . '.json'; 


if ($_GET['id']) {

     $cur_url = 'https://v3.football.api-sports.io/fixtures?&id=' . $_GET['id'];
  }
   else {
    $cur_url = 'https://v3.football.api-sports.io/fixtures?&league=' . $league_id . '&season='. $current_season;
  }

  /*
  if (file_exists($json_fixture)) {

    $response_json_fixture = file_get_contents($json_fixture, true);
    $response = json_decode($response_json_fixture, true);   
  }

  
       if ( ($_GET['id']) && (!file_exists($json_fixture)) &&
           (date('d-m-Y', strtotime($response['response'][$i]['fixture']['timestamp'])) < date('d-m-Y', strtotime('Today'))) ) {
             
           $json_file_fixture = fopen($json_fixture, "w");
           
           fwrite($json_file_fixture, $response);
           
           fclose($json_file_fixture);
           }
*/

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
    'x-rapidapi-key: f7e1aa54fd70dd93a3c920f503282930',
    'x-rapidapi-host: v3.football.api-sports.io',
    
  ),
));

$response = curl_exec($curl);

curl_close($curl);



//if ($day < date('Y-m-d', strtotime('today'))) {

if (5 < 4) {
$json_file_mt = fopen($json_league_season_path, "w");

fwrite($json_file_mt, $response);

fclose($json_file_mt);

$response_json = file_get_contents($json_league_season_path, true);

$response= json_decode($response_json, true);
}

//$response= json_decode($response, true);

//echo date('d-m-Y', $response[0]['response']['fixture']['timestamp']);


// Deze 5 regels uitcommentariëren

/*
$array_season = './season_20242025.json';
$curr_season = fopen($array_season, 'r');
fclose($curr_season);
$response_json = file_get_contents($array_season, true);
$response= json_decode($response_json, true);
*/

$response = json_decode($response, true);

$prevent_loop = false;

$enddate_selected_round = [];
$games_per_round = [];

$numGames = $response['results'];

include('./league_header.php');

//echo 'Test: ' . date('d-m-Y', strtotime($response['response'][0]['fixture']['timestamp']));
//echo 'Vandaag: ' . date('d-m-Y', strtotime('Today'));


$matchesInRound = [];

if ($numGames > 0 ) {

  for ($i = 0; $i < $numGames; $i++) {

  if (!$prevent_loop) {

  $homeTeam = $response['response'][$i]['teams']['home']['name'];
  $awayTeam = $response['response'][$i]['teams']['away']['name'];
  $matchId = $response['response'][$i]['fixture']['id'];
  $matchStatus = $response['response'][$i]['fixture']['status']['short'];
  $selectedround = intval(explode(' ', $response['response'][$i]['league']['round'])[3]);

  $enddate_selected_round['Ronde '. $selectedround] = $response["response"][$i]["fixture"]["timestamp"];

  if ((!$_GET['round_selection']) || is_null($_GET['round_selection'])) { 
    $_GET['round_selection'] = 1;
    $selectedround = 1;
  }

  if ($_GET['round_selection'] == $selectedround) {
    
    array_push($matchesInRound, $response['response'][$i]);

  //if ((!$_GET['id']) || ($_GET['id'] == $matchId)) {

  $date = date_create($response['response'][$i]['fixture']['date']);

  if (date('d-m-Y') === date_format($date, 'd-m-Y')) {

    echo '<div class="main_container">';
   }
  
else { 

 echo '<div class="main_container">'; 
}


  if (!$_GET['id']) {
   
      echo '<a '. (date('d-m-Y') === date_format($date, 'd-m-Y') ? 'style="background-color: ' . $backgr_today_match : null) . '" href="' . $_SERVER['PHP_SELF'] . '?round_selection=' . $selectedround . '&league=' . $league_id . '&id=' . $matchId . '">';
  }  

  echo '
  <div class="country_container">'; 


  echo '
  <div class="flag_container' . (date('d-m-Y') === date_format($date, 'd-m-Y') ? ' black_color' : ' white_color') .'">
  <img src="'.$response['response'][$i]['teams']['home']['logo'] . '"/>
  <p>
  ' . $response['response'][$i]['teams']['home']['name'] . '</div>'; 

  
  echo '</div>

  <div class="stscore_container'. (date('d-m-Y') === date_format($date, 'd-m-Y') ? ' black_color' : ' white_color') . '">'; 

                  
         if ($_GET['id']) { echo $response['response'][$i]['fixture']['venue']['name'] . '<br>'; }

         if (!$_GET['id'])  { echo $response['response'][$i]['fixture']['venue']['city'] . '<br>'; }

         echo date_format($date, 'd-m-Y') . ' ';
         echo date('H:i', $response['response'][$i]['fixture']['timestamp'])  . '<br>';

         echo 
         '<div style="font-size:15pt; font-weight:600" '. (array_key_exists($matchStatus, $status)? 'class="red">' 
         . $status[$matchStatus] : 'class="black_color"') . 
         '<br>
         <div class="score">' .
        '<div class="score_home ' . (!is_null($response['response'][$i]['goals']['home']) ? 'w-12 pd_score' : null) . '">' . $response['response'][$i]['goals']['home'] . '</div>' . 
        
        '<div class="vs '. (array_key_exists($matchStatus, $status) ? 'red' : 'white_color') . '"> - ' . '</div>' .   
         '<div class="score_away '. (!is_null($response['response'][$i]['goals']['away']) ? 'w-12 pd_score' : null) . '">'. $response['response'][$i]['goals']['away'] . '</div>
          
        </div>
        </div>';

       
        if ($_GET['id']) { 

            echo '<p><div class="stscore_ref">
            <img id="ref" src="../ref.png">' . '<br> ' . explode(',', $response['response'][$i]['fixture']['referee'])[0] . 
           '<br>'; 

                 
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
   <div class="flag_container'. (date('d-m-Y') === date_format($date, 'd-m-Y') ? ' black_color' : ' white_color') .'">
   <img src="'.$response['response'][$i]['teams']['away']['logo'] . '"/>
   <p>' . 
   $response['response'][$i]['teams']['away']['name'] . '
   </div>'; 

 
   echo '</div>
   </div>';

   if (!$_GET['id']) {
    echo '</a>';
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


$json_file_enddate = fopen($json_enddates, "w");

fwrite($json_file_enddate, json_encode($enddate_selected_round));

fclose($json_file_enddate);

?>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js">
</script>

</body>
</html>
