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
            fwrite($fp, "similar_playlists,$user\n");
            $result = fgets($fp, 128);
            fwrite($fp, "bye\n");
            fclose($fp);
        }
    } 
    echo "<br>Here are your results!<br>";
    return $result;
}

echo <<<_END
    <form method='post' action='similar_playlists.php'>$error
        <div data-role='fieldcontain'>
            <label></label>
            <h3>Enter a SongID to see what playlists share that similar song!</h3>
        </div>
        <div data-role='fieldcontain'>
            <label>Enter in a SongID<br></label>
            <input type='text' maxlength='16' name='user' value='$user1'>
            <label></label>
            <!-- <div id='used'>&nbsp;</div> -->
    
        </div>
        <br>
        <p>Song examples to enter</p>
        <ul>
        <li>SongID: 667,  SongName: Chicken Nugget Piano </li>
        <li>SongID: 668,  SongName: Little Hat Gucci</li>
        <li>SongID: 669,  SongName: Top Hat Swagger</li>
        </ul>
        <br>
        <div data-role='fieldcontain'>
            <label></label>
            <input data-transition='slide' type='submit' value='Find Similar Playlists'>
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