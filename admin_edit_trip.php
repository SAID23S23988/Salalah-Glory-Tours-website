<?php
try {
    $servername = "localhost";
    $username = "said";
    $password = "99181513SAeed";
    $dbname = "user";

    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->query("SELECT * FROM trips");
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guide Around Tours</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Allura&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Volkhov&display=swap">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    

    <style>
       body {
    margin: 0;
    padding: 0;
    font-family: 'Roboto', sans-serif;
    color: #ffffff;
    background-color: #ffffff;
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
    margin: 0 20px;
    font-family: 'Montserrat', sans-serif;
    transition: color 0.3s ease;
}

.top-bar a:hover {
    color: #27ae60;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    position: relative;
    background-color: #f2f2f2;
    border-bottom: 1px solid #ccc;
}

.container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    padding: 20px;
    margin-top: 100px; 
}

.tour {
    position: relative;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    margin: 20px;
    width: calc(30% - 40px);
    transition: transform 0.3s ease;
    color: #444;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.tour:hover {
    transform: scale(1.05);
}

.tour-title {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #4e4a8e;
    font-family: 'Georgia', serif;
}

.tour-info {
    margin-bottom: 10px;
    font-family: 'Times New Roman', serif;
    flex-grow: 1;
}

.tour-price {
    font-size: 20px;
    font-weight: bold;
    color: #ff0000;
    margin-bottom: 10px;
    font-family: 'Cambria', serif;
    margin-top: 10px;
}

.edit-button,
.delete-button {
    display: block;
    width: 100%;
    background-color: #36234d;
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    margin-top: 10px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    text-decoration: none;
    text-align: center;
}

.edit-button:hover,
.delete-button:hover {
    background-color: #219653;
}

.delete-button {
    background-color: #e74c3c;
}

.delete-button:hover {
    background-color: #c0392b;
}

.tour-image {
    max-width: 100%;
    border-radius: 10px;
    margin-bottom: 10px;
}

.discount-image {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 1;
    opacity: 0.8;
    width: 80px;
}

.title {
    text-align: center;
    margin-bottom: 40px; 
    font-size: 30px; 
    font-weight: bold;
    color: #000000;
    font-family: 'Baskerville Old Face', serif;
    width: 100%;
    position: relative;
    top: 100px; 
}

    </style>
</head>
<body>
<div class="top-bar">
        <a href="admin.php">Add New Tour</a>
        <a href="admin_bookings.php">View bookings</a>
        <a href="admin_edit_trip.php">Edit Trips</a>
        <a href="login.php">Log out</a>
    </div>
    <div class="title"> EXPLORE OUR TRIPS</div>
    <div class="container">

        <?php
        try {
            $servername = "localhost";
            $username = "said";
            $password = "99181513SAeed";
            $dbname = "user";

            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->query("SELECT * FROM trips");

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='tour'>";
                echo "<img class='tour-image' src='" . $row['image'] . "' alt='" . $row['name'] . "'>";
                echo "<div class='tour-title'>" . $row['name'] . "</div>";
                echo "<div class='tour-info'>" . $row['details'] . "</div>";
                echo "<div class='tour-price'>Price: $" . $row
                ['price'] . "</div>";
                echo "<button class='delete-button' data-trip-id='" . $row['id'] . "'>Delete</button>";
                echo "</div>";
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
    </div>
    <script>
$(document).ready(function(){
    $('.delete-button').click(function(){
        var tripId = $(this).data('trip-id');
       
        
        var confirmDelete = confirm("Are you sure you want to delete this trip?");
        
        
        if (confirmDelete) {
            $.post('admin_delete_trip.php', { id: tripId }, function(response) {
                if(response === 'success') {
                    window.location.reload();
                } else {
                    console.error('Error:', response);
                    alert('Failed to delete the trip. Please try again later.');
                }
            });
        }
    });
});
</script>

    
</body>
</html>
