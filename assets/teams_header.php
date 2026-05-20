

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

// Menu Penalty's 

 echo 
'<div class="menubuttons">
<li><a target="_blank" href= "../penalty">P</a></li>
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


// Menu Overzicht


if ($_GET['id']) {

 echo "
<form action='./' method='post'>
<input type='hidden' name='country_code' value= '$country_to_match'>
<button type='submit' id='send_cc' name='send_cc'> Overzicht</button>
</form>";
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

 // Menu EK/WK 

 echo 
'<div class="menubuttons"> 
<select class="menu_sel_item" style=background-color:' .  $array_bgcolor_menubar[$league_id] . ' name="EKWK" onchange="window.open(this.value);">
  <option class="menu_option" selected disabled value="">EK/WK</option>
  <option class="menu_option" value="../EK">EK</option>
  <option class="menu_option" value="../WK/">WK</option>
  </select>

</ul>
</div>
</div>
</div>';


if ($_GET['id']) {
  setcookie('teams_team_selection', $selected_team_team, (time() + 3600), '/', '', true);

}

for ($i=$current_season; $i >= 2010; $i--) {
  array_push($allseasons, $i);
}

for ($i=0 ; $i < sizeof($fav_teams); $i++) {
 if ($fav_teams[$i]['value'] == $team_id) {
   $bc = $fav_teams[$i]['bg'];
 }
}

if (!$bc) {
 $bc = '#e6e7ec';
}

$fav_teams_3 = [$fav_teams[0], $fav_teams[1], $fav_teams[2]]; 

echo '
<div class="title_container" style="background-image: linear-gradient(to right, ' . $bc . ')">

<div style="height: 20px; background-image: linear-gradient(to right, ' . $bc . ')"></div>

 
<div class="container_logos">';

if (!is_null($_POST['team_code'])) {
 $selected_team_logo = $_POST['team_code'];
} else {
$selected_team_logo = $_COOKIE['teams_team_selection'];
}

foreach ($fav_teams_3 as $team) {

echo '
<div id="logo_club" ' . ($team['value'] == $selected_team_logo ? "style='border: grey 2px solid'" : null) . '">

<form action="./" method="post">
  <input type="hidden" id="team_code" name="team_code" value='. $team['value'] .'> 
  <input type="hidden" id="country_code" name="country_code" value="Netherlands"> 
  <button type="submit" name="send_team" id="send_team"> 
 <img '.($team['value'] == $selected_team_logo ? setcookie('teams_team_selection', $team['value'], time() + 3600, '/', '', true)
 : null) . ' src= "https://media.api-sports.io/football/teams/'. $team['value']. '.png"/> 
 </button>
 </form> 
</div>';
}


echo '</div>';

include('../assets/teams_from_lg.php');

echo "<div class='center_buttons'>

<form action='./' method='post'>

<select name='season_selection' onchange='this.form.submit()'> 

<option disabled>Kies een seizoen</option>"; 

for ($i =0; $i < sizeof($allseasons); $i++) {
  
    echo '<option '. ($allseasons[$i] == $season ? 'selected' : null) . ' value= ' . $allseasons[$i] . 
    '>' . $allseasons[$i] . ' - ' . ($allseasons[$i] +1) .'</option>'; 
    }

echo "
</select>
</form>
</div>";



