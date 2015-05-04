<?php


$usernameInput=$_POST['userName'];
$passwordInput=$_POST['password'];

$host = 'co-project.lboro.ac.uk';
$username = 'coidckw';
$dbName ='coidckw';
$password ='ekd93pqk';


$errorMessage = "";

try {
	    $conn = new PDO("mysql:host=$host;dbname=$dbName", $username, $password); // Setup the connection to database
	    // set the PDO error mode to exception
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    
	    $usernameSql = "SELECT * FROM Student WHERE Username = '$usernameInput'";
	    $queryResults1 = $conn->query($usernameSql);
	    //$usernameQueryResult = $conn->query($usernamesql); // Execute the SQL query
		$usernameResultArray = $queryResults1 -> fetchAll(); // Put the query results in an array

		if( count($usernameResultArray) < 1) // If less then zero then no results were found
		{	
			$errorMessage = "The username $usernameInput does not exist"; // Create an error message
			header('location: index.php?failed=1&error=' . $errorMessage); // Redirect the user to the login page
			exit();
		}

	    // Build the SQL
		$sql = "SELECT * FROM Student WHERE Username = '$usernameInput' AND Password = '$passwordInput'";
		
		$queryResults = $conn->query($sql); // Execute the SQL query
		$resultArray = $queryResults -> fetchAll(); // Put the query results in an array

		session_start();

		if( count($resultArray) == 1) // If the number of results in the array is exactly one then a match was found
		{	
			$_SESSION[ 'loggedIn'] = true; // Set logged in as true
			$_SESSION[ 'usernameInput'] = $usernameInput; // Save the username in a session varialbe
			header('location: mymodules.php'); // Redirect the user to the main page
		}
		else
		{
			
			$errorMessage = "Username and password combination is incorrect"; // Create an error message
			header('location: index.php?failed=1&error=' . $errorMessage); // Redirect the user to the login page

		}	
	}
catch(PDOException $e)
    {    	
    	$errorMessage = "Could not connect to server" . $e->getMessage(); // Create an error message
    	header('location: index.php?failed=1&error=' . $errorMessage);
    	echo "Connection failed: " . $e->getMessage();
    }		


?>