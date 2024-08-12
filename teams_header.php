
<?php 

for ($i=2010; $i <= $current_season; $i++) {
  array_push($allseasons, $i);
}

$teams = array(
  ['club' => 'Ajax', 'value' => 194, 'bg' => '#c2002f, #fff'],
  ['club' => 'NEC', 'value' => 413, 'bg' => '#009b69, #ed1c24, #080808'],
  ['club' => 'Feyenoord', 'value' => 209, 'bg' => '#ed1c24, #000'],
  ['club' => 'PSV', 'value' => 197, 'bg' => '#ed1c24, #fff'],
);

for ($i=0 ; $i < sizeof($teams); $i++) {
 if ($teams[$i]['value'] == $team_id) {
   $bc = $teams[$i]['bg'];
 }
}

echo "
<div class='title_container' style='background-image: linear-gradient(to right, $bc)'>
<div id='logo'>
  <img src= 'https://media.api-sports.io/football/teams/$team_id.png'/> 
 
 
</div>

<div class= 'btn_container'> 

<div id='header_info'> 
</div>
<p>
<form action='./teams.php' method='post'>";

echo "<select name='team_selection' onchange='this.form.submit()'> 

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


echo "
</select>
</form>";

echo "<form action='./teams.php' method='post'>";

echo "<select name='season_selection' onchange='this.form.submit()'> 

<option disabled>Kies een seizoen</option>"; 

for ($i =0; $i < sizeof($allseasons); $i++) {

  if ($allseasons[$i] == $season) {

    echo '<option selected  value= ' . $allseasons[$i] . '>' . $allseasons[$i] . '</option>'; 
  } 
  else 
  { 
    echo '<option  value= ' . $allseasons[$i] . '>' . $allseasons[$i] . '</option>'; 
   
  }
} 


echo "
</select>
</form>";

setcookie('teams_team_selection', $_POST['team_selection'], time() + 3600, '/');
setcookie('teams_season_selection', $_POST['season_selection'], time() + 3600, '/');


