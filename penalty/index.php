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

/* Wishlist:

- check op zelfde land of club (alert) - en vlag niet invullen 

- stippen in overflow

- geen winner-melding als team B begint
   - en met ingang 5e ronde Team B een goal voor staat (bijv. 2-3) en de vijfde raak schiet 
   - bij om-en-om (v.a. ronde 6 en Team A wint) 
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
</div>

</ul>
</div>
</div>
</div>";

if ($_POST['reload'] === 'yes') {
   $_POST['countryA'] === 'Selecteer land:';
   $_POST['countryB'] === 'Selecteer land:';
};

if ($_POST['countryA'] != 'Selecteer land:') {   
setcookie("CountryA", $_POST['countryA'], time() + 3600, "/", '', true);
setcookie("clubA", '', time() + 3600, "/", '', true); 

}

if ($_POST['countryB'] != 'Selecteer land:') {
setcookie("CountryB", $_POST['countryB'], time() + 3600, "/", '', true);
setcookie("clubB", '', time() + 3600, "/", '', true); 

}

if ($_POST['clubA']) { 
 setcookie("clubA", $_POST['clubA'], time() + 3600, "/", '', true); 
 setcookie("CountryA", '', time() + 3600, "/", '', true);
}

if ($_POST['clubB']) { 
 setcookie("clubB", $_POST['clubB'], time() + 3600, "/", '', true); 
 setcookie("CountryB", '', time() + 3600, "/", '', true);

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

// Landen naar het Nederlands vertalen en in alfabetische volgorde zetten...

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

<div class="container_countries_teams_startingteam">

<div class="main_container_countries_teams">

 <div class="container_team_A">
       <strong><u>Team A</u></strong>  
<div>

<form method="post" action="">

    <select name="countryA" id="countryA" onchange="this.form.submit()">
        <option selected >Selecteer land:</option>';
        
        if ($_POST['countryA'] ) {
            $selca = $_POST['countryA']; 
            }
            elseif ( ($_COOKIE['CountryA']) && ($_COOKIE['CountryA'] != '') ) {
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

   <h4> Welk team begint? </h4>'; 

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
  } 
  elseif ($_COOKIE['clubB']) {
    $teamB = $_COOKIE['clubB'];
  }

 ?>

<script>
    let nameTeamA = <?php echo json_encode($teamA); ?>;
    let nameTeamB = <?php echo json_encode($teamB); ?>;
    
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
</div>

<div id="winner"> </div>

<div class="main_container_penalty"> 

<div class="container_reload"">

<form action="./" method="post">

<input type="hidden" name="reload" value="yes">

<input type="image" src="../img/refresh.png" name="submit" id="btn_restart">

</form>

</div>


<div id="round"></div>

<div class="main_container_player">

<div class="container_playerA">

 <div id="flagA">';

if ( ($_COOKIE['CountryA'] !='') || ($_POST['countryA'] != 'Selecteer land:') ) {
    echo '
    <div id="setFlagA">
    <img src= ' . $selca . '>
    </div>';
}

if ($_POST['clubA']) {
     echo '
     <div id="setClubA">
     <div>' . $teamA . '</div>
     </div>';
     ?>
     <script>
     const setFlagA = document.getElementById('setFlagA');

     if (setFlagA) {
     setFlagA.removeChild(setFlagA.firstElementChild);
    }

     document.getElementById('flagB').innerHTML = `<div id="setClubB"> ${nameTeamB}</div>`;
    
   </script>
   
   <?php
    }

echo '
</div>       

<div id="playerA">
</div>
</div>

<div id="scoreA">0
</div>

</div>

<div class="main_container_player"> 

<div class="container_playerB">

<div id="flagB">';

if ( ($_COOKIE['CountryB'] !='') || ($_POST['countryB'] != 'Selecteer land:') ) {
    echo '
    <div id="setFlagB">
    <img src= ' . $selcb . '>
    </div>';
}

if ($_POST['clubB']) {
     echo '
     <div id="setClubB">
     <div>' . $teamB . '</div>
     </div>';
     ?>
     <script>
     const setFlagB = document.getElementById('setFlagB');
     if (setFlagB) {
     setFlagB.removeChild(setFlagB.firstElementChild);
    }
     document.getElementById('flagA').innerHTML = `<div id="setClubA"> ${nameTeamA}</div>`;
  
     </script>
   
   <?php
    }

echo'
</div>

<div id="playerB">
</div>
</div>

<div id="scoreB">0
</div>

</div>

</div>
</div>';

?>

</body>

</html>