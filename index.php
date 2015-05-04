<!DOCTYPE HTML>
<html>
    <head>
        <link rel="shortcut icon" href="images/favicon.ico" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Login</title>

        <link rel="stylesheet" type="text/css" href="css/main.css"/>
        <script type="text/javascript"> </script>

   <script>
    function validateForm() 
    {
        var userNameInput = document.forms["loginForm"]["userName"].value;
        
        if ( (/[^a-zA-Z0-9\-\/]/.test(userNameInput)) ) 
        {
            document.getElementById("errorMessageContainer").className = "error-message";
            document.getElementById("errorMessageContainer").innerHTML = "Please only enter numbers or letters as the username";
            var elementExists = document.getElementById("hiddenError");
             if( elementExists != null)
             {
                elementExists.className += " hidden";
             }
            return false;
        }
    }
    


</script>
    </head>
    <body>

    <div class="page-header" id="page-header"> 
      <img src="images/LULogo.png" alt="LU Logo">
      <p class="title-text">My Feedback</p>
    </div>  

    <br/>

    <?php

    session_start(); // Start the session


    if(isset($_GET['failed'])) // Check if the Failed variable exists
    { 
        if($_GET['failed'] == 1) // Check if the Failed is equal to 1 then show the error
        { 
            echo "<p id='hiddenError' class='error-message'>" .  $_GET['error'] . "</p>"; 
        } 
    }

    // Code for to log the user out
     if(isset($_GET['logout'])) 
    { 
        if($_GET['logout'] == 1) 
        { 
            $_SESSION[ 'loggedIn'] = false;
            session_destroy(); //Destroy the session
            echo "<p class='logout-message' id='hiddenError'>Logout successful </p>";
            
        } 
    }


    ?>
    <p class="error-message hidden" id="errorMessageContainer"> </p>
    <form class="login-form" name="loginForm" method="post" onsubmit="return validateForm()" action="login.php">

        <label class="login-label" for= "userName">Username: </label>
            <input class="login-input" name="userName" type="text" id="userName" required>
        <br/>
        <br/>
        <label class="login-label" for="password">Password: </label>
            <input class="login-input" name="password" type="password" id="password" required> 
        <br/>
        <br/>

        <button class="login-button" type="submit" > Login</button>      

    </form>        
    

    </body>
</html>
