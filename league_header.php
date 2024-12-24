
<?php 

$current_page = explode('/', $_SERVER['PHP_SELF'])[4];

// $played_rounds = sizeof($php_array_for_dates);

$allrounds = [];

$array_leagues = [88, 89, 78, 79, 135, 140, 39, 40, 179, 408]; // 357 = Ierse competitie

$array_bgcolor_leagues = ['#002e61', '#c9152a', '#cf0513', '#cf0513', '#0c90fd', '#ff4b44', '#3d185c', '#9ba5d0', '#301b76', '#264439']; 

$array_bgcolor_menubar = array_combine($array_leagues, $array_bgcolor_leagues); 

$round_from_match_to_overview = setcookie('round_from_match_to_overview', $_GET['round_selection'], 3600, '/');

$_GET['league'] ? $league_id = $_GET['league'] : $league_id = 88; 

$_GET['season'] ? $selected_season = $_GET['season'] : $selected_season = $current_season; 

$json_enddates = './JSON/enddates_'. $league_id . '_' . $selected_season . ($selected_season + 1) . '.json'; 

$enddates = file_get_contents($json_enddates, true);

$php_array_for_dates = json_decode($enddates, true);

$IntlDateFormatter = new IntlDateFormatter(
  'nl_NL',
  IntlDateFormatter::LONG,
  IntlDateFormatter::NONE

);

for ($i = 1; $i < sizeof($php_array_for_dates) + 1; $i++) {
    array_push($allrounds, $i);
}

echo "
<div class='title_container'> 

<div>
<a>
  <img id='logo' src='https://media.api-sports.io/football/leagues/" . $league_id . ".png'/>
  </a>
</div>

<div class='btn_container'> 

<div id='season_title'> Seizoen 
<select id='season_selection'>";

for ($i = $current_season; $i >= 2020; $i--) {
  $end_season = $i + 1; 
  echo "<option value=$i> $i-$end_season </option>";
}

echo "
</select>
</div>
<p>";

if ($current_page !== 'standings.php') {

echo "

<form action='./league.php?season=$selected_season&round_selection=$selectedround' method='get'>

<select " . ($_GET['id'] ? 'style=visibility: hidden' : null) . " id='round_selection' name='round_selection'>";

for ($i =0; $i < sizeof($allrounds); $i++) {
 
    echo '<option '. ($allrounds[$i] == $_GET['round_selection'] ? 'selected' : null) . ' value= ' . $allrounds[$i] . '> Ronde ' . $allrounds[$i] . ' (' . $IntlDateFormatter-> format($php_array_for_dates['Ronde ' . $allrounds[$i]]) .')</option>'; 
  }; 
  
echo "
</select>
</form>";

}

echo "
</div>

<div class='container_league_logos'>";

foreach ($array_leagues as $al) {

$round_determination = [];

$json_enddates = './JSON/enddates_'. $al . '_' . $selected_season . ($selected_season + 1) . '.json'; 

$enddates = file_get_contents($json_enddates, true);

$php_array_for_dates = json_decode($enddates, true);

for ($i=1; $i < sizeof($php_array_for_dates); $i++) {

  if (
  
    (date('Y-m-d', $php_array_for_dates['Ronde ' . $i]) >= date('Y-m-d', strtotime('tomorrow'))) 
      
    )
  
   { 
    array_push($round_determination, $php_array_for_dates['Ronde ' . $i] . '+' . $i);
  }
  }
  
  asort($round_determination);
  $array_of_round_of_first_upcoming_matches = $round_determination[array_key_first($round_determination)];
  
  $round_of_first_upcoming_matches = explode('+', $array_of_round_of_first_upcoming_matches)[1];

  $page_to_go; 

  if ($current_page === 'league.php') {
    $page_to_go = "./league.php?league=$al&season=$selected_season&round_selection=$round_of_first_upcoming_matches";
  }
  else {
    $page_to_go = "./standings.php?league=$al&season=$selected_season";
  }

  echo "
  <form action=$page_to_go name='form_leagues$al' method='post'>

  <a onClick='document.form_leagues$al.submit();'>
  <img class='league_icon' src='https://media.api-sports.io/football/leagues/$al.png'/>
  </a>
  </form>";
 
}

echo "
</div>

<div class='fixed fixed_menubar' style='background-color:  $array_bgcolor_menubar[$league_id]; '>
<div class='center_buttons'>

<div class='menubar'>
<div class='menubuttons'>
</div>
</div>

<ul>";

 
if ($current_page === "league.php") {
echo '<li><a href="./standings.php?league=' . $league_id . '&season=' . $selected_season . '">Toon stand</a></li>';
}
else {

  echo '<li><a href="./league.php?league=' . $league_id . '&season=' . $selected_season . '&round_selection=' . $round_of_first_upcoming_matches . '">Toon programma</a></li>';

}

if ($_GET['id']) {

  $round_to_match = $_GET['round_selection']; 

  echo 
  "<div class='menubuttons'>
<ul>
 <li><a href='./league.php?league=$league_id&round_selection=$round_to_match'>Terug naar overzicht</a></li>
 </ul>
 </div>'";
}

echo '
</ul>
</div>
</div>';

?>


<script>

  let leagueId = <?php echo json_encode($league_id); ?>;
  if (Object.is(leagueId, null)) { leagueId == 88 };

  currentPage = <?php echo json_encode($current_page) ?>;

if (currentPage !="standings.php") {

document.getElementById('round_selection').addEventListener('change', (ev) => {
roundSelection = ev.target.value;
window.location.href='./league.php?league='+leagueId+'&round_selection='+roundSelection
});

}

document.getElementById('season_selection').addEventListener('change', (ev) => {
seasonSelection = ev.target.value;
console.log(seasonSelection);
//window.location.href='./league.php?league='+leagueId+'&season='+seasonSelection
//+'&round_selection='+roundSelection
});



/* 
function clickBtnLeague(idBtn) {
  document.getElementById(idBtn).addEventListener('click', () => { 
 

     
    if (currentPage === 'standings.php') {
      window.location.href='./standings.php?league='+document.getElementById(idBtn).id+'&season='+<?php echo json_encode($_GET['season']); ?>
      }
     
     else { 
      window.location.href='./league.php?league='+document.getElementById(idBtn).id+'&round_selection='+<?php echo json_encode($round_of_first_upcoming_matches) ?>
      
    }
       
 })
      
}


 clickBtnLeague(88);
 clickBtnLeague(89);
 clickBtnLeague(78);
 clickBtnLeague(79);
 clickBtnLeague(135);
 clickBtnLeague(140);
 clickBtnLeague(39);
 clickBtnLeague(40);
 clickBtnLeague(179);
 clickBtnLeague(357);
 clickBtnLeague(408);
*/

 </script>
 
 