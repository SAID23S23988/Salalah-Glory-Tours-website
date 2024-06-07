<?php
session_start();
$host = 'localhost';
$dbname = 'user';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];
        if ($password !== $confirm_password) {
            echo "<script>alert('Passwords do not match.')</script>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Invalid email format.')</script>";
        } else {
        
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO user_info (username, email, password) VALUES (:username, :email, :password)";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);

            try {
                if ($stmt->execute()) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $username;
                    echo "<script>alert('Registration successful. Thank you, $username.'); window.location.href = 'login.php';</script>";
                } else {
                    echo "<script>alert('Registration failed.')</script>";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
    $conn = null;
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>









<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Register Form - CodeCraftedWeb</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="main">
        <div class="register">
            <form class="form" action="register.php" method="post">
                <label for="chk" aria-hidden="true">Register</label>
                <input class="input" type="text" name="username" placeholder="Username" required>
                <input class="input" type="email" name="email" placeholder="Email" required>
                <input class="input" type="password" name="password" placeholder="Password" required>
                <input class="input" type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit" name="register">Register</button>
            </form>
        </div>
    </div>
</div>

<style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Verdana, Geneva, Tahoma, sans-serif;
}

.container {
    width: 100%;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #fff;
}

.main {
    position: relative;
    display: flex;
    flex-direction: column;
    background-color: #240046;
    max-height: 450px;
    width: 400px;
    overflow: hidden;
    border-radius: 12px;
    box-shadow: rgba(59, 0, 130, 0.442) 0px 30px 90px;
}


.register {
    display: flex;
    flex-direction: column;
    gap: 14px;
    padding: 24px;
    justify-content: center;
    align-items: center;
    color: #fff; 
    text-align: center;
    font-size: 1.5rem; 

}

.input {
    width: 100%;
    height: 40px;
    font-size: 1rem;
    background: #e0dede;
    padding: 10px;
    margin-top: 15px;
    border: none;
    outline: none;
    border-radius: 4px;
}

button {
    width: 70%;
    height: 40px;
    margin: 15px auto 10%;
    color: #fff;
    background: #573b8a;
    font-size: 1rem;
    font-weight: bold;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: .2s ease-in;
}

button:hover {
    background-color: #6d44b8;
}

</style>
</body>
</html>







