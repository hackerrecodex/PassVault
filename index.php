<?php

session_start();

// Load passwords from JSON file (if exists)
$passwords = json_decode(file_get_contents('passwords.json'), true) ?? [];

// Security: This is a placeholder; use a proper password hashing library!
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT); // Example
}

// Handle login/logout
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (isset($passwords[$username]) && password_verify($password, $passwords[$username])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header('Location: index.php');
    } else {
        $error_msg = "Invalid username or password.";
    }
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
}

// Handle password addition
if (isset($_POST['add_password'])) {
    $username = $_SESSION['username'];
    $website = $_POST['website'];
    $password = $_POST['password'];

    $passwords[$username][$website] = hashPassword($password);

    // Save data to JSON file (VERY INSECURE; use a database in production)
    file_put_contents('passwords.json', json_encode($passwords, JSON_PRETTY_PRINT)); 

    $success_msg = "Password saved successfully.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Manager</title>
    <style>
        /* ... (Your CSS for gradient background, form styling, etc.) ... */
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

<?php if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']): ?>
    <h2>Login</h2>
    <?php if (isset($error_msg)): ?>
        <p style="color: red;"><?php echo $error_msg; ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit" name="login">Login</button>
    </form>
<?php else: ?>
    <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>

    <?php if (isset($success_msg)): ?>
        <p style="color: green;"><?php echo $success_msg; ?></p>
    <?php endif; ?>

    <?php if (isset($error_msg)): ?>
        <p style="color: red;"><?php echo $error_msg; ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="website">Website:</label>
        <input type="text" name="website" id="website" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit" name="add_password">Save Password</button>
    </form>

    <a href="index.php?logout=true">Logout</a>
<?php endif; ?>

</body>
</html>
