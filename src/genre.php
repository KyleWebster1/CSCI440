<?php
session_start();
require_once 'header.php';


echo "<h3>Welcome to the the Music Database! </h3>";
echo "<div>";

function writeQuery(){
    if (isset($_POST['user'])) {
        $user = implode(",", $_POST);
        $fp = fsockopen("skywhale.science", 42101, $errno, $errstr, 30);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } 
        else {
            fwrite($fp, "genre,$user\n");
            $result = fgets($fp, 128);
            fwrite($fp, "bye\n");
            fclose($fp);
        }
    } 
    echo "<br>Here are your results!<br>";
    return $result;
}
echo <<<_END
    <form method='post' action='genre.php'>$error
        <div data-role='fieldcontain'>
            <label></label>
            <h3>Find Music Related to the Genre of your choice!</h3>
        </div>
        <div data-role='fieldcontain'>
            <label>Genre Type<br></label>
            <input type='text' maxlength='16' name='user' value='$genre'>
            <label></label>
            <!-- <div id='used'>&nbsp;</div> -->
    
        </div>
            
        </div>
        <br><br>
        <div data-role='fieldcontain'>
            <label></label>
            <input data-transition='slide' type='submit' value='Find'>
        </div>
    </form>
    <br>
_END;

$result = writeQuery();
$newResult = explode(",", $result);
for ($x =0; $x <= sizeof($newResult); $x++)
{
    echo "$newResult[$x]<br>";
}
echo <<<_END
    </div><br>
_END;
require_once 'footer.php';
?>