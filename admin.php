<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['username'] !== "Admin") {
    header('Location: login.php');
    exit;
}

$servername = "localhost";
$username = "said";
$password = "99181513SAeed";
$dbname = "user";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST['name']);
    $details = htmlspecialchars($_POST['details']);
    $price = isset($_POST['price']) ? floatval($_POST['price']) : null;
    if ($price <= 0 || !is_numeric($price)) {
        echo "Error: Invalid price";
        exit();
    }
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo "Error: Image upload failed";
        exit();
    }
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploads/' . $image_name;
    if (!move_uploaded_file($image_tmp_name, $image_folder)) {
        echo "Error: Failed to move uploaded file";
        exit();
    }

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("INSERT INTO trips (name, details, price, image) VALUES (:name, :details, :price, :image)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':details', $details);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image', $image_folder);
        $stmt->execute();
        echo "<div class='success-message'>Tour added successfully</div>";

    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Tour - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            color: #36234d;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
        }

        .top-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #36234d; 
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000; 
        }

        .top-bar a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            margin: 0 15px; 
            font-family: 'Montserrat', sans-serif; 
            transition: color 0.3s ease;
        }

        .top-bar a:hover {
            color: #27ae60;
        }

        .container {
            padding: 20px;
            max-width: 400px;
            width: 100%;
            margin: 60px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1;
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #000000;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            color: #000;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #36234d;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 18px;
            display: block;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #27ae60;
        }

        .success-message {
            background-color: #27ae60;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
        }
    </style>
</head>

<body>
    <div class="top-bar">
        <a href="#">Add New Tour</a>
        <a href="admin_bookings.php">View bookings</a>
        <a href="admin_edit_trip.php">Edit Trips</a>
        <a href="login.php">Log out</a>
    </div>
    <div class="container">
        <h2>Add New Tour</h2>
        <?php
        if (isset($_SESSION['admin_welcome'])) {
            echo "<h1>" . $_SESSION['admin_welcome'] . "</h1>";
            unset($_SESSION['admin_welcome']); 
        }
        ?>
        <form method="post" action="" enctype="multipart/form-data">
            <label for="name">Tour Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="details">Tour Details:</label>
            <textarea id="details" name="details" rows="4" required></textarea>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" required>

            <label for="image">Upload Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <input type="submit" value="Save">
        </form>
    </div>
</body>

</html>















