<?php
$servername = "localhost";
$username = "said";
$password = "99181513SAeed";
$dbname = "user";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT id, name, price, details FROM trips");
    $stmt->execute();
    $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking and Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Allura&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Volkhov&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/css/select2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/css/intlTelInput.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/js/intlTelInput.min.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=AbJ4p9tuLlP8vO65MmE3TmiPZsLy-RhxV-L0bs9I-jJApWvWP1OvifZFRgH3WKoljsErZQq6RvHcgcV-"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url('pattern.png');
            background-size: cover;
            margin: 0;
            padding: 0;
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
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 100;
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

        .container {
            display: flex;
            justify-content: space-between;
            max-width: 900px;
            margin: 70px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px #cccccc36;
        }

        .left-container,
        .right-container {
            width: calc(50% - 10px);
        }

        .left-container {
            border-right: 2px solid #ccc;
            padding-right: 20px;
        }

        .right-container {
            padding-left: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-family: 'Allura', cursive;
            color: #6743a5;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-family: 'Volkhov', serif;
        }

        input[type="text"],
        input[type="tel"],
        input[type="date"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        .total-cost {
            border: 1px solid #ccc;
            padding: 10px;
            margin-top: 20px;
            display: none;
            font-family: 'Baskerville Old Face', serif;
        }

        .total-cost p {
            margin: 5px 0;
        }

        .total-cost span.black {
            color: blueviolet;
        }

        .total-cost span.red {
            color: red;
        }

        #total-cost-container {
            text-align: center;
        }

        .booking-button {
            background-color: #36234d;
            color: white;
            padding: 14px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
            font-size: 16px;
            font-family: 'Volkhov', serif;
        }

        .booking-button:hover {
            background-color: #36234d;
        }

        #paypal-button-container {
            position: sticky;
            top: 60px;
            right: 20px;
            z-index: 9999;
        }

        .image-container {
            text-align: right;
        }

        .image-container img {
            max-width: 100%;
            height: auto;
        }

    </style>
</head>

<body>
    <div class="top-bar">
        <a href="http://localhost/HOME2.html">Home</a>
        <a href="HTMLPage2.html">About Us</a>
        <a href="trips.php">Guide Around</a>
        <a href="#">Booking/Payment</a>
    </div>

    <div class="container">
        <div class="left-container">
            <h2>Confirm Your Tour</h2>
            
            <div id="myModal" class="modal">
                <div class="modal-content">
                    
                    <div class="image-container">
                       
                    </div>
                    <div class="form-group">
        <label for="trip">Trip:</label>
        <select id="trip" name="trip" onchange="calculateTotal()" required>
            <option value="">Select Trip</option>
            <?php foreach ($trips as $trip): ?>
                <option value="<?php echo htmlspecialchars($trip['id']); ?>" data-price="<?php echo htmlspecialchars($trip['price']); ?>">
                    <?php echo htmlspecialchars($trip['name']); ?> | 
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="total-cost" id="total-cost-container">
        <p><strong>Total Amount Due:</strong></p>
        <p><span class="black">Trip Name:</span> <span id="tour-name" class="red"></span></p>
        <p><span class="black">Trip Price:</span> <span id="tour-price" class="red"></span></p>
        <p><span class="black">Total:</span> <span id="total-amount" class="red"></span></p>
    </div>
    <script>
        let tripPrice = 0;

        function calculateTotal() {
            var tripSelect = document.getElementById("trip");
            var selectedOption = tripSelect.options[tripSelect.selectedIndex];
            var tripName = selectedOption.text;
            tripPrice = selectedOption.getAttribute("data-price");

            document.getElementById("tour-name").textContent = " " + tripName;
            document.getElementById("tour-price").textContent = " $" + tripPrice;
            document.getElementById("total-amount").textContent = " $" + tripPrice;
            document.getElementById("total-cost-container").style.display = "block";
        }
    </script>

                    <div id="paypal-button-container"></div>
                </div>
            </div>
        </div>
        <div class="right-container">
            <div class="image-container">
                <img src="comp_3.gif" alt="Tour Image">
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById("myModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }

        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: tripPrice
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert('Transaction completed by ' + details.payer.name.given_name);
                  
                    renderPayPalButton();
                });
            }
        }).render('#paypal-button-container');
        function renderPayPalButton() {
            document.getElementById('paypal-button-container').innerHTML = '<div id="paypal-button"></div>';
            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: tripPrice
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        alert('Transaction completed by ' + details.payer.name.given_name);
                        renderPayPalButton();
                    });
                }
            }).render('#paypal-button');
        }

    </script>
</body>

</html>

