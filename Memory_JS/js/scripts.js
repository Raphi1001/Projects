var leftCards = [];
for (var i = 1; i <= 16; ++i) { //creates array from 1-16
    leftCards.push(i);
}

var tryCount = 0;
var time = 0;

var waiting = false;
var clock = setInterval(timer, 1000);

/* count seconds */
function timer() {

    if (document.getElementsByClassName("found").length < 16) {
        ++time;
        document.getElementById("zeit").innerHTML = "Zeit: " + time; //updates the "Zeit" inforcard

    } else { //if user found all pairs, end game
        if (confirm("Glückwunsch, Sie haben gewonnen!\nVersuche: " + Math.floor(tryCount / 2) + "\nZeit: " + time + " Sekunden\nNochmal spielen ? ")) {
            location.reload(); //replay
        } else {
            clearInterval(clock); //end
        }
    }
}

function setUserName() {
    var username = window.prompt("Geben Sie Ihren Namen ein: ")
    if (username == null || username == "") { //if user enters nothing or cancels, set default username
        username = "Max Mustermann"
    }
    var allowedLetters = /^[0-9 A-Za-z]+$/;
    if (username.match(allowedLetters)) { //validates user input
        document.getElementById("spieler").innerHTML = "Spieler: " + username; //updates the "Spieler" inforcard
    } else { //if input is invalid try again
        alert("Ungültige Eingabe");
        setUserName();
    }
}
//creates to playing field
function printPlayingField() {

    var playingField = document.getElementById("spielbereich");
    for (var i = 0; i < 16;) {
        for (var u = 0; u < 4; ++u) {
            var newCard = document.createElement("img");
            newCard.setAttribute("src", "pics/memoryBg.png");
            newCard.setAttribute("id", "card-" + i);
            newCard.setAttribute("onclick", "uncoverCard(" + i + ")");
            newCard.setAttribute("data-card", createRandomCard()); // replace "createRandomCard()" with" i + 1 " to get fixed card positions

            playingField.appendChild(newCard);
            ++i;
        }
        var br = document.createElement("br"); //linebreak every 4th card
        playingField.appendChild(br);
    }
}
//gets a random card from all left cards
function createRandomCard() {
    var rand = Math.floor(Math.random() * leftCards.length)
    var CardId = leftCards[rand]; //picks random index of leftCards array
    leftCards.splice(rand, 1); //deletes used card id from array
    return CardId;
}

//uncovers Card if user clicks it
function uncoverCard(i) {
    var cardToUncover = document.getElementById("card-" + i);

    if (waiting == false && !cardToUncover.classList.contains("uncovered")) { //checks if user clicked an already uncovered card or more than 2 cards
        waiting = true;
        cardToUncover.setAttribute("src", "pics/card" + cardToUncover.dataset.card + ".png");
        cardToUncover.classList.add("uncovered");

        ++tryCount;
        document.getElementById("versuche").innerHTML = "Versuche: " + Math.floor(tryCount / 2); //updates the "Versuche" inforcard

        if (tryCount % 2 == 0) { //every second click, check if cards match

            setTimeout(checkCards, 1000); //1 second timeout 

        } else { //if its the first click, continue
            waiting = false;
        }
    }
}

//checks if two uncovered cards match
function checkCards() {
    var cardsToCheck = document.getElementsByClassName("uncovered"); //array of all uncovered cards
    if ((17 - cardsToCheck[0].dataset.card) == cardsToCheck[1].dataset.card) {
        var repeat = cardsToCheck.length
        for (var i = 0; i < repeat; ++i) {
            cardsToCheck[0].setAttribute("src", "pics/memoryBgI.png");
            cardsToCheck[0].removeAttribute("onclick");

            cardsToCheck[0].classList.add("found");
            cardsToCheck[0].classList.remove("uncovered");
        }
        waiting = false;
    } else { //if they don't match cover them
        coverCards();
    }
}

//covers all uncovered cards
function coverCards() {
    var cardsToCover = document.getElementsByClassName("uncovered"); //array of all uncovered cards
    var repeat = cardsToCover.length
    for (var i = 0; i < repeat; ++i) {
        cardsToCover[0].setAttribute("src", "pics/memoryBg.png");
        cardsToCover[0].classList.remove("uncovered");
    }
    waiting = false;

}