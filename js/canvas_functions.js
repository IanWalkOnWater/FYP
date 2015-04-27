
  /**************************************************************
  * Calculate the x and y co-ordinates with relation to length
  ***************************************************************/      
  function getNewYCoordinate( yposition, lengthMultiplier, spaceFromBottom, canvasHeight)
  {
    var newYPosition = -1;

    newYPosition = canvasHeight - (yposition * lengthMultiplier) - (spaceFromBottom - 5 );
    console.log( "newYPosition is ");
    console.log( newYPosition);
    return Number(newYPosition)
  }
  /**************************************************************
  * Draw one bar within a canvas
  ***************************************************************/
  function drawOneBar( context,
                    xposition,
                    yposition,
                    barWidth,
                    dataValue,
                    spaceFromBottom,
                    lengthMultiplier,
                    fadedColour,
                    addBorder,
                    classAverage)
  {
    
    // Check is spaceFromBottom is passed in
    if( spaceFromBottom == undefined) spaceFromBottom = 20;
    if( lengthMultiplier == undefined) lengthMultiplier = 1.0;
    if( fadedColour == undefined) fadedColour = false;
    if( addBorder == undefined) addBorder = false;
    if( classAverage == undefined) classAverage = -1;

    // if length multiplyer is greater than 1 then get the new y coordinate
    if( lengthMultiplier > 1)
      yposition = context.canvas.height - (dataValue * lengthMultiplier) - (spaceFromBottom - 5 );

    var labelPosition = xposition + (barWidth/3); // Get the labelPosition of the bar

    try{
        drawGreyBar(context, xposition, 100, spaceFromBottom, lengthMultiplier);
        context.beginPath();
        context.rect(xposition, yposition , barWidth, dataValue * lengthMultiplier); // X-pos, Y-Pos ( from top), width, height
        context.fillStyle = '#FFCA28';

        switch( true)
        {
          case ( dataValue >= 70): context.fillStyle = "#388e3c"; break;
          case ( dataValue < 40): context.fillStyle = "#FF5722"; break;
        }

        if( fadedColour == true)
        {
          switch( true)
          {
            case ( dataValue >= 70): context.fillStyle = "#A5D6A7"; break;
            case ( dataValue < 70 && dataValue >= 40): context.fillStyle = "#FFE082"; break;
            case ( dataValue < 40): context.fillStyle = "#FF8A65"; break;
          }
        }// End of If
        
        context.fill(); // Fill in the bar 

        if( addBorder == true) // If addBorder is true then draw a border around the bar
        {  
        // Add a thin black border around the bar
        context.lineWidth = 0.75;
        context.strokeStyle = 'black'; 
        }
        else
        {
          // This bit of code is to remove any borders already on the bar. This code will cover up the old border with a thicker border
          context.lineWidth = 2;
          context.strokeStyle = context.fillStyle;
        }

        context.stroke(); // Draw the border
        
        // Add the data label below the bar
        context.fillStyle = "black";
        context.font = 'italic 10pt Calibri';
        context.fillText( dataValue, labelPosition, yposition - (spaceFromBottom/2));
        context.closePath();
console.log( "classAverage is");
console.log( classAverage);
        if( classAverage > -1)
        {  
         
          //var tempa = context.canvas.height - (dataValue * lengthMultiplier) - (spaceFromBottom - 5 );
          ypos = yposition + (dataValue - classAverage );
          addClassAverage( context, xposition, ypos, barWidth);
        }
      }

      // Catch all errors and just log it
      catch( error)
      {
        console.log( "Failed to draw one bar " + error);
      }      
  }// End of drawOneBar
  /**************************************************************
  * Draw one gey bar
  ***************************************************************/
  function drawGreyBar (context, xposition, Value, spaceFromBottom, lengthMultiplier)
  {  

  // Check is spaceFromBottom is passed in
  if( spaceFromBottom == undefined) spaceFromBottom = 20;
  if( lengthMultiplier == undefined) lengthMultiplier = 1.0;

  var yposition = context.canvas.height - ( Number(Value) * lengthMultiplier ) - spaceFromBottom;

  context.beginPath();
  context.rect(xposition, yposition , barWidth, dataValue * lengthMultiplier); // X-pos, Y-Pos ( from top), width, height
  context.fillStyle = '#E0E0E0';

  context.fill();

  context.lineWidth = 2;
  context.strokeStyle = context.fillStyle;
  context.stroke();

  context.closePath();
  }// End of draw grey bar
  /**************************************************************
  * Main function that draws the bar charts
  ***************************************************************/
  function drawBarChart ( dataArray, 
                            dataLabelArray, 
                            barWidth, 
                            canvasHeight,
                            canvas,
                            lengthMultiplier,
                            fadedColour,
                            classAverageArray) 
  { 
    if( classAverageArray == undefined) classAverageArray = [];    
    var dataValue = 0;
    var xposition = 0;
    var spaceBetweenBars = 70;
    var moduleCode = "";
    var heightMultiplyer = 1.5;
    var spaceFromBottom = 20; // moves all the bars up by x pixels
    var objectArray = [];
    var classAverage = -1;
    var addBorder = false;

    console.log( "classAverageArray");
    console.log( classAverageArray);
    
    canvas.height = canvasHeight;
    var context = canvas.getContext('2d');

    for( var i =0; i< dataArray.length; i++)
    {
      dataValue = Number(dataArray[i]);

      xposition = xposition + (spaceBetweenBars);
      
      yposition = (canvasHeight - Number(dataArray[i]) - spaceFromBottom) ;

      if( classAverageArray.length > 0)
      {  
        classAverage = classAverageArray[i];
        
      }
      
      drawOneBar( context, xposition, yposition, barWidth, dataValue, spaceFromBottom, lengthMultiplier, fadedColour,addBorder, classAverage);

      
      topLeft = [ xposition, yposition ];
      topRight = [ xposition + barWidth, yposition  ];
      bottomLeft = [xposition , yposition + dataValue];
      bottomRight = [xposition + barWidth, yposition + dataValue];
      value = dataValue;
    
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
        moduleCode : moduleCode,
        value : value,
        classAverage : classAverage
      });
    
    }; // End of For

    return objectArray;

  } // End of function drawBarChart
  /**************************************************************
  * Draw class average
  ***************************************************************/
  function addClassAverage( context,
                            xposition,
                            yposition,
                            barWidth)
  {
    context.beginPath();
    context.rect(xposition, yposition , barWidth, 1); // X-pos, Y-Pos ( from top), width, height
    context.fillStyle = '#000000'; // color black
    context.fill();
    context.closePath;
  }

  function checkX( xCoordinate, leftCoordinate, rightCoordinate)
  {
    if(xCoordinate >= leftCoordinate && xCoordinate <= rightCoordinate )
      return true;
    else return false;

  }
      

  function checkY( yCoordinate, topCoordinate, bottomCoordinate)
  {
    if(yCoordinate >= topCoordinate && yCoordinate <= bottomCoordinate )
      return true;
    else return false;

  }

  // Find the position of the mouse in the canvas
  // param: canvas object, event
  function getMousePos(canvas, evt)
  {
    var rect = canvas.getBoundingClientRect();
    return {
      x: evt.clientX - rect.left,
      y: evt.clientY - rect.top
    };
  }// End of getMousePos


  // Resets the canvas. Takes a canvas object as input
  function resetCanvas( canvas)
  {
    context = canvas.getContext('2d');

    try
    {
      context.clearRect(0, 0, canvas.width, canvas.height);
    }
    
    catch(error)    
    {
      console.log( "failed to clear canvas. " + error );
    }
  } // End of resetCanvas

  // Check if a bar is clicked or not.
  // Requires the input of x coordinate and y coordinate in an array
  // and a object with topLeft,topRight,bottomLeft and bottomRight  
  function checkBarIsClicked( inputArray, objectToCompare)
  {
    var returnFlag = false;
    var topLeft = objectToCompare.topLeft;
    var topRight = objectToCompare.topRight;

    var bottomLeft = objectToCompare.bottomLeft;
    var bottomRight = objectToCompare.bottomRight;
    if( checkX( inputArray[0], topLeft[0], topRight[0]) == true
      && checkY( inputArray[1], topLeft[1], bottomLeft[1] ) == true )
    {
      returnFlag = true;
    }  
    return returnFlag;
  }// End of checkBarIsClicked