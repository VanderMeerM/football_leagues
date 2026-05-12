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


<body class="text-center">

<?php 

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.apicountries.com/countries");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
curl_close($ch);
//echo $output;
?>

    <h2>Selecteer land of voer (club)naam in </h2>

    <div class="float-lg-left">
       <strong> Team A </strong> 
       
       <p>

    <select id="countryA">
            <option selected >Selecteer land:</option>
    </select>
   <p></p>
    <input placeholder='Naam club' id='clubA'>

    <p>

</div> 

<div class="float-lg-right">
       <strong> Team B </strong> 
       
       <p>

    <select id="countryB">
        <option selected>Selecteer land:</option>
</select>
<p></p>
<input placeholder='Naam club' id='clubB'>

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
   
<div id="winner"> </div>

</body>

</html>