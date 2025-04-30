<?php
// Here is the link to the published version of game
// https://cs4640.cs.virginia.edu/hbv6pz/hw6
error_reporting(E_ALL);
ini_set("display_errors", 1);
spl_autoload_register(function ($classname) {
    //include "/opt/src/trivia/$classname.php";
    include __DIR__ . "/src/$classname.php";
    require __DIR__ . "/Config.php";
    require __DIR__ . "/Database.php";
});

$game = new AnagramsGameController($_GET, $_POST);
$game->run();
