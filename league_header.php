
<?php 

/* IS ONDERSTAANDE ARRAY (3 regels) NOG NODIG?
$json_enddates = './JSON/enddates/enddates_'. $league_id . '_' . $selected_season . ($selected_season + 1) . '.json'; 

$enddates = file_get_contents($json_enddates, true);

$php_array_for_dates = json_decode($enddates, true);
*/

//echo sizeof($php_array_for_dates); 

$IntlDateFormatter = new IntlDateFormatter(
  'nl_NL',
  IntlDateFormatter::SHORT,
  IntlDateFormatter::NONE

);

for ($i = 1; $i < sizeof($allrounds) + 1; $i++) {
    array_push($allrounds, $i);
}

($_GET['id'] ? $big_image_leage = $league_to_fixture : $big_image_leage = $league_id); 

echo "
<div class='title_container'> 

<div>
<a>
  <img id='logo' src='https://media.api-sports.io/football/leagues/" . $big_image_leage . ".png'/>
  </a>
</div>

<div class='btn_container'>"; 


if (!$_GET['id']) {

echo"

<div id='season_title' style='color: $array_bgcolor_menubar[$league_id];'> Seizoen 
<select id='season_selection'>";

for ($i = $current_season; $i >= 2016; $i--) {
  echo '<option ' . ($i == $_GET['season'] ? 'selected ' : null) . 'value= ' . $i . '>' . $i . '-' . ($i + 1) . '</option>';
}

echo "
</select>
</div>
<p>";


if ($current_page !== 'standings.php') {

echo "

<form action='./league.php?season=$selected_season&round_selection=$round_of_first_upcoming_matches' method='get'>

<select " . ($_GET['id'] ? 'style=visibility: hidden' : null) . " id='round_selection' name='round_selection'>";

/* !! als op laatste dag een wedstrijd is, staat deze ook als begindatum; wordt niet opgelost met sortering zoals onder..

$array_values= explode(',', $array_dates_round[27]);
print_r($array_values[9]); 

for ($u = 0; $u < $lastdate_selected_round; $u++) {
  asort(explode(',', $array_dates_round[$u]));
 }
 implode($array_dates_round);
*/

($_GET['round_selection'] ? $round_to_select = $_GET['round_selection'] : $round_to_select = $round_of_first_upcoming_matches); 

for ($i = 1; $i <= sizeof($array_dates_round); $i++) {

   
    echo '
    <option '. ($i == intval($round_to_select) ? 'selected' : null) . ' value= ' . $i . '>
     Ronde ' . $i . ' 
     (' . substr($IntlDateFormatter-> format(explode(',', $array_dates_round[$i])[0]), 0, -3) . ' - ' 
      . substr($IntlDateFormatter-> format(explode(',', $array_dates_round[$i])[$lastdate_selected_round]), 0, -3) .')
     </option>'; 
  }; 
  
echo "
</select>
</form>";

}

echo "</div>";

};

echo "<div class='container_league_logos'>";

foreach ($array_leagues as $al) {


  /* Kan weg? 
  
  $json_enddates = './JSON/enddates/enddates_'. $al . '_' . $selected_season . ($selected_season + 1) . '.json'; 
  
  $enddates = file_get_contents($json_enddates, true);
  
  $php_array_for_dates = json_decode($enddates, true);
  
   print_r ($array_dates_round[1]);
  
  echo 'Aantal: ' . sizeof(explode(',',$array_dates_round[1]));
  
  echo 'Eind: ' . explode(',',$array_dates_round[1])[8];
  
  */
  
   
    if ($current_page === 'league.php') {
      $page_to_go = "./league.php?league=$al&season=$selected_season";
    }
    else {
      $page_to_go = "./standings.php?league=$al&season=$selected_season";
    }

  echo "
  <form action='' name='form_leagues$al' method='post'>

  <a href=$page_to_go> 
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

  if ($_GET['id']) {
    echo '<li><a href="./standings.php?league=' . $league_to_fixture . '&season=' . $season_to_fixture . '">Toon stand</a></li>';
  }
  else {
echo '<li><a href="./standings.php?league=' . $league_id . '&season=' . $selected_season . '">Toon stand</a></li>';
}
}
else {

  echo '<li><a href="./league.php?league=' . $league_id . '&season=' . $selected_season . '">Toon programma</a></li>';

}

if ($_GET['id']) {

 
  echo 
  "<div class='menubuttons'>
<ul>
 <li><a href='./league.php?league=$league_to_fixture&season=$season_to_fixture&round_selection=$round_to_fixture'>Terug naar overzicht</a></li>
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

(window.location.href.split('season=')[1] == undefined) ? seasonSelection = 1 :
seasonSelection = window.location.href.split('season=')[1].slice(0,4);

window.location.href='./league.php?league='+leagueId+'&season='+seasonSelection+'&round_selection='+roundSelection;
}
);
};
  
document.getElementById('season_selection').addEventListener('change', (ev) => {
seasonSelection = ev.target.value;

(window.location.href.split('round_selection=')[1] == undefined) ? roundSelection = 1 :
roundSelection = window.location.href.split('round_selection=')[1];

if (currentPage !="standings.php") {

window.location.href='./league.php?league='+leagueId+'&season='+seasonSelection;
}
else 
{
  window.location.href='./standings.php?league='+leagueId+'&season='+seasonSelection;

}}
)


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
 
 