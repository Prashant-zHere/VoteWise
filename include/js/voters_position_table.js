function filterVoters() {
    var key = document.getElementById("voterSearch").value;
    console.log(key);
    var xml = new XMLHttpRequest();
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            console.log("Filtering voters...");
            document.getElementById("display").innerHTML = xml.responseText;
        }
    };
    xml.open("GET", "pages/votersList.php?key="+key);
    xml.send();
}


function filterPosition() {
    var key = document.getElementById("positionSearch").value;
    console.log(key);

    var xml = new XMLHttpRequest();
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            document.getElementById("display").innerHTML = xml.responseText;
        }
    };
    xml.open("GET", "pages/positionList.php?key="+key);
    xml.send();
}