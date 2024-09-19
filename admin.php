<?php

session_start();

// Load passwords from JSON file (if exists)
$passwords = json_decode(file_get_contents('passwords.json'), true) ?? [];

// Check if admin is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['username'] !== 'admin') {
    header('Location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        /* ... (Your CSS for gradient background, table styling, etc.) ... */
                body {
            font-family: sans-serif;
            background: linear-gradient(to right, #f0f0f0, #e0e0e0); /* Gradient background */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    
    </style>
</head>
<body>
    <h2>Admin Panel</h2>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Website</th>
                <th>Password</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($passwords as $username => $user_passwords): ?>
                <?php foreach ($user_passwords as $website => $hashed_password): ?>
                    <tr>
                        <td><?php echo $username; ?></td>
                        <td><?php echo $website; ?></td>
                        <td><?php echo $hashed_password; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="index.php?logout=true">Logout</a>
</body>
</html>
