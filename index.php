<?php
session_start();

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;

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

// Check if the form is submitted for QR code login via image upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['scan_qr'])) {
    // Check if a previous request was made within the last minute
    if (isset($_SESSION['last_upload_time']) && time() - $_SESSION['last_upload_time'] < 60) {
        echo "Please wait at least 1 minute before uploading another image.";
        exit;
    }

    // Check if an image file was uploaded
    if (isset($_FILES['qr_image']) && $_FILES['qr_image']['error'] === UPLOAD_ERR_OK) {
        // Validate file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['qr_image']['type'], $allowed_types)) {
            echo "Only JPEG, PNG, and GIF images are allowed.";
            exit;
        }

        // Validate file size (limit to 5MB)
        $max_size = 5 * 1024 * 1024; // 5MB in bytes
        if ($_FILES['qr_image']['size'] > $max_size) {
            echo "File size exceeds the maximum limit of 5MB.";
            exit;
        }

        $qr_image = $_FILES['qr_image']['tmp_name'];
        $decoded_qr_code = scanQRCode($qr_image);
        if ($decoded_qr_code !== false) {
            // Extract username, registration code, and CSRF token from QR code content
            $qr_data = $decoded_qr_code['data'][0]['allFields'][0]['fieldValue'];
            preg_match_all('/Username: (.*?)\nRegistration Code: (.*?)\nCSRF Token: (.*)/', $qr_data, $matches);
            $username = $matches[1][0];
            $registration_code = $matches[2][0];
            $csrf_token = $matches[3][0];

            // Validate username and registration code against the database
            $sql = "SELECT * FROM users WHERE username=:username AND registration_code=:registration_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':registration_code', $registration_code);
            try {
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($user) {
                    // Store username and CSRF token in session
                    $_SESSION['username'] = $username;
                    $_SESSION['csrf_token'] = $csrf_token;
                    $_SESSION['last_upload_time'] = time(); // Record the time of this upload
                    header("Location: dashboard.php");
                    exit;
                } else {
                    echo "Invalid username or registration code.";
                }
            } catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Error scanning QR code.";
        }
    } else {
        echo "Error uploading QR code image.";
    }
}

// Function to scan QR code using Guzzle for API requests
function scanQRCode($qr_image) {
    $client = new \GuzzleHttp\Client();

    try {
        $response = $client->request('POST', 'https://qr-code-and-barcode-scanner.p.rapidapi.com/ScanCode', [
            'multipart' => [
                [
                    'name' => 'image',
                    'filename' => 'qr_code.png',
                    'contents' => fopen($qr_image, 'r'),
                    'headers' => [
                        'Content-Type' => 'application/octet-stream',
                    ]
                ]
            ],
            'headers' => [
                'X-RapidAPI-Host' => 'qr-code-and-barcode-scanner.p.rapidapi.com',
                'X-RapidAPI-Key' => 'f161ffaf6amsh3818e8c5b2e8a0fp1aa104jsn1a38027a5b44',
            ],
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);

        if (isset($data['data'][0]['allFields'][0]['fieldValue'])) {
            return $data;
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white p-8 rounded shadow-lg">
        <h2 class="text-2xl font-bold mb-6">Scan QR Code to Login</h2>
        <form action="" method="post" enctype="multipart/form-data" class="space-y-4">
            <label for="qr_image" class="block text-sm font-medium text-gray-700">Upload QR Code Image:</label>
            <input type="file" accept="image/*" id="qr_image" name="qr_image" required
                class="block w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            
            <button type="submit" name="scan_qr"
                class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Scan QR Code
            </button>
        </form>
        <div class="mt-4 text-center">
            Don't have an account? <a href="register.php" class="text-blue-500 hover:text-blue-700 text-sm"><span class="text-blue-500 hover:text-blue-700">Register here.</span></a>
        </div>
    </div>
</body>
</html>

