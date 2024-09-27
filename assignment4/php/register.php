<?php
//AnimalShelter class
require_once 'AnimalShelter.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$responses = [
    400 => "Bad Request",
    404 => "Not Found",
    405 => "Method Not Allowed",
    500 => "Internal server error"
];

function send_error($code, $message) {
    global $responses;
    header("HTTP/1.1 $code " . $responses[$code]);
    echo json_encode(['error' => $message]);
    exit;
}

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_error(405, 'Request method not allowed');
}

// Check if POST data is not empty
if (empty($_POST)) {
    send_error(400, 'No data received');
}

// Extract POST data
$username = $_POST['username'] ?? '';
$fullname = $_POST['fullname'] ?? '';
$dateOfBirth = $_POST['dob'] ?? '';
$email = $_POST['email'] ?? '';

// Validate input data
if (empty($username) || empty($fullname) || empty($dateOfBirth) || empty($email)) {
    send_error(400, 'Missing required fields');
}

// Validate username
if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[~!@#$%^&*])[A-Za-z\d~!@#$%^&*]{8,20}$/', $username)) {
    send_error(400, 'Invalid username');
}

// Validate fullname
if (!preg_match('/^[A-Za-z\'\-]+ [A-Za-z\'\-]+$/', $fullname)) {
    send_error(400, 'Invalid fullname');
}

// Validate dateOfBirth
if (!preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dateOfBirth)) {
    send_error(400, 'Invalid date of birth');
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    send_error(400, 'Invalid email address');
}

// Generate temporary password
function generate_password($username, $dateOfBirth) {
    $pet_names = ["snowball", "disco", "jester", "sandwiches", "dasher", "toffee", "tigerlilly", "mushu", "solemnbum", "spats", "bluey", "wonda"];
    $pet_name = $pet_names[array_rand($pet_names)];
    
    // Extract year of birth and calculate age
    $year_of_birth = explode('/', $dateOfBirth)[2];
    $age = date('Y') - $year_of_birth;
    
    // Shuffle username characters
    $username_chars = str_split($username);
    shuffle($username_chars);
    $shuffled_username = implode('', $username_chars);
    
    // Generate the password
    return $pet_name . $shuffled_username . $age;
}

$password = generate_password($username, $dateOfBirth);

// Create AnimalShelter object
$shelter = new AnimalShelter($username, $fullname, $dateOfBirth, $email);

// Write data to file
$file = 'data.json';
$data = json_encode($shelter);

// Append data to file
file_put_contents($file, $data . PHP_EOL, FILE_APPEND);

// Send success response
echo json_encode(['password' => $password]);
?>
