# Anagrams Game

This project is a full-featured Anagrams word game implemented in PHP.

## Overview

Players enter their name, email, and password to log in. They are shown 7 shuffled letters from a randomly selected 7-letter word and must guess valid shorter words using only those letters. The game ends when the full 7-letter word is guessed.

## Key Features

- **Session-based Login & Game State**
  - Welcome screen with name/email input
  - Game and Game Over screens
  - Word validation using `words7.txt` and `word_bank.json`
  - Scoring based on word length
  - PHP session tracking and reshuffling

- **Persistent User & Game Stats with PostgreSQL**
  - User accounts stored in database (name, email, hashed password)
  - Prevents reuse of previously played target words
  - Tracks and displays:
    - Total games played
    - Win percentage
    - Highest score
    - Average score

## Technologies

- PHP (OOP)
- PostgreSQL
- HTML/CSS
- Sessions & form handling
- JSON file parsing
- Secure password authentication
