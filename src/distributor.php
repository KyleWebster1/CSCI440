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
            fwrite($fp, "distributor,$user\n");
            $result = fgets($fp, 128);
            fwrite($fp, "bye\n");
            fclose($fp);
        }
    } 
    echo "<br>Here are your results!<br>";
    return $result;
}

echo <<<_END
    <form method='post' action='distributor.php'>$error
        <div data-role='fieldcontain'>
            <label></label>
            <h3>Enter in a SongID to find who it's distributed by!!</h3>
        </div>
        <div data-role='fieldcontain'>
            <label>Please enter in SongID<br></label>
            <input type='text' maxlength='16' name='user' value='$user1'>
            <label></label>
            <!-- <div id='used'>&nbsp;</div> -->

        </div>
        <br><br>
        <p>Song examples to enter</p>
        <ul>
        <li>Song example: SongID: 12345678 Name: Example</li>
        <li>Song example: SongID: 4567890876 Name: Temp</li>
        <li>Song example: SongID: 23456798  Name: Example</li>
        </ul>
        <br>
        <br>
        <div data-role='fieldcontain'>
            <label></label>
            <input data-transition='slide' type='submit' value='Check Distributor'>
        </div>
    </form>
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