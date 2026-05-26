<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../assets/teams.css" />   
    <link href="./penaltyboard.css" rel="stylesheet" type="text/css" />
    <script defer src="./penaltyboard.js"></script> 

   <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous"> -->
  

    <title>Penalties schieten</title>
</head>

<?php 

// Menu 

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



// Menu teams (met shirtje) 

echo "
<div class='menubuttons'>
<a style='padding: 0px' href= '../teams' > <img id='shirt' src='../img/shirt.png'></a>
</div>";

// Menu Vandaag 

$today = strtotime('today');

echo 
  "<div class='menubuttons'>
<form method='post' action='../day'>
<input type='image' id='agenda' style='cursor:pointer; width: 30px; height: 30px' src='../img/agenda.png'>
<input type='hidden' name='sel_day' value=$today>
<input type='submit' style='display: none'>
</form>
</div>";

 // Menu EK/WK 

 echo 
'<div class="menubuttons"> 
<select class="menu_sel_item" style="background-color: #002e61; color: white; font-weight: bold;" name="EKWK" onchange="window.open(this.value);">
  <option class="menu_option" style="color: white; font-weight: bold;" selected disabled value="">EK/WK</option>
  <option class="menu_option" style="color: white; font-weight: bold;" value="../EK">EK</option>
  <option class="menu_option" style="color: white; font-weight: bold;" value="../WK">WK</option>
  </select>';

 echo '
</ul>
</div>
</div>
</div>';


if ($_POST['countryA']) {
setcookie("CountryA", $_POST['countryA'], time() + 86400, "/", '', true);
}

elseif ($_POST['countryB']) {
setcookie("CountryB", $_POST['countryB'], time() + 86400, "/", '', true);
}

$curl_url = "https://www.apicountries.com/countries";

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $curl_url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
  ),
));

$response = curl_exec($curl);

$response = json_decode($response, true);

for ($i=0; $i < sizeof($response); $i++) {

  $array_countries_nl[] = 
  [
  'country' => $response[$i]['translations']['nl'],
  'flag' => $response[$i]['flags']['png'] 
  ];

  }

array_multisort(array_column($array_countries_nl, 'country'), SORT_ASC, $array_countries_nl);


echo '
<body class="text-center">

    <h2>Selecteer land of voer (club)naam in </h2>

    <div class="container_countries_teams">

    <div class="float-lg-left">
       <strong> Team A </strong> 

      
    <form method="post" action="">

    <select name="countryA" id="countryA" onchange="this.form.submit()">
        <option selected >Selecteer land:</option>';
        
        if ($_POST['countryA']) {
            $selca = $_POST['countryA']; 
            }
            else {
               $selca = $_COOKIE['CountryA'];  
            }

        for ($i=0; $i < sizeof($array_countries_nl); $i++) {
            echo '<option ' . ($array_countries_nl[$i]['flag'] === $selca ? 'selected' : null) .' 
            value='. $array_countries_nl[$i]['flag'] .'>'. $array_countries_nl[$i]['country'] .'</option>';
        }

echo '
</select></div>

<div class="container_club">
<input placeholder="Naam club" id="clubA">

</div>
</div>
</form>';

echo '
<div class="float-lg-right">
       <strong> Team B </strong> 
     
<form method="post" action="">

    <select name="countryB" id="countryB" onchange="this.form.submit()">
        <option selected>Selecteer land:</option>';

         if ($_POST['countryB']) {
            $selcb = $_POST['countryB']; 
            }
            else {
               $selcb = $_COOKIE['CountryB'];  
            }
       
        for ($i=0; $i < sizeof($array_countries_nl); $i++) {
            echo '<option ' . ($array_countries_nl[$i]['flag'] === $selcb ? 'selected' : null) .' 
            value='.$array_countries_nl[$i]['flag'].'>'. $array_countries_nl[$i]['country'] .'</option>';
        }
echo '
</select>
<div class="container_club">
<input placeholder="Naam club" id="clubB">
</div>
</div></form>';

echo '</div>';

echo '
<div style="display:block">

    Welk team start? 

<div id="startingTeam">
    <input type="radio" checked id="Team_A" name="startteam" value="Team A">
    <label for="Team_A">Team A</label>
    <input type="radio" id="Team_B" name="startteam" value="Team B">
    <label for="Team_B">Team B</label>

</div>

</div>

</div>


<div id="playerA_name">
    <div id="flagA">
    <img src= ' . $selca . '>
        
    <div id="playerA">';
/*
        for ($i=0; $i < 5; $i++) {
       echo 
       '<div class="left" style="background-color: green;"></div>
        <div class="right" style="background-color: red;"></div>';
        }
        echo '
        </div>
        </div> */
echo '</div>

<br>

<div id="playerB_name">
   <div id="flagB">
   <img src= ' . $selcb . '>
     <div id="playerB">';

/*      for ($i=0; $i < 5; $i++) {
       echo 
       '<div class="left" style="background-color: green;"></div>
        <div class="right" style="background-color: red;"></div>';
        }
*/
       echo '
       </div>
       </div>

</div>
   
<div id="winner"> </div>';


?>


</body>

</html>