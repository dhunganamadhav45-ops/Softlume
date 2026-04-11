<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// DB connection
$conn = new mysqli("localhost", "root", "", "softlumeDB");

if ($conn->connect_error) {
    die(json_encode([
        "success" => false,
        "error" => "Database connection failed"
    ]));
}

// Get JSON data
$data = json_decode(file_get_contents("php://input"), true);

// Insert query
$stmt = $conn->prepare("INSERT INTO contacts 
(first_name, last_name, email, phone, service, message) 
VALUES (?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "ssssss",
    $data['first_name'],
    $data['last_name'],
    $data['email'],
    $data['phone'],
    $data['service'],
    $data['message']
);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Message saved successfully!"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "error" => $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>