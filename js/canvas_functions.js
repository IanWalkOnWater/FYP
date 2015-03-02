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