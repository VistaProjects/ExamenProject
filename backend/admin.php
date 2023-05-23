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


$options = array();

// Get all table names from database
$sql = "SHOW TABLES";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // Loop through each table
    while($row = $result->fetch_assoc()) {
        // Add table name to options array
        array_push($options, $row['Tables_in_' . $dbName]);
    }
} else {
    // echo "0 results";
}

if(!isset($_POST['submit'])) {
	$name_list = array();
	
	foreach($options as $option) {
		// echo $option . "<br>";
		// Get all column names from table
		$sql = "SELECT * FROM $option";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			// Loop through each column
			while($row = $result->fetch_assoc()) {
				// echo $row['name'] . "<br>";
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
	
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	if ($_GET['type'] == "addhtml") {
		if (empty($_POST['TABELNAME']) || empty($_POST['NAME'])) die("<p style='color:red; font-weight:bold;'>Please fill in all fields 💀</p>
		<br><button onclick='window.history.back()'>Go back</button>");
		// Get form data
		$tabel = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['TABELNAME']);
		$name = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['NAME']);
		$code = $_POST['HTMLCODE'];
		if (empty($tabel) || empty($name) || empty($code)) {
			echo "<p style='color:red; font-weight:bold;'>Please fill in all fields 💀</p>";
			header("Refresh:2");
		} else {
	
			$slashes = addslashes($code);

			// Insert data into database
			$sql = "INSERT INTO $tabel (name, htmlcode) VALUES ('$name', '$slashes')";
			if ($conn->query($sql) === TRUE) {
				echo "<p style='color:green; font-weight:bold;'>New html code created 💕</p>";
				header("Refresh:2");
			} else {
				// echo "Error: " . $sql . " " . $conn->error;
				echo "<p style='color:red; font-weight:bold;'>Name already exists ☢</p>";
				header("Refresh:2");
			}
			$conn->close();
		}
	}

	if ($_GET['type'] == "addtable") {
		// echo "Creating new table " .  $_POST['TABELNAME'];
		if (empty($_POST['TABELNAME'])) die("<p style='color:red; font-weight:bold;'>Please fill in all fields 💀</p>
		<br><button onclick='window.history.back()'>Go back</button>");

        $tabelname = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['TABELNAME']);

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

	if ($_GET['type'] == "edithtml") {
		// echo "Creating new table " .  $_POST['TABELNAME'];

        $tabelname = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['TABELNAME']);
        $name = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['NAME']);
		
		$code = $_POST['HTMLCODE'];
		$slashes = addslashes($code);

	
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
<form id="html" method="post" action="<?php echo $_SERVER['PHP_SELF'] . "?type=addhtml" ?>">
	<label for="TABELNAME">TABELNAME:</label>
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

<h2>Add new categorie</h2>

<form id="table" method="post" action="<?php echo $_SERVER['PHP_SELF'] . "?type=addtable" ?>" onsubmit='return validateForm()'>
	<label for="TABELNAME">TABELNAME:</label>
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
	// function reload () {
	// 	setTimeout(() => window.location.reload(), 300);
	// }	
		// window.addEventListener('popstate', reload(), false);
		
		window.history.pushState({}, document.title, window.location.href.split('?')[0]);
	    <?php
            // Loop through options array and print options
			echo 'let options = [';
            foreach($options as $option) {
                echo '"' . strtolower($option) .'", ';
            }
			echo ']';

            // Loop through options array and print options
			// echo 'let options = [';
            // foreach($name_list as $name) {
            //     echo '"' . strtolower($name) .'", ';
            // }
			// echo ']';


		?>


		// let test = [
		// 	card = [ "code 1", "code 2" ],
		// 	test = [ "code 1", "code 2" ]
		// ]

		function CheckForm () {
			// check if TAbelname is parent of Name
			document.getElementById("HTMLCODE").value = ''
			// return !document.getElementById("TABELNAME").value == document.getElementById("NAME").id;
		}



		function editcode() {
			var value = document.getElementById("TABELNAME").value;
			// console.log(value)
            var name = document.getElementById("NAME").value;
            // create ajax call
            var xhttp = new XMLHttpRequest();
            // set the callback function
            xhttp.onreadystatechange = function() {
                // check if the response is ready
                if (this.readyState == 4 && this.status == 200) {
                    // add the response to the element
                    let json = JSON.parse(this.responseText)
					// console.log(json);
					let code = document.getElementById("HTMLCODE")
					let codeblock = document.getElementById("testing")
					
                    code.value = html_beautify(json.card[name])
					

					html = hljs.highlightAuto(code.value)

					codeblock.innerHTML = html.value
					// code.value = html.value
					// console.log(html.value);
                }
            };
            // open the request
			
            xhttp.open("GET", '<?php echo $base_link . "/index.php?item=" ?>' + value, true);
            // send the request
            xhttp.send();
            
		}

		check()
        function check() {
            // get value from slection
            var value = document.getElementById("TABELNAME").value;
            console.log(value)

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

		document.getElementById("html").reset()
		document.getElementById("table").reset()

		function checkName() {
			// Get the entered name
			var name = (document.getElementById("name").value).toLowerCase();

			console.log({
				test: options.includes(name),
				name,
				options
			})
			// Check if the name exists in your database or list
			// This is where you would implement your own logic to check for name existence

			// If the name exists, add the error class to the input field and display an error message
			if (options.includes(name)) { // replace "John Doe" with your name check logic
				document.getElementById("name").classList.add("error");
				document.getElementById("name-error").textContent = "Name already exists";
			}
			// If the name does not exist, remove the error class and clear the error message
			else {
				document.getElementById("name").classList.remove("error");
				document.getElementById("name-error").textContent = "";
			}
		}

		function validateForm() {
			return !document.getElementById("name").classList.contains("error")
		}


		const source = document.getElementById('name')

		source.addEventListener('input', checkName);
		source.addEventListener('propertychange', checkName); // for IE8
		// Firefox/Edge18-/IE9+ don’t fire on <select><option>
		// source.addEventListener('change', inputHandler); 

</script>
</html>
