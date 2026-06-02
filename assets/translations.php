<?php 


$status_live = array('1H', 'HT', '2H', 'ET', 'P', 'SUSP', 'INT');

$status_nl = array('1e helft', 'Rust', '2e helft', 'Verlenging', 'Penalties', 'Stilgelegd', 
'Tijd. stilgelegd');

$status_cancel_abbrev = array('PST', 'ABD'); 

$status_cancel_nl = array('Uitgesteld', 'Afgelast');

$status = array_combine($status_live, $status_nl);

$status_cancel = array_combine($status_cancel_abbrev, $status_cancel_nl);


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
false => 'playoffs',
'League Stage - 1' => 'ronde 1',
'League Stage - 2' => 'ronde 2',
'League Stage - 3' => 'ronde 3',
'League Stage - 4' => 'ronde 4',
'League Stage - 5' => 'ronde 5',
'League Stage - 6' => 'ronde 6',
'League Stage - 7' => 'ronde 7',
'League Stage - 8' => 'ronde 8',
'Group A - 1' => 'groep A - 1',
'Group B - 1' => 'groep B - 1',
'Group C - 1' => 'groep C - 1',
'Group D - 1' => 'groep D - 1',
'Group E - 1' => 'groep E - 1',
'Group F - 1' => 'groep F - 1',
'Group A - 2' => 'groep A - 2',
'Group B - 2' => 'groep B - 2',
'Group C - 2' => 'groep C - 2',
'Group D - 2' => 'groep D - 2',
'Group E - 2' => 'groep E - 2',
'Group F - 2' => 'groep F - 2',
'Group A - 3' => 'groep A - 3',
'Group B - 3' => 'groep B - 3',
'Group C - 3' => 'groep C - 3',
'Group D - 3' => 'groep D - 3',
'Group E - 3' => 'groep E - 3',
'Group F - 3' => 'groep F - 3',
'Group Stage - 1' => 'groepsfase 1',
'Group Stage - 2' => 'groepsfase 2',
'Group Stage - 3' => 'groepsfase 3', 
'3rd Place Final' => 'Finale 3e plaats',
'Knockout Round Play-offs' => 'Tussenronde',
'Round of 32' => 'zestiende finales',     
'Round of 16' => 'achtste finales',
'Quarter-finals' => 'kwartfinale',
'Semi-finals' => 'halve finale',
'Final' => 'finale'

);

$array_position = array(
'Goalkeeper' => 'Keeper',
'Defender' => 'Verdediger',
'Midfielder' => 'Middenvelder',
'Attacker' => 'Aanvaller'
);

$array_countries = array(
   // Europese landen..
  'Netherlands' => 'Nederland',
  'Belgium' => 'België',
  'Germany' => 'Duitsland',
  'Switzerland' => 'Zwitserland',
  'Austria' => 'Oostenrijk', 
  'France' => 'Frankrijk',
  'Italy' => 'Italië',
  'Spain' => 'Spanje',
  'Norway' => 'Noorwegen',
  'Sweden' => 'Zweden',
  'Denmark' => 'Denemarken',
  'England' => 'Engeland',
  'Scotland' => 'Schotland',
  'Ireland' => 'Ierland',
  'Northern Ireland' => 'Noord-Ierland',
  'Iceland' => 'IJsland',
  'Poland' => 'Polen',
  'Hungary' => 'Hongarije', 
  'Slovakia' => 'Slowakije',
  'Czechia' => 'Tsjechië',
  'Czech Republic' => 'Tsjechië',
  'Bosnia & Herzegovina' => 'Bosnië en Herzegovina',
  'Türkiye' => 'Turkije',
  'Croatia' => 'Kroatië',

  // Afrikaanse landen.. 
  'Morocco' => 'Marokko',
  'South Africa' => 'Zuid-Afrika',
  'Ivory Coast' => 'Ivoorkust',
  'Tunisia' => 'Tunesië',
  'Cape Verde Islands' => 'Kaapverdische Eilanden',
  'Egypt' => 'Egypte',
  'Algeria' => 'Algerije',
  'Congo DR' => 'DR Congo',

  // Amerikaanse landen..
  'USA' => 'Verenigde Staten', 
  'Haiti' => 'Haïti',
  'Brazil' => 'Brazilië', 
  'Argentina' => 'Argentinië',
  
  // Aziatische landen..
  'Australia' => 'Australië',
  'New Zealand' => 'Nieuw-Zeeland',
  'South Korea' => 'Zuid-Korea', 
  'Saudi Arabia' => 'Saoedie-Arabië',
  'Iraq' => 'Irak',
  'Jordan' => 'Jordanië',
  'Uzbekistan' => 'Oezbekistan',
   
  // Clubs.. 
  'Waalwijk' => 'RKC'

);

