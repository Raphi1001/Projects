function addItem() {
    newItem = $("#item").val();
    if (newItem.length == 0) return; //if an empty item is submitted do nothing

    var allowedLetters = /^[0-9 A-Za-z]+$/;
    if (!newItem.match(allowedLetters)) return alert("Invalid Input"); //validates user input

    newItem = "<li>" + $("#item").val() + "</li>";
    $(newItem).hide().appendTo("#list").slideDown(500); //adds new item to list

    $("#item").val("").hide().fadeIn(); //removes input value after item is added to list
}

function removeItem() {
    $(this).slideUp(500); //removed item slides up
    setTimeout(() => { //wait until animation has finished to remove item
        $(this).remove();
    }, 1000);
}

function hideList() {
    $("#list").fadeToggle(500); //Toggles fade in / out of List

    if ($(this).text() == "Hide List") return $(this).text("Show List"); //Change Button Text to "Show list"
    $(this).text("Hide List"); //Change Button Text to "Hide list"
}

$(document).ready(function() {
    $("#add").on("click", addItem), //click add button to add new item to list
        $("#hide").on("click", hideList), //click hide button to hide / show list
        $("#list").on("dblclick", "li", removeItem), //double click to remove list item
        $("#list").sortable() //makes the list sortable
});