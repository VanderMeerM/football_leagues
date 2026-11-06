
<?php 

//echo $_POST['Inp_teamA'] . '/' . $_POST['Inp_teamB'];
// print_r($array_clel);

$NL_league = $array_leagues_countries['Netherlands'];

$all_matches = [];
$found_matches = [];

for ($i=0; $i < sizeof($NL_league); $i++) { 
  // $cur_url = 'https://v3.football.api-sports.io/fixtures?&league='. $array_clel[$i] . '&season='. $selected_season;
      $cur_url = 'https://v3.football.api-sports.io/fixtures?&league='. $NL_league[$i] . '&season=2025';


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $cur_url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'x-rapidapi-key: ' . $api_key .'',
    'x-rapidapi-host: v3.football.api-sports.io',
    
  ),
));

$response = curl_exec($curl);

$response = json_decode($response, true);

array_push($all_matches, $response);
}

$all_matches_values = array_values($all_matches);

//echo $all_matches_values[0]['response'][1]['teams']['home']['name'];

$ind = 0; 

while ($ind < sizeof($NL_league)) {

    for ($l=0; $l < (sizeof($all_matches_values[0]['response'])); $l++) {

    $home_team = $all_matches_values[$ind]['response'][$l]['teams']['home']['id'];
    $away_team = $all_matches_values[$ind]['response'][$l]['teams']['away']['id'];

      
    if ( ( ($home_team == $_POST['Inp_teamA'] ) && ($away_team == $_POST['Inp_teamB']) ) ||
         ( ($home_team == $_POST['Inp_teamB']) && ($away_team == $_POST['Inp_teamA']) ) 
        ) 
    {
      array_push($found_matches, $all_matches_values[$ind]['response'][$l]);
    } 
       
}

$ind++;

};
//echo ($all_matches_values[0]['response'][0]['teams']['home']['id']);

if ($found_matches == 0) {
    echo 'Geen wedstrijden gevonden';
} else {

echo 

'<div class="background_match">
<div class="container_league_logo_name">
<a id="space_cont_league_logo" href="#">
<img id="league_logo" src = ' . $found_matches [$i]['league']['logo'] . ' id="img_logo_day"">' 
. $league_name . ' ('.$round . (array_key_exists($selectedround, $array_rounds) ? $array_rounds[$selectedround] : $selectedround) .')</a></div>

<div class="main_container">';

for ($i=0; $i < sizeof($found_matches); $i++) {

echo '<a style="background-color: black;" 
 href="../league?id=' . $matchId . '&datum='. date('d-m-Y', $found_matches[$i]['fixture']['timestamp'])  .'">';

echo '<div class="country_container">';

// <div class="flag_container' . (date('d-m-Y') === date('d-m-Y', $_POST['sel_day']) ? ' black_color' : ' white_color') .'">
 echo '<div class="flag_container white_color">

  <img src="'.$found_matches[$i]['teams']['home']['logo'] . '"/>
  <p>
 naam team 
  </div> 
  </div>';

   //<div class="stscore_container' . (date('d-m-Y') === date('d-m-Y', $_POST['sel_day']) ? ' black_color' : ' white_color') .'">'; 
   echo '<div class="stscore_container white_color">'; 

        if (!array_key_exists($matchStatus, $status)) {
   
       echo '<div'. ($matchStatus === 'PEN' ? ' id="rem_text_when_pen"' : null) .'>'
          . $found_matches[$i]['fixture']['venue']['city']; 

          echo '</div>';
         
                  
         echo '<div'. ($matchStatus === 'PEN' ? ' id="rem_text_when_pen"' : null) .'>' 

        . date('d-m-Y H:i', $found_matches[$i]['fixture']['timestamp']) . '</div>'; 
        }

        
         // Bij live wedstrijden elke minuut pagina herladen om status te checken..

         if (array_key_exists($matchStatus, $status)) {
          ?>
          <script>
      setTimeout(() => {
       window.location.href = window.location;
    }, 60 * 1000);
          </script>
            <?php
         }
          
         if (array_key_exists($matchStatus, $status_cancel)) {
          echo '<div class="font_status_match red">' . $status_cancel[$matchStatus] . '<br>';
         }
         else {
          echo
         '<div '. (array_key_exists($matchStatus, $status)? 'class="font_status_match red">
         '. ($matchStatus === 'P' ? null : $elapsed . '"') . 
         '<br>'.$status[$matchStatus] : 'class="black_color"') . 
         '<br>';
         }

          if ($matchStatus === 'PEN') {
            if ($found_matches[$i]['teams']['home']['winner'] == 1) { 
                echo '<div class= 
                ' .(date('d-m-Y') === date('d-m-Y', $_POST['sel_day']) ? "black_color" : "white_color") .'>
                ' . (array_key_exists($homeTeam, $array_countries) ? $array_countries[$homeTeam] : $homeTeam). '
                w.n.s. <br>'; 
                }
                elseif ($found_matches[$i]['teams']['away']['winner'] == 1) { 
                   echo '<div class=
                  ' .(date('d-m-Y') === date('d-m-Y', $_POST['sel_day']) ? "black_color" : "white_color") .'>
                  ' . (array_key_exists($awayTeam, $array_countries) ? $array_countries[$awayTeam] : $awayTeam). '
                   w.n.s.<br>'; 
                }
                 echo '(' . $found_matches[$i]['score']['penalty']['home']. ' - 
                ' .$found_matches[$i]['score']['penalty']['away'] . ')</div>';
          }
             
         echo '
         <div class="score" ' . (!array_key_exists($matchStatus, $status)? 'style="padding-top: 10%"' :null) . '>' .
        '<div class="score_home ' . (!is_null($found_matches[$i]['goals']['home']) ? 'pd_score' : null) . '">' . $found_matches[$i]['goals']['home'] . '</div>' . 
        
        '<div class="vs '. (date('d-m-Y') === date('d-m-Y', $_POST['sel_day']) ? 'black_color' : 'white_color') . '"> - ' . '</div>' .   
        
         '<div class="score_away '. (!is_null($found_matches[$i]['goals']['away']) ? 'pd_score' : null) . '">'. $found_matches[$i]['goals']['away'] . '</div>
          
        </div>
        </div>';

       if ($_GET['id']) { 

            echo '<p><div class="stscore_ref">
            <img id="ref" src="../img/ref.png">' . '<br> ' . explode(',', $found_matches[$i]['fixture']['referee'])[0] . 
           '<br>'; 
          
          
           // Wedstrijd opslaan nadat deze een dag in het verleden ligt..

          if ( (!file_exists($json_fixture)) &&
            (date('d-m-Y', $found_matches[$i]['fixture']['timestamp'])) < 
              date('d-m-Y', strtotime('today')) &&
                ($found_matches[$i]['fixture']['status']['short'] === 'FT')  )

          {
    
           $json_file_fixture = fopen($json_fixture, "w");
   
           fwrite($json_file_fixture, json_encode($response));
   
           fclose($json_file_fixture);
                
           }            

      }
        echo '</div>';

        if ($_GET['id']) {
          echo '</div>';
        }
         
   echo '<div class="country_container">';
 //<div class="flag_container' . (date('d-m-Y') === date('d-m-Y', $_POST['sel_day']) ? ' black_color' : ' white_color') .'">
echo '<div class="flag_container white_color">

   <img src="'. $found_matches[$i]['teams']['away']['logo'] . '"/>
   <p> naam team B
  ' . (array_key_exists($awayTeam, $array_countries) ? $array_countries[$awayTeam] : $awayTeam). '
   </div>'; 

 
   echo 
   '</div>
   </a>';
}

echo '</div>
   </div>';
};

   


