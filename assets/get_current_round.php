
<?php 

// Data van elke ronde in array plaatsen: 

$array_dates_round = []; 

$array_dates_intern_leagues = [];

$round_determination = [];

$round_determination_int =[];


if ($numGames > 0 ) {

for ($i = 0; $i < $numGames; $i++) {

$each_round = intval(explode(' ', $response['response'][$i]['league']['round'])[3]);

$array_dates_round[$each_round] .= $response["response"][$i]["fixture"]["timestamp"] . ',';

$each_round_int_leagues = $response['response'][$i]['league']['round'];

$array_dates_intern_leagues[$each_round_int_leagues] .= $response["response"][$i]["fixture"]["timestamp"] . ',';

}


// Keys (rondes) extra in juiste volgorde plaatsen vanwege andere volgorde bij live wedstrijden  
ksort($array_dates_round); 
//ksort($array_dates_intern_leagues);

  for ($i=1; $i < sizeof($array_dates_round); $i++) {
    substr($array_dates_round[$i], 0, -1);
  }

   for ($i=1; $i < sizeof($array_dates_intern_leagues); $i++) {
    substr($array_dates_intern_leagues[$i], 0, -1);
  }
  
  
// Wedstrijden in ronde op volgorde zetten  
$array_dates_round_values = array_values($array_dates_round); 
$array_dates_intern_leagues_values = array_values($array_dates_intern_leagues);

for ($i=0; $i < sizeof($array_dates_round_values); $i++) {
  $array_dates_round_sorted[] = explode(',', $array_dates_round_values[$i]);
  asort($array_dates_round_sorted);
  array_pop($array_dates_round_sorted[$i]);
  asort($array_dates_round_sorted[$i]); // Zet waarden in volgorde om in rondeselectie de eerste en laatste speeldag te tonen. 
}


for ($i=0; $i < sizeof($array_dates_intern_leagues_values); $i++) {
  $array_dates_int_round_sorted[] = explode(',', $array_dates_intern_leagues_values[$i]);
  asort($array_dates_int_round_sorted);
  array_pop($array_dates_int_round_sorted[$i]);
  asort($array_dates_int_round_sorted[$i]); // Zet waarden in volgorde om in rondeselectie de eerste en laatste speeldag te tonen. 
}

//echo date('d-m', $array_dates_round_sorted[0][11]);

 // echo rtrim(end($array_dates_round[1]));

/*
$array_dates_round = rtrim(implode($array_dates_round), ",");
$array_dates_round = explode(',', $array_dates_round);
*/

}

//$startdate_selected_round = $array_dates_round_sorted[0][0]; //explode(',', $array_dates_round[2])[0] . '<br>';
//$lastdate_selected_round = intval(sizeof(explode(',', $array_dates_round[2])) - 2);

$lastdate_selected_round_int_leagues = intval(sizeof(explode(',', $array_rounds_International_leagues[2])) - 2);

//Indien reguliere competitie, doorloop dan volgende loop om eerstvolgende ronde te bepalen..

if (in_array($league_id, $array_reg_leagues)) {

for ($i=1; $i < sizeof($array_dates_round_sorted); $i++) {
  
 $num_dates = intval(sizeof($array_dates_round_sorted[$i]));
  
 $days_between = date_diff(
  date_create(date('Y-m-d', $array_dates_round_sorted[$i][0])),
  date_create(date('Y-m-d', $array_dates_round_sorted[$i][$num_dates-1]))
 )->format("%a");

 if ($days_between > 2) {
  $dif = 3; 
 }
 else {
  $diff = $days_between + 1;
 }

  if   
     (date('Y-m-d', ($array_dates_round_sorted[$i][0] + ($diff * (3600 * 24)))) >= date('Y-m-d', strtotime('Today'))) 
     
    { 
     array_push($round_determination, ($i+1 . '-' . $array_dates_round_sorted[$i][$num_dates-1]));
   }
   }
}

   // Indien internationale competitie, doorloop dan deze loop om ronde te bepalen... 

  elseif (in_array($league_id, $array_intern_leagues)) {

  $array_keys_int_leagues = array_keys($array_dates_intern_leagues);

  for ($i=0; $i < sizeof($array_keys_int_leagues); $i++) {

   if (date('Y-m-d', explode(',', $array_dates_intern_leagues[$array_keys_int_leagues[$i]])[0]) >= date('Y-m-d', strtotime('Today'))) 
   
   {
    array_push($round_determination_int, ($array_keys_int_leagues[$i] . ':' . explode(',', $array_dates_intern_leagues[$array_keys_int_leagues[$i]])[0]));
   }
  }
 $round_of_first_upcoming_matches_int = explode(':', $round_determination_int[0])[0];

  }

  $array_of_round_of_first_upcoming_matches = $round_determination[0]; 

    // Afhankelijk van soort competitie key waarde nemen als eerstvolgende ronde  
      in_array($league_id, $array_intern_leagues) ? $round_of_first_upcoming_matches = $round_of_first_upcoming_matches_int : 
       $round_of_first_upcoming_matches = explode('-', $array_of_round_of_first_upcoming_matches)[0];
      

     ?>