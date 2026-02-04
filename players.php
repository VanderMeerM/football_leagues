
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    


<?php

require('./api.php');

include('./variables.php');

include('./translations.php');

include('./close_window.php');


$IntlDateFormatter = new IntlDateFormatter(
  'nl_NL',
  IntlDateFormatter::LONG,
  IntlDateFormatter::NONE

);


// Profiel speler ophalen... 

    $curl_player = curl_init();
    
    curl_setopt_array($curl_player, array(
      CURLOPT_URL => 'https://v3.football.api-sports.io/players/profiles?player=' . $_GET['id']. '', 
     
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
         //'x-rapidapi-key: ' . $api_key .'',
     'x-rapidapi-key: 863bcd048478f98225b64bced629b376',
        'x-rapidapi-host: v3.football.api-sports.io',
        
      ),
    ));
    
    $response_player = curl_exec($curl_player);
    
    curl_close($curl_player);

    $response_player = json_decode($response_player, true);

    ?>
    <script>
     document.title= <?php echo json_encode($response_player['response'][0]['player']['name']); ?>;
</script>

<?php

// Teams van speler ophalen...

 $curl_player_teams = curl_init();
    
    curl_setopt_array($curl_player_teams, array(
      CURLOPT_URL => 'https://v3.football.api-sports.io/players/teams?player=' . $_GET['id']. '', 
     
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
         //'x-rapidapi-key: ' . $api_key .'',
     'x-rapidapi-key: 863bcd048478f98225b64bced629b376',
        'x-rapidapi-host: v3.football.api-sports.io',
        
      ),
    ));
    
  $response_player_teams = curl_exec($curl_player_teams);
    
    curl_close($curl_player_teams);

$response_player_teams = json_decode($response_player_teams, true);
 
?>

<style>

table {
    margin: 80px auto;
    width: 30%;
    border-collapse: collapse;
}

table tr td img {
    display: block;
    margin: 0 auto;
}

td {
    width: 50%;
    padding: 2%;
}


</style>

<?php 

echo '<br> 

<table>

<tr>
<td colspan="2">
<img src='. $response_player['response'][0]['player']['photo'] .'>
</td>
</tr>
<tr>
<td valign="top">Naam: </td> 
<td> ' . $response_player['response'][0]['player']['firstname'] . ' ' . $response_player['response'][0]['player']['lastname'] .

'</td></tr>

<tr><td valign="top">
Geboren op: </td>
<td> ' . $IntlDateFormatter->format(strtotime($response_player['response'][0]['player']['birth']['date'])) . 
' (' . $response_player['response'][0]['player']['age'] .' jaar) 
<br>te ' .$response_player['response'][0]['player']['birth']['place'] . ' (' 
.   (array_key_exists($response_player['response'][0]['player']['birth']['country'], $array_countries) ?  
         $array_countries[$response_player['response'][0]['player']['birth']['country']] : 
         $response_player['response'][0]['player']['birth']['country']) .')</td></tr> 

<tr><td>
Nationaliteit: </td>
<td> ' . (array_key_exists($response_player['response'][0]['player']['nationality'], $array_countries) ?  
         $array_countries[$response_player['response'][0]['player']['nationality']] : 
         $response_player['response'][0]['player']['nationality']) . '
         </td></tr> 
         </td></tr>

<tr><td>
Positie: </td><td> ' 
        . (array_key_exists($response_player['response'][0]['player']['position'], $array_position) ?  
         $array_position[$response_player['response'][0]['player']['position']] : 
         $response_player['response'][0]['player']['position']) . 
         '</td></tr>';  



$array_teams = array_reverse($response_player_teams['response']); 


echo '<td colspan="2"><strong>Clubs</strong></td>';

for ($i=0; $i < sizeof($array_teams); $i++) {

echo 
'<tr><td valign="top">' . $array_teams[$i]['team']['name'] . '</td>
<td>'; 

$counter = 0; 

foreach (array_reverse($array_teams[$i]['seasons']) as $seasons) {
    echo 
    $seasons . ($counter < (count($array_teams[$i]['seasons']) - 1) ? ', ' : null);
    $counter++;  

}}

echo 
'</td></tr>
</table>';


 