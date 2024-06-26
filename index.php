<?php
session_start();
//ari ang fucntion para maka access or maka request sa api endpoint guzzle ato gamit ash/lloyd
require_once 'vendor/autoload.php';
//mao ni
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

//i check diri na if kung when gi upload chuy chuy rani
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['scan_qr'])) {
    if (isset($_SESSION['last_upload_time']) && time() - $_SESSION['last_upload_time'] < 60) {
        echo "Please wait at least 1 minute before uploading another image.";
        exit;
    }
    //kani diri i check kung way error during upload shit 
    if (isset($_FILES['qr_image']) && $_FILES['qr_image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        //kani kay i check what type of of image na shit
        if (!in_array($_FILES['qr_image']['type'], $allowed_types)) {
            echo "Only JPEG, PNG, and GIF images are allowed.";
            exit;
        }
       //i check diri nag size sabotable ra ang variable name ash/lloyd
        $max_size = 5 * 1024 * 1024; 
        if ($_FILES['qr_image']['size'] > $max_size) {
            echo "File size exceeds the maximum limit of 5MB.";
            exit;
        }
      //ari ma sotre ang iamge inig upload sa user 
        $qr_image = $_FILES['qr_image']['tmp_name'];
        // ari i call ang fucntion para maka scan sa qr code
        $decoded_qr_code = scanQRCode($qr_image);
        //ari i check ni diri ang parameters sa qrcode shit kung sakto ba nya kugn sakto maka login ang user
        if ($decoded_qr_code !== false) {
            $qr_data = $decoded_qr_code['data'][0]['allFields'][0]['fieldValue'];
            preg_match_all('/Username: (.*?)\nRegistration Code: (.*?)\nCSRF Token: (.*)/', $qr_data, $matches);
            $username = $matches[1][0];
            $registration_code = $matches[2][0];
            $csrf_token = $matches[3][0];

            $sql = "SELECT * FROM users WHERE username=:username AND registration_code=:registration_code";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':registration_code', $registration_code);
            try {
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($user) {
                    $_SESSION['username'] = $username;
                    $_SESSION['csrf_token'] = $csrf_token;
                    $_SESSION['last_upload_time'] = time(); 
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

//sabotable raman guro ang function name ari i scan ang image or qrcode gamit ko og api naa nay parameters sa ubos which is sabotable ra di nako ma disclose ang secret key kay 
//related siya sa akong personal account 
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
                'X-RapidAPI-Key' => 'secretkey',
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
//ari login page scanning sa qr code and upload shit
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

