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

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$order_by = "booking_date";
$country_filter = "";
if (isset($_GET['sort'])) {
    if ($_GET['sort'] == 'name') {
        $order_by = "name";
    } elseif ($_GET['sort'] == 'nationality') {
        $order_by = "nationality";
    }
}
if (isset($_GET['country']) && $_GET['country'] != '') {
    $country_filter = "WHERE nationality = '" . $conn->real_escape_string($_GET['country']) . "'";
}

$sql = "SELECT * FROM bookings $country_filter ORDER BY $order_by";
$result = $conn->query($sql);

$countries_sql = "SELECT DISTINCT nationality FROM bookings";
$countries_result = $conn->query($countries_sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bookings - Admin Panel</title>
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
    padding: 40px;
    max-width: 1000px;
    width: 100%;
    margin: 80px auto;
    background-color: rgba(255, 255, 255, 0.9);
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    z-index: 1;
    border-radius: 10px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #36234d; /* Adding color for consistency */
}

.filter-options {
    text-align: center;
    margin-bottom: 20px;
}

.filter-options a,
.filter-options select {
    margin: 0 10px;
    text-decoration: none;
    color: #36234d;
    font-weight: bold;
    transition: color 0.3s ease;
}

.filter-options a:hover {
    color: #27ae60;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

th,
td {
    padding: 12px 15px;
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background-color: #36234d;
    color: #fff;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #f1f1f1;
}

.no-results {
    text-align: center;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    color: #36234d; 
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

    <div class="container">
        <h2>Bookings</h2>
        <div class="filter-options">
            <a href="?sort=booking_date">Sort by Booking Date</a>
            <a href="?sort=name">Sort by Name</a>
            <a href="?sort=nationality">Sort by Nationality</a>
            <form method="GET" action="" style="display:inline;">
                <label for="country">Filter by Country:</label>
                <select name="country" id="country" onchange="this.form.submit()">
                    <option value="">Select a country</option>
                    <?php
                    while ($country_row = $countries_result->fetch_assoc()) {
                        $selected = isset($_GET['country']) && $_GET['country'] == $country_row['nationality'] ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($country_row['nationality']) . "' $selected>" . htmlspecialchars($country_row['nationality']) . "</option>";
                    }
                    ?>
                </select>
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($order_by); ?>">
            </form>
        </div>
        <?php
        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Name</th>
                        <th>Nationality</th>
                        <th>Trip</th>
                        <th>Booking Date</th>
                        <th>Phone Number</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row["name"]) . "</td>
                        <td>" . htmlspecialchars($row["nationality"]) . "</td>
                        <td>" . htmlspecialchars($row["trip"]) . "</td>
                        <td>" . htmlspecialchars($row["booking_date"]) . "</td>
                        <td>" . htmlspecialchars($row["phone_number"]) . "</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='no-results'>No bookings found</div>";
        }
        $conn->close();
        ?>
    </div>
</body>

</html>



