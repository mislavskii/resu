<?php
session_start();
require_once "pdo.php";
no_login_or_cancel() ;  // no further processing

// Updating profile data in the db
if ( isset($_POST['save']) && isset($_GET['profile_id']) ) {
    if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1
        || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1
        || strlen($_POST['summary']) < 1 ) {
        $_SESSION['failure'] = "All fields are required";
        header( 'Location: edit.php?profile_id='.$_REQUEST['profile_id'] ) ;
        return;
    } elseif ( strpos($_POST['email'],'@') === false ) {
        $_SESSION['failure'] = "Email address must contain @";
        header( 'Location: edit.php?profile_id='.$_REQUEST['profile_id'] ) ;
        return;
    } else {
        $stmt = $pdo->prepare('UPDATE profile SET
          first_name = :fn, last_name = :ln, email = :em,
          headline = :he, summary = :su WHERE profile_id = :pid');
        $stmt->execute(array(
          ':pid' => $_GET['profile_id'],
          ':fn' => $_POST['first_name'],
          ':ln' => $_POST['last_name'],
          ':em' => $_POST['email'],
          ':he' => $_POST['headline'],
          ':su' => $_POST['summary'])
        );
        $_SESSION['success'] = "Record updated";
        header( 'Location: index.php' ) ;
        return;
    }
}

$row = get_entry('profile') ;  // retrieving the entry to be edited from db by id in GET
owner() ;  // check if current user owns the entry, else skip back to index
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <?php require_once "bootstrap.php"; ?>
    <title>Maksim Mislavskii's Resume Registry - Edit Entry</title>
  </head>
  <body>
    <div class="container">
      <h1>Editing Profile for <?= htmlentities($_SESSION['name']) ?></h1>
      <?= failure() ; ?>
      <form method="post">
        <p>First Name:
          <input type="text" name="first_name"
          value="<?= htmlentities($row['first_name']) ?>" size="60"/></p>
        <p>Last Name:
          <input type="text" name="last_name"
          value="<?= htmlentities($row['last_name']) ?>" size="60"/></p>
        <p>Email:
          <input type="text" name="email"
          value="<?= htmlentities($row['email']) ?>" size="30"/></p>
        <p>Headline:<br/>
          <input type="text" name="headline"
          value="<?= htmlentities($row['headline']) ?>" size="80"/></p>
        <p>Summary:<br/>
          <textarea name="summary" rows="8"
          cols="82"><?= htmlentities($row['summary']) ?></textarea>
        </p>
        <p>
          <input type="submit" name="save" value="Save">
          <input type="submit" name="cancel" value="Cancel">
        </p>
      </form>
    </div>
  </body>
</html>
