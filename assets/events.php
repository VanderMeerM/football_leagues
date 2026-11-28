
<?php

if ($_GET['id'] && file_exists($json_events_path)) {

  $response_json_event = file_get_contents($json_events_path, true);

  $response_event = json_decode($response_json_event, true);

}
else {

$response_event = $response['response'][0]['events'];
  }

$num_events = sizeof($response_event);

$min_playing_minute = 
$response_event[$num_events-1]['time']['elapsed'] + 
$response_event[$num_events-1]['time']['extra'];

$home_team_events = [];
$away_team_events = [];
$all_team_events = [];
$all_team_subst = [];

$home_team_goals = [];
$away_team_goals = [];

 for ($i = 0; $i < $num_events; $i++) {
      
    if (($response_event[$i]['team']['name'] === $homeTeam) && 
        ($response_event[$i]['type'] === 'Goal' || 
        $response_event[$i]['type'] === 'Card'         
        )) {
        array_push($all_team_events, [ 
        'team' => $homeTeam,
        'type' => $response_event[$i]['type'], 
        'detail' => $response_event[$i]['detail'],
        'elapsed' => $response_event[$i]['time']['elapsed'] + $response_event[$i]['time']['extra'], 
        'name' => $response_event[$i]['player']['name'],
        'id' => $response_event[$i]['player']['id'],
        'assist_name' => $response_event[$i]['assist']['name'],
        'comments' => $response_event[$i]['comments']
        ]);
    }

    if (($response_event[$i]['team']['name'] === $homeTeam) && ($response_event[$i]['type'] === 'subst')) {

       array_push($all_team_subst, [ 
        'team' => $homeTeam,
        'elapsed' => $response_event[$i]['time']['elapsed'] + $response_event[$i]['time']['extra'], 
        'name' => $response_event[$i]['player']['name'],
        'id' => $response_event[$i]['player']['id'],
        'assist_name' => $response_event[$i]['assist']['name'],
        'assist_id' => $response_event[$i]['assist']['id'],
        'comments' => $response_event[$i]['comments']
        ]);

    }

     if (($response_event[$i]['team']['name'] === $awayTeam) && 
        ($response_event[$i]['type'] === 'Goal' || 
        $response_event[$i]['type'] === 'Card' 
        )) {
        array_push($all_team_events, [ 
       'team' => $awayTeam,
        'type' => $response_event[$i]['type'], 
        'detail' => $response_event[$i]['detail'],
        'elapsed' => $response_event[$i]['time']['elapsed'] + $response_event[$i]['time']['extra'], 
        'name' => $response_event[$i]['player']['name'],
        'id' => $response_event[$i]['player']['id'],
        'assist_id' => $response_event[$i]['assist']['id'],
        'assist_name' => $response_event[$i]['assist']['name'],
        'assist_id' => $response_event[$i]['assist']['id'],
        'comments' => $response_event[$i]['comments']
        ]);
    }

    if (($response_event[$i]['team']['name'] === $awayTeam) && ($response_event[$i]['type'] === 'subst')) {

       array_push($all_team_subst, [ 
        'team' => $awayTeam,
        'detail' => $response_event[$i]['detail'],
        'elapsed' => $response_event[$i]['time']['elapsed'] + $response_event[$i]['time']['extra'], 
        'name' => $response_event[$i]['player']['name'],
        'id' => $response_event[$i]['player']['id'],
        'assist_id' => $response_event[$i]['assist']['id'],
        'assist_name' => $response_event[$i]['assist']['name'],
        'comments' => $response_event[$i]['comments']
        ]);

    }

  }
 
  usort($all_team_events, function($a, $b) {
    return $a['elapsed'] <=> $b['elapsed'];
  });

    $prevent_loop = true; 
    
   // echo '<div class="main_container_event">';

                
   echo '<table style="background-color: ' . (date('d-m-Y') === $date ? $backgr_today_match : null) . '">';
                    
        for($i=0; $i < sizeof($all_team_events); $i++) {

        echo '<tr><td style="color: ' . (date('d-m-Y') === $date ? "black" : "white") . '; width: 50%;">';

       echo ($all_team_events[$i]['team'] === $awayTeam ? '
       <td style="color: ' . (date('d-m-Y') === $date ? "black" : "white") . '; width: 50%;">' : null);
         
            if (array_key_exists($all_team_events[$i]['type'], $array_type)
             || array_key_exists($all_team_events[$i]['detail'], $array_type)
            ) 
            {

            
            if ($all_team_events[$i]['type'] !== 'subst') {

               echo
              
                '<img id="type_pic" src="../img/' . 
               $array_type[$all_team_events[$i]['type']] . 
               $array_type[$all_team_events[$i]['detail']] . ' ' . '">  ';
            }    
            
            echo 
               (sizeof(explode('-', $all_team_events[$i]['elapsed'])) > 1 ? 
                explode('-', $all_team_events[$i]['elapsed'])[1]:
 
                $all_team_events[$i]['elapsed']) . "' " .

              '<a style="color: ' . (date('d-m-Y') === $date ? "black" : "white") . '" href= "../assets/players.php?id='.$all_team_events[$i]['id'] .'" target=_blank>'
              //. ($all_team_events[$i]['type'] === 'subst' ?  '<span id="arrow_red"> → </span>' : null) 
              . $all_team_events[$i]['name'] . '</a>';

               /*
               if ($all_team_events[$i]['type'] === 'subst') {

                if (is_null($all_team_events[$i]['assist_id'])) {
                 echo ' <span id="arrow_green"> ← </span> ' . '<span style="color:#676161;"> ' . $all_team_events[$i]['assist_name'] . '</span>';
                  
                  } else {             
                    
                echo 
              '<a href= "../assets/players.php?id='.$all_team_events[$i]['assist_id'] .'" target=_blank>
              <span id="arrow_green"> ← </span>'. $all_team_events[$i]['assist_name'] . '</a>';
               }
               }
               */

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
                 { echo '<i> (' . $array_goal[$all_team_events[$i]['detail']] . ')</i>'; 
               }

          if ($all_team_events[$i]['team'] === $homeTeam) {
          echo '<td>'; 
          }
          echo '</tr>';
         }
        
       echo '
      </table> 
       </div>';
    

       // Event opslaan als de wedstrijd ook is opgeslagen (en dus minstens een dag in het verleden ligt..)

       if  (file_exists($json_fixture)) 

     {

      $json_file_event = fopen($json_events_path, "w");

      fwrite($json_file_event, json_encode($response_event));

      fclose($json_file_event);
           
      }

         
?>