<?php
session_start();
require_once 'header.php';

function writeQuery(){
    if (isset($_POST['user'])) {
        $user = implode(",", $_POST);
        $fp = fsockopen("skywhale.science", 42101, $errno, $errstr, 30);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } 
        else {
            fwrite($fp, "purchase,$user\n");
            $result = fgets($fp, 128);
            fwrite($fp, "bye\n");
            fclose($fp);
        }
    }
    return $result;
}

echo "<h3>Welcome to the the Music Database! </h3>";
echo "<div>";


echo <<<_END
    <form method='post' action='purchase.php'>$error
        <div data-role='fieldcontain'>
            <label></label>
            <h3>Purchase a song for your ID (free of charge!!)</h3>
        </div>
        <div data-role='fieldcontain'>
            <label>User ID<br></label>
            <input type='text' maxlength='16' name='user' value='$user1'>
            <label></label>
            <!-- <div id='used'>&nbsp;</div> -->
    
        </div>

        <br><br>
        <div data-role='fieldcontain'>
            <label>Song ID<br></label>
            <input type='text' maxlength='16' name='song' value='$song'>
            <br><br>
            <label>Song example: SongID: 0987654q34567 Name: Jams</label> <br>
            <label>Song example: SongID: 4567890876 Name: Temp</label> 
            
        </div>
        <br><br>
        <div data-role='fieldcontain'>
            <label></label>
            <input data-transition='slide' type='submit' value='Purchase'>
        </div>
    </form>
    <br>
_END;

$result = writeQuery();
echo $result;

echo <<<_END
    </div><br>
_END;
require_once 'footer.php';
?>