
<?php 

echo "
<div class='fixed fixed_menubar' style='background-color:  $array_bgcolor_menubar[$league_id]; '>
<div class='center_buttons'>

<div class='menubar'>
<div class='menubuttons'>
</div>
</div>


<ul>";

//<li><a href= './teams'>Clubs</a></li>

echo "
<div class='menubuttons'>
<a style='padding: 0px' href= './teams' > <img id='shirt' style='cursor:pointer' src='./img/shirt.png'></a>
</div>";

$view = 'hidden';
 
if (str_contains($current_page, $menu_league) && (!str_contains($current_page, $menu_day)) && (!str_contains($current_page, $menu_standings))) {
  
  if ($_GET['id']) {
    echo '<li><a id="table_txt" href="./standings?league=' . $league_to_fixture . '&season=' . $season_to_fixture . '"></a></li>';
  }
  else {
  echo '<li><a id="table_txt" href="./standings?league=' . $league_id . '&season=' . $selected_season . '"></a></li>';
}
}

if (str_contains($current_page, $menu_standings)) {
  echo '<li><a id="prog_txt" href="./league?league=' . $league_id . '&season=' . $selected_season . '"></a></li>';
}

// Menu Overzicht
if ($_GET['id']) {

 $ref = "./league?league=$league_to_fixture&season=$season_to_fixture&round_selection=$round_to_fixture";
 $font_color = "white";
 $cursor = "pointer";
} 
else {
  $ref = "#";
  $font_color = 'lightgray';
  $cursor = "none";
}

echo "
<li><a href= $ref style= 'color: $font_color ;cursor: $cursor'>
Overzicht</a></li>";


// Menu Vandaag 

$today = strtotime('today');

echo 
  "<div class='menubuttons'>
<form method='post' action='./day'>
<input type='image' id='agenda' style='cursor:pointer' src='./img/agenda.png'>
<input type='hidden' name='sel_day' value=$today>
<input type='submit' style='display: none'>
</form>
</div>";

 // Menu EK/WK 

 echo 
'<div class="menubuttons"> 
<select class="menu_sel_item" style=background-color:' .  $array_bgcolor_menubar[$league_id] . ' name="EKWK" onchange="window.open(this.value);">
  <option class="menu_option" selected disabled value="">EK/WK</option>
  <option class="menu_option" value="../EK">EK</option>
  <option class="menu_option" value="../WK">WK</option>
  </select>';

 echo '
</ul>
</div>
</div>
</div>';



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
<div class='title_container'>  </div>

<div class='main_container_league_season_rounds " 
 . (str_contains($current_page, $menu_standings) ? 'block_class' : null)  . ">";


echo "
<div class='container_big_logo_league'>";

if (!str_contains($current_page, $menu_day)) {

echo "
<a href='./league?league=" . $league_id . "&season=" . $selected_season . "'>
  <img id='logo' src='https://media.api-sports.io/football/leagues/" . $big_image_leage . ".png'/>
  </a>

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
</div>";
}


// !! als op laatste dag een wedstrijd is, staat deze ook als begindatum; wordt niet opgelost met sortering zoals onder..
// alleen array met geselecteerde ronde op volgorde zetten; de rest staat al goed. 

//print_r(explode(',', $array_dates_round[$_GET['round_selection']]));
 //print_r($array_dates_round[$_GET['round_selection']]); //print_r($array_dates_round[28]);

if (!str_contains($current_page, $menu_standings)) {

  /* 
  for ($u = 0; $u < sizeof($array_dates_round); $u++) {
  explode(',', $array_dates_round[$u]);
  //asort($array_dates_round[$u]);
 }
  */

//echo sizeof($array_dates_intern_leagues);
//echo $array_rounds_International_leagues[$lastdate_selected_round_int_leagues];

echo "
<div class='container_select_rounds'>

<form action='./league?season=$selected_season&round_selection=$round_of_first_upcoming_matches' method='get'>

<select " . ($_GET['id'] ? 'style=visibility: hidden' : null) . " id='round_selection' name='round_selection'>";


// Selectie voor internationale competities.. 

if (in_array($league_id, $array_intern_leagues)) {

if ($_GET['round_selection']) {

$round_to_select = $_GET['round_selection'];
 }

else { // indien geen ronde, haal eerstvolgende speeldatum op. 

$selected_date_int_round = []; 

for ($i=0; $i < sizeof($array_dates_int_round_sorted); $i++) {
  
if (date('Y-m-d', $array_dates_int_round_sorted[$i][0]) >= date('Y-m-d', strtotime('Today'))) 
  
  { 
    array_push($selected_date_int_round, $array_dates_int_round_sorted[$i][0]);
  };

  echo $round_to_select = $array_dates_int_round_sorted[$_GET['round_selection']];

}}

for ($i = 0; $i < sizeof($array_dates_intern_leagues); $i++) {

 $first_key_int = array_key_first($array_dates_int_round_sorted[$i]);
 $last_key_int = array_key_last($array_dates_int_round_sorted[$i]);

   echo '
    <option '. ($selected_date_int_round[0] >=  $array_dates_int_round_sorted[$i][$first_key_int] ? 'selected' : null) . ' value= "' . array_keys($array_dates_intern_leagues)[$i] . '">'
     . array_keys($array_dates_intern_leagues)[$i] . ' 
     (' .  date('d-m', $array_dates_int_round_sorted[$i][$first_key_int]) . ' - ' 
      . date('d-m', $array_dates_int_round_sorted[$i][$last_key_int]) .')
     </option>'; 

    }'';
}

// Selectie voor reguliere competities.. 

else {

($_GET['round_selection'] ? $round_to_select = $_GET['round_selection'] : $round_to_select = $round_of_first_upcoming_matches); 
  
for ($i =1; $i <= sizeof($array_dates_round_sorted); $i++) {

  //$ind_lastdate_selected_round = sizeof($array_dates_round_sorted[$i-1])-1;

 $first_key = array_key_first($array_dates_round_sorted[$i-1]);
 $last_key = array_key_last($array_dates_round_sorted[$i-1]);


/*
  echo '
    <option '. ($i == intval($round_to_select) ? 'selected' : null) . ' value= ' . $i . '>Ronde ' . $i . ' 
     (' . substr($IntlDateFormatter-> format(explode(',', $array_dates_round[$i])[0]), 0, -3) . ' - ' 
      . substr($IntlDateFormatter-> format(explode(',', $array_dates_round[$i])[$lastdate_selected_round]), 0, -3) .')
     </option>'; 
 */
     echo '
      <option '. ($i == intval($round_to_select) ? 'selected' : null) . ' value= ' . $i . '>Ronde ' . $i . ' 
     (' . date('d-m', $array_dates_round_sorted[$i-1][$first_key]) . ' - ' 
      . date('d-m', $array_dates_round_sorted[$i-1][$last_key]) .')
     </option>'; 

    }
  }; 
  
echo "
</select>
</form>
</div>
</div>";
}};


echo "
</div>
</div>";


// Rij met logo's van competities opbouwen..

echo "<div style=display:" . (str_contains($current_page, $menu_standings) ? 'block;' : "flex;") . "text-align: center>";
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
  
   
 if (!str_contains($current_page, $menu_standings)) {
      $page_to_go = "./league?league=$al&season=$selected_season";
    }
 else {
      $page_to_go = "./standings?league=$al&season=$selected_season";
    }

  echo "
  <form action='' name='form_leagues$al' method='post'>

  <a href=$page_to_go> 
  <img class='league_icon' src='https://media.api-sports.io/football/leagues/$al.png'/>
  <input type='hidden' value=$al >
  </a>
  </form>";
}

echo 
"</div>";


// Rij met data opbouwen..

$array_of_dates = [];
$number_dates = 14;

for ($i= (-1 * $number_dates); $i <= $number_dates; $i++) {
  array_push($array_of_dates, $today + $i * 86400);
  }

echo 
"</div>

<div class='menu_dates container_league_logos'>";

foreach ($array_of_dates as $aod) {
  echo 
    '<div class="container_dates ' . 
    (($_GET['datum'] === date('d-m-Y', $aod) && str_contains($current_page, $menu_day)) || 
    ((!$_GET['datum'] && date('d-m-Y', $today) === date('d-m-Y', $aod)) && str_contains($current_page, $menu_day)) ? 'highlight_date' : null).'"> 
    <a href="./day?datum='. date('d-m-Y', $aod) . '">
    <strong>' . 
    date('d', $aod) . '</strong><br> '
    . date('m', $aod) . '</a></div>';
}
    
echo "</div>";
?>


<script>

  let leagueId = <?php echo json_encode($league_id); ?>;
  if (Object.is(leagueId, null)) { leagueId == 88 };

  currentPage = <?php echo json_encode($current_page) ?>;
  currentSeason = <?php echo json_encode($current_season) ?>;

if (currentPage !="standings.php") {

if (document.getElementById('round_selection') != undefined) {

document.getElementById('round_selection').addEventListener('change', (ev) => {
roundSelection = ev.target.value;

currentSeason = <?php echo json_encode($current_season) ?>;

(window.location.href.split('season=')[1] == undefined) ? seasonSelection = 1 :
seasonSelection = window.location.href.split('season=')[1].slice(0,4);

window.location.href='./league?league='+leagueId+'&season='+seasonSelection+'&round_selection='+roundSelection;

}
);
}};
  
document.getElementById('season_selection').addEventListener('change', (ev) => {
seasonSelection = ev.target.value;

(window.location.href.split('round_selection=')[1] == undefined) ? roundSelection = 1 :
roundSelection = window.location.href.split('round_selection=')[1];

if (currentPage !="standings.php") {

window.location.href='./league?league='+leagueId+'&season='+seasonSelection;
}
else 
{
  window.location.href='./standings?league='+leagueId+'&season='+seasonSelection;

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

*/

 </script>
 
 