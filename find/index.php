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

  .selection_teams {
    display: flex;
    justify-content: left;
    margin: 5%;

  }
</style>

<?php 

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

$all_teams = [];
$all_teams_response = [];
$name_teams = [];
$ind = 0; 

require('../assets/api.php');

include('../assets/variables.php');

include('../assets/translations.php');

include('../assets/getTZ.php');


// Teams uit competitie ophalen...

 //$array_all_leagues = array_merge($array_leagues, $array_cup_leagues, $array_extra_leagues);

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


// in array met id en naam team...

while ($ind < sizeof($all_teams_response)) {

for ($i=0; $i < sizeof($all_teams_response[$ind]); $i++) {

  array_push($name_teams, ['id'=> $all_teams_response[$ind][$i]['team']['id'], 'name' => $all_teams_response[$ind][$i]['team']['name']]);
  
  }

  $ind++;
}

echo "
<div class='fixed fixed_menubar' style='background-color: #002e61;'>
<div class='center_buttons'>

<div class='menubar'>
<div class='menubuttons'>
</div>
</div>

<ul>";

// Menu Penalty's 

 echo 
'<div class="menubuttons">
<li><a href= "../penalty">P</a></li>
</div>';


// Menu Find 

 echo 
'<div class="menubuttons">
<li><a href= "../find">F</a></li>
</div>';


// Menu teams (met shirtje) 

(str_contains($current_page, $menu_teams) ? $link_teams = './' : $link_teams = './teams');  

echo 
"<div class='menubuttons'>
<a style='padding: 0px' href= $link_teams > <img id='shirt' style='cursor:pointer' src='../img/shirt.png'></a>
</div>";


// Menu Stand

if (!in_array($league_to_fixture, $array_cup_leagues)) { // Alleen tonen indien geen bekerwedstrijd 

echo '<li><a id="table_txt" href="../standings?league='. $selected_leage_team .'&season=' . $selected_season_team . '&team='.$team_id.'"></a></li>';


}

// Menu Vandaag 

$today = strtotime('today');

echo 
  "<div class='menubuttons'>
<form method='post' action='../day'>
<input type='image' id='agenda' style='cursor:pointer' src='../img/agenda.png'>
<input type='hidden' name='sel_day' value=$today>
<input type='submit' style='display: none'>
</form>
</div>";

echo '
</ul>
</div>
</div>
</div>';

if ($_POST['first_team']) {
  
echo '
<div>
  <img src="https://media.api-sports.io/football/teams/'.$_POST['first_team'].'.png">
</div>';  
 
}


echo '<div class="selection_teams">

<form method="post">

<select name="first_team" onchange="this.form.submit()">

<option selected disabled> Selecteer een team </option>';

for ($i=0; $i < sizeof($name_teams); $i++) {
  echo '<option value='.$name_teams[$i]['id'].'> ' .$name_teams[$i]['name']. '</option>';
}

echo '
</select>
</form>
</div>';


/*
if (sizeof($matches_on_selected_day) == 0) {
  echo '<div id="no_found_matches"> Geen wedstrijden op '. date('d-m-Y', $_POST['sel_day']).' gevonden.</div>';
  return;
};

//Wedstrijden sorteren op tijdstip;

if (($_POST['orderByLeagueTime'] === 'ob_time') || (!$_POST['orderByLeagueTime'])) {

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

$matches_on_selected_day = array_merge($matches_live, $matches_not_live);

$numGames = sizeof($matches_on_selected_day);


if ($_GET['id']) {
  $round_to_fixture = intval(explode(' ', $response[0]['league']['round'])[3]);
  $season_to_fixture = $response[0]['league']['season'];
  $league_to_fixture = $response[0]['league']['id'];
 }

$prevent_loop = false;

$games_per_round = [];

echo '</div>';



// Aantal competities tellen om sorteerkeuze te tonen (bij meer dan 1 competitie)..

$num_leagues = []; 

for ($i=0; $i < sizeof($matches_on_selected_day); $i++) {
 array_push($num_leagues, $matches_on_selected_day[$i]['league']['id']);
}

if (sizeof(array_unique($num_leagues)) > 1) {

echo ' 

<div class= "container_sortby_league_time">
<form method="post" action=" '.($_GET['datum'] ? '?datum='. $_GET['datum'] : null) . '">

<input name="orderByLeagueTime" onchange="this.form.submit();" value="ob_league" type="radio" 
'. ($_POST['orderByLeagueTime'] === 'ob_league' ? 'checked' : null ) .' > Competitie

<input name="orderByLeagueTime" onchange="this.form.submit();" value="ob_time" type="radio" 
'. (($_POST['orderByLeagueTime'] === 'ob_time' || !$_POST['orderByLeagueTime']) ? 'checked' : null ) .' > Tijdstip 

</form>';


if (sizeof($matches_live) > 0) {
echo '<div id="loading" class="position_circle_left">
  <div class="hold left">
    <div class="fill"></div>
  </div>
  <div class="hold right">
    <div class="fill"></div>
  </div>
</div>'; 
}

echo '</div>';
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

 (!$selectedround ? $round='' : null);

// Competitielogo met -naam 
echo 
'<div class="background_match">
<div class="container_league_logo_name">
<a id="space_cont_league_logo" href="#">
<img id="league_logo" src = ' . $matches_on_selected_day [$i]['league']['logo'] . ' id="img_logo_day"">' 
. $league_name . ' ('.$round . (array_key_exists($selectedround, $array_rounds) ? $array_rounds[$selectedround] : $selectedround) .')</a></div>

<div class="main_container">';

   echo '<a style="background-color: ' .(date('d-m-Y') === date('d-m-Y', $_POST['sel_day']) ? $backgr_today_match : null) .'" 
   href="../league?id=' . $matchId . '&datum='. date('d-m-Y', $matches_on_selected_day[$i]['fixture']['timestamp'])  .'">';

}


 echo '<div class="country_container">

 <div class="flag_container' . (date('d-m-Y') === date('d-m-Y', $_POST['sel_day']) ? ' black_color' : ' white_color') .'">
  <img src="'.$matches_on_selected_day[$i]['teams']['home']['logo'] . '"/>
  <p>
  ' . (array_key_exists($homeTeam, $array_countries) ? $array_countries[$homeTeam] : $homeTeam). '
  </div> 
  </div>

   <div class="stscore_container' . (date('d-m-Y') === date('d-m-Y', $_POST['sel_day']) ? ' black_color' : ' white_color') .'">'; 
 
        if (!array_key_exists($matchStatus, $status)) {
   
       echo '<div'. ($matchStatus === 'PEN' ? ' id="rem_text_when_pen"' : null) .'>'
          . $matches_on_selected_day[$i]['fixture']['venue']['city']; 

          echo '</div>';
         
                  
         echo '<div'. ($matchStatus === 'PEN' ? ' id="rem_text_when_pen"' : null) .'>' 

        . date('d-m-Y H:i', $matches_on_selected_day[$i]['fixture']['timestamp']) . '</div>'; 
        }

        
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
         '<div '. (array_key_exists($matchStatus, $status)? 'class="font_status_match red">
         '. ($matchStatus === 'P' ? null : $elapsed . '"') . 
         '<br>'.$status[$matchStatus] : 'class="black_color"') . 
         '<br>';
         }

          if ($matchStatus === 'PEN') {
            if ($matches_on_selected_day[$i]['teams']['home']['winner'] == 1) { 
                echo '<div class= 
                ' .(date('d-m-Y') === date('d-m-Y', $_POST['sel_day']) ? "black_color" : "white_color") .'>
                ' . (array_key_exists($homeTeam, $array_countries) ? $array_countries[$homeTeam] : $homeTeam). '
                w.n.s. <br>'; 
                }
                elseif ($matches_on_selected_day[$i]['teams']['away']['winner'] == 1) { 
                   echo '<div class=
                  ' .(date('d-m-Y') === date('d-m-Y', $_POST['sel_day']) ? "black_color" : "white_color") .'>
                  ' . (array_key_exists($awayTeam, $array_countries) ? $array_countries[$awayTeam] : $awayTeam). '
                   w.n.s.<br>'; 
                }
                 echo '(' . $matches_on_selected_day[$i]['score']['penalty']['home']. ' - 
                ' .$matches_on_selected_day[$i]['score']['penalty']['away'] . ')</div>';
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
            <img id="ref" src="../img/ref.png">' . '<br> ' . explode(',', $matches_on_selected_day[$i]['fixture']['referee'])[0] . 
           '<br>'; 
          
          
         

      }
        echo '</div>';

        if ($_GET['id']) {
          echo '</div>';
        }
         
   echo '<div class="country_container">
 <div class="flag_container' . (date('d-m-Y') === date('d-m-Y', $_POST['sel_day']) ? ' black_color' : ' white_color') .'">
  <img src="'. $matches_on_selected_day[$i]['teams']['away']['logo'] . '"/>
   <p>
  ' . (array_key_exists($awayTeam, $array_countries) ? $array_countries[$awayTeam] : $awayTeam). '
   </div>'; 

 
   echo 
   '</div></a>
   </div>
   </div>';
      };

   echo '</div>
   <div style="height: 10px"></div>';


   if ( (sizeof($matches_on_selected_day) > 3) || ($_GET['id']) ) {
  echo '
  <div id="arrow_up">↑</div>';
};

   if ($_GET['id']) {
   include ('../assets/events.php');
   include ('../assets/lineup.php');
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
  localStorage.removeItem("lineup");

if (document.getElementById('arrow_up') !=null) {

 document.getElementById('arrow_up').addEventListener('click', () => {

  if (document.getElementById('top') !=null) {
  document.getElementById('top').scrollIntoView({behavior: 'smooth'});
}
   
})
} 
</script>


*/
?> 
</body>
</html>
