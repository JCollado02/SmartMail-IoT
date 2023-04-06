<?php

// Create connection to MySQL database
$servername = "localhost";
$username = "ADDUSERNAME";
$password = "ADDPASSWORD";
$dbname = "sensors";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if POST data is present
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Decode JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    // Insert sensor readings into database
    $sql = "INSERT INTO temperature (sensor_id, temperature, humidity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdd", $data['sensorId'], $data['temp'], $data['humidity']);
    if ($stmt->execute()) {
        echo "Sensor readings added successfully!";
    } else {
        echo "Error inserting sensor readings: " . $stmt->error;
    }
    $stmt->close();
}

// Retrieve all sensor readings from database
$sql = "SELECT * FROM temperature";
$result = $conn->query($sql);

// Generate HTML table of sensor readings
if ($result->num_rows > 0) {
    echo "<table><tr><th>Sensor ID</th><th>Temperature</th><th>Humidity</th><th>Timestamp</tr></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["sensor_id"] . "</td><td>" . $row["temperature"] . "</td><td>" . $row["humidity"] . "</td><td>" . $row["timestamp"] . "</td>";
    }
    echo "</table>";
} else {
    echo "No sensor readings found.";
}

$conn->close();

?>

