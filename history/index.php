<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Leagues</title>  
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

// Deze regels straks weer verwijderen/uitcommentarieren...
/*
$team_json_file = './all_teams.json';
$teams = fopen($team_json_file, 'r');
fclose($teams);
$response_json = file_get_contents($team_json_file, true);
$response = json_decode($response_json, true);
array_push($all_teams, $response);
*/


$all_teams_response = [];
$name_teams = [];
$ind = 0; 

require('../assets/api.php');

include('../assets/variables.php');

include('../assets/translations.php');

include('../assets/getTZ.php');


// Teams uit competitie ophalen...

// $array_all_leagues = array_merge($array_leagues, $array_cup_leagues, $array_extra_leagues);

  
// Deze regels weer uitcommentariëren..

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

// in array met id, land en naam team...

while ($ind < sizeof($all_teams_response)) {

for ($i=0; $i < sizeof($all_teams_response[$ind]); $i++) {

  array_push($name_teams, [
  'id'=> $all_teams_response[$ind][$i]['team']['id'], 
  'name' => $all_teams_response[$ind][$i]['team']['name'], 
  'country' => $all_teams_response[$ind][$i]['team']['country'], 
  'main_league' => $all_teams[$ind]['parameters']['league']
  ]);

  array_multisort(array_column($name_teams, 'name'), SORT_ASC, $name_teams);

  }

  $ind++;
}

include('./menu_history.php');

echo '<div id="no_found_matches" style="font-weight: bold; font-style:normal">Confrontaties uit het verleden (t/m '.($current_season-1).'-'.$current_season.')</div>';


if ($_POST['TeamA'] != 'Selecteer een team:') {   
setcookie("TeamA", $_POST['TeamA'], time() + 3600, "/", '', true);
}

if ($_POST['TeamB'] != 'Selecteer een team:') {   
setcookie("TeamB", $_POST['TeamB'], time() + 3600, "/", '', true);

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

if ($_POST['Inp_teamA'] && $_POST['Inp_teamB']) {

include ('./find_matches.php');
 
}

?>

</body>
</html>
