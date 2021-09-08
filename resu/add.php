<?php
session_start();
require_once "pdo.php";
require_once "util.php";

no_login_or_cancel() ;  //if user not looged in or clicked cancel duly terminate

// Inserting new profile data into the db
if ( isset($_POST['add']) ) {
    if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1
        || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1
        || strlen($_POST['summary']) < 1 ) {
        $_SESSION['failure'] = "All fields are required";
        header( 'Location: add.php' ) ;
        return;
    } elseif ( strpos($_POST['email'],'@') === false ) {
        $_SESSION['failure'] = "Email address must contain @";
        header( 'Location: add.php' ) ;
        return;
    } else {
        $stmt = $pdo->prepare('INSERT INTO Profile
          (user_id, first_name, last_name, email, headline, summary)
          VALUES ( :uid, :fn, :ln, :em, :he, :su)');
        $stmt->execute(array(
          ':uid' => $_SESSION['user_id'],
          ':fn' => $_POST['first_name'],
          ':ln' => $_POST['last_name'],
          ':em' => $_POST['email'],
          ':he' => $_POST['headline'],
          ':su' => $_POST['summary'])
        );
        $_SESSION['success'] = "Record added";
        header( 'Location: index.php' ) ;
        return;
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <?php require_once "bootstrap.php"; ?>
    <title>Maksim Mislavskii's Resume Registry - Adding new entry</title>
  </head>
  <body>
    <div class="container">
      <h1>Adding Profile for <?= htmlentities($_SESSION['name']) ?></h1>
      <?= failure() ; ?>
      <form method="post">
        <p>First Name:
          <input type="text" name="first_name" size="60"/></p>
        <p>Last Name:
          <input type="text" name="last_name" size="60"/></p>
        <p>Email:
          <input type="text" name="email" size="30"/></p>
        <p>Headline:<br/>
          <input type="text" name="headline" size="80"/></p>
        <p>Summary:<br/>
          <textarea name="summary" rows="8" cols="82"></textarea>
        </p>
        <p>
          <input type="submit" name="add" value="Add">
          <input type="submit" name="cancel" value="Cancel">
        </p>
      </form>
    </div>
  </body>
</html>
