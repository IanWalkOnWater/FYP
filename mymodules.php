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
  <img src="http://www.lboro.ac.uk/media/wwwlboroacuk/internal/styleassets/img/LU_logo.png" alt="LU Logo">
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

try {
	    $conn = new PDO("mysql:host=$servername;dbname=coidckw", $username, $password);
	    // set the PDO error mode to exception
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    

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

// Join Assessment, Mark Critera and Module Table together 
//SELECT Module.*, Assessment.*, Mark_Criteria.* FROM Module JOIN Assessment ON Module.Module_Code = Assessment.Module_Code JOIN Mark_Criteria ON Assessment.Assessment_ID = Mark_Criteria.Assessment_ID 
	   
	    // use exec() because no results are returned
	    //$conn->exec($sql);
	    
	    $jsonToEncode = array();
	    $tempvar = array();

	    foreach ($conn->query($sql) as $row) 
		  {
  			$tempvar = array
        (
  				"module_code" => $row['Module_Code'],
  				"module_mark" => $row['Module_Mark'],
          "module_title" => $row['Module_Title'],
  				"student_id" => $row['Student_ID'],
          "student_name" => $row['Student_Name'],
          "staff_id" => $row['Staff_ID'],
          "lecturer" => $row['Lecturer_Name'],
          "semester1" => $row['Semester1'],
          "semester2" => $row['Semester2'],
          "year" => $row['Year'],
          "category" => $row['Category'],

  			);

        $name = $row['Student_Name'];
        $jsonToEncode[] = $tempvar;
		  }

    $assessmentSQL = "SELECT Module.*, Assessment.*, Mark_Criteria.*, Lecturer.*, Student_Mark_Criteria.* FROM ";
    $assessmentSQL .= "Module JOIN Assessment ON Module.Module_Code = Assessment.Module_Code JOIN ";
    $assessmentSQL .= "Mark_Criteria ON Assessment.Assessment_ID = Mark_Criteria.Assessment_ID JOIN ";
    $assessmentSQL .= "Lecturer ON Lecturer.Staff_ID = Module.Staff_ID JOIN "; 
    $assessmentSQL .= "Student_Mark_Criteria ON Student_Mark_Criteria.Criteria_ID = Mark_Criteria.Criteria_ID";

    echo $assessmentSQL;
     echo "<br/>";
	 
    foreach ($conn->query($assessmentSQL) as $row) 
      {
        $rawAssessmentData = array
        (
          "moduleCode" => $row['Module_Code'],
          "moduleTitle" => $row['Module_Title'],
          "staffID" => $row['Staff_ID'],
          "lecturerName" => $row['Lecturer_Name'],
          "assessmentName" => $row['Assessment_Name'],
          "assessmentWeighting" => $row['Assessment_Weighting'],
          "assessmentID" => $row['Assessment_ID'],
          "criteriaID" => $row['Criteria_ID'],
          "criteriaName" => $row['Criteria_Name'],
          "weighting" => $row['Weighting'],
          "maxMark" => $row['Max_Mark'],
          "year" => $row['Year'],
          "studentMark" => $row['Student_Mark'],
          "criteriaFeedback" => $row[ 'Criteria_Feedback']
          
        );

        //$name = $row['Student_Name'];
        $assessmentDataArray[] = $rawAssessmentData;
      }   
		//$someJson = json_encode($tempvar);
		$moduleInfoJSON = json_encode($jsonToEncode);
    $assessmentDataJSON = json_encode($assessmentDataArray);
    print "Welcome " . $name . "! (logout)<br/>";
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?> 



<div  class="section-title">All Modules </div>




<br/>
<br/>
<br/>
<!--<button type = "button" onclick = "drawAllModules()"> Show all modules </button> -->
<button type = "button" onclick = "resetButtonHandler()"> Up a level  </button>
<button type = "button" onclick = "compareButtonHandler()"> Compare </button>
<select id = "filterSelector" onchange = "filterBarChart(this)">
  <option>All</option>
</select>
<canvas width = "10000" height= "300" id= "myCanvas"></canvas>
<br/>
<div id = "moduleInfoContainer">
  <div id = "moduleInfoDiv" class = "moduleInfoDiv"></div>
  <div id = "moduleInfoDiv2" class = "moduleInfoDiv"></div>  
</div>


<script type="text/javascript">
  var abc = <?php echo $moduleInfoJSON; ?>;
  var assessmentDataJSONArray = <?php echo $assessmentDataJSON; ?>;

  var moduleMarkArray = [];
  var moduleCodeArray = [];
  var moduleObjectArray = [];
  var globalTestvara = [];
  var globalBarPositionArray = [];
  var globalTheoryArray = [];
  var globalProgrammingArray = [];
  var globalMathematicsArray = [];

  var selectElement = document.getElementById("filterSelector");
  var categoryArray = [];

  var objectArray = []; // Store each bar as an object and put it into this array
  for(var i=0; i<abc.length; i++)
  {
  	
  	moduleMarkArray[moduleMarkArray.length] = abc[i].module_mark;
  	moduleCodeArray[moduleCodeArray.length] = abc[i].module_code;

    moduleObjectArray.push({  
            moduleCode: abc[i].module_code,
            moduleTitle: abc[i].module_title,
            staffID: abc[i].staff_id,
            lecturerName: abc[i].lecturer,
            semester1: abc[i].semester1,
            semester2: abc[i].semester2,
            moduleMark: abc[i].module_mark,
            year: abc[i].year,
            category: abc[i].category,
          });

    switch( abc[i].category )
    {
      case "Theory": globalTheoryArray.push( moduleObjectArray[i] ); break;
      case "Programming": globalProgrammingArray.push( moduleObjectArray[i] ); break;
      case "Mathematics": globalMathematicsArray.push( moduleObjectArray[i] ); break;


    }

    // Check if the category is in the category array, if not then add it to dropdown list
    if( categoryArray.indexOf( abc[i].category) == -1)
    {  
      var optionElement = document.createElement("option");
      optionElement.textContent = abc[i].category;
      selectElement.appendChild( optionElement);
      categoryArray.push( abc[i].category);
    };  
  }

      // Helper function that takes 1 parameter instead of 5
     function drawBarChartFromObject ( inputObject)
    {
       var a = drawBarChart(
                    inputObject.dataArray,
                    inputObject.dataLabelArray,
                    inputObject.barWidth,
                    inputObject.canvasHeight,
                    inputObject.canvas,
                    inputObject.lengthMultiplier,
                    inputObject.fadedColour
                  );
       currentCanvasChart = inputObject;
      
       assignClickEvent( inputObject.canvas, a, inputObject );
    }

    // Input a module code and grab all relevant module and assessment info
    function getAssessmentInfo ( moduleCodeInput)
    {
      var returnDataArray = [];
      for( var i = 0; i< assessmentDataJSONArray.length; i++)
      {
        
        if( moduleCodeInput == assessmentDataJSONArray[i].moduleCode)
        {
          returnDataArray.push( assessmentDataJSONArray[i]);
          
        }  
      }

      // If not assessment data was found try looking at the module info array
      if( returnDataArray.length == 0)
      {

        for( var i = 0; i< moduleObjectArray.length; i++)
       {
          if( moduleCodeInput == moduleObjectArray[i].moduleCode)
          {
            returnDataArray.push( moduleObjectArray[i] );
          }  
        }  

      }    
      return( returnDataArray )        
    } // End of function getAssessmentInfo


    function drawSummaryChart( moduleObjectArray, canvas)
    {
     
      var tempscorearray2011 = [];
      var tempscorearray2012 = [];
      var scorearray2015 = [];   
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
            case "2015": scorearray2015.push(moduleObjectArray[i]);
                      break;                    
            default: console.log( "error in switch with: " + moduleObjectArray[i]);
          }

      }  
     var averageScore2011 = calculateAverage( tempscorearray2011);
     var averageScore2012 = calculateAverage( tempscorearray2012);
     var averageScore2015 = calculateAverage( scorearray2015);
     var spaceFromBottom = 20;
     var spaceBetweenBars = 70;
     var barwidth = 50;
     var lengthMultiplier = 2;


      yposition = (canvasHeight - Number(averageScore2011) - spaceFromBottom);
      xposition = spaceBetweenBars;
      dataValue = Number( averageScore2011.toFixed(1) ); // Trim the number to 1d.p
      
      var temparray2011 = {
        topLeft : [ xposition, yposition ],
          topRight : [ xposition + barWidth , yposition  ],
          bottomLeft : [xposition , yposition + dataValue],
          bottomRight : [xposition + barWidth, yposition + dataValue],
          value : dataValue,
          year: 2011,
      }
      
      drawOneBar( context, xposition, yposition, barWidth, dataValue, spaceFromBottom, lengthMultiplier);
      var textPosition =  parseInt(dataValue) + yposition + spaceFromBottom ;
      context.fillText( "Part A", xposition, textPosition);
      // Do the same for 2012 data
      yposition = (canvasHeight - Number(averageScore2012) - spaceFromBottom);
      xposition = xposition + spaceBetweenBars;
      dataValue = Number( averageScore2012.toFixed(1) );
      
      drawOneBar( context, xposition, yposition, barWidth, dataValue, spaceFromBottom, lengthMultiplier);// Draw part B bar
      textPosition =  parseInt(dataValue) + yposition + spaceFromBottom ;
      context.fillText( "Part B", xposition, textPosition);

      var temparray2012 = {
        topLeft : [ xposition, yposition ],
          topRight : [ xposition + barWidth , yposition  ],
          bottomLeft : [xposition , yposition + dataValue],
          bottomRight : [xposition + barWidth, yposition + dataValue],
          value : dataValue,
          year: 2012,
      }
      // Do same again for 2015 data
      yposition = (canvasHeight - Number(averageScore2015) - spaceFromBottom);
      xposition = xposition + spaceBetweenBars;
      dataValue = Number( averageScore2015.toFixed(1) ); // Trim the number to 1d.p
      
      var temparray2015 = {
        topLeft : [ xposition, yposition ],
          topRight : [ xposition + barWidth , yposition  ],
          bottomLeft : [xposition , yposition + dataValue],
          bottomRight : [xposition + barWidth, yposition + dataValue],
          value : dataValue,
          year: 2015,
      }
      
      drawOneBar( context, xposition, yposition, barWidth, dataValue, spaceFromBottom, lengthMultiplier);
      var textPosition =  parseInt(dataValue) + yposition + spaceFromBottom ;
      context.fillText( "Part T", xposition, textPosition);

      var arrayOfScores2011 = [];
      var arrayOfScores2012 = [];
      var arrayOfScores2015 = [];
      var arrayofModuleCodes2011 =[];
      var arrayofModuleCodes2012 = [];
      var arrayofModuleCodes2015 = [];
      // I hate to do this but iterate over tempscorearray and pull out all mark scores and assign it to modulemarkarray
      for( var a = 0; a < tempscorearray2011.length; a++)
      {
        arrayOfScores2011.push( Number(tempscorearray2011[a].moduleMark));
        arrayofModuleCodes2011.push( tempscorearray2011[a].moduleCode);
      }

      barChartParameterObject2011 = {
        dataArray: arrayOfScores2011,
        dataLabelArray: arrayofModuleCodes2011,
        barWidth: barWidth,
        canvasHeight: canvasHeight,
        canvas: canvas,
        lengthMultiplier: 2
      }
      // Do the same for 2012
      for( var a = 0; a < tempscorearray2012.length; a++)
      {
        arrayOfScores2012.push( Number(tempscorearray2012[a].moduleMark));
        arrayofModuleCodes2012.push( tempscorearray2012[a].moduleCode);
      }

      barChartParameterObject2012 = {
        dataArray: arrayOfScores2012,
        dataLabelArray: arrayofModuleCodes2012,
        barWidth: barWidth,
        canvasHeight: canvasHeight,
        canvas: canvas,
        lengthMultiplier: 2
      }
      // Do same again for 2015 data
      for( var a = 0; a < scorearray2015.length; a++)
      {
        arrayOfScores2015.push( Number(scorearray2015[a].moduleMark));
        arrayofModuleCodes2015.push( scorearray2015[a].moduleCode);
      }

      barChartParameterObject2015 = {
        dataArray: arrayOfScores2015,
        dataLabelArray: arrayofModuleCodes2015,
        barWidth: barWidth,
        canvasHeight: canvasHeight,
        canvas: canvas,
        lengthMultiplier: 2
      }

      var newarray = [ temparray2011, temparray2012, temparray2015];

      assignClickEvent( canvas, newarray, barChartParameterObject2011 , barChartParameterObject2012, barChartParameterObject2015);

    } // End of drawSummaryChart
    /**************************************************************
    * populate the info div with module and assessment info
    ***************************************************************/
    function populateInfoDiv( moduleCodeInput)
    {
          var moduleInfoDiv = document.getElementById("moduleInfoDiv");
          var moduleInfo = getAssessmentInfo( moduleCodeInput );
         
          var lecturerName = moduleInfo[0].lecturerName;
          var moduleTitle = moduleInfo[0].moduleTitle;
          var moduleYear = moduleInfo[0].year;
          var currentAssessment = "";
          var htmlString = "";

          htmlString += "<p>" + moduleYear +" " +  moduleCodeInput + "</p>";
          
          htmlString += "<p >" + moduleTitle + "</p>";
          htmlString += "<p> Lecturer: " + lecturerName + "</p>";
          htmlString += "<p> Class Average: 69%" +  "</p>";

          //htmlString += "<div class='collapsable' data-height='400'>";
          if( moduleInfo[0].criteriaID != undefined )
          {  
              
              var numberOf = 1;
              for( var i = 0; i< moduleInfo.length; i++)
              { 
                var datum = moduleInfo[i];
                if( currentAssessment != datum.assessmentID )
                {
                  htmlString += "<p>" + datum.assessmentName + "</p>";    
                  
                }  
                currentAssessment = datum.assessmentID;

                if (numberOf == 1) htmlString += "<div class = 'infoRow' >";
                htmlString += "<div class='collapsable' data-height='400'>";
                htmlString += "<div onclick='toggleOpen(this);'>";           
                
                
                htmlString += "<p>" + datum.criteriaName + ": " + datum.studentMark + "/" + datum.maxMark + "</p>";
                htmlString += "<p>" + datum.criteriaFeedback + "</p>";
                htmlString += "</div>"; 
                htmlString += "</div>";
                if (numberOf == 4)
                {
                  numberOf = 0;
                  htmlString += "</div  >";
                } 
                numberOf++;
              }
            
          }
          
          moduleInfoDiv.innerHTML = htmlString;      
    }// End of populateInfoDiv
    /**************************************************************
    * populate the info div2 with module and assessment info
    ***************************************************************/
    function populateInfoDiv2( moduleCodeInput )
    {
          var moduleInfoDiv1 = document.getElementById("moduleInfoDiv");
          moduleInfoDiv1.className += " half-width";

          var moduleInfoDiv = document.getElementById("moduleInfoDiv2");
          moduleInfoDiv.className += " half-width";
          
          var moduleInfo = getAssessmentInfo( moduleCodeInput );
         
          var lecturerName = moduleInfo[0].lecturerName;
          var moduleTitle = moduleInfo[0].moduleTitle;
          var moduleYear = moduleInfo[0].year;
          var htmlString = "";

          htmlString += "<p>" + moduleYear +" " +  moduleCodeInput + "</p>";
          
          htmlString += "<p >" + moduleTitle + "</p>";
          htmlString += "<p> Lecturer: " + lecturerName + "</p>";

          //htmlString += "<div class='collapsable' data-height='400'>";
          if( moduleInfo[0].criteriaID != undefined )
          {  
              
              var numberOf = 1;
              for( var i = 0; i< moduleInfo.length; i++)
              { 

                if (numberOf == 1) htmlString += "<div class = 'infoRow' >";
                htmlString += "<div class='collapsable' data-height='400'>";
                htmlString += "<div onclick='toggleOpen(this);'>";           
                var datum = moduleInfo[i];
                htmlString += "<p>" + datum.criteriaName + ": " + datum.studentMark + "/" + datum.maxMark + "</p>";
                htmlString += "<p>" + datum.criteriaFeedback + "</p>";
                htmlString += "</div>"; 
                htmlString += "</div>";
                if (numberOf == 4)
                {
                  numberOf = 0;
                  htmlString += "</div  >";
                } 
                numberOf++;
              }
            
          }
          
          moduleInfoDiv.innerHTML = htmlString;      
    }// End of populateInfoDiv2

    var globalClickFunction = null;
    var globalHoverFunction = null;
    // Give the canvas a click event listener
    function assignClickEvent( canvasObject, positionObjectArray, barChartParameters, optionalInput, optionalInput2 )
    {
      
      try 
      {
        canvas.removeEventListener("click", globalClickFunction )     
      }
      catch(error)
      {
        console.log( error);
      }

      finally {

        globalClickFunction = function (event) {
            var mousePos = getMousePos(canvasObject, event);
            var message = 'Mouse position: ' + mousePos.x + ',' + mousePos.y;
            
            for( var counter = 0; counter < positionObjectArray.length; counter++ )
            {
             
              if( checkBarIsClicked( [mousePos.x, mousePos.y ], positionObjectArray[counter]) == true )
               {
                  var barPositionObject = positionObjectArray[counter];
                  // If bar is clicked do something
                  console.log( barPositionObject);
                  console.log( counter + " was clicked;")

                  // If a bar with Part (years) is clicked then go into this block
                  if(barPositionObject.year != undefined)
                  {
                    switch( barPositionObject.year)
                    {
                      case 2011: drawBarChartFromObject( barChartParameters);
                                return;
                      case 2012: drawBarChartFromObject( optionalInput);
                                return;
                      case 2015: drawBarChartFromObject( optionalInput2);
                                return;                                        
                    };
                  }  

                  // If a bar with module codes is clicked then go into this block
                  if(barPositionObject.moduleCode != undefined)
                  {
                    if(compareMode == true ) compareModeClickHandler( canvasObject, barPositionObject);// Code only runs if in compare mode
                    else populateInfoDiv (barPositionObject.moduleCode );
                  }
                // break out of for loop if a match is found
                break;       
               } 
                  
            }// End of for loop
          }; // End of globalClickFunction

         canvas.addEventListener("click", globalClickFunction,  false);
         console.log( "current canvas chart is:");
         console.log( currentCanvasChart);
      }
    }// End of assignClickEvent

    /**************************************************************
    * code for compare mode
    ***************************************************************/
    function compareModeClickHandler( canvasObject, barPositionObject )
    {
      // If number of bars selected is less than 2 and the bar that is clicked is Not in barSelectedArray
      if( barsSelectedtoCompare < 2 && barSelectedArray.indexOf( barPositionObject) == -1)
      {  
        // Fill in the bar with unfaded colour
        drawOneBar( canvasObject.getContext('2d'),
            barPositionObject.topLeft[0],
            barPositionObject.topLeft[1],
            50,
            barPositionObject.value,
            20,
            2,
            false);

        // check the number of bars selected and populate the right div
        if( barsSelectedtoCompare == 0) populateInfoDiv(barPositionObject.moduleCode );
        else
        {
          populateInfoDiv2(barPositionObject.moduleCode);
        }  
        barsSelectedtoCompare++;
        barSelectedArray.push( barPositionObject);

      }
      else // If the bar is already in barSelectedArray then toggle this bar back to faded as it is not selected anymore
      {    
        if(barSelectedArray.indexOf( barPositionObject) > -1 )
        {

          drawOneBar( canvasObject.getContext('2d'),
              barPositionObject.topLeft[0],
              barPositionObject.topLeft[1],
              50,
              barPositionObject.value,
              20,
              2,
              true);
          barsSelectedtoCompare--;
          barSelectedArray.splice( barSelectedArray.indexOf( barPositionObject), 1 );

          // When first bar is unclicked then assign moduleinfodiv1 with the contents of moduleinfodiv2
          var infoDiv1 = document.getElementById("moduleInfoDiv");
          var infoDiv2 = document.getElementById("moduleInfoDiv2");
          if(infoDiv1.innerHTML.indexOf( barPositionObject.moduleCode) > -1 )
          {
            infoDiv1.innerHTML = infoDiv2.innerHTML;
            infoDiv2.innerHTML = "";
            infoDiv1.className = "moduleInfoDiv";
          }
          
        } 
      }// End of else    
    }      
    /**************************************************************
    * calculate the average score
    ***************************************************************/
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

    /**************************************************************
    * code for Filtering
    ***************************************************************/
    function filterBarChart(filterSelectorElement)
    {
      filterResetArray = currentCanvasChart;
      var allCurrentModuleArray = currentCanvasChart.dataLabelArray;
      
      // Get the filter that the user has selected
      var filterSelected = filterSelectorElement.options[ filterSelectorElement.selectedIndex].text;
      // If All is picked then just return and exit the function
      if( filterSelected == "All") return;
      var moduleObjectArray = null;
      var arrayToCompare = [];

      var tempArray = [];
      var tempsArray = [];

     

      switch( filterSelected)
      {
        case "Theory": moduleObjectArray = globalTheoryArray; break;
        case "Programming": moduleObjectArray = globalProgrammingArray; break;
        case "Mathematics": moduleObjectArray = globalMathematicsArray; break;
      }

      for( var i = 0; i< moduleObjectArray.length; i++)
      {
        arrayToCompare.push( moduleObjectArray[i].moduleCode);
      }  
      

      for( var i = 0; i< allCurrentModuleArray.length; i++)
      {
        if( arrayToCompare.indexOf( allCurrentModuleArray[i] ) > -1)
        {  
          tempArray.push( allCurrentModuleArray[i]);
          tempsArray.push( currentCanvasChart.dataArray[i]);
        }  

      }  
      

     var returnvalue =  drawBarChart ( tempsArray, 
                            tempArray, 
                            currentCanvasChart.barWidth, 
                            currentCanvasChart.canvasHeight,
                            canvas,
                            currentCanvasChart.lengthMultiplier,
                            false);
     console.log( returnvalue);

     assignClickEvent( canvas, returnvalue )

    }
      
    // Draws a bar chart with every module
    function drawAllModules()
    {
      resetCanvas( canvas);
      drawBarChart( moduleMarkArray, 
                  moduleCodeArray, 
                  barWidth, 
                  canvasHeight,
                  canvas,
                  2 // Length Multiplier
                  ); 
    }// End of drawAllModules

    //Function that resets the canvas to when the page is first loaded
    function resetButtonHandler()
    {
      resetCanvas(canvas);
      drawSummaryChart( moduleObjectArray, canvas);
    };

    // Function that fires when the compare button is clicked
    function compareButtonHandler()
    {
      var currentCanvasChartObject = currentCanvasChart;
      
      if( compareMode == false)
      {
        compareMode = true;
        currentCanvasChartObject.fadedColour = true;
      }
      else
      {
        currentCanvasChartObject.fadedColour = false;
        compareMode = false;
      } 
      drawBarChartFromObject( currentCanvasChartObject);
    }

    // Toggles collapsible content
    function toggleOpen (elementObject) 
    {
        var elem = elementObject.parentNode;
        
        if (elem.className.indexOf("open") == -1) 
        {
            elem.className += " open";
        } 
        else 
        {
          var pieces = elem.className.split(" ");
          pieces.splice(pieces.indexOf("open"), 1);
          elem.className = pieces.join(" ");
        }  
    }
/**********************************************************************/
console.log( "moduleObjectArray is:");
console.log( moduleObjectArray);
  var canvas = document.getElementById('myCanvas');

  var currentCanvasChart = null;
  var compareMode = false;
  var barsSelectedtoCompare = 0;
  var barSelectedArray = [];

  var filterResetOjbect = null; // This array contains the bar chart before a filter was applied

  var barWidth = 50;
  var canvasHeight = 300;
  drawSummaryChart( moduleObjectArray, canvas);



    

</script>




</body>

</html>