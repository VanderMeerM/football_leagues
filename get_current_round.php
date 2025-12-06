
<?php 

// Data van elke ronde in array plaatsen: 

$array_dates_round = []; 

$array_rounds_International_leagues = [];

$round_determination = [];


if ($numGames > 0 ) {

for ($i = 0; $i < $numGames; $i++) {

$each_round = intval(explode(' ', $response['response'][$i]['league']['round'])[3]);

$array_dates_round[$each_round] .= $response["response"][$i]["fixture"]["timestamp"] . ',';

$each_round_int_leagues = $response['response'][$i]['league']['round'];

$array_rounds_International_leagues[$each_round_int_leagues] .= $response["response"][$i]["fixture"]["timestamp"] . ',';

  }

  for ($i=1; $i < sizeof($array_dates_round); $i++) {
    substr($array_dates_round[$i], 0, -1);
  }

   for ($i=1; $i < sizeof($array_rounds_International_leagues); $i++) {
    substr($array_rounds_International_leagues[$i], 0, -1);
  }
  
  
 // echo rtrim(end($array_dates_round[1]));

/*
$array_dates_round = rtrim(implode($array_dates_round), ",");
$array_dates_round = explode(',', $array_dates_round);
*/

}

$startdate_selected_round = explode(',', $array_dates_round[2])[0] . '<br>';
$lastdate_selected_round = intval(sizeof(explode(',', $array_dates_round[2])) - 2);

$lastdate_selected_round_int_leagues = intval(sizeof(explode(',', $array_rounds_International_leagues[2])) - 2);


for ($i=1; $i < sizeof($array_dates_round); $i++) {
  
  $num_dates = intval(sizeof(explode(',', $array_dates_round[$i])));
 
  if   
     (date('Y-m-d', explode(',', $array_dates_round[$i])[$num_dates-2] + (3600 * 24)) >= date('Y-m-d', strtotime('Today'))) 
       
     
    { 
     array_push($round_determination, ($i . '-' . explode(',', $array_dates_round[$i])[$num_dates-2]));
   }
   }

   asort($round_determination);

   print_r($round_determination);
  
    $array_of_round_of_first_upcoming_matches = $round_determination[0];
    
    $round_of_first_upcoming_matches = explode('-', $array_of_round_of_first_upcoming_matches)[0];


     ?>