<?php
 ob_start();
 session_start();
 if( isset($_SESSION['user'])!="" ){
  header("Location: index.php");
 }
 include_once 'dbconnect.php';

 $error = false;

 if ( isset($_POST['btn-signup']) ) {

  // clean user inputs to prevent sql injections
/*  $userID = trim($_POST['userID']);
  $userID = strip_tags($userID);
  $userID = htmlspecialchars($userID);*/


  $firstname = trim($_POST['firstname']);
  $firstname = strip_tags($firstname);
  $firstname = htmlspecialchars($firstname);
  $lastname = trim($_POST['lastname']);
  $lastname = strip_tags($lastname);
  $lastname = htmlspecialchars($lastname);

  $email = trim($_POST['email']);
  $email = strip_tags($email);
  $email = htmlspecialchars($email);

  $pass = trim($_POST['pass']);
  $pass = strip_tags($pass);
  $pass = htmlspecialchars($pass);

     //userID validation
   /*if (empty($userID)) {
   $error = true;
   $nameError = "Please CREATE your userID.";
  } else if (!preg_match("/^[0-9]+$/",$userID)) {
   $error = true;
   $nameError = "userID must contain NUMBER only.";
  }*/
  // basic name validation
  if (empty($firstname)) {
   $error = true;
   $nameError = "Please enter your first name.";
  } else if (!preg_match("/^[a-zA-Z]+$/",$firstname)) {
   $error = true;
   $nameError = "Name must contain alphabets only.";
  }

  if (empty($lastname)) {
   $error = true;
   $nameError = "Please enter your last name.";
  } else if (!preg_match("/^[a-zA-Z]+$/",$lastname)) {
   $error = true;
   $nameError = "Name must contain alphabets only.";
  }

  //basic email validation
  if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
   $error = true;
   $emailError = "Please enter valid email address.";
  } else {
   // check email exist or not
   $query = "SELECT email_address FROM registered_user WHERE email_address='$email'";
   $result = mysql_query($query);
   $count = mysql_num_rows($result);
   if($count!=0){
    $error = true;
    $emailError = "Provided Email is already in use.";
   }
  }
  // password validation
  if (empty($pass)){
   $error = true;
   $passError = "Please enter password.";
  } else if(strlen($pass) < 6) {
   $error = true;
   $passError = "Password must have atleast 6 characters.";
  }


  // if there's no error, continue to signup
  if( !$error ) {

   $query = "INSERT INTO registered_user(first_Name,last_Name,email_address,password) VALUES('$firstname','$lastname', '$email','$pass')";
   $res = mysql_query($query);

   if ($res) {
    $errTyp = "success";
    $errMSG = "Successfully registered, you may login now";
     //unset($userID);
    unset($firstname);
    unset($lastname);
    unset($email);
    unset($pass);
   } else {
    $errTyp = "danger";
    $errMSG = "Something went wrong, try again later...";
   }

  }


 }
?>



  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Signin Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../signin/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>


  <div class="container">

    <div id="login-form">
      <form class="form-signin" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">

        <h2 class="form-signin-heading">Please sign in</h2>

          <?php
          if ( isset($errMSG) ) {

            ?>
            <div class="form-group">
              <div class="alert alert-<?php echo ($errTyp=="success") ? "success" : $errTyp; ?>">
                <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
              </div>
            </div>
            <?php
          }
          ?>

          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
              <input type="text" name="firstname" class="form-control" placeholder="Enter First Name" maxlength="50" value="<?php echo $firstname ?>" />
            </div>
            <span class="text-danger"><?php echo $nameError; ?></span>
          </div>
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
              <input type="text" name="lastname" class="form-control" placeholder="Enter Last Name" maxlength="50" value="<?php echo $lastname ?>" />
            </div>
            <span class="text-danger"><?php echo $nameError; ?></span>
          </div>

          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
              <input type="text" name="email" class="form-control" placeholder="Enter Your Email" maxlength="40" value="<?php echo $email ?>" />
            </div>
            <span class="text-danger"><?php echo $emailError; ?></span>
          </div>

          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
              <input type="text" name="pass" class="form-control" placeholder="Enter Password" maxlength="15" />
            </div>
            <span class="text-danger"><?php echo $passError; ?></span>
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-block btn-primary" name="btn-signup">Sign Up</button>
          </div>

          <div class="form-group">
            <a href="index.php">Sign in Here</a>
          </div>

        </div>

      </form>
    </div>

  </div>

  <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
  <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
  </html>
<?php ob_end_flush(); ?>
