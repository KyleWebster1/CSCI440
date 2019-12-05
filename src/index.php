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
            fwrite($fp, "add_user,$user\n");
            $result = fgets($fp, 128);
            fwrite($fp, "bye\n");
            fclose($fp);
        }
    } 
    return $result;
}
echo <<<_END
    <form method='post' action='index.php'>$error
        <div data-role='fieldcontain'>
            <label></label>
            <h3>Create username for database</h3>
        </div>
        <div data-role='fieldcontain'>
            <label>Create User Name<br></label>
            <input type='text' maxlength='50' name='user' value='$user'>
            <label></label>
            <!-- <div id='used'>&nbsp;</div> -->
    
        </div>
        <br><br>
        <div data-role='fieldcontain'>
            <label>First Name<br></label>
            <input type='text' maxlength='50' name='first' value='$first'>
            
        </div>
        <br><br>
        <div data-role='fieldcontain'>
            <label>Last Name<br></label>
            <input type='text' maxlength='50' name='last' value='$last'>
            
        </div>
        <br><br>
        <div data-role='fieldcontain'>
            <label>Email<br></label>
            <input type='text' maxlength='50' name='email' value='$email'>
            
        </div>
        <br><br>
        <div data-role='fieldcontain'>
            <label></label>
            <input data-transition='slide' type='submit' value='Add User'>
        </div>
    </form>
_END;

$result = writeQuery();

echo $result;

echo <<<_END
    </div><br>
_END;
require_once 'footer.php';
?>