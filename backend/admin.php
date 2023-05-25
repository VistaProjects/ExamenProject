<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<title>Admin panel</title>
	<style>
		* {
			padding : 5px;
		} 
		.error {
			color: red;
		}
	</style>
	<link rel="stylesheet" href="vs.min.css">
	<script src="highlight.min.js"></script>
	<script>hljs.highlightAll();</script>
</head>
<body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.7/beautify-html.min.js"></script>

<?php

include_once 'database.php';

// Check if the connection to the database is working
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// Create an array to store all the table names
$options = array();

// Get all table names from database
$sql = "SHOW TABLES";
$result = $conn->query($sql);

// Check if there are any tables in the database and add them to the options array
if ($result->num_rows > 0) {
	// Loop through each table
	while($row = $result->fetch_assoc()) {
		// Add table name to options array
		array_push($options, $row['Tables_in_' . $dbName]);
	}
} else {
	echo "Please create a table first to start using the admin panel";
}

// Check if form has been submitted
if(!isset($_POST['submit'])) {
	// Create a multidimensional array to store all the column names
	$name_list = array();
	
	foreach($options as $option) {
		// Get all column names from table
		$sql = "SELECT * FROM $option";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			// Loop through each column
			while($row = $result->fetch_assoc()) {
				// Add column name to options array
				$name_list[$option][] = $row['name'];
	
			}
		} else {
			// echo "0 results";
		}
		// echo "<br>" . $name_list[$option][1];
	}

}  



// Check if form has been submitted
if(isset($_POST['submit'])) {
	// echo $_GET['type'];
	
	// Check if we want to add new html code
	if ($_GET['type'] == "addhtml") {
		// Check if all fields are filled in otherwise show error message to go back
		if (empty($_POST['TABELNAME']) || empty($_POST['NAME'])) {
			die("<p style='color:red; font-weight:bold;'>Please fill in all fields 💀</p><br><button onclick='window.history.back()'>Go back</button>");
		}

		// Get form data and remove all special characters
		$tabel = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['TABELNAME']);
		$name = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['NAME']);
		$code = $_POST['HTMLCODE'];

		// Check if all fields are filled in otherwise show error message to go back
		if (empty($tabel) || empty($name) || empty($code)) {
			echo "<p style='color:red; font-weight:bold;'>Please fill in all fields 💀</p>";
			
			// If we dont have the required fields filled in reload the page after 2 seconds
			header("Refresh:2");
		} else {
	
			// Escape special characters
			$slashes = addslashes($code);

			// Insert data into database
			$sql = "INSERT INTO $tabel (name, htmlcode) VALUES ('$name', '$slashes')";
			if ($conn->query($sql) === TRUE) {
				echo "<p style='color:green; font-weight:bold;'>New html code created 💕</p>";
				header("Refresh:2");
			} else {
				echo "<p style='color:red; font-weight:bold;'>Name already exists ☢</p>";
				header("Refresh:2");
			}
			$conn->close();
		}
	}

	// Check if we want to add new table
	if ($_GET['type'] == "addtable") {
		// Check if we have a table name
		if (empty($_POST['TABELNAME'])) {
			die("<p style='color:red; font-weight:bold;'>Please fill in all fields 💀</p><br><button onclick='window.history.back()'>Go back</button>");
		}

		// Get form data and remove all special characters
		$tabelname = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['TABELNAME']);

		// Setup SQL query to create new table
		$sql = "CREATE TABLE `$tabelname` (
			`id` INT NOT NULL AUTO_INCREMENT,
			`htmlcode` VARCHAR(1000),
			`name` VARCHAR(225) NOT NULL UNIQUE,
			PRIMARY KEY (`id`)
		)";
			
		if ($conn->query($sql) === TRUE) {
		  	echo "<p style='color:green; font-weight:bold;'>New html code created 💕</p>";
			header("Refresh:2");
		} else {
			echo "<p style='color:red; font-weight:bold;'>Error creating table: " . $conn->error . "💀</p><br>";
			header("Refresh:2");
		}
		$conn->close();
	}

	// Check if we want to edit html code
	if ($_GET['type'] == "edithtml") {

		// Get form data and remove all special characters
		$tabelname = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['TABELNAME']);
		$name = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['NAME']);
		
		$code = $_POST['HTMLCODE'];
		$slashes = addslashes($code);

		// Update the html code in the database
		$sql = "UPDATE $tabelname SET htmlcode='$slashes' WHERE name='$name'";
			
		if ($conn->query($sql) === TRUE) {
			echo "<p style='color:green; font-weight:bold;'>New html code created 💕</p>";
			header("Refresh:2");
		} else {
			echo "<p style='color:red; font-weight:bold;'>Error creating table: " . $conn->error . "💀</p><br>";
			header("Refresh:2");
		}
		$conn->close();
	}

}
?>

<h2>Add new Component to DB</h2>
<!-- Create a action that will link to the current file using PHP_SELF -->
<form id="html" method="post" action="<?php echo $_SERVER['PHP_SELF'] . "?type=addhtml" ?>">
	<label for="TABELNAME">CATEGORY:</label>
	<select name="TABELNAME">
		<?php
			// Loop through options array and print options
			foreach($options as $option) {
				echo "<option value='" . $option . "'>" . $option . "</option>";
			}
		?>
	</select><br>

	<label for="NAME">NAME:</label>
	<input type="text" name="NAME"><br>

	<label for="HTMLCODE">HTMLCODE:</label>
	<input type="text" name="HTMLCODE"><br>

	<input type="submit" name="submit" value="Submit" id="inputButton">
</form>

<hr>

<h2>Add new category</h2>

<form id="table" method="post" action="<?php echo $_SERVER['PHP_SELF'] . "?type=addtable" ?>" onsubmit='return validateForm()'>
	<label for="TABELNAME">CATEGORIE:</label>
	<input type="text" name="TABELNAME" onchange="checkName()" id="name"><br>
	<span id="name-error"></span><br>
	<input type="submit" name="submit" value="Submit" id="inputButton">
</form>

<hr>

<h2>Edit Component</h2>

<form id="edit" method="post" action="<?php echo $_SERVER['PHP_SELF'] . "?type=edithtml" ?>" >
	<label for="TABELNAME">TABELNAME:</label>
	<select name="TABELNAME" id="TABELNAME" onchange="check(), CheckForm()">
		<?php
			// Loop through options array and print options
			foreach($options as $option) {
				echo "<option value='" . $option . "' >" . $option . "</option>";
			}
		?>
	</select><br>

	<label for="NAME">NAME:</label>
	<select name="NAME" id="NAME" onchange="editcode()">
		<option value=""></option>
		<?php
			// Loop through options array and print options
			// foreach($name_list as $list_name) {
				
			//     foreach($list_name as $name) {
			//         echo "<option id=`$name` style='display: none;' value='" . strtolower($name) . "'>" . $name . "</option>";
			//     }
			//     // echo "<option value='" . strtolower($name) . "'>" . $name . "</option>";
			// }

			foreach($options as $option) {
				// echo "<option>" . $option . "</option>";
				foreach($name_list[$option] as $name) {
					echo "<option id='$option' style='display: none;' value='" . $name . "'>" . $name . "</option>";
				}
			}
		?>
	</select><br>
	<label for="HTMLCODE">HTMLCODE:</label><br>
	<pre>
		<div id="testing"></div>
	</pre>
	<textarea style="width: 90%; height: 250px;" type="text" name="HTMLCODE" id="HTMLCODE" value=""></textarea ><br>
	
	<input type="submit" name="submit" value="Submit" id="inputButton">
</form>
</body>

<script>
		// Remove the query string from the url so we dont resubmit the form but keep the url clean
		window.history.pushState({}, document.title, window.location.href.split('?')[0]);
		<?php
			// Loop through options array and echo them in the JavaScript array
			echo 'let options = [';
			foreach($options as $option) {
				echo '"' . strtolower($option) .'", ';
			}
			echo ']';
		?>

		function CheckForm () {
			// check if Tabelname is parent of Name and set the value to nothing
			document.getElementById("HTMLCODE").value = ''
		}



		function editcode() {
			// Get the value of the selected option
			var value = document.getElementById("TABELNAME").value;

			// Get the name
			var name = document.getElementById("NAME").value;
			
			// Create the HTTP request
			var xhttp = new XMLHttpRequest();

			xhttp.onreadystatechange = function() {
				// check if the response is ready
				if (this.readyState == 4 && this.status == 200) {
					// add the response to the element

					// Parse the JSON response
					let json = JSON.parse(this.responseText)

					let code = document.getElementById("HTMLCODE")
					let codeblock = document.getElementById("testing")
					
					// Beautify the code
					code.value = html_beautify(json.card[name])
					
					// Highlight the code with highlight.js
					html = hljs.highlightAuto(code.value)

					// Add the code to the codeblock
					codeblock.innerHTML = html.value
				}
			};
			// open the request
			
			xhttp.open("GET", '<?php echo $base_link . "/index.php?item=" ?>' + value, true);
			// send the request to the server and use the base_link to get the correct path from database.php
			xhttp.send();
			
		}

		check()
		function check() {
			// Get the value of the selected option
			var value = document.getElementById("TABELNAME").value;


			// Loop through the list of <option id="name here">
			// If the id matches the selected value, show the option
			// If the id does not match the selected value, hide the option
			for (var i = 0; i < document.getElementById("NAME").length; i++) {
				if (document.getElementById("NAME").options[i].id == value) {
					document.getElementById("NAME").options[i].style.display = "block";
				} else {
					document.getElementById("NAME").options[i].style.display = "none";
				}
			}
		}

		// Reset the form
		document.getElementById("html").reset()
		document.getElementById("table").reset()

		function checkName() {
			// Get the entered name when the input field changes
			var name = (document.getElementById("name").value).toLowerCase();

			
			// Check if the name exists in the options array
			if (options.includes(name)) {
				// If the name exists, add the error class to the input field and display an error message
				document.getElementById("name").classList.add("error");
				document.getElementById("name-error").textContent = "Name already exists";
			}
			// If the name does not exist, remove the error class and clear the error message
			else {
				document.getElementById("name").classList.remove("error");
				document.getElementById("name-error").textContent = "";
			}
		}

		// Check if the form is valid
		function validateForm() {
			return !document.getElementById("name").classList.contains("error")
		}


		// Add event listeners to the name input field to automatically check the name
		const source = document.getElementById('name')

		source.addEventListener('input', checkName);
		source.addEventListener('propertychange', checkName); 

</script>
</html>
