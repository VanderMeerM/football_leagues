
<?php 

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

*/

echo "
<div class='fixed fixed_menubar' style='background-color: #002e61;'>
<div class='center_buttons'>

<div class='menubar'>
<div class='menubuttons'>
</div>
</div>

<ul>";

echo "
<div class='menubuttons'>
<a style='padding: 0px' href= './teams' > <img id='shirt' style='cursor:pointer' src='./img/shirt.png'></a>
</div>";

$view = 'hidden';
 
if (str_contains($current_page, $menu_league) && (!str_contains($current_page, $menu_day)) && (!str_contains($current_page, $menu_standings))) {
  
  echo '<li><a id="table_txt" href="./standings?league=' . $league_id . '&season=' . $selected_season . '"></a></li>';
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


for ($i=2010; $i <= $current_season; $i++) {
  array_push($allseasons, $i);
}

$teams = array(
  ['club' => 'Ajax', 'value' => 194, 'bg' => '#c2002f, #fff'],
  ['club' => 'NEC', 'value' => 413, 'bg' => '#009b69, #ed1c24, #080808'],
  ['club' => 'Feyenoord', 'value' => 209, 'bg' => '#ed1c24, #000'],
  ['club' => 'PSV', 'value' => 197, 'bg' => '#ed1c24, #fff'],
  ['club' => 'MVV', 'value' => 412, 'bg' => '#e73140, #fff'],
  ['club' => '1. FC Köln', 'value' => 192, 'bg' => '#ff0000, #fff']
  
);

for ($i=0 ; $i < sizeof($teams); $i++) {
 if ($teams[$i]['value'] == $team_id) {
   $bc = $teams[$i]['bg'];
 }
}

echo "
<div class='title_container' style='background-image: linear-gradient(to right, $bc)'>
<div id='logo_club'>
  <img src= 'https://media.api-sports.io/football/teams/$team_id.png'/> 
 
 
</div>

<div> 

<div id='header_info'> 
</div>

<div class='center_buttons'>

<form action='./teams' method='post'>";

echo "<select name='team_selection' id='team_selection' onchange='this.form.submit()'> 

<option disabled>Kies een team</option>"; 

for ($i =0; $i < sizeof($teams); $i++) {

  if (intval($teams[$i]['value']) == $team_id) {

    echo '<option selected  value= ' . $teams[$i]['value'] . '>' . $teams[$i]['club'] . '</option>'; 
  } 
  else 
  { 
    echo '<option  value= ' . $teams[$i]['value'] . '>' . $teams[$i]['club'] . '</option>'; 
   
  }
} 
  setcookie('teams_team_selection', $_POST['team_selection'], time() + 3600, '/');


echo "
</select>
</form>";

echo "<form action='./teams' method='post'>";

echo "<select name='season_selection' onchange='this.form.submit()'> 

<option disabled>Kies een seizoen</option>"; 

for ($i =0; $i < sizeof($allseasons); $i++) {

  
    echo '<option '. ($allseasons[$i] == $season ? 'selected' : null) . ' value= ' . $allseasons[$i] . 
    '>' . $allseasons[$i] . ' - ' . ($allseasons[$i] +1) .'</option>'; 
   
    }

setcookie('teams_season_selection', $_POST['season_selection'], time() + 3600, '/');

// cookie in JS opslaan, zodat selectie altijd behouden blijft. 

?>

<script> 
  for (i=1; i< document.getElementById('team_selection').length; i++) {
if (document.getElementById('team_selection')[i].selected) {
  selectedTeam = document.getElementById('team_selection')[i].value
}
}
document.cookie = 'teams_team_selection=' + selectedTeam + ';expires= ' + Date.now() + 86400 + '; path=/';
</script> 

<?php 

echo "
</select>
</form>
</div>";



