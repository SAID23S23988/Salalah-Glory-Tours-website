<?php
session_start();

$servername = "localhost";
$username = "said";
$password = "99181513SAeed";
$dbname = "user";

$adminEmail = "ad@gmail.com";
$adminPassword = "Ad12345";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
        $email = $_POST["email"];
        $password = $_POST["password"]; 
        if ($email == $adminEmail && $password == $adminPassword) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = "Admin";
            $_SESSION['admin_welcome'] = "Welcome, Admin!";
            header('Location: admin.php');
            exit;
        }

        $sql = "SELECT * FROM user_info WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['username'];
            header('Location: HOME2.html');
            exit;
        } else {
            echo "<script>alert('Invalid email or password.')</script>";
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
        <input type="checkbox" id="chk" aria-hidden="true">

        <div class="login">
            <form class="form" action="login.php" method="post">
                <label for="chk" aria-hidden="true">Log in</label>
                <input class="input" type="email" name="email" placeholder="Email" required>
                <input class="input" type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Log in</button>
                <p class="register-text">Don't have an account? <a href="register.php" class="register-link">Register now</a></p>
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

    .form {
        display: flex;
        flex-direction: column;
        gap: 14px;
        padding: 24px;
    }


    #chk {
        display: none;
    }

   

    .login {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .login label {
        margin: 5% 0 5%;
    }

    label {
        color: #fff;
        font-size: 2rem;
        justify-content: center;
        display: flex;
        font-weight: bold;
        cursor: pointer;
        transition: .5s ease-in-out;
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

    

    .form button {
        width: 100%;
        height: 40px;
        margin: 10px 0;
        color: #fff;
        background: #573b8a;
        font-size: 1rem;
        font-weight: bold;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: .2s ease-in;
    }

    .form button:hover {
        background-color: #6d44b8;
    }

   
    .register-text {
        color: #fff;
        font-size: 0.9rem;
        text-align: center;
        margin-top: 15px;
    }

    .register-link {
        color: #fff;
        font-weight: bold;
        text-decoration: none;
        transition: .2s ease-in;
    }

    .register-link:hover {
        text-decoration: underline;
    }
    .register {
            background: #eee;
            border-radius: 60% / 10%;
            transition: .8s ease-in-out;
        }

            .register label {
                color: #573b8a;
                transform: scale(.6);
            }

        #chk:checked ~ .register {
            transform: translateY(-68%);
        }

            #chk:checked ~ .register label {
                transform: scale(1);
                margin-bottom: .5rem;
            }

        #chk:checked ~ .login label {
            transform: scale(.6);
        }

        .form button {
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

            .form button:hover {
                background-color: #6d44b8;
            }

 

</style>
</body>
</html>






