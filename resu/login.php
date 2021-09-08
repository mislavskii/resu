<?php // Do not put any HTML above this line
session_start();
require_once "pdo.php";

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to index.php
    header("Location: index.php");
    return;
}

// Authentication
$salt = 'XyZzy12*_';

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    unset($_SESSION['name']);  // Logout current user if any
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass'] ) < 1 ) {
        $_SESSION['error'] = "Email and password are required";
        header( 'Location: login.php' ) ;
        return;
    } elseif ( !strpos($_POST['email'], '@') ) {
        $_SESSION['error'] = "Email must have an at-sign (@)";
        header( 'Location: login.php' ) ;
        return;
    } else {
        $check = hash('md5', $salt.$_POST['pass']);  // salted submitted password
        $stmt = $pdo->prepare('SELECT user_id, name FROM users
            WHERE email = :em AND password = :pw');
        $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ( $row !== false ) {  // means there is such email & hashed password pair in the db
             $_SESSION['name'] = $row['name'];
             $_SESSION['user_id'] = $row['user_id'];
             // Redirect the browser to index.php
             header("Location: index.php");
             return;
        } else {
            $_SESSION['error'] = "Incorrect password";
            error_log("Login fail ".$_POST['email']." $check");
            header( 'Location: login.php' ) ;
            return;
        }
    }
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Maksim Mislavskii's Resume Registry - Login</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>
<form method="POST">
  <label for="nam">Email</label>
  <input type="text" name="email" id="nam"><br/>
  <label for="id_1723">Password</label>
  <input type="text" name="pass" id="id_1723"><br/>
  <input type="submit" onclick="return doValidate();" value="Log In">
  <input type="submit" name="cancel" value="Cancel">
</form>

<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint:
The account is umsi@umich.edu
The password is the three character name of the
programming language used in this class (all lower case)
followed by 123. -->
</p>

</div>
<script type="text/javascript">
  function doValidate() {
      console.log('Validating...');
      try {
          pw = document.getElementById('id_1723').value;
          em = document.getElementById('nam').value;
          console.log("Validating pw="+pw);
          console.log("Validating em="+em);
          if (!pw || !em) {
              alert("Both fields must be filled out");
              return false;
          } else if ( !em.includes('@') ) {
              alert("Inavlid email address");
              return false;
          }
          return true;
      } catch(e) {
          return false;
      }
      return false;
  }
</script>
</body>
</html>
