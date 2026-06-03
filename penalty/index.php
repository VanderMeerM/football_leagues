<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../assets/teams.css" />   
    <link href="./penaltyboard.css" rel="stylesheet" type="text/css" />
    <script defer src="./penaltyboard.js"></script> 


    <title>Penalties schieten</title>
</head>

<?php 

/* To do:

- Clubnamen functionaliteit
- check op zelfde land of club (alert) 
- flow met penalty's nog beter checken  (m.n. als team B begint..)
*/

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

 /*
 echo 
'<div class="menubuttons"> 
<select class="menu_sel_item" style="background-color: #002e61; color: white; font-weight: bold;" name="EKWK" onchange="window.open(this.value);">
  <option class="menu_option" style="color: white; font-weight: bold;" selected disabled value="">EK/WK</option>
  <option class="menu_option" style="color: white; font-weight: bold;" value="../EK">EK</option>
  <option class="menu_option" style="color: white; font-weight: bold;" value="../WK">WK</option>
  </select>';

  */

 echo '
</ul>
</div>
</div>
</div>';

if ($_POST['countryA'] != 'Selecteer land:') {   
setcookie("CountryA", $_POST['countryA'], time() + 3600, "/", '', true);
}

if ($_POST['countryB'] != 'Selecteer land:') {
setcookie("CountryB", $_POST['countryB'], time() + 3600, "/", '', true);
}

if ($_POST['clubA']) { 
 setcookie("clubA", $_POST['clubA'], time() + 3600, "/", '', true); 
}

if ($_POST['clubB']) { 
 setcookie("clubB", $_POST['clubB'], time() + 3600, "/", '', true); 
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

<div class="main_container_countries_teams">

 <div class="container_team_A">
       <strong><u>Team A</u></strong>  
<div>

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
</select>
</div>

<div>
<form action="" method="post">
<input placeholder="Naam club A" name="clubA" id="clubA">
<input type="submit" style="display: none">

</form>
</div>
</form>
</div>';

?>

<script>
  document.getElementById('countryA').addEventListener('change', () => {
    if (document.getElementById('countryA').value === document.getElementById('countryB').value) {
      alert('Een land kan niet tegen zichzelf spelen.');
     }
    }
    )
</script>
<?php

echo '
<div class="container_team_B">
<strong><u>Team B</u></strong>  
<div>

    
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
</div>

<div>
<form action="" method="post">
<input placeholder="Naam club B" name="clubB" id="clubB">
<input type="submit" style="display: none">
</div>

</form>
</div>';

?>

<script>
  document.getElementById('countryB').addEventListener('change', () => {
    if (document.getElementById('countryB').value === document.getElementById('countryA').value) {
      alert('Een land kan niet tegen zichzelf spelen.');
     }
    }
    )
</script>

<?php

echo '
</div>
<div>

   <h3> Welk team start? </h3>'; 

if ($_POST['countryA']) {
    
    for ($i=0; $i < sizeof($array_countries_nl); $i++) {

      if ($array_countries_nl[$i]['flag'] === $_POST['countryA']) {
        $teamA = $array_countries_nl[$i]['country'];
      }
    }    
 } 
 
 elseif($_COOKIE['CountryA']) {
     for ($i=0; $i < sizeof($array_countries_nl); $i++) {

      if ($array_countries_nl[$i]['flag'] === $_COOKIE['CountryA']) {
        $teamA = $array_countries_nl[$i]['country'];
      }
    } 
      
 } else {
     $teamA = 'Team A';
 }

  if ($_POST['clubA']) {

    $teamA = $_POST['clubA'];
  } 
  elseif ($_COOKIE['clubA']) {
    $teamA = $_COOKIE['clubA'];
  }
 

  if ($_POST['countryB']) {

    for ($i=0; $i < sizeof($array_countries_nl); $i++) {

      if ($array_countries_nl[$i]['flag'] === $_POST['countryB']) {
        $teamB = $array_countries_nl[$i]['country'];
      }
    }  
} elseif ($_COOKIE['CountryB']) {
     for ($i=0; $i < sizeof($array_countries_nl); $i++) {

      if ($array_countries_nl[$i]['flag'] === $_COOKIE['CountryB']) {
        $teamB = $array_countries_nl[$i]['country'];
      }
    } 

 } else {
    $teamB = 'Team B';
 }
 
  if ($_POST['clubB']) {
       $teamB = $_POST['clubB'];
  } elseif ($_COOKIE['clubB']) {
    $teamB = $_COOKIE['clubB'];
  }

 ?>

<script>
    let nameTeamA = <?php echo json_encode($teamA); ?>;
    let nameTeamB = <?php echo json_encode($teamB); ?>;
    
   /* if (nameTeamA != 'Team A') {
      document.getElementById('clubA').value = nameTeamA;
    }
    
    if (nameTeamB != 'Team B') {
    document.getElementById('clubB').value = nameTeamB;
    }
*/
  </script>

<?php 

echo '<div id="startingTeam">
    <input type="radio" checked id="Team_A" name="startteam" value="Team A">
    <label for="Team_A">' . $teamA . '</label>
    <input type="radio" id="Team_B" name="startteam" value="Team B">
    <label for="Team_B">'. $teamB .'</label>

</div>
</div>
</div>


<div class="main_container_penalty"> 

<div class="container_playerA">
 <div id="flagA">';

if ($_COOKIE['CountryA'] || ($_POST['countryA'] != 'Selecteer land:') ) {
    echo '
    <img src= ' . $selca . '>';
}

if ($_COOKIE['clubA'] || $_POST['clubA']) {
     echo '<div>' . $teamA . '</div>';
}

echo '
</div>       
    <div id="playerA">';

     /* for ($i=0; $i < 5; $i++) {
       echo 
       '<div class="right" style="background-color: red;"></div>
       <div class="left" style="background-color: green;"></div>';
        }
        echo '
        </div>
        </div>';
*/
echo 
'</div>
</div>

<div class="container_playerB">
<div id="flagB">';

 if ($_COOKIE['CountryB'] || ($_POST['countryB'] != 'Selecteer land:') ) {
    echo '
    <img src= ' . $selcb . '>';
}

if ($_COOKIE['clubB'] || $_POST['clubB']) {
     echo '<div>' . $teamB . '</div>';
}

 echo'
 </div>
  <div id="playerB">';

  /*
 for ($i=0; $i < 5; $i++) {
       echo 
       '<div class="right" style="background-color: red;"></div>
       <div class="left" style="background-color: green;"></div>';
        };
*/
echo 
'</div>
</div>

</div>
</div>
   
<div id="winner"> </div>';


?>


</body>

</html>