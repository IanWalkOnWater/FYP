<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="robots" content="noindex, nofollow" />
        <link rel="shortcut icon" href="http://learn.lboro.ac.uk/theme/image.php/lboro2/theme/1416506521/favicon" />
        <title>My Modules </title>
        <link rel="stylesheet" type="text/css" href="css/main.css"/>
        <script type="text/javascript" src="js/canvas_functions.js"></script>
    </head>

<body>

<div class="page-header" id="page-header"> 
  <img src="http://www.lboro.ac.uk/media/wwwlboroacuk/internal/styleassets/img/LU_logo.png">
  Home &nbsp; My Modules &nbsp; 
</div>

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
	    echo "Connected successfully <br/><br/>"; 

	    // Build the SQL
	    //$sql = "SELECT * FROM coidckw.Student_Module";
$sql = "SELECT Student_Module.*, Student.*, Module.*, Lecturer.* FROM Student_Module JOIN Student ON ";
$sql .= "Student.Student_ID = Student_Module.Student_ID JOIN Module ON Module.Module_Code = Student_Module.Module_Code ";
$sql .= "JOIN Lecturer ON Module.Staff_ID = Lecturer.Staff_ID ";

// UPDATE Module
// SET Staff_ID = 
// WHERE Module_Code='';
// SELECT * FROM Module

// SELECT * FROM Module Join  Lecturer ON Module.Staff_ID = Lecturer.Staff_ID

//SELECT Student_Module.*, Student.*, Module.* FROM Student_Module JOIN Student ON Student.Student_ID = Student_Module.Student_ID JOIN Module ON Module.Module_Code = Student_Module.Module_Code 

	   echo $sql;
	    // use exec() because no results are returned
	    //$conn->exec($sql);
	    //echo "Database created successfully<br>";
	    $jsonToEncode = array();
	    $tempvar = array();

	    foreach ($conn->query($sql) as $row) 
		{
			// print $row['Module_Code'] . "\t";
			// print $row['Module_Mark'] . "\t ";
   //    print $row['Module_Title'] . "\t ";
			// print $row['Student_ID'] . "<br/>";

			$tempvar = array(
				"module_code" => $row['Module_Code'],
				"module_mark" => $row['Module_Mark'],
        "module_title" => $row['Module_Title'],
				"student_id" => $row['Student_ID'],
        "staff_id" => $row['Staff_ID'],
        "semester1" => $row['Semester1'],
        "semester2" => $row['Semester2'],
        "year" => $row['Year'],
        
			);
      $name = $row['Student_Name'];
			 $jsonToEncode[] = $tempvar;
		}
	 print "Welcome " . $name . "! (logout)<br/>";
		//$someJson = json_encode($tempvar);
		$someJson = json_encode($jsonToEncode);
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?> 



<div style="background-color:#586A95; color:white; border:1px solid #DDD;padding-left: 1%;border-radius: 5px;">All Modules </div>


</div>

<br/>
<br/>
<br/>
<canvas width= "10000" height= "1000" id= "myCanvas"></canvas>
<div id= "moduleInfoDiv">



<script type="text/javascript">
var abc = <?php echo $someJson; ?>;
//console.log(abc.module_mark);
//console.log(abc);
var moduleMarkArray = [];
var moduleCodeArray = [];
var moduleObjectArray = [];

var objectArray = []; // Store each bar as an object and put it into this array
for(var i=0; i<abc.length; i++)
{
	//console.log(abc[i].module_code);
	moduleMarkArray[moduleMarkArray.length] = abc[i].module_mark;
	moduleCodeArray[moduleCodeArray.length] = abc[i].module_code;

  moduleObjectArray.push({  
          moduleCode: abc[i].module_code,
          moduleTitle: abc[i].module_title,
          staffID: abc[i].staff_id,
          semester1: abc[i].semester1,
          semester2: abc[i].semester2,
          moduleMark: abc[i].module_mark,
          year: abc[i].year,
        });

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
      var moduleCode = "";
      var heightMultiplyer = 1.5;
      var spaceFromBottom = 20; // moves all the bars up by x pixels

      
      canvas.height = canvasHeight;
      var context = canvas.getContext('2d');

      for( var i =0; i< dataArray.length; i++)
      {
        dataValue = dataArray[i];

        xposition = xposition + (spaceBetweenBars);
        
        yposition = (canvasHeight - Number(dataArray[i]) - spaceFromBottom) ;
        
        drawOneBar( context, xposition, yposition, barWidth, dataValue, spaceFromBottom);

        topLeft = [ xposition, yposition ];
        topRight = [ xposition + barWidth, yposition  ];
        bottomLeft = [xposition , yposition + dataValue];
        bottomRight = [xposition + barWidth, yposition + dataValue];
      
        if( dataArray.length == dataLabelArray.length)
        {
          context.font = 'italic 15pt Calibri';
            
          //var textPosition = parseInt(yposition) + parseInt(dataValue) + 20;
          //var textPosition =  parseInt(dataArray[i]) + yposition + 20 ;
          var textPosition =  parseInt(dataValue) + yposition + spaceFromBottom ;
          context.fillText( dataLabelArray[i], xposition, textPosition);//190);//yposition + dataValue + 20);
          moduleCode = dataLabelArray[i];
        }
        else{
          moduleCode = "unknown";
        }  

        objectArray.push({  
          topLeft : [ xposition, yposition ],
          topRight : [ xposition + barWidth , yposition  ],
          bottomLeft : [xposition , yposition + dataValue],
          bottomRight : [xposition + barWidth, yposition + dataValue],
          moduleCode: moduleCode,
        });
      
      }; // End of For

      return objectArray;

     } // End of function drawBarChart  

     function drawOneBar( context,
                        xposition,
                        yposition,
                        barWidth,
                        dataValue,
                        spaceFromBottom)
     {
        try{
            context.beginPath();
            context.rect(xposition, yposition , barWidth, dataValue ); // X-pos, Y-Pos ( from top), width, height
            context.fillStyle = '#ffca28';

            
            if( dataValue > 70) context.fillStyle = "#388e3c";
            context.fill();
            // Fill in the Border
            // context.lineWidth = 2;
            // context.strokeStyle = 'black';
            // context.stroke();
            
            context.fillStyle = "black";
            context.font = 'italic 10pt Calibri';
            context.fillText( dataValue, xposition, yposition - spaceFromBottom);
          }

          catch( error)
          {
            console.log( "Failed to draw one bar " + error);
          }

          context.closePath();
     }// End of drawOneBar

      

     
    function getModuleInfo ( moduleCodeInput)
    {
      for( var i = 0; i< moduleObjectArray.length; i++)
      {
        //console.log( moduleObjectArray[i].module_code );
        if( moduleCodeInput == moduleObjectArray[i].moduleCode)
        {
          console.log( moduleObjectArray[i].moduleTitle);
          return( moduleObjectArray[i] )
        }  
      }        
    } // End of function getModuleInfo

    function drawPartBarChart ( moduleObjectArray,
                                canvas)
    {
      var partAArray = [];
      var partBArray = [];
      var canvasHeight = canvas.height;
      var context = canvas.getContext('2d');
      var barWidth = 50; 
      var spaceFromBottom = 20;
      var spaceBetweenBars = 70;
      var xposition = 0;

      resetCanvas( canvas);
      console.log( "moduleObjectArray from drawPartBarChart is:");
      console.log( moduleObjectArray);
      for( var i = 0; i< moduleObjectArray.length; i++)
      {  
          var moduleCode = moduleObjectArray[i].moduleCode;
          var partCode = moduleCode.substr(2,1);
          switch( partCode)
          {
            case "A": partAArray.push(moduleObjectArray[i]);
                      break;           
            case "B": partBArray.push(moduleObjectArray[i]);
                      break;          
            default: console.log( "error in switch with: " + partCode);
          }


         
          
      }// End of for 
        // drawBarChart ( dataArray, 
        //                       dataLabelArray, 
        //                       barWidth, 
        //                       canvasHeight,
        //                       canvas) 
       

        for( var j =0; j< partAArray.length; j++)
         {
          dataValue = partAArray[j].moduleMark;
          
           xposition = xposition + (spaceBetweenBars);
          
           yposition = (canvasHeight - Number(dataValue) );// - spaceFromBottom) ;
          
           drawOneBar( context, xposition, yposition, barWidth, dataValue, spaceFromBottom);

          // topLeft = [ xposition, yposition ];
          // topRight = [ xposition + barWidth, yposition  ];
          // bottomLeft = [xposition , yposition + dataValue];
          // bottomRight = [xposition + barWidth, yposition + dataValue];
        
          // if( dataArray.length == dataLabelArray.length)
          // {
          //   context.font = 'italic 15pt Calibri';
              
          //   //var textPosition = parseInt(yposition) + parseInt(dataValue) + 20;
          //   //var textPosition =  parseInt(dataArray[i]) + yposition + 20 ;
          //   var textPosition =  parseInt(dataValue) + yposition + spaceFromBottom ;
          //   context.fillText( dataLabelArray[i], xposition, textPosition);//190);//yposition + dataValue + 20);
          // }
         
        
        } // End of For  


        //console.log(partBArray);    
    }// End of drawPartBarChart


    function drawSummaryChart( moduleObjectArray, canvas)
    {
     
      var tempscorearray2011 = [];
      var tempscorearray2012 = [];   
      var context = canvas.getContext('2d');

      for( var i = 0; i< moduleObjectArray.length; i++)
      {

        var moduleYear = moduleObjectArray[i].year;
        switch( moduleYear)
          {
            case "2011": tempscorearray2011.push(moduleObjectArray[i]);
                      break;           
            case "2012": tempscorearray2012.push(moduleObjectArray[i]);
                      break;          
            default: console.log( "error in switch with: " + moduleObjectArray[i]);
          }


      }  
     var averageScore2011 = calculateAverage( tempscorearray2011);
     var averageScore2012 = calculateAverage( tempscorearray2012);
     var spaceFromBottom = 20;
     var spaceBetweenBars = 70;
     var barwidth = 50;


      yposition = (canvasHeight - Number(averageScore2011) - spaceFromBottom);
      xposition = 0;
      dataValue = Number( averageScore2011.toFixed(1) ); // Trim the number to 1d.p
      
      var temparray2011 = {
        topLeft : [ xposition, yposition ],
          topRight : [ xposition + barWidth , yposition  ],
          bottomLeft : [xposition , yposition + dataValue],
          bottomRight : [xposition + barWidth, yposition + dataValue],
          year: 2011,
      }
      
      drawOneBar( context, xposition, yposition, barWidth, dataValue, spaceFromBottom);
      var textPosition =  parseInt(dataValue) + yposition + spaceFromBottom ;
      context.fillText( "Part A", xposition, textPosition);

      yposition = (canvasHeight - Number(averageScore2012) - spaceFromBottom);
      xposition = xposition + spaceBetweenBars;
      dataValue = averageScore2012;
      
      drawOneBar( context, xposition, yposition, barWidth, dataValue, spaceFromBottom);// Draw part B bar
      textPosition =  parseInt(dataValue) + yposition + spaceFromBottom ;
      context.fillText( "Part B", xposition, textPosition);

      var temparray2012 = {
        topLeft : [ xposition, yposition ],
          topRight : [ xposition + barWidth , yposition  ],
          bottomLeft : [xposition , yposition + dataValue],
          bottomRight : [xposition + barWidth, yposition + dataValue],
          year: 2012,
      }

      var newarray = [ temparray2011, temparray2012];
      
      // Add a onclick event listener to the canvas
      canvas.addEventListener('click', function(evt) {
        var mousePos = getMousePos(canvas, evt);
        var message = 'Mouse position: ' + mousePos.x + ',' + mousePos.y;
        
        for( var counter = 0; counter < newarray.length; counter++ )
        {
          if( checkBarIsClicked( [mousePos.x, mousePos.y ], newarray[counter]) == true )
           {

            var arrayOfScores = [];
            var arrayofModuleCodes = [];
            // I hate to do this but iterate over tempscorearray and pull out all mark scores and assign it to modulemarkarray
            for( var a = 0; a < tempscorearray2012.length; a++)
            {
              arrayOfScores.push( Number(tempscorearray2012[a].moduleMark));

              arrayofModuleCodes.push( tempscorearray2012[a].moduleCode);

            }  
            moduleMarkArray = arrayOfScores;
            moduleCodeArray = arrayofModuleCodes;
            barWidth = 50;
            canvasHeight = 200;
            
            var testvara = drawBarChart( moduleMarkArray, 
                    moduleCodeArray, 
                    barWidth, 
                    canvasHeight,
                    canvas); 
           } 
        
        }
      }, false);

    } // End of drawSummaryChart

    function calculateAverage(inputArray)
    {
      var sum = 0;
      var result = -1;

      for( var counter = 0; counter < inputArray.length; counter++)
      {
        sum = sum + Number(inputArray[counter].moduleMark);
      }// End of For  

      
      if( sum > 0)
      {
        result = sum / inputArray.length;
      }  

      return result;
    }// End of calculateAverage

    //drawPartBarChart ( moduleObjectArray, canvas);
    var canvas = document.getElementById('myCanvas');
    //resetCanvas(canvas);
    //canvas = document.getElementById('myCanvas');
    


      var barWidth = 50;
      var canvasHeight = 200;

      
      // drawBarChart( moduleMarkArray, 
      //               moduleCodeArray, 
      //               barWidth, 
      //               canvasHeight,
      //               canvas); 
      // function clickHandler(event) {
      //   var mousePos = getMousePos(canvas, event);
      //   var message = 'Mouse position: ' + mousePos.x + ',' + mousePos.y;

      //   for( var counter = 0; counter < objectArray.length; counter++ )
      //   {
      //     if( checkBarIsClicked( [mousePos.x, mousePos.y ], objectArray[counter]) == true)
      //     {
      //       console.log(message);

      //     }            
      //   }
      // }// End of clickHandler

      // Add a onclick event listener to the canvas
      //canvas.addEventListener('click', clickHandler, false);

      

      //resetCanvas(canvas);

     drawSummaryChart( moduleObjectArray, canvas);

     //canvas.removeEventListener('click', clickHandler);

     // var moduleInfo = getModuleInfo( objectToCompare.moduleCode );

     //      document.getElementById("moduleInfoDiv").innerHTML = "<p>" + moduleInfo.moduleCode + "</p>";
     //      document.getElementById("moduleInfoDiv").innerHTML += "<p>" + moduleInfo.moduleTitle + "</p>";
     //      document.getElementById("moduleInfoDiv").innerHTML += "<p> Lecturer:" + moduleInfo.staffID + "</p>";
    

</script>




</body>

</html>