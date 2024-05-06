<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';

$servername = "localhost";
$username = "paws";
$password = "paws";
$dbname = "joverhackingpdo";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {

    $username = htmlspecialchars($_POST['name']);
    $password = htmlspecialchars($_POST['password']);
    
   
    if (!empty($username) && !empty($password)) {
        $registrationCode = uniqid();
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, registration_code) VALUES (:username, :password, :registrationCode)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':registrationCode', $registrationCode);
        try {
            $stmt->execute();

            generateQRCode($username, $registrationCode);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Error: Username and password are required.";
    }
}


function generateQRCode($username, $registrationCode) {
    $csrfToken = bin2hex(random_bytes(32)); 
    $data = "Username: $username\nRegistration Code: $registrationCode\nCSRF Token: $csrfToken";

 
    $encodedData = urlencode($data);

   
    $size = '150x150';


    $apiUrl = "http://api.qrserver.com/v1/create-qr-code/?data=$encodedData&size=$size";

 
    echo "<div id='qrModal' class='modal'>";
    echo "<div class='modal-content'>";
    echo "<span onclick=\"document.getElementById('qrModal').style.display='none'\" class='close'>&times;</span>";
    echo "<img src='$apiUrl' alt='QR Code'>";
    echo "</div>";
    echo "</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration with QR Code Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
      
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
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
        }

        /* Close Button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white p-8 rounded shadow-lg">
        <h2 class="text-2xl font-bold mb-6">Registration</h2>
        <form method="post" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
                <input type="text" id="name" name="name" required
                    class="block w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
                <input type="password" id="password" name="password" required
                    class="block w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="flex justify-between items-center">
                <button type="submit" name="register"
                    class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Register
                </button>
                <button onclick="document.getElementById('qrModal').style.display='block'" class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    View QR Code
                </button>
            </div>
        </form>
        <div class="mt-4">
            <p class="text-sm text-gray-600">Already have an account? <a href="index.php" class="font-medium text-indigo-600 hover:text-indigo-500">Login here</a></p>
        </div>
    </div>


    <div id="qrModal" class="modal">
        <div class="modal-content">
            <span onclick="document.getElementById('qrModal').style.display='none'" class="close">&times;</span>
            <img src="" alt="QR Code" id="qrCodeImage">
        </div>
    </div>

    <script>
        var modal = document.getElementById('qrModal');

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
