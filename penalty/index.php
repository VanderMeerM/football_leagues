<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./penaltyboard.css" rel="stylesheet" type="text/css" />
    <script defer src="./penaltyboard.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  

    <title>Penalties schieten</title>
</head>

<?php 

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

//print_r($response[0]['translations']['nl']);

$array_countries_nl = [];

for ($i=0; $i < sizeof($response); $i++) {

  array_push($array_countries_nl, $response[$i]['translations']['nl']);

  }

sort($array_countries_nl);

//print_r($array_countries_nl);


echo '

<body class="text-center">

    <h2>Selecteer land of voer (club)naam in </h2>

    <div class="float-lg-left">
       <strong> Team A </strong> 
       
       <p>

    <select id="countryA">
        <option selected >Selecteer land:</option>';


        for ($i=0; $i < sizeof($array_countries_nl); $i++) {
            echo '<option>'. $array_countries_nl[$i] .'</option>';
        }

echo '
</select>
   <p></p>
    <input placeholder="Naam club" id="clubA">
    <p>

</div>

<div class="float-lg-right">
       <strong> Team B </strong> 
       
       <p>

    <select id="countryB">
        <option selected>Selecteer land:</option>';

       
        for ($i=0; $i < sizeof($array_countries_nl); $i++) {
            echo '<option>'. $array_countries_nl[$i] .'</option>';
        }

echo '
</select>
<p></p>
<input placeholder="Naam club" id="clubB">

<p></p>

</div> 


<div style="display:block">

    Welk team start? 

    <p></p>
 
<div id="startingTeam">
    <input type="radio" checked id="Team_A" name="startteam" value="Team A">
    <label for="Team_A">Team A</label>
    <input type="radio" id="Team_B" name="startteam" value="Team B">
    <label for="Team_B">Team B</label>

</div>

</div>

</div>


<div id="playerA_name">
    <div id="flagA"></div>
        <div id="playerA"></div>
</div>

<br>

<div id="playerB_name">
    <div id="flagB"></div>
        <div id="playerB"></div>
</div>
   
<div id="winner"> </div>';

?>


</body>

</html>