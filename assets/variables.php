
<?php
 
(date('m') < 7 ? $current_season = (date('Y') - 1) : $current_season = date('Y')); 

$_GET['league'] ? $league_id = $_GET['league'] : $league_id = 88; 

$_GET['season'] ? $selected_season = $_GET['season'] : $selected_season = $current_season; 

$backgr_today_match = '#e4cd84';


$allrounds = [];

// Nodig om in team alleen in competities van betreffende land te kijken..

$array_leagues_countries = 
[
  'Netherlands' => [88, 89, 90],  // 90 KNVB beker,
  'Germany' => [78,79, 81], // 81 DFB Pokal
  'Italy' => [135, 136, 137], // 137 Coppa Italia 
  'Spain' => [140, 141, 143], 
  'England' => [39, 40, 45], // 45 FA Cup,
  'Scotland' => [179],
  'Northern Ireland' => [408],
  'France' => [61],
  'Belgium' => [144]
  
];


// In league (reguliere competities tonen)..
$array_reg_leagues = [78,79, 88, 89, 135, 140, 39, 40, 179, 408, 61, 144];  

// In league om wel/niet menu voor stand te tonen..
$array_cup_leagues = [81, 90, 137, 143, 45]; // 81 - DFB Pokal, 90 - KNVB beker

$array_intern_leagues = [2, 3, 848]; // 2, 3, 848 (CL, EL & Conf. League)

// In day (32 WK-Kwalificatie Europa, 960 EK-kwalificatie, 5 Nations League)..
$array_extra_leagues = [48, 32, 960, 5];

// In league (reguliere + internationale competities) 
// teams (ook door internationale competities)
// day (alle competities doornemen)...
$array_leagues = array_merge($array_reg_leagues, $array_intern_leagues); 


$menu_league = 'league';
$menu_standings = 'standings';
$menu_day = 'day';
$menu_teams = 'teams';


$fav_teams = array(
  ['value' => 194, 'bg' => '#c2002f, #fff'], // Ajax
  ['value' => 413, 'bg' => '#009b69, #ed1c24, #080808'], // NEC
  ['value' => 412, 'bg' => '#e73140, #fff'], // MVV
  ['value' => 209, 'bg' => '#ed1c24, #000'], // Feyenoord
  ['value' => 197, 'bg' => '#ed1c24, #fff'], // PSV 
  ['value' => 200, 'bg' => '#fec900, #000'], // Vitesse 
  ['value' => 192, 'bg' => '#ff0000, #fff'], // 1. FC Köln 
  ['value' => 174, 'bg' => '#004b9c, #fff'], // FC Schalke 04
  ['value' => 176, 'bg' => '#095ba4, #fff'], // VFL Bochum 
  ['value' => 497, 'bg' => '#990a2c, #fbba00'], // AS Roma 
  
);

/* 
$teams1 = array(
  [194 => '#c2002f, #fff'], // Ajax
  [209 => '#ed1c24, #000'], // Feyenoord
  [197 => '#ed1c24, #fff'], // PSV 
  [413 => '#009b69, #ed1c24, #080808'], // NEC
  [200 => '#fec900, #000'], // Vitesse 
  [412 => '#e73140, #fff'], // MVV
  [192 => '#ff0000, #fff'], // 1. FC Köln 
  [174 => '#004b9c, #fff'], // FC Schalke 04
  
);

357 = Ierse competitie

*/ 

$array_bgcolor_leagues = 
['#002e61', '#c9152a', '#cf0513', '#cf0513', '#0c90fd', '#ff4b44', 
'#3d185c', '#9ba5d0', '#301b76', '#264439', '#091c3e', '#d6142c','#000', '#ff6b04', '#00be14']; 


$array_bgcolor_menubar = array_combine($array_leagues, $array_bgcolor_leagues); 

// Paden 

$json_league_season_path = './JSON/seasons/'. $league_id . '_season_'. $selected_season . ($selected_season + 1) . '.json'; 

$json_fixture = './JSON/fixtures/fixture_' . $_GET['id'] . '.json';

$json_lineup_path = './JSON/lineups/lineup_' . $_GET['id'] . '.json'; 

$json_events_path = './JSON/events/event_' . $_GET['id'] . '.json'; 

$json_standings_path = './JSON/seasons/season_' . $_GET['season'] . '.json'; 

$current_page = $_SERVER['PHP_SELF'];

?>