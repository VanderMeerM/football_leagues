
const playerA = document.getElementById('playerA');
const playerB = document.getElementById('playerB');
const playerA_name = document.getElementById('playerA_name');
const playerB_name = document.getElementById('playerB_name');
const winner = document.getElementById('winner');
const countryA = document.getElementById('countryA');
const countryB = document.getElementById('countryB');
const flagA = document.getElementById('flagA');
const flagB = document.getElementById('flagB');
const clubA = document.getElementById('clubA');
const clubB = document.getElementById('clubB');
const teamA = document.getElementById('Team_A');
const teamB = document.getElementById('Team_B');

const optionSelectCountry = 'Selecteer land:';

// const confirmTeams = document.getElementById('confirmTeams');
const startingTeam = document.getElementById('startingTeam');

let totalScorePlayerA = [];
let totalScorePlayerB = [];
let filteredArrayA = [];
let filteredArrayB = [];

const sessionStoragesToBeRemoved = ['A', 'B', 'A_backup', 'B_backup'];
sessionStoragesToBeRemoved.map(ss => {sessionStorage.removeItem(ss)
});


function buildSelectCountry(country) {

const url = 'https://www.apicountries.com/alpha/IL'
fetch(url)
.then (res => res.json())
.then (data => {

const arrayCountries = []; 

for (let i=0; i < data.length; i++) {

arrayCountries.push({country: data[i].translations.nld.common, flag: data[i].flags.png});
  
}

arrayCountries.sort((a, b) => (a.country > b.country) ? 1 : ((b.country > a.country) ? -1 : 0))
.map(ac => {

const newOpt = document.createElement('option');
newOpt.innerText += ac.country;

country.appendChild(newOpt);
console.log(arrayCountries);

})
})


}

function checkIfOnlyClubOrCountry(club, country) {
    if (club.value !='' && country.value !=optionSelectCountry) {
     alert ('Kies per team één land of één club - niet beide');
    // country.setAttribute('class', 'redborder');
    // club.setAttribute('class', 'redborder');
     club.value = '';
 }
 return;
}

function setFlagOrClub(divflag, selectedCountry) {

   const url = 'https://restcountries.com/v3.1/all'
    fetch(url)
    .then (res => res.json())
    .then (data => {
    
    const arrayFlags = []; 
  
    for (let i=0; i < data.length; i++) {

        arrayFlags.push({country: data[i].translations.nld.common, flag: data[i].flags.png});
          
        }

         const selectedFlag = arrayFlags.filter(ct => ct.country === selectedCountry.value);
         const newImg = document.createElement('img');

         newImg.src = selectedFlag[0].flag;
         divflag.appendChild(newImg);
                    
        });
    }  

    function removeItems(item) {
        while (item.lastChild) {
            item.removeChild(item.lastChild)
            }  
    }

    function removeRedBorders() {
    const divsWhereRedBorderToBeRemoved = [countryA, clubA, countryB, clubB, startingTeam]; 

   divsWhereRedBorderToBeRemoved.map(div => {
        div.removeAttribute('class', 'redborder');
    })
    }

 
buildSelectCountry(countryA);
buildSelectCountry(countryB);


/*
confirmTeams.addEventListener('click', () => {

removeItems(playerA);
removeItems(playerB);
   
setCircles(playerA, totalScorePlayerA, 5, filteredArrayA);
setCircles(playerB, totalScorePlayerB, 5, filteredArrayB);

removeRedBorders(); 
    
checkIfOnlyClubOrCountry(clubA, countryA);
checkIfOnlyClubOrCountry(clubB, countryB);


if (clubA.value !='') {
    
    if (clubA.value.toLowerCase() === clubB.value.toLowerCase()) {
    alert ('Een club kan niet tegen zichzelf spelen.');
    return;
}}

if (!document.getElementById('Team_A').checked && !document.getElementById('Team_B').checked) {
    alert ('Selecteer welk team start');
    startingTeam.setAttribute('class', 'redborder');
    return;
}

if (teamB.checked) {
if (countryA.value !='Selecteer land:' && countryA.value !='') {
    setFlagOrClub(flagB, countryA);
}
else {
    flagB.textContent = clubA.value;
}

if (countryB.value !='Selecteer land:' && countryB.value !='') {
    setFlagOrClub(flagA, countryB); 
}

else {
   flagA.textContent = clubB.value;
}
}

else if (teamA.checked) {
    if (countryA.value !='Selecteer land:' && countryA.value !='') {
        setFlagOrClub(flagA, countryA);
    }
    else {
        flagA.textContent = clubA.value;
    }

    if (countryB.value !='Selecteer land:' && countryB.value !='') {
        setFlagOrClub(flagB, countryB); 
    }
    
    else {
       flagB.textContent = clubB.value;
    }
     
}}
)
*/

clubA.addEventListener('input', () => {
removeItems(playerA);
  
setCircles(playerA, totalScorePlayerA, 5, filteredArrayA);

removeRedBorders(); 
    
flagA.textContent = clubA.value;
checkIfOnlyClubOrCountry(clubA, countryA);


if (clubB.value !='') {
    
    if (clubA.value.toLowerCase() === clubB.value.toLowerCase()) {
    alert ('Een club kan niet tegen zichzelf spelen.');
    return;
}}
})

clubB.addEventListener('input', () => {
    removeItems(playerB);
    
    setCircles(playerB, totalScorePlayerB, 5, filteredArrayB);
    
    removeRedBorders(); 
        
    flagB.textContent = clubB.value;
    checkIfOnlyClubOrCountry(clubB, countryB);


    if (clubA.value !='') {
    
        if (clubA.value.toLowerCase() === clubB.value.toLowerCase()) {
        alert ('Een club kan niet tegen zichzelf spelen.');
        return;
    }}
    })


countryA.addEventListener('change', () => {

        if (countryA.value !=optionSelectCountry) {
            if (countryA.value === countryB.value) {
                alert ('Een land kan niet tegen zichzelf spelen.');
               return;
            }}

           (countryA.firstChild.parentElement.value != optionSelectCountry) ?
            sessionStorage.setItem('A', countryA.firstChild.parentElement.value) : null;

                   
removeItems(flagA);
removeItems(playerA);

playerA_name.setAttribute('class', 'lightblue_background');

setCircles(playerA, totalScorePlayerA, 5, filteredArrayA);

removeRedBorders(); 

if (countryA.value ===optionSelectCountry) {
    return;
} 
    else {
setFlagOrClub(flagA, countryA);
  
    }
checkIfOnlyClubOrCountry(clubA, countryA);
})

countryB.addEventListener('change', () => {

    if (countryB.value !=optionSelectCountry) {
        if (countryB.value === countryA.value) {
            alert ('Een land kan niet tegen zichzelf spelen.');
           return;
        }}

        (countryB.firstChild.parentElement.value != optionSelectCountry) ?
     sessionStorage.setItem('B', countryB.firstChild.parentElement.value) :null;
        
     
removeItems(flagB);
removeItems(playerB);

setCircles(playerB, totalScorePlayerB, 5, filteredArrayB);

removeRedBorders(); 

/*
if (countryA.value ==='Selecteer land:') {
    return;
} 
    else {
        */
setFlagOrClub(flagB, countryB);
 // }

checkIfOnlyClubOrCountry(clubB, countryB);
})

    teamA.addEventListener('click', () => {

        if (sessionStorage.getItem('A')) {
            sessionStorage.setItem('A', sessionStorage.getItem('A_backup'));
            sessionStorage.removeItem('A_backup');;
        }

        if (sessionStorage.getItem('B')) {
            sessionStorage.setItem('B', sessionStorage.getItem('B_backup'));
            sessionStorage.removeItem('B_backup');
        }

       removeItems(flagA);
        removeItems(flagB);
        removeItems(playerA);
      
            
         if (countryA.value !=optionSelectCountry && countryA.value !='') {
            setFlagOrClub(flagA, countryA);
        }
        else {
            flagA.textContent = clubA.value;
            sessionStorage.removeItem("A");

        }
    
        if (countryB.value !=optionSelectCountry && countryB.value !='') {
            setFlagOrClub(flagB, countryB); 
        }
        
        else {
           flagB.textContent = clubB.value;
           sessionStorage.removeItem("B");
        }

        setCircles(playerA, totalScorePlayerA, 5, filteredArrayA);

         
    })

    teamB.addEventListener('click', () => {

        if (sessionStorage.getItem('A')) {
            sessionStorage.setItem('A_backup', countryA.firstChild.parentElement.value);
            sessionStorage.setItem('B', sessionStorage.getItem('A_backup'));
            
        }

        if (sessionStorage.getItem('B')) {
            sessionStorage.setItem('B_backup', countryB.firstChild.parentElement.value);
            sessionStorage.setItem('A', sessionStorage.getItem('B_backup'));
        }

        removeItems(flagA);
        removeItems(flagB);
        removeItems(playerB);

              
        if (countryA.value !=optionSelectCountry && countryA.value !='') {
            setFlagOrClub(flagB, countryA);
        }
        else {
            flagB.textContent = clubA.value;
            sessionStorage.removeItem("B");
        }
        
        if (countryB.value !=optionSelectCountry && countryB.value !='') {
            setFlagOrClub(flagA, countryB); 
        }
        
        else {
           flagA.textContent = clubB.value;
           sessionStorage.removeItem("A");
        }
        setCircles(playerB, totalScorePlayerB, 5, filteredArrayB);

    })

    
function setCircles(player, array, num, filteredarray) {

    function switchBackground(player_name) {
        console.dir(player_name.className)
        if (player_name = playerA_name) {
            playerA_name.removeAttribute('class','lightblue_background')
            playerB_name.setAttribute('class','lightblue_background')
        }
        else if (player_name = playerB_name) {
           playerB_name.removeAttribute('class','lightblue_background')
           playerA_name.setAttribute('class','lightblue_background')
        }
    }   
        
    function gameOver() {
       sessionStorage.clear();
        [...playerA.querySelectorAll('div')].filter(arr => !arr.clicked).map(ar => ar.style.visibility = 'hidden');
        [...playerB.querySelectorAll('div')].filter(arr => !arr.clicked).map(ar => ar.style.visibility = 'hidden');
    }

    function evaluateScore() {

        // console.log(turns-totalScorePlayerB.length)
        //console.log(filteredArrayB.length - filteredArrayA.length)
        if //((totalScorePlayerA.length && totalScorePlayerB.length == turns) ||
            (filteredArrayB.length - filteredArrayA.length > (num - totalScorePlayerB.length)) {


                if (sessionStorage.getItem('B')) {
                   
                    winner.textContent = `${sessionStorage.getItem('B')} heeft gewonnen.`
                }
                else {

            winner.textContent = `${flagB.innerText} heeft gewonnen`;
                }
            gameOver()
            return
        }

        else if
            (filteredArrayA.length - filteredArrayB.length > (num - totalScorePlayerB.length)) {

                if (sessionStorage.getItem('A')) {
                   
                    winner.textContent = `${sessionStorage.getItem('A')} heeft gewonnen.`
                }
                else {
                
                winner.textContent = `${flagA.innerText} heeft gewonnen`;
                }

                gameOver()
                return
        }

        else if
            (totalScorePlayerB.length == num &&
            filteredArrayB.length - filteredArrayA.length == (num - totalScorePlayerB.length)) {
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
        newDivLeft.style.backgroundColor = 'green';

        newDivRight.classList.add('right');
        newDivRight.style.backgroundColor = 'red';

        player.appendChild(newDivLeft);
        player.appendChild(newDivRight);
    }

    const divArray = [...player.querySelectorAll('div')]
    divArray.map((arr, idx) => {
        arr.addEventListener('click', () => {

           // if (player_name.className === 'lightblue_background') {

            if (!divArray[idx].clicked) {

                if (idx % 2 == 0) {
                    divArray[idx + 1].style.background = 'green';
                    array.push({ turn: (idx / 2) + 1, answer: true });
                    divArray[idx].clicked = true;
                    divArray[idx + 1].clicked = true;
                    filteredarray.push({ turn: (idx / 2) + 1, answer: true });

                    switchBackground(playerA_name)
                    switchBackground(playerB_name)

                    evaluateScore()

                    return
                }

                else if (idx % 2 == 1) {
                    divArray[idx - 1].style.background = 'red'
                
                array.push({ turn: Math.ceil(idx / 2), answer: false });
                divArray[idx].clicked = true;
                divArray[idx - 1].clicked = true;

                switchBackground(playerA_name)
                switchBackground(playerB_name)

                evaluateScore()

                return 
                }
            }
        
                   

        })

    })
}


