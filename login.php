<?php


$usernameInput=$_POST['userName'];
$passwordInput=$_POST['password'];

$host = 'co-project.lboro.ac.uk';
$username = 'coidckw';
$dbName ='coidckw';
$password ='ekd93pqk';

//$_SESSION[ 'wrongLogin'] = true;

$errorMessage = "";

try {
	    $conn = new PDO("mysql:host=$host;dbname=coidckw", $username, $password);
	    // set the PDO error mode to exception
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    

	    // Build the SQL
	    //$sql = "SELECT * FROM coidckw.Student_Module";
		$sql = "SELECT  * FROM Student WHERE Username = '$usernameInput'";
		//PDO::query ( string $statement )

		$a = $conn->query($sql);
		$b = $a -> fetchAll();

		echo $sql;	
		echo count($b);

		session_start();

		if( count($b) == 1)
		{	
			$_SESSION[ 'loggedIn'] = true;
			$_SESSION[ 'usernameInput'] = $usernameInput;
			header('location: mymodules.php');
		}
		else
		{
			
			$errorMessage = "Username and password combination is incorrect";
			header('location: index.php?failed=1&error=' . $errorMessage);

		}	
	}
catch(PDOException $e)
    {    	
    	$errorMessage = "Could not connect to server";
    	header('location: index.php?failed=1&error=' . $errorMessage);
    	echo "Connection failed: " . $e->getMessage();
    }		


?>