<?php
session_start();
require_once 'header.php';

if (isset($_POST['user'])) {
    $user = implode(",", $_POST);
    $fp = fsockopen("skywhale.science", 42101, $errno, $errstr, 30);
    if (!$fp) {
        echo "$errstr ($errno)<br />\n";
    } 
    else {
        fwrite($fp, "$user");
        fwrite($fp, "bye\n");
        while (!feof($fp)) {
            echo fgets($fp, 128);
        }
    fclose($fp);
    }
}
echo "<h3>Welcome to the the Music Database! </h3>";
echo "<div>";


echo <<<_END
    <form method='post' action='index.php'>$error
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
_END;
echo <<<_END
    </div><br>
_END;
require_once 'footer.php';
?>