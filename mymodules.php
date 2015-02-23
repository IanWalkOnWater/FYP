<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="robots" content="noindex, nofollow" />
</head>

<body>

<?php

// Get all the data from the ajax page

//require_once('MDB2.php');



$host='co-project.lboro.ac.uk';
$username='coidckw';
$dbName=' coidckw';
$password='ekd93pqk';
//$servername = "localhost";
$servername = $host;

/*$conn=mysql_connect("co-project.lboro.ac.uk", $username, $password); 

$dsn = "mysql://$username:$password@$host/$dbName"; 

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    echo "Connected failed";
}

echo "connection success";

$sql = "SELECT * from Student";

if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}*/

try {
	    $conn = new PDO("mysql:host=$servername;dbname=coidckw", $username, $password);
	    // set the PDO error mode to exception
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    echo "Connected successfully <br/>"; 

	    // Build the SQL
	    $sql = "SELECT * FROM coidckw.Student_Module";
	    //$sql2 = "SELECT Degree_Programme FROM Student";
	    // use exec() because no results are returned
	    //$conn->exec($sql);
	    //echo "Database created successfully<br>";
	    $jsonToEncode = array();
	    $tempvar = array();
	    foreach ($conn->query($sql) as $row) 
		{
			print $row['Module_Code'] . "\t";
			print $row['Module_Mark'] . "\t </br>";
			//print $row['Student_ID'] . "</br>";

			$tempvar = array(
				"module_code" => $row['Module_Code'],
				"module_mark" => $row['Module_Mark'],
				"student_id" => $row['Student_ID'],
			);

			 $jsonToEncode[] = $tempvar;
		}
	
		//$someJson = json_encode($tempvar);
		$someJson = json_encode($jsonToEncode);
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }




?> 

<canvas width="10000" height="1000" id="myCanvas"></canvas>



<script type="text/javascript">
var abc = <?php echo $someJson; ?>;
//console.log(abc.module_mark);
//console.log(abc);
var moduleMarkArray = [];
var moduleCodeArray = []
for(var i=0; i<abc.length; i++)
{
	//console.log(abc[i].module_code);
	moduleMarkArray[moduleMarkArray.length] = abc[i].module_mark;
	moduleCodeArray[moduleCodeArray.length] = abc[i].module_code;

}

//console.log(moduleMarkArray);


 function drawBarChart ( dataArray, 
                            dataLabelArray, 
                            barWidth, 
                            canvasHeight,
                            canvas) 
    {     
      var dataValue = 0;
      var xposition = 0;
      var spaceBetweenBars = 70;

      
      canvas.height = canvasHeight;
      var context = canvas.getContext('2d');

      for( var i =0; i< dataArray.length; i++)
      {
        dataValue = dataArray[i];
        context.beginPath();
        xposition = xposition + (spaceBetweenBars);
        
        yposition = (canvasHeight - Number(dataArray[i]) - 20) ;
        
        context.rect(xposition, yposition, barWidth, dataValue); // X-pos, Y-Pos ( from top), width, height
        context.fillStyle = 'yellow';
        context.fill();
        context.lineWidth = 7;
        context.strokeStyle = 'black';
        context.stroke();

        context.fillStyle = "black";
        context.font = 'italic 10pt Calibri';
        context.fillText( dataValue, xposition, yposition - 20);
      
        if( dataArray.length == dataLabelArray.length)
        {
          context.font = 'italic 15pt Calibri';
            console.log(  parseInt( dataValue));
            console.log(parseInt(yposition) );
            console.log("");
          //var tVariable = parseInt(yposition) + parseInt(dataValue) + 20;
          //var tVariable =  parseInt(dataArray[i]) + yposition + 20 ;
          var tVariable =  parseInt(dataValue) + yposition + 20 ;
          context.fillText( dataLabelArray[i], xposition, tVariable);//190);//yposition + dataValue + 20);
          //console.log(tVariable);
        }  

      
      }; // End of For
     }  

     //var dataArray = [65, 66, 80, 41, 75];
      //var dataLabelArray = ["A", "B", "C", "D", "E"];
      //moduleCodeArray = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18];
      var barWidth = 50;
      var canvasHeight = 200;
//console.log(moduleCodeArray);
      var canvas = document.getElementById('myCanvas');
     drawBarChart( moduleMarkArray, 
                            moduleCodeArray, 
                            barWidth, 
                            canvasHeight,
                            canvas); 
      


</script>




</body>

</html>