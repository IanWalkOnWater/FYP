 // function check( inputArray, objectToCompare)
 //     {
        
 //        var topLeft = objectToCompare.topLeft;
 //        var topRight = objectToCompare.topRight;

 //        var bottomLeft = objectToCompare.bottomLeft;
 //        var bottomRight = objectToCompare.bottomRight;
 //        if( checkX( inputArray[0], topLeft[0], topRight[0]) == true
 //          && checkY( inputArray[1], topLeft[1], bottomLeft[1] ) == true )
 //        {
 //          console.log( " check passed");
 //        }  

 //        //if( checkY( inputArray[1], topLeft[1], bottomLeft[1] ))
 //          //console.log( "ycheck passed");

 //     }

      

      // Draw one bar within a canvas
      function drawOneBar( context,
                        xposition,
                        yposition,
                        barWidth,
                        dataValue,
                        spaceFromBottom)
     {
        yposition = 300 - (dataValue*2) - (spaceFromBottom -5 );
        // Check is spaceFromBottom is passed in
        if( spaceFromBottom == undefined) spaceFromBottom = 20;
        var labelPosition = xposition + (barWidth/3); // Get the labelPosition of the bar

        try{
            context.beginPath();
            context.rect(xposition, yposition , barWidth, dataValue *2); // X-pos, Y-Pos ( from top), width, height
            context.fillStyle = '#ffca28';

            
            if( dataValue > 70) context.fillStyle = "#388e3c";
            context.fill();
            // Fill in the Border
            // context.lineWidth = 2;
            // context.strokeStyle = 'black';
            // context.stroke();
            
            context.fillStyle = "black";
            context.font = 'italic 10pt Calibri';
            context.fillText( dataValue, labelPosition, yposition - spaceFromBottom);
          }

          catch( error)
          {
            console.log( "Failed to draw one bar " + error);
          }

          context.closePath();
     }// End of drawOneBar

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