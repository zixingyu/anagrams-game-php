<?php
// setup.php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require __DIR__ . "/Config.php";
require __DIR__ . "/Database.php";
try {
    $db = new Database();
    
    $db->query("DROP TABLE IF EXISTS hw6_user_words, hw6_words, hw6_users CASCADE");

// Table: hw6_users
// Stores user credentials and statistics
$db->query("CREATE TABLE hw6_users (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    total_games INT DEFAULT 0,
    games_won INT DEFAULT 0,
    highest_score INT DEFAULT 0,
    total_score BIGINT DEFAULT 0
)");

// Table: hw6_words
// Stores all available 7-letter target words
$db->query("CREATE TABLE hw6_words (
    id SERIAL PRIMARY KEY,
    word TEXT UNIQUE NOT NULL
)");

// Table: hw6_user_words
// Link table between users and words they’ve played
$db->query("CREATE TABLE hw6_user_words (
    user_id INT REFERENCES hw6_users(id), -- FK to hw6_users.id
    word_id INT REFERENCES hw6_words(id), -- FK to hw6_words.id
    won BOOLEAN, -- Whether the user won the game (guessed full word)
    score INT,  -- Score earned in that game
    PRIMARY KEY (user_id, word_id) -- Composite key to ensure unique pairing
)");

    echo "Database tables created successfully!";
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}