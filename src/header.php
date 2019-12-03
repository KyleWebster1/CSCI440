<?php
session_start();

echo <<<_INIT
<!DOCTYPE html> 
<html>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'> 
        <script src='javascript.js'></script>
        <link href="https://fonts.googleapis.com/css?family=Arsenal|Lora|Muli|Source+Sans+Pro|Playfair+Display&display=swap" rel="stylesheet">
        <link rel='stylesheet' href='css/styles.css'>
        <title>Mash Potato Query</title>
        </head>
_INIT;

echo <<<_HEADER_OPEN
    
    <body>
        <header>
            <div id='logo'>Mash Potato Query Group</div>
_HEADER_OPEN;

echo <<<_GUEST

            <nav><ul>
            <li><a href='index.php'>Create User ID</a></li>
                <li><a href='purchase.php'>Purchase a Song</a></li>
                <li><a href='genre.php'>Genre Selection</a></li>
                <li><a href='owned.php'>Songs Owned by User</a></li>
                <li><a href='similar_playlists.php'>Similar Playlists</a></li>
                <li><a href='distributor.php'>Distibutor Find</a></li>
            </ul></nav>
_GUEST;


echo <<<_HEADER_CLOSE

        </header>
        <div id="content">
_HEADER_CLOSE;

?>

