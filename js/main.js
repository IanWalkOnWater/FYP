var canvasObjectToUse = null;    
    /**************************************************************
    *Toggles collapsible content
    ***************************************************************/    
    function toggleOpen (elementObject) 
    {
        var elem = elementObject.parentNode;

        var childrenElem = elementObject.childNodes;        

        var plusElem = childrenElem[0];
        var minusElem = childrenElem[1];
        
        if (elem.className.indexOf("open") == -1) // Check if open is part of the class name
        {
            elem.className += " open"; // Append opeon onto the class name
            plusElem.className = "plus hidden"; // Hide the plus class
            minusElem.className = "minus"; // Show the hidden class
        } 
        else 
        {
          var pieces = elem.className.split(" "); // Split the class name into 2
          pieces.splice(pieces.indexOf("open"), 1); 
          elem.className = pieces.join(" "); // Join it back together

          plusElem.className = "plus";
          minusElem.className = "minus hidden";
        }  
    }
    /**************************************************************
    *Input a module code and grab all relevant module and assessment info
    ***************************************************************/    
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
    } 
    /**************************************************************
    *Helper function that takes 1 parameter instead of 5
    ***************************************************************/
     function drawBarChartFromObject ( inputObject)
    {
       var barObject = drawBarChart(
                    inputObject.dataArray,
                    inputObject.dataLabelArray,
                    inputObject.barWidth,
                    inputObject.canvasHeight,
                    inputObject.canvas,
                    inputObject.lengthMultiplier,
                    inputObject.fadedColour,
                    inputObject.classAverageArray
                  );
       currentCanvasChart = inputObject;
      
       assignClickEvent( inputObject.canvas, barObject, inputObject );
    }
    /**************************************************************
    * Draw the bar chart with all the Parts
    ***************************************************************/
    function drawSummaryChart( moduleObjectArray, canvas)
    {
     
      var tempscorearray2011 = [];
      var tempscorearray2012 = [];
      var scorearray2015 = [];   
      var context = canvas.getContext('2d');

      var arrayOfAverages2011 = [];
      var arrayOfAverages2012 = [];
      var arrayOfAverages2015 = [];

      for( var i = 0; i< moduleObjectArray.length; i++)
      {

        var moduleYear = moduleObjectArray[i].year;
        switch( moduleYear)
          {
            case "2011": tempscorearray2011.push(moduleObjectArray[i]);
                         arrayOfAverages2011.push( moduleObjectArray[i].classAverage);
                      break;           
            case "2012": tempscorearray2012.push(moduleObjectArray[i]);
                        arrayOfAverages2012.push( moduleObjectArray[i].classAverage);
                      break;
            case "2015": scorearray2015.push(moduleObjectArray[i]);
                        arrayOfAverages2015.push( moduleObjectArray[i].classAverage);
                      break;                    
            default: console.log( "error in switch with: " + moduleObjectArray[i]);
          }
      }  
     var averageScore2011 = calculateAverage( tempscorearray2011); // Calculate the average scores
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
   
      // Comment it out for now yposition = getNewYCoordinate( yposition, lengthMultiplier, spaceFromBottom, canvasHeight );
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
      context.fillText( "Part C", xposition, textPosition);

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
      // Create new bar ojbect
      barChartParameterObject2011 = {
        dataArray: arrayOfScores2011,
        dataLabelArray: arrayofModuleCodes2011,
        barWidth: barWidth,
        canvasHeight: canvasHeight,
        canvas: canvas,
        lengthMultiplier: 2,
        classAverageArray: arrayOfAverages2011
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
        lengthMultiplier: 2,
        classAverageArray: arrayOfAverages2012
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
        lengthMultiplier: 2,
        classAverageArray: arrayOfAverages2015
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
          var classAverage = moduleInfo[0].classAverage;
          var currentAssessment = "";
          var htmlString = "";

          htmlString += "<p>" + moduleYear +" " +  moduleCodeInput + "</p>";
          
          htmlString += "<p>" + moduleTitle + "</p>";
          htmlString += "<p> Lecturer: " + lecturerName + "</p>";
          htmlString += "<p> Class Average: " + classAverage + "%</p>";

          //htmlString += "<div class='collapsable' data-height='400'>";
          if( moduleInfo[0].criteriaID != undefined )
          {  
              // Create the a table of the feedback data so it can be emailed            
              var tableOfData = createTableOfFeedback( moduleInfo);
              // Create a form with hidden input fields so it can be posted to sendmail.php
              htmlString += '<form name="emailForm" method="post" action="sendmail.php">';
              htmlString += '<input class="hidden" name="emailAddress" type="text" id="emailAddress" value="' + globalEmailAddress + '">';
              htmlString += '<input class="hidden" name="moduleCode" type="text" id="moduleCode" value="' + moduleInfo[0].moduleCode + '">';
              htmlString += '<input class="hidden" name="dataString" type="text" id="dataString" value="' + tableOfData + '">';
              htmlString += ' <button class="control-button" type="submit"><img src="images/email.png"><br/>';
              htmlString += ' Email me the feedback</button>      <br/></form>'; 
              
              // Display all the module feedback as HTML on the website
              var numberOf = 1;
              for( var i = 0; i< moduleInfo.length; i++)
              { 
                var datum = moduleInfo[i];
                // Add the assessment name only if this current peice of assessment is different to the previous one
                if( currentAssessment != datum.assessmentID )
                {
                  htmlString += "<p>" + datum.assessmentName + "</p>";    
                  
                }  
                currentAssessment = datum.assessmentID;

                if (numberOf == 1) htmlString += "<div class = 'infoRow' >";
                htmlString += "<div class='collapsable' data-height='400'>";
                htmlString += "<div onclick='toggleOpen(this);'>";           
                
                htmlString += "<span class='plus'>+</span><span class='minus hidden'>-</span>"
                
                htmlString += "<p class='feedback-info'>" + datum.criteriaName + ": " + datum.studentMark + "/" + datum.maxMark + " </p>";
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
          var currentAssessment = "";
          var lecturerName = moduleInfo[0].lecturerName;
          var moduleTitle = moduleInfo[0].moduleTitle;
          var moduleYear = moduleInfo[0].year;
          var classAverage = moduleInfo[0].classAverage;
          var htmlString = "";

          htmlString += "<p>" + moduleYear +" " +  moduleCodeInput + "</p>";
          
          htmlString += "<p >" + moduleTitle + "</p>";
          htmlString += "<p> Lecturer: " + lecturerName + "</p>";
          htmlString += "<p> Class Average: " + classAverage + "%</p>";

          //htmlString += "<div class='collapsable' data-height='400'>";
          if( moduleInfo[0].criteriaID != undefined )
          {  
              // Create the a table of the feedback data so it can be emailed            
              var tableOfData = createTableOfFeedback( moduleInfo);
              // Create a form with hidden input fields so it can be posted to sendmail.php
              htmlString += '<form name="emailForm" method="post" action="sendmail.php">';
              htmlString += '<input class="hidden" name="emailAddress" type="text" id="emailAddress" value="' + globalEmailAddress + '">';
              htmlString += '<input class="hidden" name="moduleCode" type="text" id="moduleCode" value="' + moduleInfo[0].moduleCode + '">';
              htmlString += '<button class="control-button" type="submit"><img src="images/email.png"><br/>';
              htmlString += ' Email me the feedback</button>      <br/></form>';  

              var numberOf = 1;
              for( var i = 0; i< moduleInfo.length; i++)
              { 
                var datum = moduleInfo[i]; // Get the current piece of module info

                // Add the assessment name only if this current peice of assessment is different to the previous one
                if( currentAssessment != datum.assessmentID )
                {
                  htmlString += "<p>" + datum.assessmentName + "</p>";    
                  
                }  
                currentAssessment = datum.assessmentID;

                if (numberOf == 1) htmlString += "<div class = 'infoRow' >";
                htmlString += "<div class='collapsable' data-height='400'>";
                htmlString += "<div onclick='toggleOpen(this);'>";           
                htmlString += "<span class='plus'>+</span><span class='minus hidden'>-</span>"
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
    /**************************************************************
    * Give the canvas a click event listener
    ***************************************************************/
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
            //console.log( message);
            for( var counter = 0; counter < positionObjectArray.length; counter++ )
            {
             
              if( checkBarIsClicked( [mousePos.x, mousePos.y ], positionObjectArray[counter]) == true )
               {
                  var barPositionObject = positionObjectArray[counter];
                  // If bar is clicked do something
                  

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

        //If the bar is cliked then run this section of code

        // Fill in the bar with unfaded colour
        drawOneBar( canvasObject.getContext('2d'),
            barPositionObject.topLeft[0],
            barPositionObject.topLeft[1],
            50,
            barPositionObject.value,
            20,
            2,
            false,
            true,
            barPositionObject.classAverage); 

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
              true,
              false, // No border
              barPositionObject.classAverage);
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
      
      if(filterApplied == false )
      {
        filterResetOjbect = currentCanvasChart;
        canvasObjectToUse = currentCanvasChart;
      } 
      
      var filterSelected = filterSelectorElement.options[ filterSelectorElement.selectedIndex].text;
      if( filterSelected == "All")
      {
        
        var returnvalue =  drawBarChart ( filterResetOjbect.dataArray, 
                            filterResetOjbect.dataLabelArray, 
                            filterResetOjbect.barWidth, 
                            filterResetOjbect.canvasHeight,
                            canvas,
                            filterResetOjbect.lengthMultiplier,
                            false,// fadedColour,
                            filterResetOjbect.classAverageArray);// classAverage

        currentCanvasChart = filterResetOjbect;
        filterApplied = false 
        assignClickEvent( canvas, returnvalue );
        sortBarChart( document.getElementById('sortSelector') );
        return;
      }

      
      //var allCurrentModuleArray = currentCanvasChart.dataLabelArray;
      var allCurrentModuleArray = canvasObjectToUse.dataLabelArray;
      // Get the filter that the user has selected
      
      // If All is picked then just return and exit the function
      
      var moduleObjectArray = null;
      var arrayToCompare = [];

      var dataLabelArray = [];
      var dataArray = [];
      var dataAverageArray = [];

     

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
      
      // Need to neaten this up
      for( var i = 0; i< allCurrentModuleArray.length; i++)
      {
        if( arrayToCompare.indexOf( allCurrentModuleArray[i] ) > -1)
        {  
          dataLabelArray.push( allCurrentModuleArray[i]);
          dataArray.push( canvasObjectToUse.dataArray[i]);
          dataAverageArray.push( canvasObjectToUse.classAverageArray[i] )
        }  

      }  
      
      var params = {        
                    dataArray: dataArray, 
                    dataLabelArray: dataLabelArray, 
                    barWidth: canvasObjectToUse.barWidth, 
                    canvasHeight:  canvasObjectToUse.canvasHeight,
                    canvas: canvas,
                    lengthMultiplier: canvasObjectToUse.lengthMultiplier,
                    classAverageArray: dataAverageArray
                    };

     var returnvalue =  drawBarChart ( dataArray, 
                            dataLabelArray, 
                            canvasObjectToUse.barWidth, 
                            canvasObjectToUse.canvasHeight,
                            canvas,
                            canvasObjectToUse.lengthMultiplier,
                            false,// fadedColour,
                            dataAverageArray);// classAverage
     console.log( returnvalue);

     assignClickEvent( canvas, returnvalue );
     filterApplied = true;
     currentCanvasChart = params;

     sortBarChart( document.getElementById('sortSelector') );

    }
    /**************************************************************
    * code for Sorting
    ***************************************************************/  
    function sortBarChart(sortSelectorElement)
    {
      //filterResetArray = currentCanvasChart;
      var allCurrentModuleArray = currentCanvasChart.dataLabelArray;

      // Get the Sort type that the user has selected
      var sortSelected = sortSelectorElement.options[ sortSelectorElement.selectedIndex].text;

      var moduleCodeArray = [];
      var moduleScoreArray = [];
      var dataArray = currentCanvasChart.dataArray;
      var dataLabelArray =  currentCanvasChart.dataLabelArray;
      //var sortedArray = sortArray( currentCanvasChart.dataArray, currentCanvasChart.dataLabelArray);

      switch( sortSelected)
      {
        case "Score (Low to High)": sortedArray = sortArray( dataArray, dataLabelArray);
                                    moduleScoreArray = sortedArray[0];
                                    moduleCodeArray = sortedArray[1];
                                    break;

        case "Score (High to Low)": sortedArray = sortArray( dataArray, dataLabelArray);
                                    moduleScoreArray = reverseArray( sortedArray[0] );
                                    moduleCodeArray = reverseArray( sortedArray[1] );
                                    break;                                     
        case "Module Code":         sortedArray = sortArray( dataLabelArray, dataArray);
                                    moduleScoreArray = sortedArray[1];
                                    moduleCodeArray = sortedArray[0];
                                    break;


      }

      
      var returnvalue =  drawBarChart ( moduleScoreArray, 
                            moduleCodeArray, 
                            currentCanvasChart.barWidth, 
                            currentCanvasChart.canvasHeight,
                            canvas,
                            currentCanvasChart.lengthMultiplier,
                            false,// fadedColour,
                            currentCanvasChart.classAverageArray);// classAverage
      assignClickEvent( canvas, returnvalue );
    }

    /**************************************************************
    * reverse array
    ***************************************************************/
    function reverseArray( inputArray )
    {

      var results = [];
      var lastIndex = inputArray.length - 1; // Get the last index number of inputArray
      for( i = lastIndex; i >= 0; i--)
      {
        results.push( inputArray[i]);
      } 

      return results;   
    }
    /**************************************************************
    * Sort array
    ***************************************************************/
    function sortArray( inputArray1, inputArray2 )
    {
      var results = [];
      var tempVariable = -1;
      var combinedArray = [];

      for( i=0; i< inputArray1.length; i++)
      {
        combinedArray.push( inputArray1[i] + "@" + inputArray2[i] );
      }  

      combinedArray.sort();
      
      var markArray = [];
      var codeArray = [];

      for( i=0; i< combinedArray.length; i++)
      {

        var stringVar = combinedArray[i];
        var parts = stringVar.split("@");
        markArray.push( parts[0]);
        codeArray.push( parts[1]);
      }

      results.push( markArray);
      results.push( codeArray);
      return results
    }
    /**************************************************************
    * create table of feedback
    ***************************************************************/
    function createTableOfFeedback( objectInput)
    {

      htmlString = "";
      htmlString += "<img src='http://co-project.lboro.ac.uk/users/coidckw/Electronic%20Marking%20System/images/logo.png' alt='LU Logo'><br/>"
      htmlString += "<div>Dear " + globalStudentName + ", <br/> Below is your requested feedback</div>";
      htmlString += "<table style='font-family:Arial;' border='1'>"; // Begin the Table

      htmlString += "<tr><td> Module Code</td><td>Assessment</td><td>Critera</td><td>Mark</td><td>Comment</td></tr>";
      for( var counter = 0; counter< objectInput.length; counter++)
      {
        var data = objectInput[counter];
        var assessmentName = data.assessmentName;
        var moduleCode = data.moduleCode;
        var criteriaName = data.criteriaName;
        var feedback = data.criteriaFeedback;
        var studentMark = data.studentMark + "/" + data.maxMark;

        htmlString += "<tr>";
        htmlString += "<td>" + moduleCode + "</td>" + "<td>" + assessmentName + "</td><td>" + criteriaName + "</td>";
        htmlString += "<td>" + studentMark + "</td>";
        htmlString += "<td>" + feedback + "</td>";

        htmlString += "</tr>";
      }  

      htmlString += "</table><br/>"; // End of table
      htmlString += "<div>Generated from <a href='http://co-project.lboro.ac.uk/users/coidckw/Electronic%20Marking%20System/mymodules.php'>Feedback Delivery System</a></div>"

      return htmlString;


    }
    /**************************************************************
    *Function that resets the canvas to when the page is first loaded
    ***************************************************************/
    function resetButtonHandler()
    {
      resetCanvas(canvas);
      drawSummaryChart( moduleObjectArray, canvas);
      compareMode = false;
    };

  
    /**************************************************************
    *Function that fires when the compare button is clicked
    ***************************************************************/
    function compareButtonHandler()
    {
      var currentCanvasChartObject = currentCanvasChart;
      
      if( compareMode == false) 
      { // Turn on Compare Mode
        compareMode = true;
        currentCanvasChartObject.fadedColour = true;
      }
      else
      { // Turn off Compare Mode
        currentCanvasChartObject.fadedColour = false;
        compareMode = false;
        var infoDiv2 = document.getElementById("moduleInfoDiv2");
        infoDiv2.innerHTML = "";

        // Set class name of info div 1 back to default
        var infoDiv1 = document.getElementById("moduleInfoDiv");
        infoDiv1.className = "moduleInfoDiv";

      } 
      drawBarChartFromObject( currentCanvasChartObject);
    }

