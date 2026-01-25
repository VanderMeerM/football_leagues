
<?php

require('./api.php');

if ($_GET['id'] && file_exists($json_events_path)) {

  $response_json_event = file_get_contents($json_events_path, true);

  $response_event = json_decode($response_json_event, true);

}
   
  else {
 
    $curl_event = curl_init();
    
    curl_setopt_array($curl_event, array(
      CURLOPT_URL => 'https://v3.football.api-sports.io/fixtures/events?fixture=' . $_GET['id'], 
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'x-rapidapi-key: ' . $api_key . '',
        'x-rapidapi-host: v3.football.api-sports.io',
        
      ),
    ));
    
    $response_event = curl_exec($curl_event);

    curl_close($curl_event);

    $response_event = json_decode($response_event, true);

  }
    
$num_events = $response_event['results'];

$min_playing_minute = 
$response_event['response'][$num_events-1]['time']['elapsed'] + 
$response_event['response'][$num_events-1]['time']['extra'];

$home_team_events = [];
$away_team_events = [];
$all_team_events = [];

$home_team_goals = [];
$away_team_goals = [];

 for ($i = 0; $i < $num_events; $i++) {
      
    if (($response_event['response'][$i]['team']['name'] === $homeTeam) && 
        ($response_event['response'][$i]['type'] === 'Goal' || 
        $response_event['response'][$i]['type'] === 'Card' ||
        $response_event['response'][$i]['type'] === 'subst')) {
        array_push($all_team_events, [ 
        'team' => $homeTeam,
        'type' => $response_event['response'][$i]['type'], 
        'detail' => $response_event['response'][$i]['detail'],
        'elapsed' => $response_event['response'][$i]['time']['elapsed'] + $response_event['response'][$i]['time']['extra'], 
        'name' => $response_event['response'][$i]['player']['name'],
        'id' => $response_event['response'][$i]['player']['id'],
        'assist_name' => $response_event['response'][$i]['assist']['name'],
        'comments' => $response_event['response'][$i]['comments']
        ]);
    }

     if (($response_event['response'][$i]['team']['name'] === $awayTeam) && 
        ($response_event['response'][$i]['type'] === 'Goal' || 
        $response_event['response'][$i]['type'] === 'Card' || 
        $response_event['response'][$i]['type'] === 'subst')) {
        array_push($all_team_events, [ 
       'team' => $awayTeam,
        'type' => $response_event['response'][$i]['type'], 
        'detail' => $response_event['response'][$i]['detail'],
        'elapsed' => $response_event['response'][$i]['time']['elapsed'] + $response_event['response'][$i]['time']['extra'], 
        'name' => $response_event['response'][$i]['player']['name'],
         'id' => $response_event['response'][$i]['player']['id'],
        'assist_name' => $response_event['response'][$i]['assist']['name'],
        'comments' => $response_event['response'][$i]['comments']
        ]);
    }
  }
 
  usort($all_team_events, function($a, $b) {
    return $a['elapsed'] <=> $b['elapsed'];
  });

    $prevent_loop = true; 
    
    echo '<div class="main_container_event">';

    if (in_array($matchStatus, $statusInPlay) && ($num_events > 0))   
    { 
     echo '<div id="play_min"> min. '. $min_playing_minute . "'</div>"; 
    }
              
    echo '<table>';
                    
        for($i=0; $i < sizeof($all_team_events); $i++) {

        echo '<tr>';

          if ($all_team_events[$i]['team'] === $homeTeam) {
            
          echo '<td>'; 
          //   echo '<div class= "event_container"><span class="align-left">'; 
          }

            else if ($all_team_events[$i]['team'] === $awayTeam) {
              echo '<td><td>';
             // echo '<div class= "event_container"><span class="align-right">'; 
            }
         
            if (array_key_exists($all_team_events[$i]['type'], $array_type)
             || array_key_exists($all_team_events[$i]['detail'], $array_type)
            ) 
            {
                echo
              
                '<img id="type_pic" src="./img/' . 
               $array_type[$all_team_events[$i]['type']] . 
               $array_type[$all_team_events[$i]['detail']] . ' ' . '">  ' .    
            
               (sizeof(explode('-', $all_team_events[$i]['elapsed'])) > 1 ? 
               explode('-', $all_team_events[$i]['elapsed'])[1]:
 
                  $all_team_events[$i]['elapsed']) . "' " .

              //'<a href= "./players?id='.$all_team_events[$i]['id']'" target=_blank>' 
              $all_team_events[$i]['name'];

               if ($all_team_events[$i]['type'] === 'subst') {
                echo ' (voor ' . $all_team_events[$i]['assist_name'] . ')';
               }

               if ($all_team_events[$i]['comments']) {

                if (array_key_exists($all_team_events[$i]['comments'], $array_comments))
                { 
                  echo ' <i>(' . $array_comments[$all_team_events[$i]['comments']] . ')</i>';
                  echo ($all_team_events[$i]['detail'] === 'Missed Penalty' ? '<span class="red"> '. strtoupper('<strong>gemist</strong>') . '</span>' : null);  
                    
              } else {

               echo ' <i>(' . $all_team_events[$i]['comments'] . ')</i>';
              }
               }
            };
           
            if (array_key_exists($all_team_events[$i]['detail'], $array_goal) &&
            ($all_team_events[$i]['comments'] != 'Penalty Shootout'))
                 { echo ' (' . $array_goal[$all_team_events[$i]['detail']] . ')'; 
               }

          if ($all_team_events[$i]['team'] === $homeTeam) {
          echo '<td>'; 
          }
          echo '</tr>';
         }
        
       echo '
      </table> 
       </div>
     </div>';

       // Event opslaan als de wedstrijd ook is opgeslagen (en dus minstens een dag in het verleden ligt..)

       if  (file_exists($json_fixture)) 

     {

      $json_file_event = fopen($json_events_path, "w");

      fwrite($json_file_event, json_encode($response_event));

      fclose($json_file_event);
           
      }

         
?>