var results = [];

function calculate(operation) {
    var x = document.getElementById("input1").value;
    var y = document.getElementById("input2").value;
    if (isNaN(x) || isNaN(y)) {
        var output = "Ungültige Eingabe."
    } else if (y == 0 && operation == "/") {
        var output = "Divison durch 0 nicht möglich."
    } else {
        var output = eval(x + operation + y);

        results.push(x + " " + operation + " " + y + " = " + output);
    }

    document.getElementById("output").innerHTML = output;

    printResults();
}

function printResults() {
    var list = "<ul>";
    results.forEach(printOutput);
    list += "</ul>";

    function printOutput(item) {
        list += "<li>" + item + "</li>";
    }

    document.getElementById("results").innerHTML = list;
}

function clearResults() {
    results = [];
    document.getElementById("results").innerHTML = results;
}