
const playerA = document.getElementById('playerA');
const playerB = document.getElementById('playerB');

const winner = document.getElementById('winner');
const countryA = document.getElementById('countryA');
const countryB = document.getElementById('countryB');

const clubA = document.getElementById('clubA');
const clubB = document.getElementById('clubB');
const teamA = document.getElementById('Team_A');
const teamB = document.getElementById('Team_B');
const scoreA = document.getElementById('scoreA');
const scoreB = document.getElementById('scoreB');

const startingTeam = document.getElementById('startingTeam');
const round = document.getElementById('round');

let totalScorePlayerA = [];
let totalScorePlayerB = [];
let filteredArrayA = [];
let filteredArrayB = [];

let roundNum = 0;
let scoredAftFiveA = 0;
let scoredAftFiveB = 0;

let realTotalScoreB;
let highlightBackground = true;
let highlightColor = "#aac59b";


function removeItems(item) {
    while (item.lastChild) {
            item.removeChild(item.lastChild)
            }  
    }

 

removeItems(playerA);
removeItems(playerB);
   
setCircles(playerA, totalScorePlayerA, 5, filteredArrayA);
setCircles(playerB, totalScorePlayerB, 5, filteredArrayB);


clubA.addEventListener('input', () => {
removeItems(playerA);
  
setCircles(playerA, totalScorePlayerA, 5, filteredArrayA);


if (clubB.value !='') {
    
    if (clubA.value.toLowerCase() === clubB.value.toLowerCase()) {
    alert ('Een club kan niet tegen zichzelf spelen.');
    return;
}}
})

clubB.addEventListener('input', () => {
    removeItems(playerB);
    
    setCircles(playerB, totalScorePlayerB, 5, filteredArrayB);
    
  
    if (clubA.value !='') {
    
        if (clubA.value.toLowerCase() === clubB.value.toLowerCase()) {
        alert ('Een club kan niet tegen zichzelf spelen.');
        return;
    }}
    })


switchBackground();

// Stel achtergrond in voor het land/team dat begint...

if (winner.textContent != '') {

document.getElementById('startingTeam').addEventListener('click', () => {

   if (teamB.checked) {
    highlightBackground = !highlightBackground;
    switchBackground();
    } 
        else if (team_A.checked) {
        highlightBackground = true;
        switchBackground();
    }
    })
}


function switchBackground() {
    if (highlightBackground) {
        document.querySelector('.container_playerA').setAttribute('style',`background-color: ${highlightColor}`);
        document.querySelector('.container_playerB').setAttribute('style','');
    }
    else {
        document.querySelector('.container_playerB').setAttribute('style',`background-color: ${highlightColor}`);
        document.querySelector('.container_playerA').setAttribute('style','');
    }
}

function showRound() {  

    if (totalScorePlayerA.length == totalScorePlayerB.length) { 
            
        roundNum++;

        round.textContent = `Ronde: ${roundNum -1}`;

        };
       
}

function setCircles(player, array, num, filteredarray) {

     if (num != 1) {
        showRound();
     }
      
                  
 function gameOver() {
       sessionStorage.clear();
        [...playerA.querySelectorAll('div')].filter(arr => !arr.clicked).map(ar => ar.style.visibility = 'hidden');
        [...playerB.querySelectorAll('div')].filter(arr => !arr.clicked).map(ar => ar.style.visibility = 'hidden');
    }

    function teamBWins () {
            if (sessionStorage.getItem('B')) {
                   
                    winner.textContent = `${sessionStorage.getItem('B')} heeft gewonnen!`;
                }
                else {

            winner.textContent = `${nameTeamB} heeft gewonnen!`;

                }
    }

    function evaluateScore() {

         
       showRound();

        // Gescoorde penalty's weergeven..
        
        if (filteredArrayA.length > 0 ) {
        scoreA.textContent = filteredArrayA.length + scoredAftFiveA;
        }

        if (filteredArrayB.length > 0 ) {
        scoreB.textContent = filteredArrayB.length + scoredAftFiveB;
        }

        
        // voor geval dat Team B begint en eerste drie raak schiet en A eerste drie mist
        
          if  ( filteredArrayB.length == 3 && filteredArrayA.length == 0 && (totalScorePlayerA.length == 3)) {
              teamBWins();
              gameOver();
              return;
            }

            else if ( filteredArrayB.length - filteredArrayA.length > (num -  (totalScorePlayerB.length)) 
            && (totalScorePlayerA.length == totalScorePlayerB.length) )  
            {

             teamBWins(); 
             gameOver();
             return;
        }

        // Team A wint..
                     
        else if
            (filteredArrayA.length - filteredArrayB.length > (num - totalScorePlayerB.length)) {

                if (sessionStorage.getItem('A')) {
                   
                    winner.textContent = `${sessionStorage.getItem('A')} heeft gewonnen!`
                }
                else {
                
                winner.textContent = `${nameTeamA} heeft gewonnen!`;

                }

                gameOver()
                return
        }

        else if
            (totalScorePlayerB.length == num &&
            filteredArrayB.length - filteredArrayA.length == (num - totalScorePlayerB.length))     
            {
            scoredAftFiveA = parseInt(scoreA.textContent);
            scoredAftFiveB = parseInt(scoreB.textContent);
            totalScorePlayerA = [];
            totalScorePlayerB = [];
            filteredArrayA = [];
            filteredArrayB = [];
           
            setCircles(playerA, totalScorePlayerA, 1, filteredArrayA);
            setCircles(playerB, totalScorePlayerB, 1, filteredArrayB);

            return
        }

    }


    for (x = 1; x < num + 1; x++) {

        const newDivLeft = document.createElement('div');
        const newDivRight = document.createElement('div');

        newDivLeft.classList.add('left');
        newDivLeft.style.backgroundColor = 'red';

        newDivRight.classList.add('right');
        newDivRight.style.backgroundColor = 'green';
       
        player.appendChild(newDivRight);
        player.appendChild(newDivLeft);
    }



    const divArray = [...player.querySelectorAll('div')]
    divArray.map((arr, idx) => {

       let numPen = divArray.length;
       
        arr.addEventListener('click', () => {

            // Deactiveer dropdown-menu voor landen en invoer club bij beginnen penaltyreeks..

            countryA.setAttribute('disabled', 'disabled');
            countryB.setAttribute('disabled', 'disabled');

            clubA.setAttribute('disabled', 'disabled');
            clubB.setAttribute('disabled', 'disabled');


         highlightBackground = !highlightBackground;
         switchBackground()

            if (!divArray[idx].clicked) {


             // indien er meer dan 5 penalty's worden genomen, worden maximaal 6 cirkels getoond..

              if (numPen > 10) {
                    divArray[numPen-12].style.display = 'none';
                    divArray[numPen-11].style.display = 'none';
                    }

                if (idx % 2 == 0) {
                    divArray[idx + 1].style.background = 'green';
                    array.push({ turn: (idx / 2) + 1, answer: true });
                    divArray[idx].clicked = true;
                    divArray[idx + 1].clicked = true;
                    filteredarray.push({ turn: (idx / 2) + 1, answer: true });
                                    
                    evaluateScore();

                    return;
                }

                else if (idx % 2 == 1) {
                    divArray[idx - 1].style.background = 'red'
                
                array.push({ turn: Math.ceil(idx / 2), answer: false });
                divArray[idx].clicked = true;
                divArray[idx - 1].clicked = true;
                           
                evaluateScore();

                return;
                }
            }
                   
       })
    })
}




