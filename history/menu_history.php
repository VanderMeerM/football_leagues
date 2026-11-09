<?php 

// Menu bouwen..

// Menu Penalty's 


echo "
<div class='fixed fixed_menubar' style='background-color: #002e61;'>
<div class='center_buttons'>

<div class='menubar'>
<div class='menubuttons'>
</div>
</div>

<ul>";

 echo 
'<div class="menubuttons">
<li><a href= "../penalty">P</a></li>
</div>';


// Menu Find 

 echo 
'<div class="menubuttons">
<li><a href= "../history">H</a></li>
</div>';


// Menu teams (met shirtje) 

(str_contains($current_page, $menu_teams) ? $link_teams = './' : $link_teams = './teams');  

echo 
"<div class='menubuttons'>
<a style='padding: 0px' href= $link_teams > <img id='shirt' style='cursor:pointer' src='../img/shirt.png'></a>
</div>";


// Menu Vandaag 

$today = strtotime('today');

echo 
  "<div class='menubuttons'>
<form method='post' action='../day'>
<input type='image' id='agenda' style='cursor:pointer' src='../img/agenda.png'>
<input type='hidden' name='sel_day' value=$today>
<input type='submit' style='display: none'>
</form>
</div>";

echo '
</ul>
</div>
</div>
</div>';
