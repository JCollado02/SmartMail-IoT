<?php

	$temp = realpath('/home/pi/Desktop/temp.py');
	$hum = realpath('/home/pi/Desktop/hum.py');
	$lOff = realpath('/home/pi/Desktop/yellowLightOFF.py');
	$lOn = realpath('/home/pi/Desktop/yellowLightON.py');
	$lock = realpath('/home/pi/Desktop/lock.py');
	$unLock = realpath('/home/pi/Desktop/unLock.py');
	$timeOpened = realpath('/home/pi/Desktop/timeOpened.py');


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
	<script src='https://cdn.plot.ly/plotly-2.20.0.min.js'></script>
		<script>
			function getData() {
				//window.alert("Where did you get that hat?");
				const results = document.getElementById("results");
				fetch("http://localhost/endpoint.php")     //NEEDS TO BE POINTED TOWARDS MARIADB
				.then((response) => {
					return response.json();
				})
				.then((json) => {
					results.innerHTML = JSON.stringify(json);
					showData(json.dates, json.readings);
				})
				.catch((error) => {
					console.log(error);
				});
				
			}
			function showData(xData, yData) {
				var trace1 = {
				  x: xData,
				  y: yData,
				  type: 'scatter'
				};


				var data = [trace1];

				Plotly.newPlot('chart', data);

			}
			
		</script>
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
			border: 3px solid #000080;
			background: #f5f5f5;
			border-radius: 2em;
			padding: 3em;
			margin: 1em;
			box-shadow: 10px 5px 10px black;
		}
		body {
			background: #C9A9A6;
		}
	

		
	</style>
	<body>
	
		<div class="container">
			<div class="row">
				<div class="col-sm">
				   <strong><h1>WELCOME TO THE MAILBOX MANAGER</h1></strong>
				</div>
			</div>
			<div class="row">
				<!--
				<div class="col-sm">
				   <strong>Temperature From Device: </strong><?=exec($temp)?> <strong> &#8457</strong>
				   <strong>Humidity From Device: </strong><?=exec($hum)?> <strong> units</strong>
				</div>
				-->
				
				
				<div class="col-sm">
					<h3><strong>Indicator</strong></h3>


					<form action="/index.php" method="POST">
						
						<button type="submit" name="button1" value="" >On</button>
						<button type="submit" name="button2" value="" >Off</button>
						
					</form>

				</div>
				<div class="col-sm">
					<h4><strong>Lock</strong></h4>

					<form action="/index.php" method="POST">
					  
						<!--<button type="submit" name="button3" value="" >Lock</button> -->
						<button type="submit" name="button4" value="">Unlock</button>

					</form>
				</div>
			</div>
			<div class="row">
				<div class="col-sm">
				    <strong>Mailbox Last Opened: </strong><?=exec($timeOpened)?> <strong>.</strong>
				    <h1>Get Data</h1>
					<button onclick="getData()">Get Data</button>
					<h1>Results</h1>
					<div id="results">
					
					</div>
					<h1>Chart</h1>
					<div id="chart"> <!-- Adapt to a table to display all info -->
					</div>
					
					
				</div>
			</div>
	
		</div>
		


		
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
	</body>
</html>
