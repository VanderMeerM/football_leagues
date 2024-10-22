
<?php 

$round_determination = [];

$current_page = explode('/', $_SERVER['PHP_SELF'])[4];

$json_enddates = './JSON/enddates_'. $league_id . '_' . $current_season . $complete_season . '.json'; 

$enddates = file_get_contents($json_enddates, true);

$php_array_for_dates = json_decode($enddates, true);

$played_rounds = sizeof($php_array_for_dates);

$allrounds = [];

$array_leagues = [88, 89, 78, 79, 135, 140, 39, 40, 179, 357];

$current_season = 2024;

$_GET['league'] ? $league_id = $_GET['league'] : $league_id = 88; 

$complete_season = $current_season + 1; 

for ($i=1; $i < $played_rounds; $i++) {

  if (
  
    (date('Y-m-d', $php_array_for_dates['Ronde ' . $i]) >= date('Y-m-d')) 
  
    /*
    (date('Y-m-d', $php_array_for_dates['Ronde ' . $i]) == date("Y-m-d", strtotime('yesterday'))) ||
    (date('Y-m-d', $php_array_for_dates['Ronde ' . $i]) == date('Y-m-d', strtotime('-2 days'))) || 
    (date('Y-m-d', $php_array_for_dates['Ronde ' . $i]) == date('Y-m-d', strtotime('tomorrow')))
  */
    
    )
  
   { 
    array_push($round_determination, $php_array_for_dates['Ronde ' . $i] . '+' . $i);
  }
  }
  
  asort($round_determination);
  $array_of_round_of_first_upcoming_matches = $round_determination[array_key_first($round_determination)];
  
  $round_of_first_upcoming_matches = explode('+', $array_of_round_of_first_upcoming_matches)[1];


$IntlDateFormatter = new IntlDateFormatter(
  'nl_NL',
  IntlDateFormatter::LONG,
  IntlDateFormatter::NONE

);

for ($i = 1; $i < $played_rounds + 1; $i++) {
    array_push($allrounds, $i);
}


echo "
<div class='title_container'> 

<div>
<a>
  <img id='logo' src='https://media.api-sports.io/football/leagues/" . $league_id . ".png'/>
  </a>
</div>

<div class= 'btn_container'> 

<div id='season_title'> Seizoen " . $current_season . '-' . $complete_season . "</div> 

<p>";

if ($current_page !== 'standings.php') {

echo "

<form action='./league.php?round_selection=$selectedround method='get'>

<select " . ($_GET['id'] ? 'style=visibility: hidden' : null) . " id='round_selection' name='round_selection'>";

for ($i =0; $i < sizeof($allrounds); $i++) {

  if ($allrounds[$i] == $_GET['round_selection']) {

    echo '<option selected value= ' . $allrounds[$i] . '> Ronde ' . $allrounds[$i] . ' (' . $IntlDateFormatter-> format($php_array_for_dates['Ronde ' . $allrounds[$i]]) .')</option>'; 
  } 
  else 
  { 
    echo '<option value= ' . $allrounds[$i] . '> Ronde ' . $allrounds[$i] . ' (' . $IntlDateFormatter-> format($php_array_for_dates['Ronde ' . $allrounds[$i]]) .')</option>';
   
  }
} 

echo "
</select>
</form>";

}

if ($_GET['id']) {
  echo 
  "<div class='menubuttons'>
<ul>
 <li><a href='./league.php?league=' . $league_id . '&round_selection=' . $round_selection . '>Terug naar overzicht</a></li>
 </ul>
 </div>'
 ";
}


echo "
</div>
</div>";

echo "<div class='container_league_logos'>
<form action='./league.php?league=' . $league_id . '&round_selection='. $round_of_first_upcoming_matches' method='post'>";

foreach ($array_leagues as $al) {

  echo "
 
  <button type='submit'><img id=$al class='league_icon' src='https://media.api-sports.io/football/leagues/$al.png'/></button>"; 

}

echo "
</form>
</div>
<div class='menubar'>
<div class='menubuttons'>
<ul>";
 
if ($current_page === "league.php") {
echo '<li><a href="./standings.php?league=' . $league_id . '&season=' . $current_season . '">Toon stand</a></li>';
}
else {

  echo '<li><a href="./league.php?league=' . $league_id . '&season=' . $current_season . '+round_selection=' . $round_of_first_upcoming_matches . '">Toon programma</a></li>';

}

echo '
</ul>
</div>
</div>';

?>


<script>

  let leagueId = <?php echo json_encode($league_id); ?>;
  if (Object.is(leagueId, null)) { leagueId == 88 };


  document.getElementById('round_selection').addEventListener('change', (ev) => {
roundSelection = ev.target.value;
window.location.href='./league.php?league='+leagueId+'&round_selection='+roundSelection
});


function clickBtnLeague(idBtn) {
  document.getElementById(idBtn).addEventListener('click', () => { 
 
    currentPage = <?php echo json_encode($current_page) ?>;

     
    if (currentPage === 'standings.php') {
      window.location.href='./standings.php?league='+document.getElementById(idBtn).id+'&season='+<?php echo json_encode($_GET['season']); ?>
      }
     /* else { 
      window.location.href='./league.php?league='+document.getElementById(idBtn).id+'&round_selection='+<?php echo json_encode($round_of_first_upcoming_matches) ?>
      
    }
      */
      
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

 </script>
 
 