/*
    This function allows for html file to be included in the body of the content html
*/
function includeHTML() {

    var z, i, elmnt, file, zhttp;
    z = document.getElementsByTagName("*");
    for ( i = 0; i < z.length;i++ ) {
        elmnt = z[i];
        file = elmnt.getAttribute("w3-include-html");
        if ( file ) {
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if ( this.readyState == 4 ) {
                    if ( this.status == 200 ) {elmnt.innerHTML = this.responseText}
                    if ( this.status == 400 ) {elmnt.innerHTML = "Page not found" }
                    elmnt.removeAttribute("w3-include-html");
                    includeHTML();
                } 
            }
            xhttp.open("GET", file, true);
            xhttp.send();
            return;
        }
    }
}

/*
    Perform search on pressing enter in the text box 
*/
function handleEnter(e) {
    if (e.keyCode === 13 ) {
        e.preventDefault();
        refreshData();
    }
}