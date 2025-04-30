<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require __DIR__ . "/Config.php";
require __DIR__ . "/Database.php";
$db = new Database();

$words = file('words7.txt', FILE_IGNORE_NEW_LINES);

foreach ($words as $word) {
    $cleanWord = trim(strtoupper($word));
    $db->query("INSERT INTO hw6_words (word) VALUES ($1)", $cleanWord);
}

echo "Added " . count($words) . " words to database!";