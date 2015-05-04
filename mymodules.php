<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="robots" content="noindex, nofollow" />
        <link rel="shortcut icon" href="images/favicon.ico" />
        <title>My Feedback </title>
        <link rel="stylesheet" type="text/css" href="css/main.css"/>
        <script type="text/javascript" src="js/canvas_functions.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
    </head>

<body>

<div class="page-header" id="page-header"> 
  <img src="images/LULogo.png" alt="LU Logo">
  <p class="title-text">My Feedback</p><br/>
  <p class="logout-text"> <a href="index.php?logout=1"> Logout </a></p>
</div>

<?php

session_start();
// Check the user is logged in
if(isset($_SESSION[ 'loggedIn']) && isset($_SESSION[ 'usernameInput']) )
{
  if( $_SESSION[ 'loggedIn'] == true)
  {
    $usernameInput = $_SESSION[ 'usernameInput'];
  }  
  else//empty($user_name)
  {
    header('location: index.php');
  }

}
// User is not logged in so send them back to the login page
else
{
  header('location: index.php');
}

$host='co-project.lboro.ac.uk';
$username='coidckw';
$dbName='coidckw';
$password='ekd93pqk';
//$servername = "localhost";


try {
	    $conn = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
	    // set the PDO error mode to exception
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    

	    // Build the SQL
	    //$sql = "SELECT * FROM coidckw.Student_Module";
      $sql = "SELECT Student_Module.*, Student.*, Module.*, Lecturer.* FROM Student_Module JOIN Student ON ";
      $sql .= "Student.Student_ID = Student_Module.Student_ID JOIN Module ON Module.Module_Code = Student_Module.Module_Code ";
      $sql .= "JOIN Lecturer ON Module.Staff_ID = Lecturer.Staff_ID WHERE Student.Username = '" . $usernameInput . "'";

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
          "class_average" => $row['Class_Average']

  			);

        $name = "'" . $row['Student_Name'] . "'";
        $emailAddress = "'" .  $row[ 'Email_Address'] . "'";
        $jsonToEncode[] = $tempvar;
		  }

    $assessmentSQL = "SELECT Module.*, Assessment.*, Mark_Criteria.*, Lecturer.*, Student_Mark_Criteria.* , Student.* FROM ";
    $assessmentSQL .= "Module JOIN Assessment ON Module.Module_Code = Assessment.Module_Code JOIN ";
    $assessmentSQL .= "Mark_Criteria ON Assessment.Assessment_ID = Mark_Criteria.Assessment_ID JOIN ";
    $assessmentSQL .= "Lecturer ON Lecturer.Staff_ID = Module.Staff_ID JOIN "; 
    $assessmentSQL .= "Student_Mark_Criteria ON Student_Mark_Criteria.Criteria_ID = Mark_Criteria.Criteria_ID ";
    $assessmentSQL .= " JOIN Student ON Student.Student_ID = Student_Mark_Criteria.Student_ID WHERE Student.Username = '" . $usernameInput . "'";

     
	 
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
          "criteriaFeedback" => $row[ 'Criteria_Feedback'],
          "classAverage" => $row['Class_Average']
          
        );

        //$name = $row['Student_Name'];
        $assessmentDataArray[] = $rawAssessmentData;
      }   
		//$someJson = json_encode($tempvar);
		$moduleInfoJSON = json_encode($jsonToEncode);
    $assessmentDataJSON = json_encode($assessmentDataArray);
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?> 

<div class="welcome-text"> Welcome 
  <?php 
    echo str_replace( "'" , "", $name)  // Replace the ' with with a blank space
  ?>!
</div>  
<br/>

<div class="control-div">
  <button type="button" class= "control-button" onclick = "resetButtonHandler()"> <img src="images/arrow-up.png"><br/> Up a level </button>
  <button type="button" class= "control-button" onclick= "compareButtonHandler()"> <img src="images/compare.png"><br/> Compare </button>
 &nbsp;


  <div class="control-subdiv">
    <label for = "filterSelector"> Filter By:</label><br/>
    <select id = "filterSelector" onchange = "filterBarChart(this)">
      <option>All</option>
    </select>
  </div>
&nbsp;
  <div class="control-subdiv">
    <label for = "sortSelector"> Sort By:</label><br/>
    <select id = "sortSelector" onchange = "sortBarChart(this)">
      <option>Module Code</option>
      <option>Score (High to Low)</option>
      <option>Score (Low to High) </option>
    </select>
  </div> 
</div>  

<div id="canvasContainer"><canvas width = "10000" height= "300" id= "mainCanvas"></canvas></div>
<br/>
<div id = "moduleInfoContainer">
  <div id = "moduleInfoDiv" class = "moduleInfoDiv"></div>
  <div id = "moduleInfoDiv2" class = "moduleInfoDiv"></div>  
</div>


<script type="text/javascript">
  var abc = <?php echo $moduleInfoJSON; ?>;
  var assessmentDataJSONArray = <?php echo $assessmentDataJSON; ?>;
  var globalStudentName = <?php echo $name; ?>;
  var globalEmailAddress = <?php echo $emailAddress; ?>;

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
            classAverage: abc[i].class_average
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

    




   
/**********************************************************************/
console.log( "moduleObjectArray is:");
console.log( moduleObjectArray);
  var canvas = document.getElementById('mainCanvas');

  var currentCanvasChart = null;
  var preSortCanvasChart = null;
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