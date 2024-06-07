<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $servername = "localhost";
    $username = "said";
    $password = "99181513SAeed";
    $dbname = "user";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error . " Error No: " . $conn->connect_errno);
    }
    

    $name = isset($_POST["name"]) ? $_POST["name"] : '';
    $nationality = isset($_POST["nationality"]) ? $_POST["nationality"] : '';
    $trip = isset($_POST["trip"]) ? $_POST["trip"] : '';
    $booking_date = isset($_POST["booking_date"]) ? $_POST["booking_date"] : '';
    $phone_number = isset($_POST["phone_number"]) ? $_POST["phone_number"] : '';
    $customer_id = isset($_POST["customer_id"]) ? $_POST["customer_id"] : '';
    $sql = "INSERT INTO bookings (name, nationality, trip, booking_date, phone_number, timestamp) 
            VALUES (?, ?, ?, ?, ?, current_timestamp())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $nationality, $trip, $booking_date, $phone_number);

    if ($stmt->execute()) {
    
        echo "<script>alert('Booking successful! Please complete the payment process to confirm your Booking');</script>";

        echo "<script>window.location.href = 'payment.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
}

?>
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
        .modal {
  display: none; 
  position: fixed; 
  z-index: 1; 
  left: 0;
  top: 0;
  width: 100%; 
  height: 100%; 
  overflow: auto; 
  background-color: rgba(0,0,0,0.4); 
}

.modal-content {
  background-color: #fefefe; 
  margin: 15% auto; 
  padding: 20px;
  border: 1px solid #888;
  width: 80%; 
}

.close {
  color: #36234d; 
  float: right;
  font-size: 16px; 
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #36234d; 
  text-decoration: none;
  cursor: pointer;
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
.left-container {
        position: relative;
    }

    .left-container img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    #paypal-button-container {
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        text-align: center;
    }

    </style>
</head>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Booking and Payment</title>
    <link rel="stylesheet" href="styles.css">
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
        <img src="musab-al-rawahi-HB-Xx9x-NMg-unsplash.jpg" alt="Tour Image">
           <div id="paypal-button-container"></div>
            </form>
        </div>
        

        <div class="right-container">
            <h2>Booking</h2>
            <form action="booking.php" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="nationality">Nationality:</label>
<select id="nationality" name="nationality" required>
    <option value="" disabled selected>Select Nationality</option>
    <option value="afghan">Afghan</option>
  <option value="albanian">Albanian</option>
  <option value="algerian">Algerian</option>
  <option value="american">American</option>
  <option value="andorran">Andorran</option>
  <option value="angolan">Angolan</option>
  <option value="antiguans">Antiguans</option>
  <option value="argentinean">Argentinean</option>
  <option value="armenian">Armenian</option>
  <option value="australian">Australian</option>
  <option value="austrian">Austrian</option>
  <option value="azerbaijani">Azerbaijani</option>
  <option value="bahamian">Bahamian</option>
  <option value="bahraini">Bahraini</option>
  <option value="bangladeshi">Bangladeshi</option>
  <option value="barbadian">Barbadian</option>
  <option value="barbudans">Barbudans</option>
  <option value="batswana">Batswana</option>
  <option value="belarusian">Belarusian</option>
  <option value="belgian">Belgian</option>
  <option value="belizean">Belizean</option>
  <option value="beninese">Beninese</option>
  <option value="bhutanese">Bhutanese</option>
  <option value="bolivian">Bolivian</option>
  <option value="bosnian">Bosnian</option>
  <option value="brazilian">Brazilian</option>
  <option value="british">British</option>
  <option value="bruneian">Bruneian</option>
  <option value="bulgarian">Bulgarian</option>
  <option value="burkinabe">Burkinabe</option>
  <option value="burmese">Burmese</option>
  <option value="burundian">Burundian</option>
  <option value="cambodian">Cambodian</option>
  <option value="cameroonian">Cameroonian</option>
  <option value="canadian">Canadian</option>
  <option value="cape verdean">Cape Verdean</option>
  <option value="central african">Central African</option>
  <option value="chadian">Chadian</option>
  <option value="chilean">Chilean</option>
  <option value="chinese">Chinese</option>
  <option value="colombian">Colombian</option>
  <option value="comoran">Comoran</option>
  <option value="congolese">Congolese</option>
  <option value="costa rican">Costa Rican</option>
  <option value="croatian">Croatian</option>
  <option value="cuban">Cuban</option>
  <option value="cypriot">Cypriot</option>
  <option value="czech">Czech</option>
  <option value="danish">Danish</option>
  <option value="djibouti">Djibouti</option>
  <option value="dominican">Dominican</option>
  <option value="dutch">Dutch</option>
  <option value="east timorese">East Timorese</option>
  <option value="ecuadorean">Ecuadorean</option>
  <option value="egyptian">Egyptian</option>
  <option value="emirian">Emirian</option>
  <option value="equatorial guinean">Equatorial Guinean</option>
  <option value="eritrean">Eritrean</option>
  <option value="estonian">Estonian</option>
  <option value="ethiopian">Ethiopian</option>
  <option value="fijian">Fijian</option>
  <option value="filipino">Filipino</option>
  <option value="finnish">Finnish</option>
  <option value="french">French</option>
  <option value="gabonese">Gabonese</option>
  <option value="gambian">Gambian</option>
  <option value="georgian">Georgian</option>
  <option value="german">German</option>
  <option value="ghanaian">Ghanaian</option>
  <option value="greek">Greek</option>
  <option value="grenadian">Grenadian</option>
  <option value="guatemalan">Guatemalan</option>
  <option value="guinea-bissauan">Guinea-Bissauan</option>
  <option value="guinean">Guinean</option>
  <option value="guyanese">Guyanese</option>
  <option value="haitian">Haitian</option>
  <option value="herzegovinian">Herzegovinian</option>
  <option value="honduran">Honduran</option>
  <option value="hungarian">Hungarian</option>
  <option value="icelander">Icelander</option>
  <option value="indian">Indian</option>
  <option value="indonesian">Indonesian</option>
  <option value="iranian">Iranian</option>
  <option value="iraqi">Iraqi</option>
  <option value="irish">Irish</option>
  <option value="israeli">Israeli</option>
  <option value="italian">Italian</option>
  <option value="ivorian">Ivorian</option>
  <option value="jamaican">Jamaican</option>
  <option value="japanese">Japanese</option>
  <option value="jordanian">Jordanian</option>
  <option value="kazakhstani">Kazakhstani</option>
  <option value="kenyan">Kenyan</option>
  <option value="kittian and nevisian">Kittian and Nevisian</option>
  <option value="kuwaiti">Kuwaiti</option>
  <option value="kyrgyz">Kyrgyz</option>
  <option value="laotian">Laotian</option>
  <option value="latvian">Latvian</option>
  <option value="lebanese">Lebanese</option>
  <option value="liberian">Liberian</option>
  <option value="libyan">Libyan</option>
  <option value="liechtensteiner">Liechtensteiner</option>
  <option value="lithuanian">Lithuanian</option>
  <option value="luxembourger">Luxembourger</option>
  <option value="macedonian">Macedonian</option>
  <option value="malagasy">Malagasy</option>
  <option value="malawian">Malawian</option>
  <option value="malaysian">Malaysian</option>
  <option value="maldivan">Maldivan</option>
  <option value="malian">Malian</option>
  <option value="maltese">Maltese</option>
  <option value="marshallese">Marshallese</option>
  <option value="mauritanian">Mauritanian</option>
  <option value="mauritian">Mauritian</option>
  <option value="mexican">Mexican</option>
  <option value="micronesian">Micronesian</option>
  <option value="moldovan">Moldovan</option>
  <option value="monacan">Monacan</option>
  <option value="mongolian">Mongolian</option>
  <option value="moroccan">Moroccan</option>
  <option value="mosotho">Mosotho</option>
  <option value="motswana">Motswana</option>
  <option value="mozambican">Mozambican</option>
  <option value="namibian">Namibian</option>
  <option value="nauruan">Nauruan</option>
  <option value="nepalese">Nepalese</option>
  <option value="new zealander">New Zealander</option>
  <option value="ni-vanuatu">Ni-Vanuatu</option>
  <option value="nicaraguan">Nicaraguan</option>
  <option value="nigerien">Nigerien</option>
  <option value="north korean">North Korean</option>
  <option value="northern irish">Northern Irish</option>
  <option value="norwegian">Norwegian</option>
  <option value="omani">Omani</option>
  <option value="pakistani">Pakistani</option>
  <option value="palauan">Palauan</option>
  <option value="panamanian">Panamanian</option>
  <option value="papua new guinean">Papua New Guinean</option>
  <option value="paraguayan">Paraguayan</option>
  <option value="peruvian">Peruvian</option>
  <option value="polish">Polish</option>
  <option value="portuguese">Portuguese</option>
  <option value="qatari">Qatari</option>
  <option value="romanian">Romanian</option>
  <option value="russian">Russian</option>
  <option value="rwandan">Rwandan</option>
  <option value="saint lucian">Saint Lucian</option>
  <option value="salvadoran">Salvadoran</option>
  <option value="samoan">Samoan</option>
  <option value="san marinese">San Marinese</option>
  <option value="sao tomean">Sao Tomean</option>
  <option value="saudi">Saudi</option>
  <option value="scottish">Scottish</option>
  <option value="senegalese">Senegalese</option>
  <option value="serbian">Serbian</option>
  <option value="seychellois">Seychellois</option>
  <option value="sierra leonean">Sierra Leonean</option>
  <option value="singaporean">Singaporean</option>
  <option value="slovakian">Slovakian</option>
  <option value="slovenian">Slovenian</option>
  <option value="solomon islander">Solomon Islander</option>
  <option value="somali">Somali</option>
  <option value="south african">South African</option>
  <option value="south korean">South Korean</option>
  <option value="spanish">Spanish</option>
  <option value="sri lankan">Sri Lankan</option>
  <option value="sudanese">Sudanese</option>
  <option value="surinamer">Surinamer</option>
  <option value="swazi">Swazi</option>
  <option value="swedish">Swedish</option>
  <option value="swiss">Swiss</option>
  <option value="syrian">Syrian</option>
  <option value="taiwanese">Taiwanese</option>
  <option value="tajik">Tajik</option>
  <option value="tanzanian">Tanzanian</option>
  <option value="thai">Thai</option>
  <option value="togolese">Togolese</option>
  <option value="tongan">Tongan</option>
  <option value="trinidadian or tobagonian">Trinidadian or Tobagonian</option>
  <option value="tunisian">Tunisian</option>
  <option value="turkish">Turkish</option>
  <option value="tuvaluan">Tuvaluan</option>
  <option value="ugandan">Ugandan</option>
  <option value="ukrainian">Ukrainian</option>
  <option value="uruguayan">Uruguayan</option>
  <option value="uzbekistani">Uzbekistani</option>
  <option value="venezuelan">Venezuelan</option>
  <option value="vietnamese">Vietnamese</option>
  <option value="welsh">Welsh</option>
  <option value="yemenite">Yemenite</option>
  <option value="zambian">Zambian</option>
  <option value="zimbabwean">Zimbabwean</option>

</select>

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

    <label for="booking_date">Booking Date:</label>
    <input type="date" id="booking_date" name="booking_date" required>

    <label for="phone">Phone Number:</label>
    <input type="tel" id="phone" name="phone_number" required>

    <div class="total-cost" id="total-cost-container">
        <p><strong>Total Amount Due:</strong></p>
        <p><span class="black">Trip Name:</span> <span id="tour-name" class="red"></span></p>
        <p><span class="black">Trip Price:</span> <span id="tour-price" class="red"></span></p>
        <p><span class="black">Total:</span> <span id="total-amount" class="red"></span></p>
    </div>

    <input type="submit" value="Confirm Booking" class="booking-button">

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
</body>
    

</body>

</html>

