
<?php
 
(date('m') < 7 ? $current_season = (date('Y') - 1) : $current_season = date('Y')); 

$_GET['league'] ? $league_id = $_GET['league'] : $league_id = 88; 

$_GET['season'] ? $selected_season = $_GET['season'] : $selected_season = $current_season; 

$backgr_today_match = '#e4cd84';


$allrounds = [];

$array_leagues = [88, 89, 78, 79, 135, 140, 39, 40, 179, 408]; // 357 = Ierse competitie

$array_bgcolor_leagues = ['#002e61', '#c9152a', '#cf0513', '#cf0513', '#0c90fd', '#ff4b44', '#3d185c', '#9ba5d0', '#301b76', '#264439']; 

$array_bgcolor_menubar = array_combine($array_leagues, $array_bgcolor_leagues); 

//$round_from_match_to_overview = setcookie('round_from_match_to_overview', $_GET['round_selection'], 3600, '/');

//$_GET['league'] ? $league_id = $_GET['league'] : $league_id = 88; 

//$_GET['season'] ? $selected_season = $_GET['season'] : $selected_season = $current_season; 



// Paden 

$json_league_season_path = './JSON/seasons/'. $league_id . '_season_'. $selected_season . ($selected_season + 1) . '.json'; 

$json_fixture = './JSON/fixtures/fixture_' . $_GET['id'] . '.json';

$json_lineup_path = './JSON/lineups/lineup_' . $_GET['id'] . '.json'; 

$json_events_path = './JSON/events/event_' . $_GET['id'] . '.json'; 

$current_page = explode('/', $_SERVER['PHP_SELF'])[4];

?>