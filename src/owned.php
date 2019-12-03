<?php
session_start();
require_once 'header.php';
echo "<h3>Welcome to the the Music Database! </h3>";
echo "<div>";


if (isset($_POST['user'])) {
    $user = implode(",", $_POST);

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
    <form method='post' action='owned.php'>$error
        <div data-role='fieldcontain'>
            <label></label>
            <h3>Enter in Username and find what songs they have</h3>
        </div>
        <div data-role='fieldcontain'>
            <label>UserID<br></label>
            <input type='text' maxlength='16' name='user' value='$user1'>
            <label></label>
            <!-- <div id='used'>&nbsp;</div> -->
    
        </div>
        <br>
        <br>
        <div data-role='fieldcontain'>
            <label></label>
            <input data-transition='slide' type='submit' value='Check User'>
        </div>
    </form>
_END;
echo <<<_END
    </div><br>
_END;
require_once 'footer.php';
?>