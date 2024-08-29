
<?php 

$json_enddates = './JSON/enddates_'. $league_id . '_' . $current_season . $complete_season . '.json'; 

$enddates = file_get_contents($json_enddates, true);

$php_array_for_dates = json_decode($enddates, true);

$played_rounds = sizeof($php_array_for_dates);

$allrounds = [];

$IntlDateFormatter = new IntlDateFormatter(
  'nl_NL',
  IntlDateFormatter::LONG,
  IntlDateFormatter::NONE

);

for ($i = 1; $i < $played_rounds + 1; $i++) {
    array_push($allrounds, $i);
}

/* test voor controleren op huidige datum speelronde 

echo $foundDate = var_dump(array_filter($php_array_for_dates, function ($d) {
  return date('d-M-Y', $d) == date('d-M-Y'); 
}, ARRAY_FILTER_USE_BOTH));
*/ 

echo "
<div class='title_container'> 

<div>
<a>
  <img id='logo' src='https://media.api-sports.io/football/leagues/" . $league_id . ".png'/>
  </a>
</div>

<div class= 'btn_container'> 

<div id='season_title'> Seizoen " . $current_season . '-' . $complete_season . "</div> 

<p> 

<form action='./league.php?round_selection=$selectedround method='get'>

<select id='round_selection' name='round_selection'>";

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

echo "
</div>
</div>";

echo "<div class='container_league_logos'>";

foreach ($array_leagues as $al) {

  echo "
 
  <img id=$al class='league_icon' src='https://media.api-sports.io/football/leagues/$al.png'/>"; 

}

echo "

</div>";

echo '
<form action="./standings.php?league=' . $league_id . '&season=' . $current_season . '" method="post">
 <button id="show_league">Toon stand </button>
</form>';

?>

<script>

  let leagueId = <?php echo json_encode($league_id); ?>;
  if (Object.is(leagueId, null)) { leagueId == 88 };

 document.getElementById('logo').addEventListener('click', () => window.location.href='./league.php?league='+leagueId+'&round_selection='+roundSelection);

 document.getElementById('round_selection').addEventListener('change', (ev) => {
 roundSelection = ev.target.value;
 //regSeason = 'Regular Season - ';
 window.location.href='./league.php?league='+leagueId+'&round_selection='+roundSelection
});

 function clickBtnLeague(idBtn){
  document.getElementById(idBtn).addEventListener('click', () => { 
    
    roundSelection = <?php if (isset($_GET['round_selection'])) { echo json_encode($_GET['round_selection']); }
    else { echo json_encode(1); } ?>
    
      window.location.href='./league.php?league='+document.getElementById(idBtn).id+'&round_selection='+roundSelection
 });

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

