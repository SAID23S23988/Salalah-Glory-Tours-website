<?php

if(isset($_POST['id'])) {
   
    $trip_id = $_POST['id'];

    $servername = "localhost";
    $username = "said";
    $password = "99181513SAeed";
    $dbname = "user";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        
        $sql = "DELETE FROM trips WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $trip_id);
        $stmt->execute();

        echo "success";
    } catch(PDOException $e) {
       
        echo "Error: " . $e->getMessage();
    }
} else {
    
    echo "Trip ID is not provided";
}
?>
