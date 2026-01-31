<?php 

$status_live = array('1H', 'HT', '2H', 'ET', 'P', 'SUSP', 'INT');

$statusInPlay = array('1H', 'HT', '2H','ET', 'P', 'SUSP', 'INT', 'PEN', 'PST', 'ABD');

$status_nl = array('1e helft', 'Rust', '2e helft', 'Verlenging', 'Penalties', 'Stilgelegd', 
'Tijd. stilgelegd', 'Afgelopen NS', 'Uitgesteld', 'Afgelast');

$status = array_combine($statusInPlay, $status_nl);


$array_type = array('Goal' => 'football.png', 'Yellow Card' => 'yellow_card.png', 
'Red Card' => 'red_card.png', 'subst' => 'substitute.png');

$array_goal = array(
'Own Goal' => 'eigen goal', 
'Penalty' => 'strafschop', 
'Missed Penalty' => 'strafschop gemist');

$array_comments = array(
  'Foul' => 'overtreding', 
  'Argument' => 'commentaar',
  'Time wasting' => 'tijd rekken',
  'Handball' => 'hands',
  'Unallowed field entering' => 'geen toestemming betreden veld',
  'Holding' => 'vasthouden',
  'Penalty Shootout' => 'penaltyserie',
  'Unsportsmanlike conduct' => 'onsportief gedrag'
);

$array_standings = array(
  //Eredivisie 
  'Promotion - Champions League (League phase: )' => 'Champions League',
  'Promotion - Champions League (Qualification: )'=> 'Voorronde Champions League',
  'Promotion - Europa League (League phase: )' => 'Europa League',
  'Promotion - Europa League (Qualification: )' => 'Voorronde Europa League',
  'Promotion - Eredivisie (Conference League - Play Offs: )' => 'Play offs voor Conference League',

  // Jupiler League
  'Eredivisie (Relegation - Play Offs: )' => 'Promotie/degradatie',
  'Relegation - Eerste Divisie' => 'Gedegradeerd naar de Jupiler League', 
  'Promotion' => 'Gepromoveerd', 
  'Promotion Play-off' => 'Nacompetitie voor promotie',
  'Possible Promotion Play-off' => 'Nacompetitie (voorrondes)', 

  // 1. Bundesliga 
  'Promotion - Champions League (Group Stage: )' => 'Champions League',
  'Promotion - Europa League (Group Stage: )' => 'Europa League',
  'Promotion - Europa Conference League (Qualification: )' => 'Voorronde Conference League',
  'Bundesliga (Relegation)' => 'Relegation',
  'Relegation - 2. Bundesliga' => 'Gedegradeerd naar 2. Bundesliga',

  // 2. Bundesliga 
  'Promotion - Bundesliga' => 'Gepromoveerd naar 1. Bundesliga',
  'Promotion - Bundesliga (Relegation: )' => 'Relegation', 
  '2. Bundesliga (Relegation)' => 'Relegation',
  'Relegation - 3. Liga' => 'Gedegradeerd naar 3. Liga'

);

$array_rounds = array(
'1st Qualifying Round' => '1e kwalificatieronde',
'2nd Qualifying Round' => '2e kwalificatieronde',
'3rd Qualifying Round' => '3e kwalificatieronde',
'Play-offs' => 'play-offs',
'League Stage - 1' => 'ronde 1',
'League Stage - 2' => 'ronde 2',
'League Stage - 3' => 'ronde 3',
'League Stage - 4' => 'ronde 4',
'League Stage - 5' => 'ronde 5',
'League Stage - 6' => 'ronde 6',
'League Stage - 7' => 'ronde 7',
'League Stage - 8' => 'ronde 8', 
'Round of 16' => 'achtste finales',
'Quarter-finals' => 'kwartfinale',
'Semi-finals' => 'halve finale',
'Final' => 'finale'

);

$array_position_player {
  'Midfielder' => 'Middenvelder'
}

$array_countries {
  'Netherlands' => 'Nederland',
  'Spain' => 'Spanje'
}
