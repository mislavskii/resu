<?php
session_start();
require_once "pdo.php";
no_login_or_cancel() ;  // no further processing

// Deleting the entry
if ( isset($_POST['delete']) && isset($_POST['profile_id']) ) {
    $sql = "DELETE FROM profile WHERE profile_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['profile_id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: index.php' ) ;
    return;
}

$row = get_entry('profile') ;  // retrieving the entry to be edited from db by id in GET
owner() ;  // check if current user owns the entry, else skip back to index

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <?php require_once "bootstrap.php"; ?>
    <title>Maksim Mislavskii's Resume Registry - Delete Entry</title>  </head>
  <body>
    <div class="container">
    <h1>Deleting Profile</h1>
    <p>First Name: <?= htmlentities($row['first_name']) ?></p>
    <p>Last Name: <?= htmlentities($row['last_name']) ?></p>
    <form method="post" action="delete.php">
      <input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>"/>
      <input type="submit" name="delete" value="Delete">
      <input type="submit" name="cancel" value="Cancel">
    </form>
    </div>
  </body>
</html>
}
