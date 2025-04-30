<?php
class AnagramsGameController {
    private $input;
    private $sessionKey = 'anagrams_game';

    public function __construct($get, $post) {
        $this->input = array_merge($get, $post);
        session_start();
        $this->db = new Database(); 
    }

    public function run() {
        $command = "welcome";
        if (isset($this->input["command"])) {
            $command = $this->input["command"];
        }

        if ($command != "welcome" && $command != "login" && !$this->validSession()) {
            $command = "welcome";
        }

        switch($command) {
            case "login":
                $this->handleLogin();
                break;
            case "play":
                $this->showGame();
                break;
            case "guess":
                $this->processGuess();
                break;
            case "shuffle":
                $this->shuffleLetters();
                break;
            case "quit":
                $this->endGame();
                break;
            case "playagain":
                $this->resetGame();
                break;
            case "gameover":
                $this->showGameOver();
                break;
            default:
                $this->showWelcome();
        }
    }

    private function validSession() {
        return isset($_SESSION[$this->sessionKey]['user']);
    }

    private function handleLogin() {
        if (empty($this->input['name']) || empty($this->input['email']) || empty($this->input['password'])) {
            $_SESSION['error'] = "All fields required";
            $this->showWelcome();
            return;
        }
    
        $email = $this->input['email'];
        $password = $this->input['password'];
        $name = $this->input['name'];
    
        // Check existing user
        $user = $this->db->query("SELECT * FROM hw6_users WHERE email = $1", $email);
    
        if (empty($user)) {
            // Register new user
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $this->db->query(
                "INSERT INTO hw6_users (name, email, password) VALUES ($1, $2, $3)",
                $name, $email, $hash
            );
            $user = $this->db->query("SELECT * FROM hw6_users WHERE email = $1", $email);
        } else {
            // Verify password
            if (!password_verify($password, $user[0]['password'])) {
                $_SESSION['error'] = "Invalid credentials";
                $this->showWelcome();
                return;
            }
        }
    
        // Initialize session
        $_SESSION[$this->sessionKey] = [
            'user' => [
                'id' => $user[0]['id'],
                'name' => $user[0]['name'],
                'email' => $user[0]['email']
            ],
            'game' => $this->initializeGame($user[0]['id'])
        ];
    
        header("Location: ?command=play");
        exit();
    }

    private function initializeGame($userId) {
        // Get unused word
        $word = $this->db->query("
            SELECT w.* FROM hw6_words w
            WHERE NOT EXISTS (
                SELECT 1 FROM hw6_user_words 
                WHERE user_id = $1 AND word_id = w.id
            ) ORDER BY random() LIMIT 1
        ", $userId);
    
        if (empty($word)) die("No available words");
    
        // Track word usage
        $this->db->query("
            INSERT INTO hw6_user_words (user_id, word_id) 
            VALUES ($1, $2)
        ", $userId, $word[0]['id']);
    
        // Shuffle letters
        $letters = str_split(strtoupper($word[0]['word']));
        shuffle($letters);
    
        return [
            'targetWord' => strtoupper($word[0]['word']),
            'letters' => $letters,
            'score' => 0,
            'guessedWords' => [],
            'invalidAttempts' => 0,
            'wordId' => $word[0]['id']
        ];
    }

    private function showWelcome() {
        include("templates/welcome.php");
    }

    private function showGame() {
        $userId = $_SESSION[$this->sessionKey]['user']['id'];
    
        $stats = $this->db->query("
            SELECT 
                total_games,
                ROUND((games_won::float / NULLIF(total_games, 0) * 100)::numeric, 2) AS win_rate,
                highest_score,
                ROUND((total_score::float / NULLIF(total_games, 0))::numeric, 2) AS avg_score
            FROM hw6_users 
            WHERE id = $1
        ", $userId);
    
        if ($stats && isset($stats[0])) {
            $_SESSION[$this->sessionKey]['stats'] = $stats[0];
        } else {
            error_log(" Failed to fetch stats in showGame() for user $userId");
            $_SESSION[$this->sessionKey]['stats'] = [
                'total_games' => 0,
                'win_rate' => 0,
                'highest_score' => 0,
                'avg_score' => 0
            ];
        }
    
        $game = $_SESSION[$this->sessionKey]['game'];
        $user = $_SESSION[$this->sessionKey]['user'];
        include("templates/game.php");
    }
    
    private function processGuess() {
        $guess = strtoupper(trim($this->input['guess'] ?? ''));
        $game = &$_SESSION[$this->sessionKey]['game'];
        // duplicate guess
        if (in_array($guess, $game['guessedWords'])) {
            $_SESSION['error'] = "You already guessed this word!";
            header("Location: ?command=play");
            exit();
        }
        //invalid guess
        if (!$this->isValidGuess($guess, $game['targetWord'])) {
            $game['invalidAttempts']++;
            $_SESSION['error'] = "Used disallowed letters!";
            header("Location: ?command=play");
            exit();
        }
        // whether exist in the bank
        if (!$this->isValidWord($guess)) {
            $game['invalidAttempts']++;
            $_SESSION['error'] = "Not a valid word!";
            header("Location: ?command=play");
            exit();
        }

        $game['guessedWords'][] = $guess;
        $game['score'] += $this->calculateScore(strlen($guess));

        if (strlen($guess) === 7) {
            header("Location: ?command=gameover");
            exit();
        }

        header("Location: ?command=play");
        exit();
    }

    private function isValidGuess($guess, $targetWord) {
        $guessCounts = array_count_values(str_split($guess));
        $targetCounts = array_count_values(str_split($targetWord));

        foreach ($guessCounts as $char => $count) {
            if (!isset($targetCounts[$char]) || $count > $targetCounts[$char]) {
                return false;
            }
        }
        return true;
    }

    private function isValidWord($guess) {
        $length = strlen($guess);
        $word = strtolower($guess);
    
        if ($length === 7) {
            $wordsPath = file_exists('words7.txt') ? 'words7.txt' : '/var/www/html/homework/words7.txt';
            if (!file_exists($wordsPath)) {
                error_log("Word list not found: $wordsPath");
                return false;
            }
            $words = file($wordsPath, FILE_IGNORE_NEW_LINES);
            $words = array_map('strtoupper', $words);
            return in_array($guess, $words);
        } else {
            $wordBankPath = file_exists('word_bank.json') ? 'word_bank.json' : '/var/www/html/homework/word_bank.json';
            if (!file_exists($wordBankPath)) {
                error_log("Word bank not found: $wordBankPath");
                return false;
            }
            $wordBank = json_decode(file_get_contents($wordBankPath), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log("Invalid JSON in word bank: " . json_last_error_msg());
                return false;
            }
            return isset($wordBank[$length]) && in_array($word, $wordBank[$length]);
        }
    }

    private function calculateScore($length) {
        switch ($length) {
            case 1: return 1;
            case 2: return 2;
            case 3: return 4;
            case 4: return 8;
            case 5: return 15;
            case 6: return 30;
            default: return 0;
        }
    }

    private function shuffleLetters() {
        $game = &$_SESSION[$this->sessionKey]['game'];
        shuffle($game['letters']);
        header("Location: ?command=play");
        exit();
    }

   
    private function endGame() {
        $userId = $_SESSION[$this->sessionKey]['user']['id'];
        $game = $_SESSION[$this->sessionKey]['game'] ?? null;
    
        if ($game) {
            $won = false; 
            if (!is_bool($won)) {
                error_log("⚠️ Invalid value for \$won in endGame: " . var_export($won, true));
            }
    
            $this->db->query("
                UPDATE hw6_users SET
                    total_games = total_games + 1,
                    highest_score = GREATEST(highest_score, $1),
                    total_score = total_score + $1
                WHERE id = $2
            ", $game['score'], $userId);
    
            $this->db->query("
                UPDATE hw6_user_words SET
                    won = $1, score = $2
                WHERE user_id = $3 AND word_id = $4
            ", $won, $game['score'], $userId, $game['wordId']);
        }
    
        unset($_SESSION[$this->sessionKey]);
        header("Location: ?command=welcome");
        exit();
    }
    

    private function resetGame() {
        //$_SESSION[$this->sessionKey]['game'] = $this->initializeGame();
        $userId = $_SESSION[$this->sessionKey]['user']['id'];
        $_SESSION[$this->sessionKey]['game'] = $this->initializeGame($userId);

        header("Location: ?command=play");
        exit();
    }

    private function showGameOver() {
        $userId = $_SESSION[$this->sessionKey]['user']['id'];
        $game = $_SESSION[$this->sessionKey]['game'] ?? null;
    
        if (!$game || !isset($game['targetWord'], $game['guessedWords'], $game['score'], $game['wordId'])) {
            error_log("Incomplete game data in showGameOver. Skipping stats update.");
            include("templates/gameover.php");
            return;
        }
    
        $won = in_array($game['targetWord'], $game['guessedWords'], true) ? true : false;
    
        if (!is_bool($won)) {
            error_log("$won is NOT a boolean! Value: " . var_export($won, true));
            $won = false;
        }
    
        $result = $this->db->query("
            UPDATE hw6_users SET
                total_games = total_games + 1,
                games_won = games_won + $1,
                highest_score = GREATEST(highest_score, $2),
                total_score = total_score + $2
            WHERE id = $3
        ", $won ? 1 : 0, $game['score'], $userId);
    
        if (!$result) {
            error_log("Failed to update hw6_users stats in showGameOver() for user $userId");
        }
    
        $result2 = $this->db->query("
            UPDATE hw6_user_words SET
                won = $1, score = $2
            WHERE user_id = $3 AND word_id = $4
        ", $won ? 't' : 'f', $game['score'], $userId, $game['wordId']);  
    
        if (!$result2) {
            error_log(" Failed to update hw6_user_words in showGameOver() for user $userId. Wrote: won=" . var_export($won, true));
        }
    
        $stats = $this->db->query("
            SELECT 
                total_games,
                ROUND((games_won::float / NULLIF(total_games, 0) * 100)::numeric, 2) AS win_rate,
                highest_score,
                ROUND((total_score::float / NULLIF(total_games, 0))::numeric, 2) AS avg_score
            FROM hw6_users 
            WHERE id = $1
        ", $userId);
    
        $_SESSION[$this->sessionKey]['stats'] = $stats[0] ?? [
            'total_games' => 0,
            'win_rate' => 0,
            'highest_score' => 0,
            'avg_score' => 0
        ];
    
        include("templates/gameover.php");
    }
    
    
    
    
    
}