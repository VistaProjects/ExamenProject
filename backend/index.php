<?php
// include database file
include_once "database.php";

// set header json
header('Content-Type: application/json');
// error_reporting(0);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$error = array();
$data = array();

function displayError($text) {
	$error["success"] = false;
	$error["error"]["text"] = $text;
	die(json_encode(array($error)));
}

// Check if the file was uploaded successfully
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
	$tempFilePath = $_FILES['file']['tmp_name'];
	$newFilePath = './new.html'; // Path to save the file in the current directory
  
	// Move the uploaded file to the desired location
	if (move_uploaded_file($tempFilePath, $newFilePath)) {
	  echo 'File uploaded successfully';
	  echo $tempFilePath;
	  echo $newFilePath;
	} else {
	  echo 'Error uploading file';
	}
}




// echo "Connected successfully";
if (isset($_GET['item']))
$item = $_GET['item'];
else {
	displayError("Missing item parameter");
}

// Sanitize the input to block SQL injections 
$item = preg_replace('/[^a-zA-Z0-9_]/', '', $item);

// If we want all the items
if ($item == "all"){
	
	$names = array();
    
	$query = "SHOW TABLES;";
	$query = $conn->query($query);

	$row_count = mysqli_num_rows($query);
				
	if ($row_count > 0) {
		// Put all the tables in the $names array
		while($row = $query->fetch_assoc()) {
			$names[] = $row["Tables_in_" . $dbName];
		}

		// loop names array
		for ($i=0; $i < count($names); $i++) { 
			
			$queryTable = "SELECT * FROM " . $names[$i];
			$queryTable = $conn->query($queryTable);
			// var_dump($queryTable);
			// var_dump($query);
			if (!$queryTable){
				displayError($conn->error);
			}
			$counting = mysqli_num_rows($queryTable);
			// echo $counting;
			if ($counting >= 0 ) {
				while($rowTables = $queryTable->fetch_assoc()) {
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


// If a specific item is requested

$query = "SELECT * FROM " . $item;
try {
	$query = $conn->query($query);
}
catch (Exception $e) {
	displayError($e->getMessage());
}

// if (!$query){
// 	displayError($conn->error);
// }
$row_count = mysqli_num_rows($query);


if ($row_count > 0) {
	while($row = $query->fetch_assoc()) {

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


// localhost/api?item=card
// localhost/api?item=all
// localhost/api?item=navbar


// {
// 	"card": {
// 		"blue card": [
// 			"html code hier"
// 		],
// 		"red card": [
// 			"..."	
// 		]
// 	},
// 	"navbar": {
// 		"red card": [
// 			"..."	
// 		]
// 	}
// }