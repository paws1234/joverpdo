<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['csrf_token'])) {
    header("Location: index.php");
    exit;
}

// Validate CSRF token
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "Invalid CSRF token.";
        exit;
    }

    // Add your logic here for processing form submissions
}

// HTML for Dashboard
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Include custom styles -->
    <style>
        /* Add custom styles here */
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col justify-center items-center">
    <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4">Welcome, <?php echo $_SESSION['username']; ?>!</h2>
        <p class="text-gray-600 mb-4">This is your dashboard.</p>

        <!-- Logout link -->
        <p class="text-gray-500 text-sm mt-6"><a href="logout.php" class="text-indigo-600 hover:text-indigo-800">Logout</a></p>
    </div>
</body>
</html>

