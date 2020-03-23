/*
    Attributes needed for loading data
*/
var formatPath = "/scripts/raw/";
var running_local = false;

/*
    This function creates an AJAX request to the PHP script (on the server) that
    processes the search request
*/

function refreshData() {
    
    //the server name is not recognized locally (running a browser from the vm),
    //so change it to localhost
    if ( location.hostname == 'localhost' ) running_local = true;
    
    //ajax call to get data
    var fname = document.getElementById("fname").value;
    var display = document.getElementById("divcontent");

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", "/scripts/FileProcessor.php");
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded",
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
    );
    xmlhttp.send("fname=" + fname);
    xmlhttp.onreadystatechange = function() {
        if (this.readyState === 4 & this .status === 200 ) {
//            console.log(this.responseText);
            display.innerHTML = this.responseText;
                //(running_local)?this.responseText.replace('https://api.desb-bsed.dev.global.gc.ca','http://localhost'):this.responseText;
        }
        else {
            display.innerHTML = this.readyState + " Loading..." + this.status;
//            display.innerHTML = this.responseText;
        }
    }
}

//toggles the format between formatted and raw
function toggleBox() {
    var sw = document.getElementById('switchview');
    var xtext = document.getElementById('toggler');
    if ( sw.checked == null || !sw.checked ) {
        xtext.setAttribute('checked', 'false');
        xtext.style.color = 'gray';
        formatPath = '/scripts/raw/';
    }
    else {
        xtext.style.color = 'black';
        formatPath = '/scripts/formatted/';
    }
}

//this is what happend when a cuser click a node
function treeClick(id) {
    var tlr = document.getElementById(id);
    tlr.querySelector(".nested").classList.toggle("activeToggle");
    tlr.classList.toggle("caret-down");
}
