
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    


<?php

require('./assets/api.php');

include('./assets/variables.php');

include('./assets/translations.php');

include('./assets/close_window.php');


// Info team ophalen... 

    $curl_team = curl_init();
    
    curl_setopt_array($curl_team, array(
      CURLOPT_URL => 'https://v3.football.api-sports.io/teams?id=' . $_GET['id']. '', 
     
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
    
    $response_team = curl_exec($curl_team);
    
    curl_close($curl_team);

    $response_team = json_decode($response_team, true);

    ?>
    <script>
     document.title= <?php echo json_encode($response_team['response'][0]['team']['name']); ?>;
</script>

<style>

table {
    margin: 80px auto;
    width: 30%;
    border-collapse: collapse;
}

table tr td img {
    display: block;
    margin: 0 auto;
    max-width: 400px;
}

td {
    width: 50%;
    padding: 2%;
}

@media (max-width: 400px) {

table tr td img {
    max-width: 300px;
}
}
</style>

<?php 

echo '<br> 

<table>

<tr>
<td colspan="2">
<img src='. $response_team['response'][0]['team']['logo'] .'>
</td>
</tr>
<tr>
<td valign="top">Club: </td> 
<td> ' . $response_team['response'][0]['team']['name'] . '</td></tr>

<tr><td valign="top">
Land: </td>
<td> ' . (array_key_exists($response_team['response'][0]['team']['country'], $array_countries) ?  
         $array_countries[$response_team['response'][0]['team']['country']] : 
         $response_team['response'][0]['team']['country']) . '</td></tr> 

<tr><td>
Opgericht: </td>
<td>' . $response_team['response'][0]['team']['founded'] . '
</td></tr> 

<tr><td valign="top">
Stadion: </td>
<td>' . $response_team['response'][0]['venue']['name'] . ' te ' . $response_team['response'][0]['venue']['city'] .  
'</td></tr>  

<tr><td valign="top">
Capaciteit: </td>
<td>' . $response_team['response'][0]['venue']['capacity'] .  
'</td></tr>

<tr>
<td colspan="2">
<img src='. $response_team['response'][0]['venue']['image'] .'>
</td>
</tr>

</table>';


  