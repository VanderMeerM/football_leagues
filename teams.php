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

$league_id = 88;
// 2, 3, 848 (CL, EL & Conf. League)
// 90 KNVB beker


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

include('./teams_header.php');


//$json_matches_path = '/json/matches/matches_date_' . $day . '_.json'; 

//if (!file_exists($json_matches_path)) { 

//194 ajax
//412 mvv
//413 nec

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://v3.football.api-sports.io/fixtures?team='. $team_id . '&league=' . $league_id . '&season='. $season,
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

curl_close($curl);

/*

if ($day < date('Y-m-d', strtotime('today'))) {

$json_file_mt = fopen($json_matches_path, "w");

fwrite($json_file_mt, $response);

fclose($json_file_mt);
}




//else {
  $response_json = file_get_contents($json_matches_path, true);

  $response= json_decode($response_json, true);

*/

//
$response= json_decode($response, true);

$prevent_loop = false;

//print_r($response);

$numGames = $response['results'];


//if ($numGames > 0 ) {

  for ($i = 0; $i < $numGames; $i++) {

  if (!$prevent_loop) {

  $homeTeam = $response['response'][$i]['teams']['home']['name'];
  $awayTeam = $response['response'][$i]['teams']['away']['name'];
  $matchId = $response['response'][$i]['fixture']['id'];
  $matchStatus = $response['response'][$i]['fixture']['status']['short'];
  $date = date('d-m-Y', $response['response'][$i]['fixture']['timestamp']);

  
  if ((!$_GET['id']) || ($_GET['id'] && $_GET['id'] == $matchId)) {

  echo '
  <div class="main_container extra_padding">'; 
  
  if (!$_GET['id']) {

         $_GET['date'] = date('Y-m-d', strtotime('today'));
   
    echo '<a href="' . $_SERVER['PHP_SELF'] . '?date='. $_GET['date'] . '&id=' . $matchId . '">';
  }

echo'
  <div class="country_container' . (date('d-m-Y') === $date ? ' black_color' : ' white_color') . '">

 <div class="flag_container' . (date('d-m-Y') === $date ? ' black_color' : ' white_color') .'">
  <img src="'.$response['response'][$i]['teams']['home']['logo'] . '"/></div>
   <p>
  ' . $response['response'][$i]['teams']['home']['name'] . ''; 

   
  echo '</div>
          <div class="stscore_container white_color">';
                     
         if ($_GET['id']) { echo $response['response'][$i]['fixture']['venue']['name'] . '<br>'; }

         if (!$_GET['id'])  { echo $response['response'][$i]['fixture']['venue']['city'] . '<br>'; }

         $date = date_create($response['response'][$i]['fixture']['date']);
         echo date_format($date, 'd-m-Y') . ' ';
         echo date('H:i', $response['response'][$i]['fixture']['timestamp'])  . '<br>
         
         <div style="font-size:15pt; font-weight: 600">'. (array_key_exists($matchStatus, $status)? 
         $status[$matchStatus] : null) . 
          '</div>'; 

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

           
          if (sizeof(explode(',', $response['response'][$i]['fixture']['referee'])) > 1) {

            echo

           (array_search(explode(', ', $response['response'][$i]['fixture']['referee'])[1], $countries) ? 
           '(' . array_search(explode(', ', $response['response'][$i]['fixture']['referee'])[1], $countries) : 
           '(' . explode(', ', $response['response'][$i]['fixture']['referee'])[1]) . ')
           <br></div>'; 
                   
           }

           else {
            echo '</div>';
           }
          }

        echo '</div>';
         
   echo '
  <div class="country_container' . (date('d-m-Y') === $date ? ' black_color' : ' white_color') . '">
 <div class="flag_container' . (date('d-m-Y') === $date ? ' black_color' : ' white_color') .'">
   <img src="'.$response['response'][$i]['teams']['away']['logo'] . '"/></div>
   
    <p>
  ' . $response['response'][$i]['teams']['away']['name'] . ''; 


 // if (array_search($awayTeam, $countries)) { echo array_search($awayTeam, $countries); } 
//  else { echo $awayTeam; }  

  
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
  }
//}

/*
else {
$selectedDate = date_create($_GET['date']);
echo '<div class="nomatches"> Geen wedstrijden op ' . date_format($selectedDate, 'd-m-Y') . '</div>';
};
*/

?>

</body>
</html>
