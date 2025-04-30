<!DOCTYPE html>
<html>
<head>
    <title>Game Over</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        h1 {
            color: #343a40;
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: bold;
        }
        p {
            font-size: 1.2rem;
            color: #495057;
            margin-bottom: 15px;
        }
        .btn {
            padding: 12px 24px;
            font-size: 1rem;
            border-radius: 8px;
            width: 48%; 
            font-weight: bold;
        }
        .btn-primary {
            background-color:rgb(92, 155, 222);
            border-color:rgb(134, 174, 216);
            transition: background-color 0.2s;
        }
        .btn-primary:hover {
            background-color:rgb(147, 185, 226);
            border-color:rgb(117, 158, 202);
        }
        .btn-danger {
            background-color:rgb(254, 54, 74);
            border-color:rgb(221, 111, 122);
            transition: background-color 0.2s;
        }
        .btn-danger:hover {
            background-color:rgb(219, 116, 126);
            border-color:rgb(234, 101, 114);
        }
        .btn-group {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 20px;
        }
        .list-group {
            text-align: left;
            max-height: 150px;
            overflow-y: auto;
            margin-bottom: 20px;
        }
        .list-group-item {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #495057;
            font-size: 1rem;
        }
    </style>
</head>
<body>

<div class="container">
    
    <h1>Game Over!</h1>
    <p><strong>Final Score:</strong> <?= $_SESSION['anagrams_game']['game']['score'] ?></p>

    <p><strong>Guessed Words:</strong></p>
    <ul class="list-group">
        <?php foreach ($_SESSION['anagrams_game']['game']['guessedWords'] as $word): ?>
            <li class="list-group-item"><?= htmlspecialchars($word) ?></li>
        <?php endforeach; ?>
    </ul>

    <p><strong>Invalid Attempts:</strong> <?= $_SESSION['anagrams_game']['game']['invalidAttempts'] ?></p>

    <div class="btn-group">
        <a href="?command=playagain" class="btn btn-primary">Play Again</a>
        <a href="?command=quit" class="btn btn-danger">Exit</a>
    </div>
    
    <div class="stats mb-4 p-3 bg-light rounded">
    <h4>Your Updated Statistics</h4>
    <div class="row">
        <div class="col-6">Total Games: <?= $_SESSION['anagrams_game']['stats']['total_games'] ?? 0 ?></div>
        <div class="col-6">Win Rate: <?= $_SESSION['anagrams_game']['stats']['win_rate'] ?? 0 ?>%</div>
        <div class="col-6">High Score: <?= $_SESSION['anagrams_game']['stats']['highest_score'] ?? 0 ?></div>
        <div class="col-6">Avg Score: <?= $_SESSION['anagrams_game']['stats']['avg_score'] ?? 0 ?></div>
    </div>
</div>

</div>

</body>
</html>
