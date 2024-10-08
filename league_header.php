
<?php 

 setcookie('current_round', 7, time()+86400, '/');

function sort_by_end($arr) {
	// Sorteert van klein naar groot 
    usort($arr, function($a, $b) {
        if ($a['fixture']['timestamp'] > $b['fixture']['timestamp']) {
            return 1;
        }

        elseif ($a['fixture']['timestamp'] < $b['fixture']['timestamp']) {
            return -1;
        }

        return 0;
    });
    return $arr;
}

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

<p>";

if ((!$_GET['id']) || ($current_page !== 'standings.php')) {

  echo "

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
}

echo "
</div>
</div>";

echo "<div class='container_league_logos'>
<form action='./league.php?league=' . $league_id . '&round_selection='. $round' method='post'>";

foreach ($array_leagues as $al) {

  echo "
 
  <img id=$al class='league_icon' src='https://media.api-sports.io/football/leagues/$al.png'/>"; 

}

echo "

</form>
</div>";

echo '
<div class="menubar">
<div class="menubuttons">
<ul>';
 
if ($current_page === "league.php") {
echo '<li><a href="./standings.php?league=' . $league_id . '&season=' . $current_season . '">Toon stand</a></li>';
}
else {
echo '<li><a href="./league.php?league=' . $league_id . '&season=' . $current_season . '">Toon programma</a></li>';
}

echo '
</ul>
</div>
</div>';

?>

<script>

  let leagueId = <?php echo json_encode($league_id); ?>;
  if (Object.is(leagueId, null)) { leagueId == 88 };

 //document.getElementById('logo').addEventListener('click', () => 


document.getElementById('round_selection').addEventListener('change', (ev) => {
roundSelection = ev.target.value;
 //regSeason = 'Regular Season - ';
 window.location.href='./league.php?league='+leagueId+'&round_selection='+roundSelection
});


 function clickBtnLeague(idBtn) {
  document.getElementById(idBtn).addEventListener('click', () => { 

<?php

for ($i=1; $i < $played_rounds; $i++) {

if (
  (date('Y-m-d', $php_array_for_dates['Ronde ' . $i]) == date('Y-m-d')) ||
  (date('Y-m-d', $php_array_for_dates['Ronde ' . $i]) == date("Y-m-d", strtotime('yesterday'))) ||
  (date('Y-m-d', $php_array_for_dates['Ronde ' . $i]) == date('Y-m-d', strtotime('-2 days'))) || 
  (date('Y-m-d', $php_array_for_dates['Ronde ' . $i]) == date('Y-m-d', strtotime('tomorrow')))
  )
  
{ 
 $round = $i;
 setcookie('current_round', $i, time()+86400, '/');
 break;
}
}
?>

    currentPage = <?php echo json_encode($current_page) ?>;

    //round = <?php echo json_encode($round) ?>;
    round = <?php echo $_COOKIE['current_round'] ?>;
    console.log(round);

    roundSelection = <?php 
    if (isset($_GET['round_selection'])) { echo json_encode($_GET['round_selection']); }
    else { echo json_encode(1); } ?>

    if (currentPage === 'standings.php') {
      window.location.href='./standings.php?league='+document.getElementById(idBtn).id+'&season='+<?php echo json_encode($_GET['season']); ?>
      }
      else { 
      window.location.href='./league.php?league='+document.getElementById(idBtn).id+'&round_selection='+round;
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

 </script>