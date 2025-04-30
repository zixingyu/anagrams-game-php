<!DOCTYPE html>
<html>
<head>
    <title>Anagrams - Welcome</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h1 {
            color: #343a40;
            font-size: 2.2rem;
            margin-bottom: 20px;
            font-weight: bold;
        }
        label {
            font-weight: bold;
            color: #495057;
            text-align: left;
            display: block;
            margin-bottom: 6px;
        }
        .form-control {
            border-radius: 8px;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ced4da;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075);
            transition: border-color 0.2s;
        }
        .form-control:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 8px rgba(143, 181, 221, 0.25);
        }
        .btn-primary {
            background-color:rgb(115, 174, 237);
            border-color:rgb(156, 174, 194);
            padding: 12px;
            font-size: 1rem;
            border-radius: 8px;
            width: 100%;
            font-weight: bold;
            transition: background-color 0.2s;
        }
        .btn-primary:hover {
            background-color:rgb(70, 84, 98);
            border-color:rgb(78, 99, 121);
        }
        .alert {
            margin-bottom: 20px;
            font-size: 1rem;
            padding: 12px;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Welcome to Anagrams!</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form action="?command=login" method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Start Game</button>
    </form>
</div>

</body>
</html>
