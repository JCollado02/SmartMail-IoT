<?php

	$temp = realpath('/home/pi/Desktop/temp.py');
	$hum = realpath('/home/pi/Desktop/hum.py');
	$lOff = realpath('/home/pi/Desktop/yellowLightOFF.py');
	$lOn = realpath('/home/pi/Desktop/yellowLightON.py');
	$lock = realpath('/home/pi/Desktop/lock.py');
	$unLock = realpath('/home/pi/Desktop/unLock.py');


	if (isset($_POST["button1"])) {
        exec($lOn);
    }
	if (isset($_POST["button2"])) {
        exec($lOff);
    }
	if (isset($_POST["button3"])) {
		exec($lock, $output);
	}
	if (isset($_POST["button4"])) {
		exec($unLock);
	}
     

?>

<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
		<meta HTTP-EQUIV="EXPIRES" CONTENT="0">
		<title>Pi Dashboard</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
	</head>
	<style type="text/css">
		.col-sm {
			border: 3px solid darkblue;
			background: lightblue;
			border-radius: 2em;
			padding: 3em;
			margin: 1em;
		}
		body {
			background: #8a8120;
		}
	

		
	</style>
	<body>
		
		<div class="container">
		  <div class="row">
			<div class="col-sm">
			   <?php
					// Create connection to MySQL database
					$servername = "10.19.16.84";
					$username = "iot263";
					$password = "csc263";
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
			</div>
			<div class="col-sm">
			  <strong>Humidity From Device: </strong><?=exec($hum)?> <strong> units</strong>
				
			</div>
			<div class="col-sm">
				<h3><strong>Light & Lock</strong></h3>
			  
			  
				<form action="/index.php" method="POST">
					
					<button type="submit" name="button1" value="" >On</button>
					<button type="submit" name="button2" value="" >Off</button>
					
				</form>
			
			</div>
			</div>
		  </div>
		</div>
		


		
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
	</body>
</html>
