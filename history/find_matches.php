
<script>

// Direct pagina laden bij aanklikken tabblad in browser.. 

document.addEventListener("visibilitychange", function() {
    if (!document.hidden){
       window.location.reload();
    }
});
</script>

<?php 


// Alle wedstrijden van thuis-team ophalen over alle seizoenen (eigenlijk zelfde werking als in Teams)..

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


// Wedstrijden over laatste 10 seizoenen ophalen...

$first_season = $season - 3;

while ($first_season <= $season) {

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

for ($i=0; $i <= sizeof($all_matches_leagues); $i++) {

if (
  ($all_matches_leagues[$i]['teams']['home']['id'] == (explode('-', $_POST['Inp_teamB'])[0])) ||
  ($all_matches_leagues[$i]['teams']['away']['id'] == (explode('-', $_POST['Inp_teamB'])[0]))
  )
  {
  array_push($found_matches, $all_matches_leagues[$i]);

  array_push ($array_leagues_found_matches, 
  ($all_matches_leagues[$i]['league']['id'].'-'.$all_matches_leagues[$i]['league']['name']));
  }
}


$array_leagues_found_matches = array_unique($array_leagues_found_matches, SORT_REGULAR);

$array_leagues_found_matches = array_values(array_unique($array_leagues_found_matches));

$all_matches_leagues = $found_matches;

