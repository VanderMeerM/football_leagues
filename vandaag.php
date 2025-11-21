
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Vandaag</title>  
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

//echo $_POST['today'];
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
curl_close($curl);
 
}

else {

    $array_all_leagues = array_merge($array_leagues, $array_extra_leagues);
    $matches_today = [];

    for ($l=0; $l < sizeof($array_all_leagues); $l++) {
    $cur_url_mt = 'https://v3.football.api-sports.io/fixtures?&league=' . $l . '&season='. $current_season;
    
    $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $cur_url_mt,
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
  
$response_1 = curl_exec($curl);

//array_push($matches_today, $response);

curl_close($curl);

$matches_today = json_decode($response_1, true);


}

}

//print_r($matches_today);
//echo($matches_today['response'][3]['fixture']['date']); // = json_decode($matches_today, true);

}

$array_matches_today_filtered = [];

for ($i=0; $i < sizeof($matches_today); $i++) {
   
 echo    $date = date_create($matches_today['response'][$i]['fixture']['date']);

  if ($_POST['today'] === date_format($date, 'd-m-Y')) {

array_push($array_matches_today_filtered, $matches_today[$i]); 
}
  }

print_r($response = $array_matches_today_filtered);

echo $numGames = $response['results'];

include('./get_current_round.php');

/*
if (!$_GET['season']) {
  header("Location: ./league.php?league=" . $league_id . "&season=" . $selected_season);
}
  */

if ($_GET['id']) {
  $round_to_fixture = intval(explode(' ', $response['response'][0]['league']['round'])[3]);
  $season_to_fixture = $response['response'][0]['league']['season'];
  $league_to_fixture = $response['response'][0]['league']['id'];
 }

$prevent_loop = false;

$games_per_round = [];


include('./league_header.php');

echo '</div>';

$matchesInRound = [];

if ($numGames > 0 ) {

  for ($i = 0; $i < $numGames; $i++) {

  if (!$prevent_loop) {

  $homeTeam = $response['response'][$i]['teams']['home']['name'];
  $awayTeam = $response['response'][$i]['teams']['away']['name'];
  $matchId = $response['response'][$i]['fixture']['id'];
  $matchStatus = $response['response'][$i]['fixture']['status']['short'];

  $selectedround_int_leagues =  $response['response'][$i]['league']['round']; 
  $selectedround = intval(explode(' ', $response['response'][$i]['league']['round'])[3]);
  
  if ((!$_GET['round_selection']) || is_null($_GET['round_selection'])) { 

   if ($_GET['season'] != $current_season) {
        $round_to_select = 1;
        }
         else {
           $round_to_select = $round_of_first_upcoming_matches;
        } 
        
        if ($_GET['id']) {
       $round_to_select = $round_to_fixture;
      
   }  
   }
  
   elseif ($_GET['round_selection']) {
    $round_to_select = $_GET['round_selection'];
   }
 
   if ($round_to_select == $selectedround) {
    
 array_push($matchesInRound, $response['response'][$i]);

  $date = date_create($response['response'][$i]['fixture']['date']);

  if (date('d-m-Y') === date_format($date, 'd-m-Y')) {

    echo '<div class="main_container background_today_match">';
   }
  
else { 

 echo '<div class="main_container">'; 
}

  if (!$_GET['id']) {
   
      echo '<a '. (date('d-m-Y') === date_format($date, 'd-m-Y') ? ' style="background-color: ' . $backgr_today_match : null) . '" href="' . $_SERVER['PHP_SELF'] . '?id=' . $matchId . '">';
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
        '<div class="score_home ' . (!is_null($response['response'][$i]['goals']['home']) ? 'w-12 pd_score' : null) . '">' . $response['response'][$i]['goals']['home'] . '</div>' . 
        
        '<div class="vs '. (date('d-m-Y') === date_format($date, 'd-m-Y') ? 'black_color' : 'white_color') . '"> - ' . '</div>' .   
         '<div class="score_away '. (!is_null($response['response'][$i]['goals']['away']) ? 'w-12 pd_score' : null) . '">'. $response['response'][$i]['goals']['away'] . '</div>
          
        </div>
        </div>';

       
        if ($_GET['id']) { 

            echo '<p><div class="stscore_ref">
            <img id="ref" src="../ref.png">' . '<br> ' . explode(',', $response['response'][$i]['fixture']['referee'])[0] . 
           '<br>'; 
                
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

 }}
  }}

  $miR_sorted = array_map(function ($mt) {
    return $mt['timestamp'];
  }, $matchesInRound);

array_multisort($miR_sorted, SORT_ASC, $matchesInRound);

?>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js">
</script>

</body>
</html>