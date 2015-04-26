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
      <img src="images/logo.png" alt="LU Logo">
      Home &nbsp; My Modules &nbsp; 
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

    <form name="loginForm" method="post" action="login.php">

        <label for="userName">Username: </label><input name="userName" type="text" id="userName">
        <br/>
        <label for="password">Password: </label><input name="password" type="password" id="password"> 
        <br/>

        <button type="submit"> login</button>      

    </form>        
    

    </body>
</html>
