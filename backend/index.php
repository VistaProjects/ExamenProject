<?php

// Include database file to connect to the database
include_once "database.php";

// Set the header to json so the client knows what to expect
header('Content-Type: application/json');

// Check if the connection to the database was successful
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// Create 2 arrays to store the data and the errors that will be returned to the client
$error = array();
$data = array();

// Function to display an error with JSON and exit the script
// [{"success":false, "error":{"text":"Error text here"}}]
function displayError($text) {
	$error["success"] = false;
	$error["error"]["text"] = $text;
	die(json_encode(array($error)));
}

// Check if the file was uploaded successfully
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {

	// Get the temporary file path
	$tempFilePath = $_FILES['file']['tmp_name'];
	$currentDateTime = new DateTime();
	$currentDateTime->setTimezone(new DateTimeZone('Europe/Amsterdam'));
	// Format the current time as a string
	$formattedDateTime = $currentDateTime->format('d-m-Y H_i');

	// Output the formatted string
	// $formattedDateTime . ".html";
	// The name of the file that will be saved on the server
	$newFilePath = $formattedDateTime. ".html";
  
	// Move the uploaded file to the desired location
	if (move_uploaded_file($tempFilePath, $newFilePath)) {
	  echo 'File uploaded successfully';
	} else {
	  echo 'Error uploading file';
	}
}




// Check if the item parameter is set in the URL for example: http://localhost/index.php?item=cards
if (isset($_GET['item']))
	$item = $_GET['item'];
else {
	displayError("Missing item parameter");
}

// Sanitize the input to block SQL injections and remove any other characters that are not letters, numbers or underscores
$item = preg_replace('/[^a-zA-Z0-9_]/', '', $item);

// If we want all the items
if ($item == "all"){
	
	// Create an array to store all the table names
	$names = array();
    
	// Get all the table names from the database
	$query = "SHOW TABLES;";
	$query = $conn->query($query);

	$row_count = mysqli_num_rows($query);
				
	// Check if there are any tables in the database
	if ($row_count > 0) {

		// Put all the tables in the $names array
		while($row = $query->fetch_assoc()) {
			$names[] = $row["Tables_in_" . $dbName];
		}

		// loop through the names array and get all the data from the tables
		for ($i=0; $i < count($names); $i++) { 
			
			$queryTable = "SELECT * FROM " . $names[$i];
			$queryTable = $conn->query($queryTable);

			// If we don't get any results from the query then display an error
			if (!$queryTable){
				displayError($conn->error);
			}

			// Get the number of rows from the query
			$counting = mysqli_num_rows($queryTable);

			// If we have results then put them in the $data array
			if ($counting >= 0 ) {
				while($rowTables = $queryTable->fetch_assoc()) {

					// Example output:
					//{
					//	"htmlcode": {
					//		"test": "<!-- Example split danger button -->\n<div class=\"btn-group\">\n  <button type=\"button\" class=\"btn btn-danger\">Action</button>\n  <button type=\"button\" class=\"btn btn-danger dropdown-toggle dropdown-toggle-split\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">\n    <span class=\"visually-hidden\">Toggle Dropdown</span>\n  </button>\n  <ul class=\"dropdown-menu\">\n    <li><a class=\"dropdown-item\" href=\"#\">Action</a></li>\n    <li><a class=\"dropdown-item\" href=\"#\">Another action</a></li>\n    <li><a class=\"dropdown-item\" href=\"#\">Something else here</a></li>\n    <li><hr class=\"dropdown-divider\"></li>\n    <li><a class=\"dropdown-item\" href=\"#\">Separated link</a></li>\n  </ul>\n</div>",
					//	},
					//  "test": {
					//		"testing": "<p>hoi rik</p>",
					//		"hello world": "<h1>Hello world!</h1>"
					//	},
					//	"success": true
					//}
					$id = $rowTables['id'];
					$htmlcode = $rowTables['htmlcode'];
					$name = $rowTables['name'];
					$data[$names[$i]][$name] = $htmlcode;
					
				}
			} else {
				displayError("No rows found");
			}
		}
	} else {
		displayError("No tables found");
	}
	$data["success"] = true;
	echo json_encode($data);
	exit;
}


// If a specific item is requested create a query to get the data from the database
$query = "SELECT * FROM " . $item;

// If the query is not successful then display an error
try {
	$query = $conn->query($query);
}
catch (Exception $e) {
	displayError($e->getMessage());
}

// If we don't get any results from the query then display an error
if (!$query){
	displayError($conn->error);
}

$row_count = mysqli_num_rows($query);

// If we have results then put them in the $data array
if ($row_count > 0) {
	while($row = $query->fetch_assoc()) {

		// Example output:
		// {
		// 	"card": {
		// 		"testing": "<p>hoi rik</p>",
		// 		"hello world": "<h1>Hello world!</h1>"
		// 	},
		// 	"success": true
		// }
		$id = $row['id'];
		$htmlcode = $row['htmlcode'];
		$name = $row['name'];
		$data["card"][$name] = $htmlcode;
		$data["success"] = true;
	}
} else {
	echo "nothing";	
}

echo json_encode($data);