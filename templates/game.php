<!DOCTYPE html>
<html>
<head>
    <title>Anagrams Game</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 40px;
            max-width: 600px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1, h3 {
            color: #343a40;
        }
        .letters-container {
            font-size: 2.5em;
            letter-spacing: 0.1em;
            margin-bottom: 30px;
            text-align: center;
        }
        .btn-submit {
            background-color:rgb(70, 139, 212);
            color: #fff;
            border-color:rgb(100, 160, 223);
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 8px;
            width: 100%;
            transition: background-color 0.2s;
            font-weight: bold;
        }
        .btn-submit:hover {
            background-color:rgb(94, 159, 228);
            border-color:rgb(114, 168, 226);
        }
        .btn-shuffle {
            background-color:rgb(79, 186, 104);
            color: #fff;
            border-color: #28a745;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 8px;
            flex: 1;
            margin-right: 5px;
            font-weight: bold;
        }
        .btn-shuffle:hover {
            background-color: #218838;
            border-color:rgb(47, 154, 70);
        }
        .btn-quit {
            background-color:rgb(250, 215, 119);
            color: #fff;
            border-color:rgb(225, 225, 115);
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 8px;
            flex: 1;
            margin-left: 5px;
            font-weight: bold;
        }
        .btn-quit:hover {
            background-color:rgb(202, 183, 89);
            border-color:rgb(203, 212, 102);
        }
        .flex-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
        .alert {
            margin-top: 15px;
        }
        .list-group-item {
            font-size: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            color: #343a40;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Welcome, <?= htmlspecialchars($user['name']) ?></h1>
    <h3>Score: <?= $game['score'] ?></h3>
    <p>Email: <?= htmlspecialchars($user['email']) ?></p>
    
    <div class="instructions mb-3">
        <p>Rearrange the letters above to form valid words. Guessing the 7-letter target word will win the game!</p>
        
    </div>

    <div class="letters-container">
        <?= implode(' ', $game['letters']) ?>
    </div>

    <form action="?command=guess" method="post">
        <div class="input-group">
            <input type="text" name="guess" class="form-control" placeholder="Enter your guess" autocomplete="off" required>
            <div class="input-group-append">
                <button type="submit" class="btn btn-submit">Submit Guess</button>
            </div>
        </div>

        <div class="flex-buttons">
            <a href="?command=shuffle" class="btn btn-shuffle">Shuffle</a>
            <a href="?command=gameover" class="btn btn-quit">Quit</a>
        </div>
    </form>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="stats mb-4 p-3 bg-light rounded">
    <h4>Your Statistics</h4>
    <div class="row">
        <div class="col-6">Total Games: <?= $_SESSION['anagrams_game']['stats']['total_games'] ?? 0 ?></div>
        <div class="col-6">Win Rate: <?= $_SESSION['anagrams_game']['stats']['win_rate'] ?? 0 ?>%</div>
        <div class="col-6">High Score: <?= $_SESSION['anagrams_game']['stats']['highest_score'] ?? 0 ?></div>
        <div class="col-6">Avg Score: <?= $_SESSION['anagrams_game']['stats']['avg_score'] ?? 0 ?></div>
    </div>
    
    <div class="stats mb-4 p-3 bg-light rounded">
        <h3>Guessed Words:</h3>
        <ul class="list-group">
            <?php foreach ($game['guessedWords'] as $word): ?>
                <li class="list-group-item"><?= htmlspecialchars($word) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

</body>
</html>

