<?php
/*
    This script file contains the main search logic to find text in all OpenAPI description files
    
    In order for this routine ro find text, the content type myust be defiued as 'ICC OpenAPI', and
    a Swagger OopenAPI description file (.json, .yml, .yaml) must be assigned to the content page.
    
*/

/*

TO DO List
1. Create proper username and password for database

*/
/*

    The following paramters set up the connecytion to the MySQL database
    
*/
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "drupal8";
$searchfor = $_POST["fname"];

// Create connection and check
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn -> connect_error) {
    die ("Connection failed: " . $conn ->connect_error);
}

/*
    Main SQL that gets the firlenames of the OpenAPI descriptor files
    from the MySQL database
*/
$sql = "select concat('http://localhost/drupal/sites/default/files/', substring(uri,10)) as pathfile, "
    ."substr(uri, 10) as filename, title, type from file_managed "
    ."left join node_field_data on fid = nid";

//execute query
$result = $conn->query($sql);

//iterate through each row returned from the database
//Each row represents an OpenAPI specification document
$found = false;
$errCount = 0;
$errContents = [];
$contents;
echo "<p>&nbsp;</p>";
while ($row = $result -> fetch_assoc() ) {

    //get the contents of the OpenAPI spec and setup pattern matching
    $contents = @file_get_contents($row["pathfile"]);
    if ( $contents === false ) {
        addError($row);
    }
    else {
        
        //search through the OpenAPI spec for search criteria
        $pattern = preg_quote($searchfor,'/');
        $pattern = "/^.*$pattern.*\$/mi";
        if ( preg_match_all($pattern, $contents, $matches) ) {
            $found = true;
            processTitle($row);
            processContentResults($row, $matches[0]);
        }
    }
}

if ( $errCount > 0 ) {
    processErrors($errContents);
}

//do this if the requested search text doesn't exist
if (!$found) {
    echo "<h2>Your search yielded no results.</h2><br>";
}

//clean up (close) database connection
$conn -> close();


function addError($row) {
    global $errContents, $errCount;
    
    $ext = substr($row["pathfile"], strrpos($row["pathfile"],".") );
    $errMsg = "<tr><td><p class='errRow'>". $row['title']
        ."  ( ".$row['type']." )   Path: ".$row['filename']
        ."</p></td><td><p class='errRow'>"."Cannot process files of type "
        .$ext."</p></td></tr>";
    $errContents[$errCount++] = $errMsg;
}

function processTitle($row) {
    $title = $row['title']."  ( ".$row['type']." )   Path: ".$row['filename'];
    echo "<table><tr><td valign='bottom' style='width: 850px;' >";
    echo "<p class='padded'>".$title."</p></td></tr></table>";
}


//create html for list of found search results in the file
//and replace found text with highlighted (<mark>) text
//with an id
function processContentResults($row, $matches) {
    global $contents;
    
    $tid = $row["filename"];
    $tpos = strrpos($tid, "/") + 1;
    $contentsId = "node-".substr($tid, $tpos, strlen($tid) - $tpos);

    echo "<table><tr><td><div class='divFoundBox'>";
    echo "<table><tr><td><ul>";
    
    $i = 0;
    $findOffset = 0;
    foreach ($matches as $value ) {

        $i++;
        $lineId = $contentsId."-".$i."-line";
        $markId = $contentsId."-".$i."-mark";
        $subValue = "<mark id='".$markId."'>".$value."</mark>";
        $pos = strpos($contents, $value, $findOffset);
        $contents = substr_replace($contents, $subValue, $pos, strlen($value));
        $findOffset = $pos + strlen($subValue);
        echo "<li><a href='#' id='".$lineId."' onclick='scrollData("."&#039;".$markId."&#039;".","."&#039;".$contentsId."&#039;".")'>".$value."</a></li>";

    }
    echo "</ul></td></table></div></td>";
                   
    //create a space for the OpenAPI descriptor file
    echo '<td style="margin: 0px;"><div id="'.$contentsId.'" class="divFileContents"><pre>';
    echo $contents;
    echo "</pre></div></td><tr height=20></tr></tr></table>";

}


function processErrors($errContents) {
    echo "<table>"
        ."<tr><td valign='bottom' style='width: 850px;' >"
        ."<h3 class='padded'>WARNINGS</h3></tr></table><table>";
    foreach ($errContents as $errMsg ) {
        echo $errMsg;
    }
    echo "</table>";
}


?>