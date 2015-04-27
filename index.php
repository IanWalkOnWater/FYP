<!DOCTYPE HTML>
<html>
    <head>
        <link rel="shortcut icon" href="http://learn.lboro.ac.uk/theme/image.php/lboro2/theme/1416506521/favicon" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Login</title>

        <link rel="stylesheet" type="text/css" href="css/main.css"/>
        <script type="text/javascript"> </script>

    </head>
    <body>

    <div class="page-header" id="page-header"> 
      <img src="images/LULogo.png" alt="LU Logo">
      <p class="title-text">My Feedback</p>
    </div>  

    <br/>

    <?php

    session_start();

    // if( isset( $_SESSION['wrongLogin']) )
    //     //echo '<p style="color:red; font-weight:bold;">Please enter a valid Username</p>';
    //     echo '<div>yes</div>';
    // else
    // {    
    //     echo '<div>No</div>';
    //     $_SESSION['wrongLogin'] = false;
    // }


    if(isset($_GET['failed'])) 
    { 
        if($_GET['failed'] == 1) 
        { 
            echo $_GET['error']; 
        } 
    }


    ?>

    <form class="login-form" name="loginForm" method="post" action="login.php">

        <label class="login-label" for= "userName">Username: </label><input class="login-input" name="userName" type="text" id="userName">
        <br/>
        <br/>
        <label class="login-label" for="password">Password: </label><input class="login-input" name="password" type="password" id="password"> 
        <br/>
        <br/>

        <button class="login-button" type="submit"> Login</button>      

    </form>        
    

    </body>
</html>
