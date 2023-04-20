<!DOCTYPE html>
<html>
<head>
    <title>Smart Mailbox</title>
    <style>
        .container {
            border: 1px solid black;
            padding: 10px;
            margin-bottom: 20px;
        }
        button {
            font-size: 20px;
            margin: 10px;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Smart Mailbox</h1>
        <p>System Status: <span id="system-status">Inactive</span></p>
        <p>Lock Status: <span id="lock-status">Inactive</span></p>
        <button id="activate-btn">Activate</button>
        <button id="deactivate-btn">Deactivate</button>
    </div>

    <div class="container">
        <?php

        // Create connection to MySQL database
        $servername = "localhost";
        $username = "iot263";
        $password = "csc263";
        $dbname = "mailboxReadings";
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if POST data is present
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Decode JSON data
            $data = json_decode(file_get_contents('php://input'), true);

            // Insert sensor readings into database
            $sql = "INSERT INTO timestamps (sensor_id) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $data['sensorId']);
            if ($stmt->execute()) {
                echo "Sensor readings added successfully!";
            } else {
                echo "Error inserting sensor readings: " . $stmt->error;
            }
            $stmt->close();
        }

        // Retrieve all sensor readings from database
        $sql = "SELECT * FROM timestamps";
        $result = $conn->query($sql);

        // Generate HTML table of sensor readings
        if ($result->num_rows > 0) {
            echo "<table><tr><th>Sensor ID</th><th>Timestamp</tr></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["sensor_id"] . "</td><td>" . $row["timestamp"] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "No sensor readings found.";
        }

        $conn->close();

        ?>
    </div>

    <script>
        // Get elements
        const systemStatus = document.getElementById("system-status");
        const lockStatus = document.getElementById("lock-status");
        const activateBtn = document.getElementById("activate-btn");
        const deactivateBtn = document.getElementById("deactivate-btn");

        // Set initial status
        let systemActive = false;
        let lockActive = false;

        // Add event listeners
        activateBtn.addEventListener("click", () => {
            if (!systemActive) {
                systemStatus.textContent = "Active";
                lockStatus.textContent = "Inactive";
                systemActive = true;
                lockActive = false;
            }
        });

        deactivateBtn.addEventListener("click", () => {
            if (systemActive || lockActive) {
                systemStatus.textContent = "Inactive";
                lockStatus.textContent = "Inactive";
                systemActive = false;
                lockActive = false;
            }
        });
    </script>
</body>
</html>
