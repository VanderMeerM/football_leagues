<?php 

$status_short = array('1H', 'HT', '2H', 'ET');

$status_nl = array('1e helft', 'Rust', '2e helft', 'Verlenging');

$status = array_combine($status_short, $status_nl);

$statusInPlay = array('1H', 'HT', '2H','ET', 'BT', 'P', 'SUSP', 'INT');


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


