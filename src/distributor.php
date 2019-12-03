<?php
session_start();
require_once 'header.php';
echo "<h3>Welcome to the the Music Database! </h3>";
echo "<div>";


if (isset($_POST['user'])) {
    $user = implode("", $_POST);

    $fp = fsockopen("skywhale.science", 42101, $errno, $errstr, 30);
    if (!$fp) {
        echo "$errstr ($errno)<br />\n";
    } 
    else {
        fwrite($fp, "$user\n");
        fwrite($fp, "bye\n");
        while (!feof($fp)) {
            echo fgets($fp, 128);
        }
    fclose($fp);
    }
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
        <li>SongID: 667,  SongName: Chicken Nugget Piano </li>
        <li>SongID: 668,  SongName: Little Hat Gucci</li>
        <li>SongID: 669,  SongName: Top Hat Swagger</li>
        </ul>
        <br>
        <br>
        <div data-role='fieldcontain'>
            <label></label>
            <input data-transition='slide' type='submit' value='Check Distributor'>
        </div>
    </form>
_END;
echo <<<_END
    </div><br>
_END;
require_once 'footer.php';
?>